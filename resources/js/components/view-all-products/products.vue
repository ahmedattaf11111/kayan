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
                <a class="ps-product__image" href="product1.html">
                  <figure>
                    <img :src="getImagePath(product.image)" alt="alt" />
                  </figure>
                </a>
                <div class="ps-product__actions">
                  <div
                    class="ps-product__item"
                    data-toggle="tooltip"
                    data-placement="left"
                    title="Add to cart"
                  >
                    <a href="#" data-toggle="modal" data-target="#popupAddcart"
                      ><i class="fa fa-shopping-basket"></i
                    ></a>
                  </div>
                </div>
                <div class="ps-product__badge"></div>
                <div class="ps-product__percent">
                  {{ product.biggest_client_discount_price.clientDiscount }}%
                </div>
              </div>
              <div class="ps-product__content">
                <h5 class="ps-product__title">
                  <a href="product1.html">
                    <span>{{ product.product_name.nameAr }}</span>
                    <br />
                    <span>{{ product.product_name.nameEn }}</span>
                  </a>
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
                <div class="ps-product__actions ps-product__group-mobile">
                  <div class="ps-product__cart">
                    <a
                      class="ps-btn ps-btn--warning"
                      href="#"
                      data-toggle="modal"
                      data-target="#popupAddcart"
                      >Add to cart</a
                    >
                  </div>
                  <div
                    class="ps-product__item cart"
                    data-toggle="tooltip"
                    data-placement="left"
                    title="Add to cart"
                  >
                    <a href="#"><i class="fa fa-shopping-basket"></i></a>
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
import { inject, reactive, toRefs, watch } from "vue-demi";
import productClient from "../../shared/http-clients/product-client";
import global from "../../shared/global";
import Paginate from "vuejs-paginate-next";
import productStore from "./store";
export default {
  components: {
    Paginate,
  },
  setup() {
    const store = inject("store");
    let data = reactive({
      categoryId: null,
      categoryLevel: null,
      products: [],
      page: 1,
      pageSize: 8,
      pageCount: 0,
    });
    watch(
      productStore,
      (value) => {
        data.categoryId = productStore.categoryId;
        data.categoryLevel = productStore.categoryLevel;
        data.page = 1;
        getBiggestClientDiscountProducts();
      },
      { deep: true, immediate: true }
    );
    //Methods
    function getImagePath(image) {
      return `${global.DASHBOARD_DOMAIN}/upload/product/${image}`;
    }
    function getBiggestClientDiscountProducts() {
      store.showLoader = true;
      productClient
        .getBiggestClientDiscountProducts(
          data.categoryId,
          data.categoryLevel,
          data.page,
          data.pageSize
        )
        .then((response) => {
          store.showLoader = false;
          data.products = response.data.data;
          data.pageCount = Math.ceil(response.data.total / data.pageSize);
        });
    }
    return {
      ...toRefs(data),
      getBiggestClientDiscountProducts,
      getImagePath,
    };
  },
};
</script>

<style lang="scss">
.all-products-container {
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
  .ps-section--seller-diagnosis {
    padding-bottom: 0 !important;
  }
}
</style>
