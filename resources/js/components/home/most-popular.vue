<template>
  <div
    v-if="mostPopular.most_popular_settings && mostPopular.products.length"
    class="container"
  >
    <section class="ps-section--featured">
      <h3 class="ps-section__title">
        {{ $t("MOST_N_POPULAR", { N: mostPopular.most_popular_settings.limit }) }}
      </h3>
      <div class="row m-0 border-left border-top">
        <div
          class="col-6 col-md-4 col-lg-2dot4 p-0 border-right border-bottom"
          v-for="product in mostPopular.products"
          :key="product.id"
        >
          <div class="ps-product ps-product--standard">
            <div class="ps-product__thumbnail">
              <router-link
                class="ps-product__image"
                :to="`/product-details/${product.id}`"
                >ٍ
                <figure>
                  <img :src="getImagePath(product.image)" alt="alt" />
                </figure>
              </router-link>
              <div class="ps-product__badge"></div>
              <div class="ps-product__percent">%{{ product.price.clientDiscount }}</div>
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
                  {{ product.price.pharmacyPrice }},
                  {{ $t("POUND") }}
                </span>
                <span class="ps-product__del">
                  {{ product.price.publicPrice }}
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
    </section>
  </div>
</template>

<script>
import { inject, onMounted, reactive, toRefs } from "vue-demi";
import productClient from "../../shared/http-clients/product-client";
import cartClient from "../../shared/http-clients/cart-client";
import global from "../../shared/consts/global";
import { useRouter } from "vue-router";
import { useI18n } from "vue-i18n";
export default {
  setup(props, context) {
    let data = reactive({
      mostPopular: { most_popular_settings: null, products: [] },
    });
    let store = inject("store");
    let toast = inject("toast");
    const router = useRouter();
    const { t, locale } = useI18n({});
    onMounted(() => {
      getMostPopulars();
    });
    //Methods
    function getImagePath(image) {
      return `${global.DASHBOARD_DOMAIN}/upload/product/${image}`;
    }

    function removeCartItem(product) {
      store.showLoader = true;
      cartClient.removeCartItem(product.id, product.price.supplier_id).then(() => {
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
          supplier_id: product.price.supplier_id,
        })
        .then(() => {
          store.showLoader = false;
          product.cartClicked = true;
          product.quantity = 1;
          updateCartItemsCount();
        })
        .catch((error) => {
          store.showLoader = false;
          toast.error(t("ADDED_BEFORE_TO_CART"));
        });
    }
    function updateCartQuantity(product) {
      store.showLoader = true;
      cartClient
        .updateCartQuantity({
          product_id: product.id,
          quantity: product.quantity,
        })
        .then(() => {
          store.showLoader = false;
        });
    }
    //Commons
    function getMostPopulars() {
      productClient.getMostPopulars().then((response) => {
        data.mostPopular = setCartsQuantitiesToMostPopular(response.data);
      });
    }
    function setCartsQuantitiesToMostPopular(mostPopular) {
      let products = mostPopular.products.map((product) => {
        return {
          ...product,
          quantity: product.cart_info ? product.cart_info.quantity : 1,
        };
      });
      mostPopular.products = products;
      return mostPopular;
    }

    function updateCartItemsCount() {
      cartClient.getCartItemsCount().then((response) => {
        store.cartItemsCount = response.data;
      });
    }
    return {
      ...toRefs(data),
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

<style lang="scss" scoped>
.ps-product__percent {
  font-size: 14px;
}
.cart {
  margin-top: 5px;
  color: #0e67d0 !important;
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
</style>
