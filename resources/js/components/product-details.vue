<template>
  <div v-if="product" class="product-details-container">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 mb-5">
          <div class="row">
            <div class="col-12">
              <div class="product border">
                <div class="name">
                  <div>
                    <b>{{ product.product_name.nameAr }}</b>
                  </div>
                  <div>{{ product.product_name.nameEn }}</div>
                </div>
                <div class="carts mt-5">
                  <div class="table-responsive">
                    <table class="table table-borderless">
                      <thead>
                        <tr>
                          <th scope="col">{{ $t("SUPPLIER") }}</th>
                          <th scope="col">{{ $t("DISCOUNT") }}</th>
                          <th scope="col">{{ $t("PUBLIC_PRICE") }}</th>
                          <th scope="col">{{ $t("PHARMACY_PRICE") }}</th>
                          <th scope="col">{{ $t("ADD_TO_CART") }}</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(price, index) in product.prices" :key="price.id">
                          <td>{{ price.supplier.name }}</td>
                          <td>
                            <span
                              :class="
                                index == 0
                                  ? 'badge badge-success'
                                  : 'badge badge-secondary'
                              "
                            >
                              {{ price.clientDiscount }}%
                            </span>
                          </td>
                          <td>{{ `${price.publicPrice} ${$t("POUND")} ` }}</td>
                          <td class="">
                            {{ `${price.pharmacyPrice} ${$t("POUND")} ` }}
                          </td>
                          <td>
                            <div class="cart">
                              <template
                                v-if="price.cartClicked || price.supplier.cart_info"
                              >
                                <button
                                  @click="onIncrementClicked(price)"
                                  class="increment mr-2"
                                >
                                  <span>+</span>
                                </button>
                                <input
                                  @blur="updateCartQuantity(price)"
                                  v-model="price.quantity"
                                  class="form-control text-center"
                                />
                                <button
                                  @click="onDecrementClicked(price)"
                                  class="decrement ml-2"
                                >
                                  <span>-</span>
                                </button>
                                <button
                                  @click="removeCartItem(price)"
                                  class="mr-2 delete"
                                >
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
                                <a @click.prevent="addToCart(price)" href="#"
                                  ><i class="fa fa-shopping-basket"></i
                                ></a>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="image border">
            <img class="img-fluid" :src="getImagePath(product.image)" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { inject, onMounted, reactive, toRefs } from "vue-demi";
import { useRoute, useRouter } from "vue-router";
import productClient from "../shared/http-clients/product-client";
import cartClient from "../shared/http-clients/cart-client";
import global from "../shared/consts/global";
export default {
  setup() {
    const data = reactive({
      product: null,
    });
    const route = useRoute();
    const router = useRouter();
    const store = inject("store");
    //Methods
    onMounted(() => {
      getProductDetails();
    });
    function removeCartItem(price) {
      store.showLoader = true;
      cartClient.removeCartItem(route.params.id, price.supplier_id).then(() => {
        store.showLoader = false;
        price.supplier.cart_info = null;
        price.quantity = 0;
        price.cartClicked = false;
        if (countSuppliersAddedToCart() == 0) store.cartItemsCount--;
      });
    }
    function onIncrementClicked(price) {
      price.quantity++;
      updateCartQuantity(price);
    }
    function onDecrementClicked(price) {
      price.quantity--;
      if (price.quantity == 0) {
        removeCartItem(price);
        return;
      }
      updateCartQuantity(price);
    }

    function addToCart(price) {
      if (!store.currentUser) {
        router.push("/login");
        return;
      }
      store.showLoader = true;
      cartClient
        .addToCart({
          product_id: route.params.id,
          supplier_id: price.supplier_id,
        })
        .then(() => {
          store.showLoader = false;
          price.cartClicked = true;
          price.quantity = 1;
          if (countSuppliersAddedToCart() == 1) store.cartItemsCount++;
        });
    }
    function updateCartQuantity(price) {
      store.showLoader = true;
      cartClient
        .updateCartQuantity({
          product_id: route.params.id,
          supplier_id: price.supplier_id,
          quantity: price.quantity,
        })
        .then(() => {
          store.showLoader = false;
        });
    }
    function getImagePath(image) {
      return `${global.DASHBOARD_DOMAIN}/upload/product/${image}`;
    }
    //Commons
    function getProductDetails() {
      store.showLoader = true;
      productClient.getProductDetails(route.params.id).then((response) => {
        data.product = setCartsQuantitiesToPrices(response.data);
        store.showLoader = false;
      });
    }
    function setCartsQuantitiesToPrices(product) {
      product.prices = product.prices.map((price) => {
        return {
          ...price,
          quantity: price.supplier.cart_info ? price.supplier.cart_info.quantity : 0,
        };
      });
      return product;
    }
    function countSuppliersAddedToCart() {
      let counter = 0;
      data.product.prices.forEach((price) => {
        if (price.quantity) counter++;
      });
      return counter;
    }
    return {
      ...toRefs(data),
      addToCart,
      onIncrementClicked,
      onDecrementClicked,
      updateCartQuantity,
      removeCartItem,
      getImagePath,
    };
  },
};
</script>

<style lang="scss" scoped>
.product-details-container {
  padding: 50px 0;
  .badge {
    width: 50px;
    font-size: 14px !important;
    padding: 5px 0;
    &.badge-success {
      background-color: #00dd2f !important;
    }
  }
  table {
    thead {
      background-color: #f5f8fa;
    }
    th,
    td {
      text-align: center;
    }
  }
  .product,
  .image {
    padding: 20px 15px;
    margin-bottom: 18px;
    border-radius: 5px;
    box-shadow: 0 0 8px #b6b6b633;
  }
  .cart {
    color: #0e67d0 !important;
    display: flex;
    align-items: center;
    justify-content: center;
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
