<template>
  <div class="all-products-container">
    <section class="ps-section--seller-diagnosis">
      <h3 class="ps-section__title"  style="margin-bottom: 0px;margin-top: 30px;">{{ $t("BEST_DEAL") }}</h3>
    </section>
    <template v-if="products.length > 0">
      <div class="ps-categogy--grid" style="border:0;margin-top: 0;">
        <div class="row m-0">
          <div v-for="product in products" :key="product.id" class="col-6 col-lg-4 col-xl-3 p-0">
            <div class="border ps-product ps-product--standard" style="margin:5px;border:0;border-radius: 5px;">
              <div class="ps-product__thumbnail">
                <router-link class="ps-product__image" :to="`/product-details/${product.id}`">
                  <figure>
                    <img :src="product.image" alt="alt" />
                  </figure>
                </router-link>
                <div class="ps-product__percent">
                  {{ product.biggest_client_discount_price.client_discount }}%
                  <br />
                  {{ $t("DISCOUNT") }}
                </div>
              </div>
              <hr />
              <div class="ps-product__content">
                <h5 class="ps-product__title">
                  <router-link :to="`/product-details/${product.id}`">
                    <span>{{ product.name }}</span>
                  </router-link>
                  <p style="font-size: 13px;">
                    <i class="fa fa-eyedropper" style="margin-left:5px" aria-hidden="true"></i>
                    {{ product.biggest_client_discount_price.supplier.name }}
                  </p>
                </h5>
                <div class="ps-product__meta">
                  <span class="ps-product__price sale">
                    {{ getClientPrice(product) }}
                    {{ $t("POUND") }}
                  </span>
                  <span class="ps-product__del">
                    {{ product.public_price }}
                    {{ $t("POUND") }}
                  </span>
                </div>
                <div class="cart">
                  <template v-if="product.cartClicked || product.cart_info">
                    <button @click="onIncrementClicked(product)" class="increment mr-2">
                      <span>+</span>
                    </button>
                    <input @blur="updateCartQuantity(product)" v-model="product.quantity"
                      class="form-control text-center" />
                    <button @click="onDecrementClicked(product)" class="decrement ml-2">
                      <span>-</span>
                    </button>
                    <button @click="removeCartItem(product)" class="mr-2 delete">
                      <i class="fa fa-trash"></i>
                    </button>
                  </template>
                  <button v-else class="btn btn-cart" @click.prevent="addToCart(product)">
                    <i class="fa fa-cart-plus" style="margin-left:5px" aria-hidden="true"></i> {{ $t('ADD_TO_CART') }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="my-5 row justify-content-center">
        <paginate v-model="page" :pageCount="pageCount" :clickHandler="getBiggestClientDiscountProducts"
          prevText="<i class='fa fa-angle-double-right'></i>" nextText="<i class='fa fa-angle-double-left'></i>">
        </paginate>
      </div>
    </template>
    <template v-else>
      <div class="empty">
        <div>
          {{ $t("NO_DATA") }}
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import { inject, onMounted, reactive, toRefs, watch } from "vue-demi";
import productClient from "../../shared/http-clients/product-client";
import cartClient from "../../shared/http-clients/cart-client";
import global from "../../shared/consts/global";
import Paginate from "vuejs-paginate-next";
import productStore from "./store";
import { useRoute, useRouter } from "vue-router";
export default {
  components: {
    Paginate,
  },
  setup() {
    const store = inject("store");
    const route = useRoute();
    const router = useRouter();
    let data = reactive({
      products: [],
      page: 1,
      pageSize: 8,
      pageCount: 0,
      timeout: null,  
    });
    watch(
      () => route,
      () => {
        data.page = 1;
        if (productStore.categoryId) {
          productStore.categoriesIds = [productStore.categoryId];
        }
        else {
          productStore.categoriesIds = [];
        }
        if (productStore.companyId) {
          productStore.companiesIds = [productStore.companyId];
        }
        else {
          productStore.companiesIds = [];
        }
        if (productStore.pharmacologicalFormId) {
          productStore.pharmacologicalFormIds = [productStore.pharmacologicalFormId];
        }
        else {
          productStore.pharmacologicalFormIds = [];
        }
        if (productStore.effectiveMaterial) {
          productStore.effectiveMaterialText = productStore.effectiveMaterial;
        }
        else {
          productStore.effectiveMaterialText = "";
        }
        getBiggestClientDiscountProducts();
      },
      {
        deep: true,
        immediate: true,
      }
    );
    watch(
      () => productStore.categoriesIds,
      () => {
        productStore.categoryId = null;
        productStore.name = null;
        productStore.effectiveMaterial = null;
        productStore.pharmacologicalFormId = null;
        productStore.companyId = null;
        data.page = 1;
        getBiggestClientDiscountProducts();
      },
      {
        deep: true,
      }
    );
    watch(
      () => productStore.companiesIds,
      () => {
        productStore.categoryId = null;
        productStore.name = null;
        productStore.effectiveMaterial = null;
        productStore.pharmacologicalFormId = null;
        productStore.companyId = null;
        data.page = 1;
        getBiggestClientDiscountProducts();
      },
      {
        deep: true,
      }
    );
    watch(
      () => productStore.pharmacologicalFormIds,
      () => {
        productStore.categoryId = null;
        productStore.name = null;
        productStore.effectiveMaterial = null;
        productStore.pharmacologicalFormId = null;
        productStore.companyId = null;
        data.page = 1;
        getBiggestClientDiscountProducts();
      },
      {
        deep: true,
      }
    );
    watch(
      () => productStore.effectiveMaterialText,
      () => {
        // clear timeout variable
        clearTimeout(data.timeout);
        data.timeout = setTimeout(() => {
          productStore.categoryId = null;
          productStore.name = null;
          productStore.effectiveMaterial = null;
          productStore.pharmacologicalFormId = null;
          productStore.companyId = null;
          data.page = 1;
          getBiggestClientDiscountProducts();
        }, 500);
      },
      {
        deep: true,
      }
    );
    //Methods
    function getClientPrice(product) {
      let discountVal = product.public_price *
        (product.biggest_client_discount_price.client_discount / 100);
      return product.public_price - discountVal;
    }
    function removeCartItem(product) {
      store.showLoader = true;
      cartClient
        .removeCartItem(product.id, product.biggest_client_discount_price.supplier_id)
        .then(() => {
          store.showLoader = false;
          product.cart_info = null;
          product.cartClicked = false;
          updateCartItemsCount();
        });
    }
    function onIncrementClicked(product) {
      product.quantity++;
      updateCartQuantity(product);
    }
    function onDecrementClicked(product) {
      product.quantity--;
      updateCartQuantity(product);
    }
    function addToCart(product) {
      if (!store.currentUser) {
        router.push("/login");
        return;
      }
      store.showLoader = true;
      cartClient
        .addToCart({
          product_id: product.id,
          supplier_id: product.biggest_client_discount_price.supplier_id,
        })
        .then(() => {
          store.showLoader = false;
          product.cartClicked = true;
          product.quantity = 1;
          updateCartItemsCount();
        });
    }
    function getImagePath(image) {
      return `${global.DASHBOARD_DOMAIN}/upload/product/${image}`;
    }
    function getBiggestClientDiscountProducts() {
      store.showLoader = true;
      productClient
        .getBiggestClientDiscountProducts(
          productStore.categoriesIds,
          productStore.name,
          productStore.effectiveMaterialText,
          productStore.pharmacologicalFormIds,
          productStore.companiesIds,
          data.page,
          data.pageSize
        )
        .then((response) => {
          store.showLoader = false;
          data.products = setCartsQuantitiesToProducts(response.data.data);
          data.pageCount = Math.ceil(response.data.total / data.pageSize);
        });
    }
    function updateCartQuantity(product) {
      if (!product.quantity || product.quantity == 0) {
        removeCartItem(product);
        return;
      }
      store.showLoader = true;
      cartClient
        .updateCartQuantity({
          product_id: product.id,
          supplier_id: product.biggest_client_discount_price.supplier_id,
          quantity: product.quantity,
        })
        .then(() => {
          store.showLoader = false;
        });
    }
    //Commons
    function updateCartItemsCount() {
      cartClient.getCartItemsCount().then((response) => {
        store.cartItemsCount = response.data;
      });
    }
    function setCartsQuantitiesToProducts(products) {
      return products.map((product) => {
        return {
          ...product,
          quantity: product.cart_info ? product.cart_info.quantity : 1,
        };
      });
    }
    return {
      ...toRefs(data),
      getClientPrice,
      getBiggestClientDiscountProducts,
      getImagePath,
      addToCart,
      onIncrementClicked,
      onDecrementClicked,
      updateCartQuantity,
      removeCartItem,
    };
  },
};
</script>

<style  lang="scss">
@media (max-width:767px){
  .all-products-container{
    padding: 10px;
  }
}
.all-products-container {
  .ps-section--seller-diagnosis {
    padding-bottom: 0 !important;
  }

  .ps-categogy--grid .ps-product--standard {
    height: unset !important;
  }

  .btn-cart {
    font-size: 16px;
    border-radius: 28px;
    padding: 6px;
    background-color: #0e67d0;
    border-color: none;
    width: 200px;
    color: #fff;
  }

  .ps-product__del {
    position: relative;
    font-size: 14px;
    top: 6px;
  }

  .ps-product__meta {
    display: flex;
    justify-content: space-between;
  }

  .ps-product__percent {
    right: unset;
    background-color: #3c6;
    position: absolute;
    top: 0;
    left: 6px;
    z-index: 10;
    border-radius: 0px 0px 10px 10px;
    width: 70px;
    height: 44px;
    font-size: 14px;
  }

  .active {
    .page-link {
      background-color: #0e67d0 !important;
      color: #fff !important;
    }
  }

  .page-link {
    color: #0e67d0 !important;
    width: 30px;
    height: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
    border: none;
    border-radius: 50%;

    &:hover {
      background: none;
    }

    &:focus {
      box-shadow: none;
    }
  }

  .cart {
    margin-top: 18px;
    color: #0e67d0 !important;
  }

  .empty {
    font-size: 35px;
    margin: 100px 0;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    color: #b6b6b6 !important;

    i {
      margin-bottom: 5px;
      font-size: 37px;
    }
  }

  .cart {
    margin-top: 5px;
    display: flex;
    align-items: center;
    font-size: 18px;

    .form-control {
      border-radius: 5px;
      width: 90px;
      height: 30px;
    }

    .decrement,
    .increment {
      height: 26px;
      border: none;
      background-color: #0e67d0 !important;
      color: #fff !important;
      border-radius: 4px;
      font-size: 20px;
      padding: 0 10px;
    }

    .decrement {
      span {
        position: relative;
        bottom: 6px;
      }
    }

    .increment {
      span {
        position: relative;
        bottom: 4px;
      }
    }

    .delete {
      background: none;
      border: none;
      color: #0e67d0;
      background: none;
      font-size: 26px;
      margin-right: 7px;
    }
  }
}
</style>
