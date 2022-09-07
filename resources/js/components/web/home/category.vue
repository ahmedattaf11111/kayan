<template>
  <div class="category-container">
    <carousel :breakpoints="breakPoints" dir="rtl">
      <slide v-for="category in categories" :key="category.id">
        <section class="ps-section--category-horizontal">
          <div class="ps-category__item">
            <a class="ps-category__link" href="category-grid.html"
              ><img :src="getImagePath(category.media.file_name)" alt
            /></a>

            <h5 class="ps-category__name">
              <a href="category-grid.html">{{ category.name }}</a>
            </h5>
          </div>
        </section>
      </slide>
    </carousel>
  </div>
</template>
<script>
import "vue3-carousel/dist/carousel.css";
import { Carousel, Slide } from "vue3-carousel";
import categoryClient from "../../../shared/http-clients/category-client";
import { reactive, toRefs } from "vue-demi";
import store from "../../../shared/store";
import global from "../../../shared/global";
export default {
  components: {
    Carousel,
    Slide,
  },
  setup() {
    let data = reactive({
      categories: [],
      breakPoints: {
        100: {
          itemsToShow: 2,
        },
        // 1024 and up
        1024: {
          itemsToShow: 6,
        },
      },
    });
    onCreated();
    //Methods
    function getImagePath(image) {
      return `${global.DASHBOARD_DOMAIN}/upload/category/${image}`;
    }
    //Commons
    function onCreated() {
      store.showLoader = true;
      categoryClient.getCategories().then((response) => {
        store.showLoader = false;
        data.categories = response.data;
      });
    }
    return { getImagePath, ...toRefs(data) };
  },
};
</script>

<style lang="scss">
.category-container {
  margin: 5px 0;
  .ps-category__name {
    a {
      color: #103178 !important;
    }
  }
}
</style>
