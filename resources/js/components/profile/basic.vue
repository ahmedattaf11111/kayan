<template>
  <div class="basic-container container my-5">
    <div class="row">
      <div class="col-md-4">
        <Sidebar activePath="basic" />
      </div>
      <div class="col-md-8">
        <div class="dashboard border">
          <div class="header">
            <h5>{{ $t("ORDERS_STATISTICS") }}</h5>
            <div style="width: 100%; height: 1px" class="border-top mb-3"></div>
          </div>
          <div class="statistics">
            <div class="row">
              <div class="col-lg-4 col-md-6">
                <div class="statistic border">
                  <img
                    src="https://themes.pixelstrap.com/fastkart/assets/images/svg/order.svg"
                  />
                  <div class="info">
                    <div class="text-mute">{{ $t("TOTAL_ORDERS") }}</div>
                    <div>{{ totalOrders }}</div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-6">
                <div class="statistic border">
                  <img
                    src="https://themes.pixelstrap.com/fastkart/assets/images/svg/order.svg"
                  />
                  <div class="info">
                    <div class="text-mute">{{$t("TOTAL_PENDING_ORDERS")}}</div>
                    <div>{{totalPendingOrders}}</div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-6">
                <div class="statistic border">
                  <img
                    src="https://themes.pixelstrap.com/fastkart/assets/images/svg/order.svg"
                  />
                  <div class="info">
                    <div class="text-mute">{{$t("TOTAL_COMPLETED_ORDERS")}}</div>
                    <div>{{totalCompletedOrders}}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="basic-info mt-4">
            <div class="header">
              <h5>{{ $t("BASIC_INFO") }}</h5>
              <div style="width: 100%; height: 1px" class="border-top mb-3"></div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <b>{{ $t("NAME") }}</b> : {{ name }}
              </div>
              <div class="col-md-6 mb-3">
                <b>{{ $t("EMAIL") }}</b> : {{ email }}
              </div>
              <div class="col-md-6 mb-3">
                <b>{{ $t("STORE_NAME") }}</b> : {{ store_name }}
              </div>
              <div class="col-md-6 mb-3">
                <b>{{ $t("TYPE") }}</b> : {{ $t(type) }}
              </div>
            </div>
          </div>
          <div class="contact-info mt-4">
            <div class="header">
              <h5>{{ $t("CONTACT_INFO") }}</h5>
              <div style="width: 100%; height: 1px" class="border-top mb-3"></div>
            </div>
            <form @submit.prevent="save">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="labels">{{ $t("ADDRESS") }} </label>
                    <input
                      v-model="v$.address.$model"
                      type="text"
                      :class="{
                        'is-invalid': v$.address.$error,
                      }"
                      class="form-control"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.address.$errors" :key="error">
                        {{ $t("ADDRESS") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="labels">{{ $t("PHONE") }}</label
                    ><input
                      v-model="v$.phone.$model"
                      type="text"
                      class="form-control"
                      :class="{
                        'is-invalid': v$.phone.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.phone.$errors" :key="error">
                        {{ $t("PHONE") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>{{ $t("CITY") }}</label>
                    <div class="select-wrapper">
                      <select
                        @change="onCitySelected"
                        class="form-control"
                        :class="{
                          'is-invalid': v$.city_id.$error,
                        }"
                        v-model="v$.city_id.$model"
                      >
                        <option v-for="city in cities" :key="city.id" :value="city.id">
                          {{ city.name }}
                        </option>
                      </select>
                      <div class="invalid-feedback">
                        <div v-for="error in v$.city_id.$errors" :key="error">
                          {{ $t("CITY") + " " + $t(error.$validator) }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>{{ $t("AREA") }}</label>
                    <div class="select-wrapper">
                      <select
                        :class="{
                          'is-invalid': v$.area_id.$error,
                        }"
                        class="form-control"
                        v-model="v$.area_id.$model"
                      >
                        <option v-for="area in areas" :key="area.id" :value="area.id">
                          {{ area.name }}
                        </option>
                      </select>
                      <div class="invalid-feedback">
                        <div v-for="error in v$.area_id.$errors" :key="error">
                          {{ $t("AREA") + " " + $t(error.$validator) }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <button class="btn submit">{{ $t("SUBMIT") }}</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { inject, onMounted, reactive, toRefs } from "vue-demi";
import phone from "../../shared/validators/phone-validator";
import { required } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import authClient from "../../shared/http-clients/auth-client";
import Sidebar from "./sidebar";
import { useI18n } from "vue-i18n";
export default {
  components: {
    Sidebar,
  },
  setup() {
    const store = inject("store");
    const toast = inject("toast");
    const { t, locale } = useI18n({});
    const data = reactive({
      cities: [],
      areas: [],
      name: store.currentUser.name,
      email: store.currentUser.email,
      store_name: store.currentUser.client.store_name,
      type: store.currentUser.client.type,
      totalOrders: 0,
      totalPendingOrders: 0,
      totalCompletedOrders: 0,
    });
    const form = reactive({
      phone: store.currentUser.phone,
      address: store.currentUser.client.address,
      city_id: store.currentUser.client.city_id,
      area_id: null,
    });
    const rules = {
      address: { required },
      city_id: { required },
      area_id: { required },
      phone: {
        required,
        phone(value) {
          return phone(value);
        },
      },
    };
    const v$ = useVuelidate(rules, form);
    onMounted(() => {
      getCitites();
      getProfileStatistics();
    });
    //Methods
    function onCitySelected(event) {
      form.area_id = null;
      let city = getSelectedCity();
      data.areas = city.areas;
      if (event && !city.available) toast.warning(t("NOT_AVIALABLE_NOW"));
    }
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      store.showLoader = true;
      authClient
        .updateProfile(getForm())
        .then((response) => {
          store.showLoader = false;
          toast.success(t("UPDATED_SUCCESSFULLY"));
          updateCurrentUser();
        })
        .catch((error) => {});
    }
    //Commons
    function getSelectedCity() {
      let city = null;
      data.cities.forEach((_city) => {
        if (form.city_id == _city.id) {
          return (city = _city);
        }
      });
      return city;
    }
    function getForm() {
      return {
        address: form.address,
        phone: form.phone,
        city_id: form.city_id,
        area_id: form.area_id,
      };
    }
    function updateCurrentUser() {
      store.currentUser.phone = form.phone;
      store.currentUser.client.address = form.address;
      store.currentUser.client.city_id = form.city_id;
      store.currentUser.client.area_id = form.area_id;
    }
    function getCitites() {
      store.showLoader = true;
      authClient
        .getCitiesWithAreas()
        .then((response) => {
          store.showLoader = false;
          data.cities = response.data;
          onCitySelected();
          form.area_id = store.currentUser.client.area_id;
        })
        .catch((error) => {});
    }
    function getProfileStatistics() {
      store.showLoader = true;
      authClient.getProfileStatistics().then((response) => {
        store.showLoader = false;
        data.totalOrders = response.data.total_orders;
        data.totalPendingOrders = response.data.total_pending_orders;
        data.totalCompletedOrders = response.data.total_completed_orders;
      });
    }
    return { v$, onCitySelected, ...toRefs(data), ...toRefs(form), save };
  },
};
</script>

<style lang="scss" scoped>
.statistics {
  .col-lg-4,
  .col-md-6 {
    padding-left: 4px !important;
    padding-right: 4px !important;
    padding-bottom: 8px;
  }
}
.header {
  display: flex;
  align-items: center;
  h5 {
    width: 150px;
    background-color: #eeeeee;
    border-radius: 5px;
    padding: 10px;
  }
}
b {
  font-size: 14px;
}
.dashboard {
  border-radius: 5px;
  padding: 18px;
  .statistics {
    .statistic {
      display: flex;
      padding: 25px;
      border-radius: 9px;
      img {
        width: 40px;
        height: 47px;
        margin-left: 15px;
      }
    }
  }
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
  .submit {
    font-size: 14px;
    background-color: #0e67d0;
    color: white;
    padding: 6px 20px;
  }
}
</style>
