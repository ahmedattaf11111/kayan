<template>
  <div class="all-products-container ps-categogy">
    <div class="container">
      <div class="ps-categogy__content">
        <div class="row row-reverse">
          <div class="col-12 col-md-9">
            <Products />
          </div>
          <div class="col-12 col-md-3">
            <Category/>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { useRoute } from "vue-router";
import Category from "./category";
import Products from "./products";
export default {
  components: {
    Products,
    Category,
  },
  setup() {
  },
};
</script>

<style lang="scss"></style>
