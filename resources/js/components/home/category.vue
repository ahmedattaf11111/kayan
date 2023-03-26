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
              ><img :src="category.image" alt />
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
import global from "../../shared/consts/global";
import productStore from "../view-all-products/store";
import From from "../../shared/consts/from";
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
  img{
    border: 1px solid rgb(14, 103, 208);
    max-width: 63px !important;
    margin: auto;
    border-radius: 50%;
  }
}
</style>
