<template>
  <div class="ps-home ps-home--4">
    <div class="ps-home ps-home--4">
      <Category :categories="categories" />
      <Slider />
      <div class="ps-home__content">
        <Deal />
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
import store from "../../shared/store";
import Category from "./category";
import SimpleAdvertises from "./simple-advertises/simple-advertise";
import Deal from "./deal";
export default {
  components: {
    Slider,
    Newsletter,
    Category,
    SimpleAdvertises,
    Deal,
  },
  setup() {
    let data = reactive({
      categories: [],
    });
    onMounted(() => {
      getMainWithSubCategories();
    });

    function getMainWithSubCategories() {
      store.showLoader = true;
      productClient.getMainWithSubCategories().then((response) => {
        store.showLoader = false;
        data.categories = response.data;
      });
    }

    return { ...toRefs(data) };
  },
};
</script>

<style lang="scss"></style>
