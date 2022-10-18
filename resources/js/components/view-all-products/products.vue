<template>
  <div class="all-products-container">
    <section class="ps-section--seller-diagnosis">
      <h3 class="ps-section__title">{{ $t("BEST_CLIENT_DISCOUNT_PRODUCTS") }}</h3>
    </section>
    <template v-if="products.length > 0">
      <div class="ps-categogy--grid">
        <div class="row m-0">
          <div
            v-for="product in products"
            :key="product.id"
            class="col-6 col-lg-4 col-xl-3 p-0"
          >
            <div class="ps-product ps-product--standard">
              <div class="ps-product__thumbnail">
                <router-link
                  class="ps-product__image"
                  :to="`/product-details/${product.id}`"
                >
                  <figure>
                    <img :src="getImagePath(product.image)" alt="alt" />
                  </figure>
                </router-link>
                <div class="ps-product__percent">
                  {{ product.biggest_client_discount_price.clientDiscount }}%
                </div>
              </div>
              <div class="ps-product__content">
                <h5 class="ps-product__title">
                  <router-link :to="`/product-details/${product.id}`">
                    <span>{{ product.nameAr }}</span>
                    <br />
                    <span>{{ product.nameEn }}</span>
                  </router-link>
                </h5>
                <div class="ps-product__meta">
                  <span class="ps-product__price sale">
                    {{ product.biggest_client_discount_price.pharmacyPrice }}
                    {{ $t("POUND") }}
                  </span>
                  <span class="ps-product__del">
                    {{ product.biggest_client_discount_price.publicPrice }}
                    {{ $t("POUND") }}
                  </span>
                </div>
                <div class="cart">
                  <template v-if="product.cartClicked || product.cart_info">
                    <button @click="onIncrementClicked(product)" class="increment mr-2">
                      <span>+</span>
                    </button>
                    <input
                      @blur="updateCartQuantity(product)"
                      v-model="product.quantity"
                      class="form-control text-center"
                    />
                    <button @click="onDecrementClicked(product)" class="decrement ml-2">
                      <span>-</span>
                    </button>
                    <button @click="removeCartItem(product)" class="mr-2 delete">
                      <i class="fa fa-trash"></i>
                    </button>
                  </template>
                  <div
                    v-else
                    class="ps-product__item cart"
                    data-toggle="tooltip"
                    data-placement="left"
                    :title="$t('ADD_TO_CART')"
                  >
                    <a @click.prevent="addToCart(product)" href="#">
                      <i class="fa fa-shopping-basket"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="my-5 row justify-content-center">
        <paginate
          v-model="page"
          :pageCount="pageCount"
          :clickHandler="getBiggestClientDiscountProducts"
          prevText="<i class='fa fa-angle-double-right'></i>"
          nextText="<i class='fa fa-angle-double-left'></i>"
        >
        </paginate>
      </div>
    </template>
    <template v-else>
      <div class="empty">
        <i class="fa fa-search"></i>
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
import From from "../../shared/consts/from";
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
    });
    watch(
      () => {
        productStore.categoryId;
        productStore.categoryLevel;
      },
      (value) => {
        data.page = 1;
        getBiggestClientDiscountProducts();
      },
      { deep: true }
    );
    watch(
      () => route,
      () => {
        data.page = 1;
        if (productStore.from == From.SEARCH) productStore.categoryId = null;
        getBiggestClientDiscountProducts();
      },
      {
        deep: true,
        immediate: true,
      }
    );
    //Methods
    function removeCartItem(product) {
      store.showLoader = true;
      cartClient
        .removeCartItem(product.id, product.biggest_client_discount_price.supplier_id)
        .then(() => {
          store.showLoader = false;
          product.cart_info = null;
          product.cartClicked = false;
          if (product.carts_length == 1) store.cartItemsCount--;
          product.carts_length--;
        });
    }
    function onIncrementClicked(product) {
      product.quantity++;
      updateCartQuantity(product);
    }
    function onDecrementClicked(product) {
      product.quantity--;
      if (product.quantity == 0) {
        removeCartItem(product);
        return;
      }
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
          if (product.carts_length == 0) store.cartItemsCount++;
          product.carts_length++;
        });
    }
    function getImagePath(image) {
      return `${global.DASHBOARD_DOMAIN}/upload/product/${image}`;
    }
    function getBiggestClientDiscountProducts() {
      store.showLoader = true;
      productClient
        .getBiggestClientDiscountProducts(
          productStore.categoryId,
          productStore.categoryLevel,
          productStore.name,
          productStore.effectiveMaterial,
          productStore.pharmacologicalFormId,
          productStore.supplierId,
          productStore.discount,
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

<style lang="scss">
.all-products-container {
  .ps-section--seller-diagnosis {
    padding-bottom: 0 !important;
  }
  .ps-product__percent {
    font-size: 14px;
  }
  .page-link {
    color: #0e67d0 !important;
    padding: 5px 12px;
    border: none;
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
    margin: 100px 0;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    color: #0e67d0 !important;
    i {
      margin-bottom: 5px;
      font-size: 20px;
    }
  }
  .active {
    .page-link {
      background-color: #0e67d0 !important ;
      color: #fff !important;
      border-radius: 50px;
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
      height: 25px;
      width: 25px;
      border: none;
      background-color: #0e67d0 !important;
      color: #fff !important;
      border-radius: 50%;
      font-size: 18px;
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
      font-size: 22px;
      margin-right: 7px;
    }
  }
}
</style>
