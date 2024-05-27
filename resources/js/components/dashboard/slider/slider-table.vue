<template>
  <div class="p-3 slider-container">
    <DeleteConfirmation
      @confirm="deleteSlider"
      @closed="selectedSlider = null"
    />
    <SliderForm
      @created="onCreated"
      @updated="onUpdated"
      :selectedSlider="selectedSlider"
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
                data-target="#sliderFormModal"
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
                <th scope="col">{{ $t("IMAGE") }}</th>
                <th scope="col">{{ $t("TITLE") }}</th>
                <th scope="col">{{ $t("EXTERNAL") }}</th>
                <th scope="col">{{ $t("PRODUCT") }}</th>
                <th class="actions-header" scope="col">{{ $t("ACTIONS") }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(slider, index) in sliders" :key="slider.id">
                <td class="img"><img :src="slider.image" /></td>
                <td>{{ slider.title }}</td>
                <td>{{ slider.external ? $t("YES") : $t("NO") }}</td>
                <td>
                  <template v-if="slider.product">
                    {{ slider.product.name }}
                  </template>
                </td>
                <td class="actions-cell">
                  <div class="actions">
                    <button
                      @click="onEditClicked(slider, index)"
                      data-toggle="modal"
                      data-target="#sliderFormModal"
                      class="border text-secondary"
                    >
                      <i class="fa fa-edit" aria-hidden="true"></i>
                    </button>
                    <button
                      @click="onDeleteClicked(slider, index)"
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
            :clickHandler="getSliders"
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
import sliderClient from "../../../shared/http-clients/admin/slider-client";
import Paginate from "vuejs-paginate-next";
import exportFromJSON from "export-from-json";
import DeleteConfirmation from "../../../shared/components/delete-confirmation.vue";
import SliderForm from "./slider-form.vue";
import sliderStore from "./slider-store";
import { inject, provide, reactive, ref, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  components: {
    Paginate,
    DeleteConfirmation,
    SliderForm,
  },
  setup() {
    const data = reactive({
      pageSize: 6,
      page: 1,
      sliders: [],
      text: "",
      pageCounts: 0,
      timeout: null,
      selectedSlider: null,
    });
    const toast = inject("toast");
    const { t, locale } = useI18n({ useScope: "global" });
    provide("slider_store", sliderStore);
    created();
    //Methods
    function onAddClicked() {
      data.selectedSlider = null;
      //Make little delay to ensure that watcher that found in category form component
      // catch selectedSlider input prop
      setTimeout(() => {
        sliderStore.onFormShow = !sliderStore.onFormShow;
      }, 1);
    }
    function onEditClicked(slider, index) {
      data.selectedSlider = slider;
      //Make little delay to ensure that watcher catch selectedSlider input prop
      //in category form component
      setTimeout(() => {
        sliderStore.onFormShow = !sliderStore.onFormShow;
      }, 1);
    }
    function onDeleteClicked(category, index) {
      data.selectedSlider = category;
    }
    function getSliders() {
      sliderClient
        .getSliders(data.page, data.pageSize, data.text)
        .then((response) => {
          data.sliders = response.data.data;
          data.pageCounts = Math.ceil(response.data.total / data.pageSize);
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function downloadExcelFile() {
      sliderClient.getAllSliders().then((res) => {
        let data = res.data;
        const fileName = "products";
        const exportType = exportFromJSON.types.csv;
        if (data) exportFromJSON({ data, fileName, exportType });
      });
    }
    function print() {
      window.print();
    }
    function onCreated(event) {
      data.page = 1;
      getSliders();
    }
    function onUpdated(event) {
      data.selectedSlider = null;
      getSliders();
    }
    function deleteSlider() {
      sliderClient
        .delete(data.selectedSlider.id)
        .then((response) => {
          toast.success(t("DELETED_SUCCESSFULLY"));
          if (data.page > 1 && data.sliders.length == 1) {
            data.page--;
          }
          getSliders();
          data.selectedSlider = null;
        })
        .catch((error) => {});
    }
    function search() {
      data.page = 1;
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getSliders();
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
      getSliders();
    }
    return {
      ...toRefs(data),
      onAddClicked,
      onEditClicked,
      onDeleteClicked,
      getSliders,
      downloadExcelFile,
      onCreated,
      onUpdated,
      deleteSlider,
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
.slider-container {
  td {
    &:not(.actions-cell, .img) {
      position: relative;
      top: 6px;
    }
    &.actions-cell {
      position: relative;
      top: 8px;
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
