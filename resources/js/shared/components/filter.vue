<template>
  <div class="filter">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="exampleFormControlSelect1">{{ $t("COMPANY") }}</label>
          <div class="select-wrapper">
            <select v-model="companyId" class="form-control">
              <option v-for="company in companys" :key="company.id" :value="company.id">
                {{ company.name }}
              </option>
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="exampleFormControlSelect1">{{ $t("PHARMACIST_FORMS") }}</label>
          <div class="select-wrapper">
            <select v-model="pharmacologicalFormId" class="form-control">
              <option :value="pharmacologicalForm.id" v-for="pharmacologicalForm in pharmacologicalForms"
                :key="pharmacologicalForm.id">
                {{ pharmacologicalForm.name }}
              </option>
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="exampleFormControlSelect1">{{ $t("CATEGORY") }}</label>
          <div class="select-wrapper">
            <select v-model="categoryId" class="form-control">
              <option :value="category.id" v-for="category in categories" :key="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="exampleFormControlSelect1">{{ $t("EFFECTIVE_MATERIAL") }}</label>
          <input v-model="effectiveMaterial" type="text" class="form-control" aria-describedby="emailHelp" />
        </div>
      </div>
      <div class="col-12">
        <router-link :to="{ path: '/best-client-discount-products', force: true }"  class="btn">{{
          $t("SEARCH") }}</router-link>
        <button @click="reset" class="btn">{{ $t("DELETE") }}</button>
      </div>
    </div>
  </div>
</template>

<script>
import { inject, onMounted, reactive, toRefs } from "vue-demi";
import filterClient from "../http-clients/filter-client";
import productStore from "../../components/view-all-products/store";
import From from "../../shared/consts/from";
export default {
  setup() {
    const data = reactive({
      companys: [],
      pharmacologicalForms: [],
      categories: [],
    });
    const store = inject("store");
    onMounted(() => {
      getCompanies();
      getPharmacologicalForms();
      getCategories();
    });
    //Methods
    function reset() {
      productStore.name = null;
      productStore.effectiveMaterial = null;
      productStore.pharmacologicalFormId = null;
      productStore.companyId = null;
      productStore.categoryId = null;
    }
    //Commons
    function getCompanies() {
      store.showLoader = true;
      filterClient.getCompanies().then((response) => {
        store.showLoader = false;
        data.companys = response.data;
      });
    }
    function getPharmacologicalForms() {
      store.showLoader = true;
      filterClient.getPharmacologicalForms().then((response) => {
        store.showLoader = false;
        data.pharmacologicalForms = response.data;
      });
    }
    function getCategories() {
      store.showLoader = true;
      filterClient.getCategories().then((response) => {
        store.showLoader = false;
        data.categories = response.data;
      });
    }
    return { ...toRefs(data), ...toRefs(productStore), reset };
  },
};
</script>

<style scoped lang="scss">
select {
  -webkit-appearance: none;
  appearance: none;
}

.select-wrapper {
  position: relative;
}

.select-wrapper::after {
  content: "▼";
  font-size: 1.2rem;
  top: 15px;
  left: 18px;
  position: absolute;
}

button,
a {
  padding: 8px 16px;
  font-size: 12px;
  margin-left: 10px;
  background-color: #0e67d0 !important;
  color: #fff;
}
</style>
