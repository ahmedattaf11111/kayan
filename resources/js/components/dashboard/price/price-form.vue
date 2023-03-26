<template>
  <div class="price-form">
    <div class="modal fade" id="priceFormModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title text-secondary" id="exampleModalLabel">
                {{ $t("FORM") + " " + $t("PRICE") }}
              </h5>
              <button type="button" class="btn-close" aria-label="Close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="inputState">{{ $t("SUPPLIER") }}</label>
                      <select :class="{
                        'is-invalid': v$.supplier_id.$error,
                      }" v-model="v$.supplier_id.$model" class="form-control">
                        <option v-for="supplier in suppliers" :value="supplier.id">{{ supplier.name }}</option>
                      </select>
                      <div class="invalid-feedback">
                        <div v-for="error in v$.supplier_id.$errors" :key="error">
                          {{ $t("SUPPLIER") + " " + $t(error.$validator) }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="inputState">{{ $t("PRODUCT") }}</label>
                      <select :class="{
                        'is-invalid': v$.product_id.$error,
                      }" v-model="v$.product_id.$model" class="form-control">
                        <option v-for="product in products" :value="product.id">{{ product.name }}</option>
                      </select>
                      <div class="invalid-feedback">
                        <div v-for="error in v$.product_id.$errors" :key="error">
                          {{ $t("PRODUCT") + " " + $t(error.$validator) }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">{{
                        $t("client_discount")
                      }}</label>
                      <input type="text" class="form-control" v-model="v$.client_discount.$model" :class="{
                        'is-invalid': v$.client_discount.$error,
                      }" />
                      <div class="invalid-feedback">
                        <div v-for="error in v$.client_discount.$errors" :key="error">
                          {{ $t("client_discount") + " " + $t(error.$validator, { value: 0, maxValue: 100 }) }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">{{
                        $t("commission")
                      }}</label>
                      <input type="text" class="form-control" v-model="v$.commission.$model" :class="{
                        'is-invalid': v$.commission.$error,
                      }" />
                      <div class="invalid-feedback">
                        <div v-for="error in v$.commission.$errors" :key="error">
                          {{ $t("commission") + " " + $t(error.$validator, { value: 0, maxValue: 100 }) }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-danger">
                {{ $t("SUBMIT") }}
              </button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">
                {{ $t("CLOSE") }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import useVuelidate from "@vuelidate/core";
import { required, minValue, helpers, maxValue } from "@vuelidate/validators";
import priceClient from "../../../shared/http-clients/admin/price-client";
import { computed, inject, reactive, watch, toRefs, onMounted } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const price_store = inject("price_store");
    const toast = inject("toast");
    const data = reactive({
      suppliers: [],
      products: []
    });
    const form = reactive({
      supplier_id: null,
      product_id: null,
      commission: 0,
      client_discount: 0,
    });
    const rules = {
      supplier_id: { required },
      product_id: { required },
      client_discount: { required, minValue: minValue(0), maxValue: maxValue(100) },
      commission: { required, minValue: minValue(0), maxValue: maxValue(100) },
    };
    const v$ = useVuelidate(rules, form);
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedPrice) {
        store();
      } else {
        update();
      }
    }
    onMounted(() => {
      getSuppliers();
      getProducts();
    })
    //Commons
    function store() {
      priceClient
        .store(getForm())
        .then((response) => {
          toast.success(t("CREATED_SUCCESSFULLY"));
          context.emit("created", response.data);
          $("#priceFormModal").modal("hide");
        })
        .catch((error) => {
        });
    }
    function update() {
      priceClient
        .update(getForm())
        .then((response) => {
          toast.success(t("UPDATED_SUCCESSFULLY"));
          context.emit("updated", response.data);
          $("#priceFormModal").modal("hide");
        })
        .catch((error) => {
        });
    }
    function getSuppliers() {
      priceClient
        .getSuppliers()
        .then((response) => {
          data.suppliers = response.data;
        })
    }
    function getProducts() {
      priceClient
        .getProducts()
        .then((response) => {
          data.products = response.data;
        })
    }
    function getForm() {
      return {
        id: props.selectedPrice ? props.selectedPrice.id : null,
        client_discount: form.client_discount,
        commission: form.commission,
        supplier_id: form.supplier_id,
        product_id: form.product_id,
      }
    }
    function setForm() {
      v$.value.$reset();
      console.log(props.selectedPrice)
      form.client_discount = props.selectedPrice ? props.selectedPrice.client_discount : "";
      form.commission = props.selectedPrice ? props.selectedPrice.commission : "";
      form.supplier_id = props.selectedPrice ? props.selectedPrice.supplier_id : "";
      form.product_id = props.selectedPrice ? props.selectedPrice.product_id : "";
    }
    //Watchers
    watch(
      () => {
        price_store.onFormShow;
      },
      (value) => {
        setForm();
      },
      { deep: true }
    );
    return {
      ...toRefs(data),
      ...toRefs(form),
      v$,
      save,
    };
  },
  props: ["selectedPrice"],
};
</script>

<style scoped lang="scss">
.price-form {
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
    font-size: 16px !important;
    justify-content: center;
    display: flex;
    width: 37px;
    text-align: center;
    background-color: #f8f9fa;
    border-radius: 5px;
    align-items: center;
  }

  .modal-footer {
    button {
      width: 80px;
    }
  }
}
</style>