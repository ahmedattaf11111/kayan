<template>
  <div class="orders-container container my-5">
    <div class="row">
      <div class="col-md-4">
        <Sidebar activePath="orders" />
      </div>
      <div class="col-md-8">
        <div class="search border">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <div class="form-group">
                  <label class="labels">{{ $t("FROM") }}</label
                  ><input v-model="from" type="date" class="form-control" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <div class="form-group">
                  <label class="labels">{{ $t("TO") }}</label
                  ><input v-model="to" type="date" class="form-control" />
                </div>
              </div>
            </div>
            <div class="col-12">
              <button @click="seach" class="btn">{{ $t("SEARCH") }}</button>
              <button @click="deleteSearch" class="btn">{{ $t("DELETE") }}</button>
            </div>
          </div>
        </div>
        <template v-if="orders.length">
          <div v-for="order in orders" :key="order.id" class="order-container">
            <div class="meta-info border">
              <h4 class="text-secondary pb-3">
                {{ `${$t("ORDER_SUMMARY_NUMBER")} (${order.id})` }}
              </h4>
              <div class="row">
                <div class="col-md-6">
                  <div class="info border-bottom">
                    <span>{{ $t("ORDER_STATUS") }}</span>
                    <span>{{ $t(order.order_status) }}</span>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="info border-bottom">
                    <span>{{ $t("PAYMENT_STATUS") }}</span>
                    <span>{{ $t(order.payment_status) }}</span>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="info border-bottom">
                    <span>{{ $t("PAYMENT_METHOD") }}</span>
                    <span>{{ $t(order.payment_method) }}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="products-section">
              <div
                v-for="product in order.products_list"
                :key="product.id"
                class="product border"
              >
                <div class="header">
                  <div class="first-side">
                    <img :src="getImagePath(product.image)" />
                    <div class="name">
                      <div>
                        <b>{{ product.nameEn }}</b>
                      </div>
                      <div>{{ product.nameAr }}</div>
                    </div>
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
                        <tr
                          v-for="(cart, cartIndex) in product.carts_list"
                          :key="cartIndex"
                        >
                          <td>{{ cart.supplier.name }}</td>
                          <td>
                            <span class="badge border">
                              {{ getPrice(cart).clientDiscount }}%
                            </span>
                          </td>
                          <td>{{ `${getPrice(cart).publicPrice} ${$t("POUND")}` }}</td>
                          <td>
                            {{ `${getPrice(cart).pharmacyPrice} ${$t("POUND")}` }}
                          </td>
                          <td>
                            {{
                              `${getPrice(cart).pharmacyPrice * cart.quantity} ${$t(
                                "POUND"
                              )}`
                            }}
                          </td>
                          <td>{{ cart.quantity }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="my-5 row justify-content-center">
            <paginate
              v-model="page"
              :pageCount="pageCount"
              :clickHandler="getProfileOrders"
              prevText="<i class='fa fa-angle-double-right'></i>"
              nextText="<i class='fa fa-angle-double-left'></i>"
            >
            </paginate>
          </div>
        </template>
        <template v-else>
          <div class="cart-empty">
            <i class="fa fa-shopping-basket text-secondary"></i>
            <h4 class="my-4 text-secondary">{{ $t("ORDERS_EMPTY") }}</h4>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>
<script>
import { inject, onMounted, reactive, toRefs } from "vue-demi";
import Sidebar from "./sidebar";
import authClient from "../../shared/http-clients/auth-client";
import Paginate from "vuejs-paginate-next";
import global from "../../shared/consts/global";
export default {
  components: {
    Sidebar,
    Paginate,
  },
  setup() {
    const data = reactive({
      orders: [], //The length of 1
      page: 1,
      pageSize: 1,
      pageCount: 0,
      from: null,
      to: null,
    });
    const store = inject("store");
    onMounted(() => {
      getProfileOrders();
    });
    //Methods
    function deleteSearch() {
      data.from = null;
      data.to = null;
    }
    function getProfileOrders() {
      store.showLoader = true;
      authClient
        .getProfileOrders(data.page, data.pageSize, data.from, data.to)
        .then((response) => {
          store.showLoader = false;
          data.orders = response.data.data;
          data.pageCount = Math.ceil(response.data.total / data.pageSize);
        });
    }
    function getImagePath(image) {
      return `${global.DASHBOARD_DOMAIN}/upload/product/${image}`;
    }
    function getPrice(cart) {
      //Priority for deal price in conflict case
      if (cart.dealPrice) return cart.dealPrice;
      else if (cart.price) return cart.price;
    }
    function seach() {
      data.page = 1;
      getProfileOrders();
    }
    return {
      ...toRefs(data),
      getProfileOrders,
      getImagePath,
      getPrice,
      deleteSearch,
      seach,
    };
  },
};
</script>

<style lang="scss">
.orders-container {
  .product,
  .meta-info {
    padding: 20px 15px;
    margin-bottom: 18px;
    border-radius: 5px;
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
  .cart {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
  }
  .cart-empty {
    margin: 100px 0;
    display: flex;
    align-items: center;
    flex-direction: column;
    i {
      font-size: 60px;
    }
    i,
    h4 {
      color: #d9d9d9 !important;
    }
  }
  .search {
    padding: 12px;
    border-radius: 5px;
    margin-bottom: 18px;
    button {
      padding: 8px 16px;
      font-size: 12px;
      margin-left: 10px;
      background-color: #0e67d0 !important;
      color: #fff;
    }
  }
  .active {
    .page-link {
      background-color: #0e67d0 !important ;
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
  .badge {
    width: 50px;
    font-size: 14px !important;
    padding: 5px 0;
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
}
</style>
