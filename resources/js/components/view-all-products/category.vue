<template>
  <div class="side-categories-container">
    <div class="ps-widget ps-widget--product">
      <div class="ps-widget__block">
        <h4 class="ps-widget__title">{{ $t("CATEGORIES") }}</h4>
        <a class="ps-block-control" href="#"><i class="fa fa-angle-down"></i></a>
        <div class="ps-widget__content ps-widget__category">
          <ul class="menu--mobile">
            <li
              :id="`main-${category.id}`"
              class="main-category"
              v-for="category in categories"
              :key="category.id"
            >
              <a @click.prevent="onCategorySelected(category.id, 1)" href="#">{{
                category.name
              }}</a>
              <span @click="toggleSideCategory(category.id)" class="sub-toggle"
                ><i class="fa fa-chevron-down"></i>
              </span>
              <ul class="sub-menu">
                <li
                  class="sub-category"
                  :id="`sub-${subCategory.id}`"
                  v-for="subCategory in category.sub_categories"
                  :key="subCategory.id"
                >
                  <a @click.prevent="onCategorySelected(subCategory.id, 2)" href="#">{{
                    subCategory.name
                  }}</a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { inject, onMounted, reactive, ref, toRefs } from "vue-demi";
import { toggleMobileSideCategroy, toggleSideCategory } from "../../custom";
import { activateSideCategory } from "../../custom";
import productClient from "../../shared/http-clients/product-client";
import productStore from "./store";
export default {
  setup(props, contexts) {
    const store = inject("store");
    let data = reactive({
      categories: [],
    });
    onMounted(() => {
      getMainWithSubCategories();
    });
    //Methods
    function onCategorySelected(categoryId, categoryLevel) {
      productStore.categoryId = categoryId;
      productStore.categoryLevel = categoryLevel;
      activateSideCategory(categoryId, categoryLevel);
    }
    //Commons
    function getMainWithSubCategories() {
      store.showLoader = true;
      productClient
        .getMainWithSubCategories()
        .then((response) => {
          store.showLoader = false;
          data.categories = response.data;
        })
        .finally(() => {
          activateSideCategory(productStore.categoryId, 1);
          toggleMobileSideCategroy();
        });
    }
    return {
      ...toRefs(data),
      toggleSideCategory,
      onCategorySelected,
    };
  },
};
</script>

<style lang="scss">
.side-categories-container {
  .sub-menu {
    z-index: 1;
  }
}
</style>
