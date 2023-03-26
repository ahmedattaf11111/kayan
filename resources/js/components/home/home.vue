<template>
  <div class="ps-home ps-home--4">
    <div class="ps-home ps-home--4">
      <Category :categories="categories" />
      <Slider />
      <div class="ps-home__content">
        <Deal />
        <BestOffers />
        <BestSeller />
      </div>
    </div>
    <Footer />

  </div>
</template>
<script>
import Slider from "./slider";
import Footer from "../../shared/components/footer.vue";
import productClient from "../../shared/http-clients/product-client";
import { onMounted, reactive, toRefs } from "vue-demi";
import Category from "./category";
import BestSeller from "./best-seller";
import BestOffers from "./best-offers";
import Deal from "./deal";
export default {
  components: {
    Slider,
    Category,
    Deal,
    BestSeller,
    BestOffers,
    Footer
  },
  setup() {
    let data = reactive({
      categories: [],
      bestSellerProducts: [],
      mostPopularProducts: [],
    });
    onMounted(() => {
      getCategories();
    });

    function getCategories() {
      productClient.getCategories().then((response) => {
        data.categories = response.data;
      });
    }

    return { ...toRefs(data) };
  },
};
</script>

<style lang="scss" scoped>
.ps-home {
  border-top: none !important;
}
</style>
