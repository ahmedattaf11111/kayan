<template>
  <div
    v-if="products.length && endAtGreaterThanCurrentDate()"
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
            v-for="product in products"
            :key="product.id"
            class="ps-product ps-product--standard border-right"
          >
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
              <div class="ps-product__percent">% {{ product.deal.discount }}</div>
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
                  {{
                    calculatePharmacyPrice(
                      product.price.publicPrice,
                      product.deal.discount
                    )
                  }}
                  {{ $t("POUND") }}
                </span>

                <span class="ps-product__del">
                  {{ product.price.publicPrice }}
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
    </section>
  </div>
</template>

<script>
import { inject, onMounted, reactive, toRefs } from "vue-demi";
import { owlCarouselFunction, initializeClock } from "../../custom";
import productClient from "../../shared/http-clients/product-client";
import global from "../../shared/global";
export default {
  setup(props, context) {
    let data = reactive({
      products: [],
    });
    let store = inject("store");
    onMounted(() => {
      getDealProducts();
    });
    function calculatePharmacyPrice(publicPrice, dealDiscount) {
      return publicPrice - (publicPrice * dealDiscount) / 100;
    }
    //Methods
    function getImagePath(image) {
      return `${global.DASHBOARD_DOMAIN}/upload/product/${image}`;
    }
    function endAtGreaterThanCurrentDate() {
      return getEndAt() > new Date();
    }
    //Commons
    function getDealProducts() {
      store.showLoader = true;
      productClient
        .getDealProducts()
        .then((response) => {
          store.showLoader = false;
          data.products = response.data;
        })
        .finally(() => {
          owlCarouselFunction();
          initializeClock(getEndAt());
        });
    }
    function getEndAt() {
      return data.products.length ? new Date(data.products[0].deal.end_at) : null;
    }
    return {
      getImagePath,
      ...toRefs(data),
      calculatePharmacyPrice,
      endAtGreaterThanCurrentDate,
    };
  },
};
</script>

<style lang="scss"></style>
