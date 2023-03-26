<template>
  <div class="side-categories-container">
    <div class="ps-widget ps-widget--product">
      <div class="ps-widget__block">
        <h4 class="ps-widget__title">{{ $t("CATEGORIES") }}</h4>
        <a class="ps-block-control" href="#"><i class="fa fa-angle-down"></i></a>
        <div class="ps-widget__content ps-widget__category">
          <ul class="menu--mobile">
            <li :id="`main-${category.id}`" class="main-category" v-for="(category, index) in categories"
              :key="category.id">
              <div class="form-checkbox">
                <input :checked="categoriesIds.includes(category.id)" @change="onCategoryChecked($event, category)"
                  type="checkbox" />
              </div>
              <span>{{ category.name }}</span>
            </li>
          </ul>
          <hr />
          <h4 class="effective-header ps-widget__title">{{ $t("effective_material") }}</h4>
          <input v-model="effectiveMaterialText" :placeholder="$t('enter_effective_material')" class="form-control">
          <hr class="mt-5" />
          <h4 class="effective-header ps-widget__title">{{ $t("PHARMACIST_FORMS") }}</h4>
          <input v-model="pharmacist_form_text" @input="searchPharmacistForms()"
            :placeholder="$t('SEARCH_PHARMACIST_FORMS')" class="form-control">
          <ul class="menu--mobile border list">
            <li :id="`main-${pharmacologicalForm.id}`" class="main-category"
              v-for="pharmacologicalForm in filterPharmacistForms" :key="pharmacologicalForm.id">
              <div class="form-checkbox">
                <input :checked="pharmacologicalFormIds.includes(pharmacologicalForm.id)"
                  @change="onPharmacologicalFormChecked($event, pharmacologicalForm)" type="checkbox" />
              </div>
              <span>{{ pharmacologicalForm.name }}</span>
            </li>
          </ul>
          <hr class="mt-5" />
          <h4 class="effective-header ps-widget__title">{{ $t("COMPANY") }}</h4>
          <input v-model="company_text" @input="searchCompanies()" :placeholder="$t('SEARCH_COMPANY')"
            class="form-control">
          <ul class="menu--mobile border list">
            <li :id="`main-${company.id}`" class="main-category" v-for="company in filterCompanies" :key="company.id">
              <div class="form-checkbox">
                <input :checked="companiesIds.includes(company.id)" @change="onCompanyChecked($event, company)"
                  type="checkbox" />
              </div>
              <span>{{ company.name }}</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { inject, onMounted, reactive, ref, toRefs, watch } from "vue-demi";
import { toggleMobileSideCategroy, toggleSideCategory } from "../../custom";
import filterClient from "../../shared/http-clients/filter-client";
import productStore from "./store";
import { useRoute } from "vue-router";
export default {
  setup(props, context) {
    const store = inject("store");
    const route = useRoute();
    let data = reactive({
      categories: [],
      companies: [],
      filterCompanies: [],
      pharmacologicalForms: [],
      pharmacist_form_text: "",
      company_text: "",
      filterPharmacistForms: []
    });
    onMounted(() => {
      getCategories();
      getCompanies();
      getPharmacologicalForms();
    })
    function searchPharmacistForms() {
      data.filterPharmacistForms = data.pharmacologicalForms.filter(el => {
        return data.pharmacist_form_text == "" || el.name.includes(data.pharmacist_form_text);
      })
    }
    function searchCompanies() {
      data.filterCompanies = data.companies.filter(el => {
        return data.company_text == "" || el.name.includes(data.company_text);
      })
    }
    function onCategoryChecked(event, category) {
      if (event.target.checked) {
        productStore.categoriesIds.push(category.id);
      }
      else {
        let _index = -1;
        productStore.categoriesIds.forEach((id, index) => {
          if (id == category.id) {
            _index = index;
            return;
          }
        });
        productStore.categoriesIds.splice(_index, 1);
      }
    }
    function onCompanyChecked(event, company) {
      if (event.target.checked) {
        productStore.companiesIds.push(company.id);
      }
      else {
        let _index = -1;
        productStore.companiesIds.forEach((id, index) => {
          if (id == company.id) {
            _index = index;
            return;
          }
        });
        productStore.companiesIds.splice(_index, 1);
      }
    }

    function onPharmacologicalFormChecked(event, pharmacologicalForm) {
      if (event.target.checked) {
        productStore.pharmacologicalFormIds.push(pharmacologicalForm.id);
      }
      else {
        let _index = -1;
        productStore.pharmacologicalFormIds.forEach((id, index) => {
          if (id == pharmacologicalForm.id) {
            _index = index;
            return;
          }
        });
        productStore.pharmacologicalFormIds.splice(_index, 1);
      }
    }
    //Commons
    function getCategories() {
      store.showLoader = true;
      filterClient
        .getCategories()
        .then((response) => {
          store.showLoader = false;
          data.categories = response.data;
        })
        .finally(() => {
          toggleMobileSideCategroy();
        });
    }
    function getCompanies() {
      store.showLoader = true;
      filterClient
        .getCompanies()
        .then((response) => {
          store.showLoader = false;
          data.companies = response.data;
          data.filterCompanies = [...data.companies];
        })
        .finally(() => {
          toggleMobileSideCategroy();
        });
    }
    function getPharmacologicalForms() {
      store.showLoader = true;
      filterClient
        .getPharmacologicalForms()
        .then((response) => {
          store.showLoader = false;
          data.pharmacologicalForms = response.data;
          data.filterPharmacistForms = [...data.pharmacologicalForms];
        })
        .finally(() => {
          toggleMobileSideCategroy();
        });
    }
    return {
      ...toRefs(data),
      ...toRefs(productStore),
      searchCompanies,
      searchPharmacistForms,
      getPharmacologicalForms,
      toggleSideCategory,
      onCategoryChecked,
      onCompanyChecked,
      onPharmacologicalFormChecked
    };
  },
};
</script>

<style lang="scss">
@media (max-width:767px) {
  .side-categories-container {
    border-left: unset !important;
    margin-bottom: 105px !important;
    height: auto !important;
  }
}

.side-categories-container {
  height: calc(100vh - 107px);
  overflow: auto;
  padding-top: 10px;
  margin-bottom: 10px;
  border-left: 1px solid #dee2e6;
  .sub-menu {
    z-index: 1;
  }

  .ps-widget__category {
    padding: 0 !important;
    padding-top: 5px !important;
  }

  ul {
    li {
      padding: 0;
    }
  }

  .list {
    overflow: auto;
    height: 200px !important;
    margin-top: 14px !important;
    padding: 10px 17px 10px 17px !important;
    border-radius: 5px !important;
  }

  .effective-header {
    margin: 12px 0 10px 0;
  }

  .form-control {
    border-radius: 45px;
  }

  .main-category {
    display: flex;
    gap: 23px;
  }

  input[type="checkbox"] {
    position: relative;
    top: 1px;
  }
}</style>