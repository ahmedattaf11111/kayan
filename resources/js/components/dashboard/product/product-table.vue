<template>
  <div class="p-3 categories-container">
    <DeleteConfirmation @confirm="deleteProduct" @closed="selectedProduct = null" />
    <ProductForm @created="onCreated" @updated="onUpdated" :selectedProduct="selectedProduct" />
    <div class="header">
      <h2 class="welcome">
        <b>{{ $t("PRODUCTS") }}</b
        >, {{ $t("WELCOME_HERE") }}
      </h2>
      <div class="title">
        <router-link to="/admin-panel-settings">{{ $t("HOME") }}</router-link>
        /
        <span>{{ $t("HELLO") }}</span>
      </div>
    </div>
    <div class="px-4">
      <div class="table-container">
          <div class="table-responsive">
            <div class="controls">
              <div class="search">
                <input
                  v-model="text"
                  type="text"
                  :placeholder="$t('SEARCH')"
                  ref="search"
                />
                <i class="fa fa-search"></i>
              </div>
              <div class="actions my-2">
                <button
                  @click="onAddClicked()"
                  data-toggle="modal"
                  data-target="#productFormModal"
                  class="border text-secondary"
                >
                  <i class="fa fa-plus" aria-hidden="true"></i>
                </button>
                <button @click="downloadExcelFile" class="border text-secondary">
                  <i class="fa fa-download" aria-hidden="true"></i>
                </button>
                <button @click="print" class="border text-secondary">
                  <i class="fa fa-print" aria-hidden="true"></i>
                </button>
              </div>
            </div>
            <table id="printMe" class="table">
              <thead>
                <tr>
                  <th scope="col">{{ $t("IMAGE") }}</th>
                  <th scope="col">{{ $t("NAME") }}</th>
                  <th scope="col">{{ $t("CATEGORY") }}</th>
                  <th scope="col">{{ $t("PHARMACIST_FORMS") }}</th>
                  <th scope="col">{{ $t("COMPANY") }}</th>
                  <th scope="col">{{ $t("effective_material") }}</th>
                  <th scope="col">{{ $t("public_price") }}</th>
                  <th class="actions-header" scope="col">{{ $t("ACTIONS") }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(product, index) in products" :key="product.id">
                  <td><img :src="product.image" /></td>
                  <td>{{ product.name }}</td>
                  <td>{{ product.category.name }}</td>
                  <td>{{ product.company.name }}</td>
                  <td>{{ product.pharmacist_form.name }}</td>
                  <td>{{ product.effective_material }}</td>
                  <td>{{ product.public_price }}</td>
                  <td class="actions-cell">
                    <div class="actions">
                      <button
                        @click="onEditClicked(product, index)"
                        data-toggle="modal"
                        data-target="#productFormModal"
                        class="border text-secondary"
                      >
                        <i class="fa fa-edit" aria-hidden="true"></i>
                      </button>
                      <button
                        @click="onDeleteClicked(product, index)"
                        data-toggle="modal"
                        data-target="#deleteConfirmationModal"
                        class="border text-secondary"
                      >
                        <i class="fa fa-trash" aria-hidden="true"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        <div class="mt-1">
          <paginate
            v-model="page"
            :pageCount="pageCounts"
            :clickHandler="getProducts"
            :prevText="$t('PREV')"
            :nextText="$t('NEXT')"
          >
          </paginate>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import productClient from "../../../shared/http-clients/admin/product-client";
import Paginate from "vuejs-paginate-next";
import exportFromJSON from "export-from-json";
import DeleteConfirmation from "../../../shared/components/delete-confirmation.vue";
import ProductForm from "./product-form.vue";
import productStore from "./product-store";
import { inject, provide, reactive, ref, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  components: {
    Paginate,
    DeleteConfirmation,
    ProductForm,
  },
  setup() {
    const data = reactive({
      pageSize: 6,
      page: 1,
      products: [],
      text: "",
      pageCounts: 0,
      timeout: null,  
      selectedProduct: null,
      selectedProductIndex: 0,
    });
    const toast = inject("toast");
    const { t, locale } = useI18n({ useScope: "global" });
    provide("product_store", productStore);
    created();
    //Methods
    function onAddClicked() {
      data.selectedProduct = null;
      //Make little delay to ensure that watcher that found in category form component
      // catch selectedProduct input prop
      setTimeout(() => {
        productStore.onFormShow = !productStore.onFormShow;
      }, 1);
    }
    function onEditClicked(category, index) {
      data.selectedProduct = category;
      data.selectedProductIndex = index;
      //Make little delay to ensure that watcher catch selectedProduct input prop
      //in category form component
      setTimeout(() => {
        productStore.onFormShow = !productStore.onFormShow;
      }, 1);
    }
    function onDeleteClicked(category, index) {
      data.selectedProduct = category;
      data.selectedProductIndex = index;
    }
    function getProducts() {
      productClient
        .getProducts(data.page, data.pageSize, data.text)
        .then((response) => {
          data.products = response.data.data;
          data.pageCounts = Math.ceil(response.data.total / data.pageSize);
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function downloadExcelFile() {
      productClient.getAllProducts().then((res) => {
        let data = res.data;
        const fileName = "products";
        const exportType = exportFromJSON.types.csv;
        if (data) exportFromJSON({ data, fileName, exportType });
      });
    }
    function print() {
      window.print();
    }
    function onCreated(event) {
      data.page = 1;
      getProducts();
    }
    function onUpdated(event) {
      data.selectedProduct = null;
      getProducts();
    }
    function deleteProduct() {
      productClient
        .delete(data.selectedProduct.id)
        .then((response) => {
          toast.success(t("DELETED_SUCCESSFULLY"));
          if (data.page > 1 && data.products.length==1) {
            data.page--;
          }
          getProducts();
          data.selectedProduct = null;
        })
        .catch((error) => {});
    }
    function search() {
      data.page = 1;
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getProducts();
      }, 500);
    }
    watch(
      () => {
        data.text;
      },
      (value) => {
        search();
      },
      { deep: true }
    );
    //Commons
    function created() {
      getProducts();
    }
    return {
      ...toRefs(data),
      onAddClicked,
      onEditClicked,
      onDeleteClicked,
      getProducts,
      downloadExcelFile,
      onCreated,
      onUpdated,
      deleteProduct,
      search,
      print,
    };
  },
};
</script>

<style lang="scss">
@media print {
  body * {
    visibility: hidden;
  }
  #printMe,
  #printMe * {
    visibility: visible;
  }
  .actions-header,
  .actions-cell {
    display: none !important;
  }
  #printMe {
    position: absolute;
    top: 0;
    left: 0;
  }
}
.categories-container {
  td {
    img {
      width: 70px;
      height: 70px;
      border-radius: 3px;
      padding: 5px;
      box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
        rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
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
    box-shadow: 0 5px 20px rgb(0 0 0 / 10%);
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
    .actions {
      display: flex;
      a:hover {
        cursor: text;
      }
      button {
        width: 34px;
        height: 34px;
        background: none;
        margin: 3px 5px;
        border-radius: 5px;
      }
    }
    a:hover {
      cursor: pointer;
    }
    .active {
      a {
        color: #fff !important;
        background-color: #6d85fb !important;
        border-color: #dbdbdb !important;
      }
    }
    .page-link {
      padding: 3px 18px !important;
    }
    table {
      td,
      th {
        width: 11.11111111111111%;
      }
    }
  }
}
</style>
