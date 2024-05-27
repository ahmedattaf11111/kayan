<template>
  <div class="deal-form">
    <div class="px-4 mt-4">
      <div class="table-container">
        <form @submit.prevent="saveDeal" enctype="multipart/form-data">
          <div class="row">
            <div class="col-lg-3">
              <div class="form-group">
                <label for="exampleInputEmail1">{{ $t("END_AT") }}</label>
                <input
                  type="datetime-local"
                  class="form-control"
                  v-model="v$.end_at.$model"
                  :class="{
                    'is-invalid': v$.end_at.$error,
                  }"
                />
                <div class="invalid-feedback">
                  <div v-for="error in v$.end_at.$errors" :key="error">
                    {{ $t("END_AT") + " " + $t(error.$validator) }}
                  </div>
                </div>
              </div>
            </div>
            <hr class="mt-3" />
            <!--list-->
            <div class="list" v-for="(deal, index) in list" :key="index">
              <div class="hello row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="inputState">{{ $t("SUPPLIER") }}</label>
                    <select
                      @change="deal.supplier_id_dirty = true"
                      :class="{
                        'is-invalid':
                          deal.supplier_id_dirty &&
                          v$.list.$each.$response.$errors[index].supplier_id
                            .length,
                      }"
                      v-model="deal.supplier_id"
                      class="form-control"
                    >
                      <option
                        v-for="supplier in suppliers"
                        :value="supplier.id"
                      >
                        {{ supplier.name }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.list.$each.$response.$errors[index]
                          .supplier_id"
                        :key="error"
                      >
                        {{ $t("SUPPLIER") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="inputState">{{ $t("PRODUCT") }}</label>
                    <select
                      @change="deal.product_id_dirty = true"
                      :class="{
                        'is-invalid':
                          deal.product_id_dirty &&
                          v$.list.$each.$response.$errors[index].product_id
                            .length,
                      }"
                      v-model="deal.product_id"
                      class="form-control"
                    >
                      <option v-for="product in products" :value="product.id">
                        {{ product.name }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.list.$each.$response.$errors[index]
                          .product_id"
                        :key="error"
                      >
                        {{ $t("PRODUCT") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>{{ $t("client_discount") }}</label>
                    <input
                      @input="deal.client_discount_dirty = true"
                      type="number"
                      class="form-control"
                      v-model="deal.client_discount"
                      :class="{
                        'is-invalid':
                          deal.client_discount_dirty &&
                          v$.list.$each.$response.$errors[index].client_discount
                            .length,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.list.$each.$response.$errors[index]
                          .client_discount"
                        :key="error"
                      >
                        {{
                          $t("client_discount") +
                          " " +
                          $t(error.$validator, { value: 0, maxValue: 100 })
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <button
                    v-if="index != 0"
                    @click="removeDeal(index)"
                    class="increments"
                    type="button"
                  >
                    <i class="fa fa-trash text-danger"></i>
                  </button>
                  <button
                    v-if="index + 1 == list.length"
                    @click="addDeal"
                    class="increments mx-2"
                    type="button"
                  >
                    <i class="fa fa-plus"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-danger mt-4" style="width:80px">
            {{ $t("SUBMIT") }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import useVuelidate from "@vuelidate/core";
import { required, helpers, minValue, maxValue } from "@vuelidate/validators";
import dealClient from "../../../shared/http-clients/admin/deal-client";
import { computed, inject, reactive, toRefs, onMounted } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const toast = inject("toast");
    const form = reactive({
      end_at: "",
      list: [getElement()],
    });
    const data = reactive({
      deals: [],
      products: [],
      suppliers: [],
    });
    const rules = {
      end_at: { required },
      list: {
        $each: helpers.forEach({
          supplier_id: { required },
          product_id: { required },
          client_discount: {
            required,
            minValue: minValue(0),
            maxValue: maxValue(100),
          },
        }),
      },
    };
    onMounted(() => {
      setForm();
    });
    const v$ = useVuelidate(rules, form);
    //Methods
    function addDeal() {
      form.list.push(getElement());
    }
    function removeDeal(index) {
      if (form.list.length > 1) {
        form.list.splice(index, 1);
      }
    }
    function saveDeal() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        touchlist();
        return;
      }
      dealClient
        .saveDeal(getForm())
        .then((response) => {
          toast.success(t("UPDATED_SUCCESSFULLY"));
        })
        .catch((error) => {});
    }
    //Commons
    function touchlist() {
      form.list.forEach((deal) => {
        deal.supplier_id_dirty = true;
        deal.product_id_dirty = true;
        deal.client_discount_dirty = true;
      });
    }
    function getElement() {
      return { client_discount: "", supplier_id: "", product_id: "" };
    }
    function getForm() {
      {
        return {
          end_at: form.end_at,
          deals: form.list.map((el) => {
            return {
              client_discount: el.client_discount,
              supplier_id: el.supplier_id,
              product_id: el.product_id,
            };
          }),
        };
      }
    }
    function setForm() {
      dealClient.getDeal().then((res) => {
        form.end_at = res.data.end_at;
        form.list = res.data.deals.length
          ? _.clone(res.data.deals)
          : [getElement()];
      });
      getSuppliers();
      getProducts();
    }
    function getSuppliers() {
      dealClient.getSuppliers().then((response) => {
        data.suppliers = response.data;
      });
    }
    function getProducts() {
      dealClient.getProducts().then((response) => {
        data.products = response.data;
      });
    }
    //Watchers
    return {
      ...toRefs(data),
      ...toRefs(form),
      v$,
      addDeal,
      removeDeal,
      saveDeal,
    };
  },
  props: ["selectedHello"],
};
</script>

<style scoped lang="scss">
.deal-form {
  .form-control {
    padding: 10px;
  }

  .form-group {
    margin-bottom: 10px;

    label {
      margin-bottom: 5px;
    }
  }

  .icons {
    i {
      font-size: 20px;
    }

    i:hover {
      cursor: pointer;
    }
  }

  .image {
    box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
      rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    padding-bottom: 5px;

    img {
      width: 100%;
      height: 150px;
    }

    .image-upload {
      i {
        margin-top: 7px;
        color: #888888;
      }

      .icon {
        &:hover {
          cursor: pointer !important;
        }

        i {
          font-size: 14px;
          position: relative;
        }
      }

      text-align: center;

      input[type="file"] {
        display: none;
      }
    }
  }

  .increments {
    border: none;
    margin-top: 40px;
    font-size: 16px !important;
    background: none !important;
  }

  .modal-footer {
    button {
      width: 80px;
    }
  }
  .header {
    * {
      font-size: 17px !important;
    }
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    padding: 30px;
    .welcome {
      padding-top: 9px;
    }
    .title {
      * {
        color: #6c757d !important;
      }
      a {
        text-decoration: none;
        color: #868e96 !important;
        &:hover {
          color: #6c757d !important;
        }
      }
    }
  }
  .table-container {
    background: #ffffff;
    box-shadow: none !important;
    padding: 30px;
    .controls {
      display: flex;
      justify-content: space-between;
      @media (max-width: 500px) {
        flex-direction: column;
      }
      body[dir="ltr"] & {
        .search {
          i {
            right: 25px;
          }
        }
      }
      body[dir="rtl"] & {
        .search {
          i {
            left: 25px;
          }
        }
      }
      .search {
        margin-bottom: 10px;
        i {
          position: relative;
          top: 1px;
          color: #888888;
        }
        input {
          padding: 4px 15px;
          border: 1px solid #dee2e6 !important;
          border-radius: 5px;
        }
      }
    }
  }
}
</style>