<template>
  <div
    v-if="deal.deal_settings && deal.products.length && endAtGreaterThanCurrentDate()"
    class="deal-container container"
  >
    <section class="ps-section--deals">
      <div class="ps-section__header">
        <h3 class="ps-section__title">{{ $t("BEST_DEALS") }}</h3>
        <div class="ps-countdown">
          <div class="ps-countdown__content">
            <div class="ps-countdown__block ps-countdown__seconds">
              <div class="ps-countdown__number">
                <span class="last">0</span><span class="first">0</span>
              </div>
              <div class="ps-countdown__ref">Secs</div>
            </div>
            <div class="ps-countdown__block ps-countdown__minutes">
              <div class="ps-countdown__number">
                <span class="last">0</span><span class="first">0</span>
              </div>
              <div class="ps-countdown__ref">Mins</div>
            </div>
            <div class="ps-countdown__block ps-countdown__hours">
              <div class="ps-countdown__number">
                <span class="last">0</span><span class="first">0</span>
              </div>
              <div class="ps-countdown__ref">Hours</div>
            </div>
            <div class="ps-countdown__block ps-countdown___days">
              <div class="ps-countdown__number">
                <span class="first-1st">0</span><span class="last">0</span>
                <span class="first">0</span>
              </div>
              <div class="ps-countdown__ref">Days</div>
            </div>
          </div>
        </div>
      </div>
      <div class="ps-section__carousel">
        <div
          class="owl-carousel"
          data-owl-auto="false"
          data-owl-loop="false"
          data-owl-speed="13000"
          data-owl-gap="0"
          data-owl-nav="true"
          data-owl-dots="true"
          data-owl-item="5"
          data-owl-item-xs="2"
          data-owl-item-sm="2"
          data-owl-item-md="3"
          data-owl-item-lg="5"
          data-owl-item-xl="5"
          data-owl-duration="1000"
          data-owl-mousedrag="on"
        >
          <div
            v-for="product in deal.products"
            :key="product.id"
            class="ps-product ps-product--standard border-right"
          >
            <div class="ps-product__thumbnail">
              <a class="ps-product__image" href="product1.html">
                <figure>
                  <img :src="getImagePath(product.image)" alt="alt" />
                </figure>
              </a>
              <div class="ps-product__badge"></div>
              <div class="ps-product__percent">
                %{{ product.deal_price.clientDiscount }}
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
                  {{ product.deal_price.pharmacyPrice }},
                  {{ $t("POUND") }}
                </span>
                <span class="ps-product__del">
                  {{ product.deal_price.publicPrice }}
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
import { inject, onMounted, reactive, toRefs, watch } from "vue-demi";
import { owlCarouselFunction, initializeClock } from "../../custom";
import productClient from "../../shared/http-clients/product-client";
import cartClient from "../../shared/http-clients/cart-client";
import global from "../../shared/consts/global";
import { useRouter } from "vue-router";
import { useI18n } from "vue-i18n";
export default {
  setup(props, context) {
    let data = reactive({
      deal: { deal_settings: null, products: [] },
    });
    const { t, locale } = useI18n({});
    let toast = inject("toast");
    let store = inject("store");
    const router = useRouter();
    onMounted(() => {
      getDeal();
    });
    //Methods
    function getImagePath(image) {
      return `${global.DASHBOARD_DOMAIN}/upload/product/${image}`;
    }

    function endAtGreaterThanCurrentDate() {
      return getEndAt() > new Date();
    }

    function removeCartItem(product) {
      store.showLoader = true;
      cartClient.removeCartItem(product.id, product.deal_price.supplier_id).then(() => {
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
          supplier_id: product.deal_price.supplier_id,
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
      if (!product.quantity || product.quantity == 0) {
        removeCartItem(product);
        return;
      }
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
    function updateCartItemsCount() {
      cartClient.getCartItemsCount().then((response) => {
        store.cartItemsCount = response.data;
      });
    }
    function getDeal() {
      productClient
        .getDeal()
        .then((response) => {
          data.deal = setCartsQuantitiesTodeal(response.data);
        })
        .finally(() => {
          owlCarouselFunction();
          initializeClock(getEndAt());
        });
    }

    function setCartsQuantitiesTodeal(deal) {
      let products = deal.products.map((product) => {
        return {
          ...product,
          quantity: product.cart_info ? product.cart_info.quantity : 1,
        };
      });
      deal.products = products;
      return deal;
    }

    function getEndAt() {
      return new Date(data.deal.deal_settings.end_at);
    }
    return {
      ...toRefs(data),
      getImagePath,
      endAtGreaterThanCurrentDate,
      addToCart,
      onIncrementClicked,
      onDecrementClicked,
      updateCartQuantity,
      removeCartItem,
    };
  },
};
</script>

<style scoped lang="scss">
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
