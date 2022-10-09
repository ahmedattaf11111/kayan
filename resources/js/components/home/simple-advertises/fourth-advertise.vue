<template>
  <div class="fourth-simple-advertise-container">
    <div class="container">
      <div class="ps-banner__padding">
        <section class="ps-home__banner">
          <div class="ps-banner" style="background: #103178">
            <img
              class="ps-banner__overlay"
              :src="getImagePath(simpleAdvertise.image)"
              alt="alt"
            />
            <div class="ps-banner__block">
              <div class="ps-banner__content">
                <h2 class="ps-banner__title text-white">
                  {{ simpleAdvertise.title }}
                </h2>
                <a class="bg-yellow ps-banner__shop" :href="simpleAdvertise.url">{{
                  $t("SHOW_DETAILS")
                }}</a>
              </div>
            </div>
          </div>
        </section>
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
    return { simpleAdvertise: props.fourthSimpleAdvertise, getImagePath };
  },
  props: ["fourthSimpleAdvertise"],
};
</script>

<style>
.fourth-simple-advertise-container {
  margin: 5px 0;
}
</style>
