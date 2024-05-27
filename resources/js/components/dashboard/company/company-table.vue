<template>
  <div class="p-3 companies-container">
    <DeleteConfirmation
      @confirm="deleteCompany"
      @closed="selectedCompany = null"
    />
    <CompanyForm
      @created="onCreated"
      @updated="onUpdated"
      :selectedCompany="selectedCompany"
    />
   
    <div class="px-4">
      <div class="table-container">
        <div class="table-responsive">
          <div class="controls">
            <div class="search">
              <input
                v-model="text"
                type="text"
                :placeholder="$t('SEARCH')"
                ref="search"
              />
              <i class="fa fa-search"></i>
            </div>
            <div class="actions my-2">
              <button
                @click="onAddClicked()"
                data-toggle="modal"
                data-target="#companyFormModal"
                class="border text-secondary"
              >
                <i class="fa fa-plus" aria-hidden="true"></i>
              </button>
              <button @click="downloadExcelFile" class="border text-secondary">
                <i class="fa fa-download" aria-hidden="true"></i>
              </button>
              <button @click="print" class="border text-secondary">
                <i class="fa fa-print" aria-hidden="true"></i>
              </button>
            </div>
          </div>
          <table id="printMe" class="table">
            <thead>
              <tr>
                <th scope="col">{{ $t("NAME") }}</th>
                <th class="actions-header" scope="col">{{ $t("ACTIONS") }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(company, index) in companies" :key="company.id">
                <td>{{ company.name }}</td>
                <td class="actions-cell">
                  <div class="actions">
                    <button
                      @click="onEditClicked(company, index)"
                      data-toggle="modal"
                      data-target="#companyFormModal"
                      class="border text-secondary"
                    >
                      <i class="fa fa-edit" aria-hidden="true"></i>
                    </button>
                    <button
                      @click="onDeleteClicked(company, index)"
                      data-toggle="modal"
                      data-target="#deleteConfirmationModal"
                      class="border text-secondary"
                    >
                      <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-1">
          <paginate
            v-model="page"
            :pageCount="pageCounts"
            :clickHandler="getCompanies"
            :prevText="`<i class='fa fa-arrow-right'></i>`"
            :nextText="`<i class='fa fa-arrow-left'></i>`"
          >
          </paginate>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import companyClient from "../../../shared/http-clients/admin/company-client";
import Paginate from "vuejs-paginate-next";
import exportFromJSON from "export-from-json";
import DeleteConfirmation from "../../../shared/components/delete-confirmation.vue";
import CompanyForm from "./company-form.vue";
import companyStore from "./company-store";
import { inject, provide, reactive, ref, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  components: {
    Paginate,
    DeleteConfirmation,
    CompanyForm,
  },
  setup() {
    const data = reactive({
      pageSize: 6,
      page: 1,
      companies: [],
      text: "",
      pageCounts: 0,
      timeout: null,
      selectedCompany: null,
      selectedCompanyIndex: 0,
    });
    const toast = inject("toast");
    const { t, locale } = useI18n({ useScope: "global" });
    provide("company_store", companyStore);
    created();
    //Methods
    function onAddClicked() {
      data.selectedCompany = null;
      //Make little delay to ensure that watcher that found in category form component
      // catch selectedCompany input prop
      setTimeout(() => {
        companyStore.onFormShow = !companyStore.onFormShow;
      }, 1);
    }
    function onEditClicked(category, index) {
      data.selectedCompany = category;
      data.selectedCompanyIndex = index;
      //Make little delay to ensure that watcher catch selectedCompany input prop
      //in category form component
      setTimeout(() => {
        companyStore.onFormShow = !companyStore.onFormShow;
      }, 1);
    }
    function onDeleteClicked(company, index) {
      data.selectedCompany = company;
      data.selectedCompanyIndex = index;
    }
    function getCompanies() {
      companyClient
        .getCompanies(data.page, data.pageSize, data.text)
        .then((response) => {
          data.companies = response.data.data;
          data.pageCounts = Math.ceil(response.data.total / data.pageSize);
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function downloadExcelFile() {
      companyClient.getAllCompanies().then((res) => {
        let data = res.data;
        const fileName = "companies";
        const exportType = exportFromJSON.types.csv;
        if (data) exportFromJSON({ data, fileName, exportType });
      });
    }
    function print() {
      window.print();
    }
    function onCreated(event) {
      data.page = 1;
      getCompanies();
    }
    function onUpdated(event) {
      data.selectedCompany = null;
      getCompanies();
    }
    function deleteCompany() {
      companyClient
        .delete(data.selectedCompany.id)
        .then((response) => {
          toast.success(t("DELETED_SUCCESSFULLY"));
          if (data.page > 1 && data.companies.length == 1) {
            data.page--;
          }
          getCompanies();
          data.selectedCompany = null;
        })
        .catch((error) => {});
    }
    function search() {
      data.page = 1;
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getCompanies();
      }, 500);
    }
    watch(
      () => {
        data.text;
      },
      (value) => {
        search();
      },
      { deep: true }
    );
    //Commons
    function created() {
      getCompanies();
    }
    return {
      ...toRefs(data),
      onAddClicked,
      onEditClicked,
      onDeleteClicked,
      getCompanies,
      downloadExcelFile,
      onCreated,
      onUpdated,
      deleteCompany,
      search,
      print,
    };
  },
};
</script>


<style  lang="scss">
@media print {
  body * {
    visibility: hidden;
  }
  #printMe,
  #printMe * {
    visibility: visible;
  }
  .actions-header,
  .actions-cell {
    display: none !important;
  }
  #printMe {
    position: absolute;
    top: 0;
    left: 0;
  }
}
.companies-container {
  .actions-cell {
    padding: 5px 0 !important;
  }
  td {
    &:not(.actions-cell, .img) {
      position: relative;
      top: 3px;
    }
    &.actions-cell {
      position: relative;
      top: 0px;
    }
    height: 30px;
    vertical-align: center;
    button {
      margin: 0 2px !important;
    }
    font-size: 14px !important;
    padding: 0 !important;
    img {
      width: 35px;
      height: 35px;
      border-radius: 3px;
      padding: 5px;
      margin: 5px;
      box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
        rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    }
  }
  .header {
    * {
      font-size: 17px !important;
    }
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    padding: 30px;
    .welcome {
      padding-top: 9px;
    }
    .title {
      * {
        color: #6c757d !important;
      }
      a {
        text-decoration: none;
        color: #868e96 !important;
        &:hover {
          color: #6c757d !important;
        }
      }
    }
  }
  .table-container {
    background: #ffffff;
    box-shadow: none !important;
    padding: 30px;
    .controls {
      display: flex;
      justify-content: space-between;
      @media (max-width: 500px) {
        flex-direction: column;
      }
      body[dir="ltr"] & {
        .search {
          i {
            right: 25px;
          }
        }
      }
      body[dir="rtl"] & {
        .search {
          i {
            left: 25px;
          }
        }
      }
      .search {
        margin-bottom: 10px;
        i {
          position: relative;
          top: 1px;
          color: #888888;
        }
        input {
          padding: 4px 15px;
          border: 1px solid #dee2e6 !important;
          border-radius: 5px;
        }
      }
    }
    .actions {
      display: flex;
      a:hover {
        cursor: text;
      }
      button {
        padding: 13px;
        justify-content: center;
        align-items: center;
        display: flex;
        i {
          font-size: 12px !important;
        }
        width: 20px;
        height: 20px;
        background: none;
        margin: 3px 5px;
        border-radius: 5px;
      }
    }
    a:hover {
      cursor: pointer;
    }
    .active {
      a {
        color: #fff !important;
        background-color: #6d85fb !important;
        border-color: #dbdbdb !important;
      }
    }
    .page-item:first,
    .page-item:last-child {
      display: none !important;
    }
    .page-link {
      padding: 0 !important;
      height: 32px;
      width: 32px;
      justify-content: center;
      align-items: center;
      display: flex;
      padding: 0 !important;
    }
    table {
      td,
      th {
        width: 25%;
      }
    }
  }
}
</style>
