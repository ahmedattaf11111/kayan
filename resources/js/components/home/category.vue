<template>
  <div class="category-container">
    <carousel :breakpoints="breakPoints" dir="rtl">
      <slide v-for="category in categories" :key="category.id">
        <section class="ps-section--category-horizontal">
          <div class="ps-category__item">
            <router-link
              class="ps-category__link"
              @click="onSelectCategory(category.id)"
              :to="'/best-client-discount-products'"
              ><img :src="getImagePath(category.media.file_name)" alt />
            </router-link>
            <h5 class="ps-category__name">
              <router-link
                @click="onSelectCategory(category.id)"
                :to="'/best-client-discount-products'"
              >
                {{ category.name }}
              </router-link>
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
import { reactive, toRefs } from "vue-demi";
import global from "../../shared/global";
import productStore from "../view-all-products/store";
export default {
  components: {
    Carousel,
    Slide,
  },
  setup(props) {
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
    //Methods
    function onSelectCategory(categoryId) {
      productStore.categoryId = categoryId;
      productStore.categoryLevel = 1;
    }
    function getImagePath(image) {
      return `${global.DASHBOARD_DOMAIN}/upload/category/${image}`;
    }
    return {
      categories: props.categories,
      getImagePath,
      ...toRefs(data),
      ...toRefs(productStore),
      onSelectCategory,
    };
  },
  props: ["categories"],
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
