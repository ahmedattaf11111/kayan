<template>
  <div class="seventh-simple-advertise-container">
    <div class="container">
      <section class="ps-home__banner">
        <div class="ps-banner" style="background: #ffcc00">
          <img
            class="ps-banner__overlay"
            :src="getImagePath(simpleAdvertise.image)"
            alt="alt"
          />
          <div class="ps-banner__block">
            <div class="ps-banner__content">
              <h2 class="ps-banner__title">
                {{ simpleAdvertise.title }}
              </h2>
              <a
                class="bg-warning ps-banner__shop"
                target="_blank"
                :href="simpleAdvertise.url"
              >
                {{ $t("SHOW_DETAILS") }}
              </a>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
import global from "../../../../shared/global";
export default {
  setup(props, context) {
    //Methods
    function getImagePath(image) {
      return `${global.DASHBOARD_DOMAIN}/upload/${image}`;
    }
    return { simpleAdvertise: props.seventhSimpleAdvertise, getImagePath };
  },
  props: ["seventhSimpleAdvertise"],
};
</script>

<style>
.seventh-simple-advertise-container {
  margin: 5px 0;
}
</style>
