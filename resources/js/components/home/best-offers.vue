<template>
  <div v-if="products.length" class="best-seller-container container">
    <section class="ps-section--deals">
      <div class="ps-section__header">
        <h5 class="ps-section__title">
          {{ $t("BEST_DEAL") }}
        </h5>
        <router-link style="position: relative;top:22px" to="/best-client-discount-products">{{ $t("ALL_DEALS")
        }}</router-link>
      </div>
      <div class="ps-section__carousel border">
        <div class="owl-carousel" data-owl-auto="false" data-owl-loop="false" data-owl-speed="13000" data-owl-gap="0"
          data-owl-nav="true" data-owl-dots="true" data-owl-item="5" data-owl-item-xs="2" data-owl-item-sm="2"
          data-owl-item-md="3" data-owl-item-lg="5" data-owl-item-xl="5" data-owl-duration="1000" data-owl-mousedrag="on">
          <div v-for="product in products" :key="product.id" class="ps-product ps-product--standard border-right">
            <div class="ps-product__thumbnail">
              <router-link class="ps-product__image" :to="`/product-details/${product.id}`">ٍ
                <figure>
                  <img :src="product.image" alt="alt" />
                </figure>
              </router-link>
              <div class="ps-product__badge"></div>
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
                  <br />
                  <!-- <span>{{ product.name_e }}</span> -->
                </router-link>
                <p style="font-size: 13px;">
                  <i class="fa fa-eyedropper" style="margin-left:5px" aria-hidden="true"></i>
                  {{ product.biggest_client_discount_price.supplier.name }}
                </p>
              </h5>
              <div class="ps-product__meta">
                <span class="ps-product__price sale">
                  {{ getClientPrice(product.public_price, product.biggest_client_discount_price.client_discount) }},
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
    </section>
  </div>
</template>

<script>
import { inject, onMounted, reactive, toRefs } from "vue-demi";
import { owlCarouselFunction } from "../../custom";
import productClient from "../../shared/http-clients/product-client";
import cartClient from "../../shared/http-clients/cart-client";
import global from "../../shared/consts/global";
import { useRouter } from "vue-router";
import { useI18n } from "vue-i18n";
export default {
  setup(props, context) {
    let data = reactive({
      products: []
    });
    let store = inject("store");
    let toast = inject("toast");
    const { t, locale } = useI18n({});
    const router = useRouter();
    onMounted(() => {
      getBiggestClientDiscountProducts();
    });
    //Methods
    function getClientPrice(publicPrice, clientDiscount) {
      let discountVal = publicPrice *
        (clientDiscount / 100);
      return publicPrice - discountVal;
    }
    function getImagePath(image) {
      return `${global.DASHBOARD_DOMAIN}/upload/product/${image}`;
    }
    function removeCartItem(product) {
      store.showLoader = true;
      cartClient.removeCartItem(product.id, product.biggest_client_discount_price.supplier_id).then(() => {
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
    function getBiggestClientDiscountProducts() {
      productClient
        .getBiggestClientDiscountProducts("", "", "", "", "", 1, 10)
        .then((response) => {
          data.products = setCartsQuantitiesToBestSeller(response.data.data);
        })
        .finally(() => {
          owlCarouselFunction();
        });
    }
    function setCartsQuantitiesToBestSeller(products) {
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

<style scoped lang="scss">
.ps-product__percent {
  font-size: 14px;
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
  margin-top: 25px;
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

.ps-section__header {
  justify-content: space-between !important;
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

  .ps-section__carousel {
    border: none !important;
  }
}</style>
