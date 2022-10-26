<template>
  <div class="ps-home ps-home--4">
    <div class="ps-home ps-home--4">
      <Category :categories="categories" />
      <Slider />
      <div class="ps-home__content">
        <Deal />
        <BestSeller />
        <MostPopular />
        <SimpleAdvertises />
        <Newsletter />
      </div>
    </div>
  </div>
</template>
<script>
import Newsletter from "./newsletter";
import Slider from "./slider";
import productClient from "../../shared/http-clients/product-client";
import { onMounted, reactive, toRefs } from "vue-demi";
import Category from "./category";
import SimpleAdvertises from "./simple-advertises/simple-advertise";
import BestSeller from "./best-seller";
import MostPopular from "./most-popular";
import Deal from "./deal";
export default {
  components: {
    Slider,
    Newsletter,
    Category,
    SimpleAdvertises,
    Deal,
    BestSeller,
    MostPopular,
  },
  setup() {
    let data = reactive({
      categories: [],
    });
    onMounted(() => {
      getMainWithSubCategories();
    });

    function getMainWithSubCategories() {
      productClient.getMainWithSubCategories().then((response) => {
        data.categories = response.data;
      });
    }

    return { ...toRefs(data) };
  },
};
</script>

<style lang="scss"></style>
