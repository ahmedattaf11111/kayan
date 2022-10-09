<template>
  <div class="cart-container">
    <div class="container">
      <template v-if="cartProducts.length">
        <h4 class="cart-header">
          {{ `${$t("CART")} (${cartProducts.length})` }}
        </h4>
        <div class="row">
          <div class="col-lg-8 mb-5">
            <div class="row">
              <div class="products-section col-12">
                <div
                  v-for="(product, productIndex) in cartProducts"
                  :key="product.id"
                  class="product border"
                >
                  <div class="header">
                    <div class="first-side">
                      <img :src="getImagePath(product.image)" />
                      <div class="name">
                        <div>
                          <b>{{ product.product_name.nameEn }}</b>
                        </div>
                        <div>{{ product.product_name.nameAr }}</div>
                      </div>
                    </div>
                    <div class="second-side">
                      <button
                        @click="removeCartItems(product, productIndex)"
                        class="text-secondary"
                      >
                        <i class="fa fa-trash"></i>
                      </button>
                    </div>
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
                            <th scope="col">{{ $t("TOTAL_PRICE") }}</th>
                            <th scope="col">{{ $t("QUANTITY") }}</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(cart, cartIndex) in product.carts" :key="cartIndex">
                            <td>{{ cart.supplier.name }}</td>
                            <td>
                              <span
                                :class="
                                  cartIndex == 0
                                    ? 'badge badge-success'
                                    : 'badge badge-secondary'
                                "
                              >
                                {{ cart.price.clientDiscount }}%
                              </span>
                            </td>
                            <td>{{ `${cart.price.publicPrice} ${$t("POUND")}` }}</td>
                            <td>{{ `${cart.price.pharmacyPrice} ${$t("POUND")}` }}</td>
                            <td>
                              {{
                                `${cart.price.pharmacyPrice * cart.quantity} ${$t(
                                  "POUND"
                                )}`
                              }}
                            </td>
                            <td>
                              <div class="cart">
                                <button
                                  @click="onIncrementClicked(product, cart)"
                                  class="increment mr-2"
                                >
                                  <span>+</span>
                                </button>
                                <input
                                  @blur="updateCartQuantity(product, cart)"
                                  v-model="cart.quantity"
                                  class="form-control text-center"
                                />
                                <button
                                  @click="
                                    onDecrementClicked(
                                      product,
                                      productIndex,
                                      cart,
                                      cartIndex
                                    )
                                  "
                                  class="decrement ml-2"
                                >
                                  <span>-</span>
                                </button>
                                <button
                                  @click="
                                    removeCartItem(product, productIndex, cart, cartIndex)
                                  "
                                  class="mr-2 delete"
                                >
                                  <i class="fa fa-trash"></i>
                                </button>
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
            <div class="meta-info border">
              <h4 class="text-secondary">
                {{ `${$t("ORDER_SUMMARY")} (${totalQuantity})` }}
              </h4>
              <div class="px-3">
                <div class="info">
                  <span>{{ $t("TOTAL_BEFORE_DISCOUNT") }}</span>
                  <span>{{ `${totalBeforeDiscount} ${$t("POUND")}` }}</span>
                </div>
                <div class="info total-discount border-bottom">
                  <span
                    ><b>{{ $t("TOTAL_DISCOUNT_VALUE") }}</b></span
                  >
                  <span>
                    <b>{{
                      `(${totalDiscountPercentage}%) ${totalDiscount} ${$t("POUND")}`
                    }}</b>
                  </span>
                </div>
                <div class="info border-bottom">
                  <span>{{ $t("TOTAL_AFTER_DISCOUNT") }}</span>
                  <span>{{ `${totalAfterDiscount} ${$t("POUND")}` }}</span>
                </div>
                <div class="info">
                  <span>{{ $t("NET_INVOICE") }}</span>
                  <span>{{ `${totalAfterDiscount} ${$t("POUND")}` }}</span>
                </div>
              </div>
              <div class="order-submit">
                <router-link to="/order" class="btn">{{
                  $t("COMPLETE_ORDER")
                }}</router-link>
              </div>
            </div>
          </div>
        </div>
      </template>
      <template v-else>
        <div class="cart-empty">
          <i class="fa fa-shopping-basket text-secondary"></i>
          <h4 class="my-4 text-secondary">{{ $t("CART_EMPTY") }}</h4>
          <router-link to="/home">{{ $t("REQUEST_NEW_ORDER") }}</router-link>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
