<template>
  <div class="all-products-container ps-categogy">
    <div class="ps-categogy__content" style="padding-top: 0;">
      <div class="row row-reverse">
        <div style="height: calc(100vh - 106px);overflow: auto;" class="col-12 col-md-9">
          <Products />
        </div>
        <div class="col-12 col-md-3">
          <SearchSidebar />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import SearchSidebar from "./search-sidebar";
import Products from "./products";
export default {
  components: {
    Products,
    SearchSidebar,
  },
  setup() {
  },
};
</script>

<style lang="scss"></style>
