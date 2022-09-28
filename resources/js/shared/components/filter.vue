<template>
  <div class="filter">
    <div class="row">
      <div class="col-12">
        <div class="form-group">
          <label for="exampleFormControlSelect1">{{ $t("EFFECTIVE_MATERIAL") }}</label>
          <input
            v-model="effectiveMaterial"
            type="text"
            class="form-control"
            aria-describedby="emailHelp"
          />
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="exampleFormControlSelect1">{{ $t("PHARMACOLOGICAL_FORM") }}</label>
          <div class="select-wrapper">
            <select v-model="pharmacologicalFormId" class="form-control">
              <option
                :value="pharmacologicalForm.id"
                v-for="pharmacologicalForm in pharmacologicalForms"
                :key="pharmacologicalForm.id"
              >
                {{ pharmacologicalForm.name }}
              </option>
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="exampleFormControlSelect1">{{ $t("SUPPLIER") }}</label>
          <div class="select-wrapper">
            <select v-model="supplierId" class="form-control">
              <option
                v-for="supplier in suppliers"
                :key="supplier.id"
                :value="supplier.id"
              >
                {{ supplier.name }}
              </option>
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="exampleFormControlSelect1">{{ $t("COMPANY") }}</label>
          <div class="select-wrapper">
            <select v-model="companyId" class="form-control">
              <option v-for="company in companies" :key="company.id" :value="company.id">
                {{ company.name }}
              </option>
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="exampleFormControlSelect1">{{ $t("DISCOUNT") }}</label>
          <div class="select-wrapper">
            <select v-model="discount" class="form-control">
              <option :value="item * 10" v-for="item in 10" :key="item">
                {{ item * 10 }}
              </option>
            </select>
          </div>
        </div>
      </div>
      <div class="col-12">
        <router-link
          :to="{ path: '/best-client-discount-products', force: true }"
          @click="onSearchClicked"
          class="btn btn-primary"
          >{{ $t("SEARCH") }}</router-link
        >
        <button @click="reset" class="btn btn-primary">{{ $t("DELETE") }}</button>
      </div>
    </div>
  </div>
</template>

<script>
import { inject, onMounted, reactive, toRefs } from "vue-demi";
import filterClient from "../http-clients/filter-client";
import productStore from "../../components/view-all-products/store";
export default {
  setup() {
    const data = reactive({
      suppliers: [],
      companies: [],
      pharmacologicalForms: [],
    });
    const store = inject("store");
    onMounted(() => {
      getSuppliers();
      getCompanies();
      getPharmacologicalForms();
    });
    //Methods
    function onSearchClicked() {
      productStore.from = From.SEARCH;
    }
    function reset(){
      productStore.name=null;
      productStore.effectiveMaterial=null;
      productStore.pharmacologicalFormId=null;
      productStore.companyId=null;
      productStore.supplierId=null;
      productStore.discount=null;
    }
    //Commons
    function getSuppliers() {
      store.showLoader = true;
      filterClient.getSuppliers().then((response) => {
        store.showLoader = false;
        data.suppliers = response.data;
      });
    }
    function getCompanies() {
      store.showLoader = true;
      filterClient.getCompanies().then((response) => {
        store.showLoader = false;
        data.companies = response.data;
      });
    }

    function getPharmacologicalForms() {
      store.showLoader = true;
      filterClient.getPharmacologicalForms().then((response) => {
        store.showLoader = false;
        data.pharmacologicalForms = response.data;
      });
    }
    return { ...toRefs(data), ...toRefs(productStore), onSearchClicked,reset };
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
}
</style>
