<template>
  <div class="first-three-simple-advertise-container">
    <div class="container">
      <div class="ps-promo">
        <div class="row">
          <div
            v-for="advertise in simpleAdvertises"
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
                <h4 class="text-white ps-promo__name">
                  {{ advertise.title }}
                </h4>
                <a class="ps-promo__btn" target="_blank" :href="advertise.url">
                  {{ $t("SHOW_DETAILS") }}
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import global from "../../../shared/global";
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
