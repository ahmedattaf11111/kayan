<template>
  <div class="order-container">
    <div class="ps-account">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 col-md-8 mx-auto">
            <form @submit.prevent="save">
              <div class="ps-form--review">
                <div class="header">
                  <h2 class="ps-form__title">{{ $t("COMPLETE_ORDER") }}</h2>
                  <router-link class="mt-3" to="/cart">{{
                    $t("EDIT_ORDER")
                  }}</router-link>
                </div>
                <div class="form-checkbox">
                  <input v-model="same_address_shipping" type="checkbox" />
                  <label class="form-check-label" for="flexCheckChecked">
                    {{ $t("SHIPPING_IN_SAME_ADDRESS") }}
                  </label>
                  <label class="form-check-label text-secondary" for="flexCheckChecked">
                    {{ `(${currentUser.client.address})` }}
                  </label>
                </div>
                <template v-if="!same_address_shipping">
                  <div class="ps-form__group">
                    <label class="ps-form__label" for="exampleInputEmail1"
                      >{{ $t("ADDRESS") }}<span class="text-danger">*</span></label
                    >
                    <input
                      type="text"
                      class="form-control ps-form__input"
                      v-model="v$.address.$model"
                    />
                    <div class="text-danger">
                      <div v-for="error in v$.address.$errors" :key="error">
                        {{ $t("ADDRESS") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                  <div class="ps-form__group">
                    <label class="ps-form__label"
                      >{{ $t("CITIES") }} <span class="text-danger">*</span></label
                    >
                    <div class="select-wrapper">
                      <select
                        @change="onCitySelected"
                        class="form-control ps-form__input"
                        v-model="v$.city_id.$model"
                      >
                        <option v-for="city in cities" :key="city.id" :value="city.id">
                          {{ city.name }}
                        </option>
                      </select>
                    </div>
                    <div class="text-danger">
                      <div v-for="error in v$.city_id.$errors" :key="error">
                        {{ $t("CITY") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                  <div class="ps-form__group">
                    <label class="ps-form__label"
                      >{{ $t("AREAS") }} <span class="text-danger">*</span></label
                    >
                    <div class="select-wrapper">
                      <select
                        class="form-control ps-form__input"
                        v-model="v$.area_id.$model"
                      >
                        <option v-for="area in areas" :key="area.id" :value="area.id">
                          {{ area.name }}
                        </option>
                      </select>
                    </div>
                    <div class="text-danger">
                      <div v-for="error in v$.area_id.$errors" :key="error">
                        {{ $t("AREA") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </template>
                <div class="ps-form__submit">
                  <button
                    @click="paymentMethod = payment_method.CASH"
                    type="submit"
                    class="ps-btn ps-btn--warning"
                  >
                    {{ $t("CACHE_PAYMENT") }}
                  </button>
                  <button
                    @click="paymentMethod = payment_method.ONLINE"
                    type="submit"
                    class="ps-btn ps-btn--warning"
                  >
                    {{ $t("ONLINE_PAYMENT") }}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import useVuelidate from "@vuelidate/core";
import cartClient from "../../shared/http-clients/cart-client";
import authClient from "../../shared/http-clients/auth-client";
import { inject, onMounted, reactive, toRefs } from "@vue/runtime-core";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import payment_method from "../../shared/consts/payment-method";

export default {
  setup(props, context) {
    const { t, locale } = useI18n({});
    let store = inject("store");
    let toast = inject("toast");
    const router = useRouter();
    const data = reactive({
      cities: [],
      areas: [],
      paymentMethod: "",
    });
    const form = reactive({
      address: store.currentUser.client.shipping_address,
      city_id: store.currentUser.client.shipping_city_id,
      area_id: store.currentUser.client.shipping_area_id,
      same_address_shipping: Boolean(store.currentUser.client.same_address_shipping),
    });
    const rules = {
      address: {
        required(value) {
          if (!form.same_address_shipping) return value;
          else return true;
        },
      },
      city_id: {
        required(value) {
          if (!form.same_address_shipping) return value;
          else return true;
        },
      },
      area_id: {
        required(value) {
          if (!form.same_address_shipping) return value;
          else return true;
        },
      },
    };
    const v$ = useVuelidate(rules, form);
    onMounted(() => {
      store.showLoader = true;
      authClient
        .getCitiesWithAreas()
        .then((response) => {
          store.showLoader = false;
          data.cities = response.data;
          if (form.city_id) {
            onCitySelected();
          }
        })
        .catch((error) => {
          console.log(error.response);
        });
    });
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      store.showLoader = true;
      if (data.paymentMethod == payment_method.CASH) {
        cachePayment();
      } else if (data.paymentMethod == payment_method.ONLINE) {
        onlinePayment();
      }
    }
    function onCitySelected() {
      let city = getSelectedCity();
      data.areas = city.areas;
      if (!city.available) toast.warning(t("NOT_AVIALABLE_NOW"));
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
    function cachePayment() {
      cartClient
        .cachePayment(getForm())
        .then(() => {
          store.showLoader = false;
          router.push("/home");
          toast.success(t("ORDER_REGISTERED"));
          store.cartItemsCount = 0;
          saveCurrentUser();
        })
        .catch((error) => {
          store.showLoader = false;
        });
    }
    function onlinePayment() {
      cartClient.onlinePayment(getForm()).then((response) => {
        window.location.href = response.data.url;
      });
    }

    function getForm() {
      return {
        shipping_address: form.address,
        shipping_city_id: form.city_id,
        shipping_area_id: form.area_id,
        same_address_shipping: form.same_address_shipping,
      };
    }
    //Commons
    function saveCurrentUser() {
      store.currentUser.client.same_address_shipping = form.same_address_shipping;
      store.currentUser.client.shipping_address = form.address;
      store.currentUser.client.shipping_city_id = form.city_id;
      store.currentUser.client.shipping_area_id = form.area_id;
    }
    return {
      ...toRefs(data),
      ...toRefs(form),
      ...toRefs(store),
      v$,
      locale,
      save,
      onCitySelected,
      payment_method,
    };
  },
};
</script>

<style scoped lang="scss">
.order-container {
  .form-checkbox {
    margin-bottom: 12px;
    display: flex;
    label {
      margin: 0 5px;
    }
  }
  margin-top: 50px;
  .header {
    display: flex;
    justify-content: space-between;
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
  .ps-form__submit {
    display: flex;
    flex-wrap: wrap;
    button {
      margin-left: 5px;
      background: none !important;
      color: #00dd2f !important;
    }
  }
}
</style>
