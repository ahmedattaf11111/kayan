<template>
  <div class="first-three-simple-advertise-container">
    <div class="container">
      <div class="ps-promo">
        <div class="row">
          <div
            v-for="(advertise,index) in simpleAdvertises"
            :key="advertise.id"
            class="col-12 col-md-4"
          >
            <div class="ps-promo__item">
              <img
                class="ps-promo__banner"
                :src="getImagePath(advertise.image)"
                alt="alt"
              />
              <div class="ps-promo__content">
                <h4 class="text-white ps-promo__name" :style="index!=1?'color:#103178 !important':''">
                  {{ advertise.title }}
                </h4>
                <a
                  v-if="advertise.external"
                  class="ps-promo__btn"
                  target="_blank"
                  :href="advertise.url"
                >
                  {{ $t("SHOW_DETAILS") }}
                </a>
                <router-link
                  class="ps-promo__btn"
                  v-if="!advertise.external"
                  :to="`product-details/${advertise.product_id}`"
                >
                  {{ $t("SHOW_DETAILS") }}
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import global from "../../../shared/consts/global";
export default {
  setup(props, context) {
    //Methods
    function getImagePath(image) {
      return `${global.DASHBOARD_DOMAIN}/upload/${image}`;
    }
    return { simpleAdvertises: props.simpleAdvertises, getImagePath };
  },
  props: ["simpleAdvertises"],
};
</script>

<style lang="scss">
.first-three-simple-advertise-container {
  margin: 5px 0;
}
</style>