import { inject, onMounted, reactive, toRefs } from "vue-demi";
import cartClient from "../../shared/http-clients/cart-client";
import global from "../../shared/consts/global";
export default {
  setup() {
    const store = inject("store");
    const data = reactive({
      cartProducts: [],
      totalBeforeDiscount: 0,
      totalDiscount: 0,
      totalDiscountPercentage: 0,
      totalAfterDiscount: 0,
      totalQuantity: 0,
    });
    onMounted(() => {
      getUserCart();
    });
    //Methods
    function getImagePath(image) {
      return `${global.DASHBOARD_DOMAIN}/upload/product/${image}`;
    }
    function removeCartItem(product, productIndex, cart, cartIndex) {
      store.showLoader = true;
      cartClient.removeCartItem(product.id, cart.supplier.id).then(() => {
        store.showLoader = false;
        product.carts.splice(cartIndex, 1);
        if (product.carts.length == 0) {
          data.cartProducts.splice(productIndex, 1);
          store.cartItemsCount--;
        }
        calculateCartInfo();
      });
    }

    function removeCartItems(product, productIndex) {
      store.showLoader = true;
      cartClient.removeCartItem(product.id, null).then(
        (response) => {
          store.showLoader = false;
          data.cartProducts.splice(productIndex, 1);
          calculateCartInfo();
          store.cartItemsCount--;
        },
        (error) => {
          console.log(error.response);
        }
      );
    }
    function onIncrementClicked(product, cart) {
      cart.quantity++;
      updateCartQuantity(product, cart);
    }
    function onDecrementClicked(product, productIndex, cart, cartIndex) {
      cart.quantity--;
      if (cart.quantity == 0) {
        removeCartItem(product, productIndex, cart, cartIndex);
        return;
      }
      updateCartQuantity(product, cart);
    }
    function updateCartQuantity(product, cart) {
      store.showLoader = true;
      cartClient
        .updateCartQuantity({
          product_id: product.id,
          supplier_id: cart.supplier.id,
          quantity: cart.quantity,
        })
        .then(() => {
          store.showLoader = false;
          calculateCartInfo();
        });
    }

    //Commons
    function getUserCart() {
      store.showLoader = true;
      cartClient.getUserCart().then((response) => {
        data.cartProducts = response.data;
        store.showLoader = false;
        calculateCartInfo();
      });
    }

    function calculateCartInfo() {
      let totalBeforeDiscount = 0;
      let totalAfterDiscount = 0;
      let totalQuantity = 0;
      data.cartProducts.forEach((product) => {
        product.carts.forEach((cart) => {
          let quantity = Number.parseInt(cart.quantity);
          totalBeforeDiscount += cart.price.publicPrice * quantity;
          totalAfterDiscount += cart.price.pharmacyPrice * quantity;
          totalQuantity += quantity;
        });
      });
      data.totalBeforeDiscount = totalBeforeDiscount;
      data.totalAfterDiscount = totalAfterDiscount;
      data.totalQuantity = totalQuantity;
      calculateTotalDiscount();
    }

    function calculateTotalDiscount() {
      data.totalDiscount = data.totalBeforeDiscount - data.totalAfterDiscount;
      data.totalDiscountPercentage = Math.ceil(
        (data.totalDiscount / data.totalBeforeDiscount) * 100
      );
    }

    return {
      ...toRefs(data),
      onIncrementClicked,
      onDecrementClicked,
      updateCartQuantity,
      removeCartItem,
      removeCartItems,
      getImagePath,
    };
  },
};
</script>

<style lang="scss" scoped>
.cart-container {
  .badge {
    width: 50px;
    font-size: 14px !important;
    padding: 5px 0;
    &.badge-success {
      background-color: #00dd2f !important;
    }
  }
  .cart-empty {
    margin: 100px 0;
    display: flex;
    align-items: center;
    flex-direction: column;
    i {
      font-size: 60px;
    }
    a {
      text-align: center;
      border-radius: 5px;
      width: 200px;
      color: #fff;
      background-color: #0e67d0;
      font-size: 16px;
      padding: 15px;
      border: none;
    }
    i,
    h4 {
      color: #d9d9d9 !important;
    }
  }
  padding: 50px 0;
  .cart-header {
    color: #0e67d0;
  }
  .products-section {
    max-height: 400px;
    overflow: auto;
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
  .product {
    .header {
      display: flex;
      justify-content: space-between;
      .first-side {
        display: flex;
        align-items: center;
        img {
          margin-left: 25px;
          width: 40px;
          height: 40px;
        }
      }
      .second-side {
        button {
          font-size: 17px;
          background: none;
          border: none;
        }
      }
    }
  }
  .product,
  .meta-info {
    padding: 20px 15px;
    margin-bottom: 18px;
    border-radius: 5px;
    box-shadow: 0 0 8px #b6b6b633;
  }
  .meta-info {
    .total-discount {
      font-size: 13px;
    }
    .info {
      display: flex;
      justify-content: space-between;
      padding-bottom: 10px;
      margin-bottom: 10px;
    }
  }
  .order-submit {
    display: flex;
    justify-content: center;
    padding: 0 15px;
    a {
      border-radius: 5px;
      width: 100%;
      color: #fff;
      background-color: #0e67d0;
      font-size: 16px;
      padding: 10px;
    }
  }
  .cart {
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
