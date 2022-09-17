<template>
  <div class="register-container">
    <div class="ps-account">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 col-md-8 mx-auto">
            <form @submit.prevent="save">
              <div class="ps-form--review">
                <div class="header">
                  <h2 class="ps-form__title">{{ $t("REGISTER_NEW_ACCOUNT") }}</h2>
                  <router-link class="mt-3" to="/login">{{ $t("CANCEL") }}</router-link>
                </div>
                <div class="ps-form__group">
                  <label class="ps-form__label" for="exampleInputEmail1"
                    >{{ $t("STORE_NAME") }} <span class="text-danger">*</span></label
                  >
                  <input
                    type="text"
                    class="form-control ps-form__input"
                    v-model="v$.store_name.$model"
                  />
                  <div class="text-danger">
                    <div v-for="error in v$.store_name.$errors" :key="error">
                      {{ $t("STORE_NAME") + " " + $t(error.$validator) }}
                    </div>
                  </div>
                </div>
                <div class="ps-form__group">
                  <label class="ps-form__label" for="exampleInputEmail1"
                    >{{ $t("EMAIL") }}<span class="text-danger">*</span></label
                  >
                  <input
                    type="text"
                    class="form-control ps-form__input"
                    v-model="v$.email.$model"
                  />
                  <div class="text-danger">
                    <div v-for="error in v$.email.$errors" :key="error">
                      {{ $t("EMAIL") + " " + $t(error.$validator) }}
                    </div>
                    <div v-if="!v$.email.$invalid && emailExist">
                      {{ $t("EXIST", { field: $t("EMAIL") }) }}
                    </div>
                  </div>
                </div>
                <div class="ps-form__group">
                  <label class="ps-form__label"
                    >{{ $t("PASSWORD") }} <span class="text-danger">*</span></label
                  >
                  <div class="input-group">
                    <input
                      v-model="v$.password.$model"
                      :type="passwordHidden ? 'password' : 'text'"
                      class="form-control ps-form__input"
                    />
                    <div class="input-group-append">
                      <a
                        class="fa fa-eye-slash toogle-password"
                        href="javascript: void(0);"
                        @click="passwordHidden = !passwordHidden"
                      ></a>
                    </div>
                  </div>
                  <div class="text-danger">
                    <div v-for="error in v$.password.$errors" :key="error">
                      {{ $t("PASSWORD") + " " + $t(error.$validator) }}
                    </div>
                  </div>
                </div>
                <div class="ps-form__group">
                  <label class="ps-form__label"
                    >{{ $t("PASSWORD_CONFIRMATION") }}<span class="text-danger">*</span>
                  </label>
                  <div class="input-group">
                    <input
                      v-model="v$.password_confirmation.$model"
                      :type="passwordConfirmationHidden ? 'password' : 'text'"
                      class="form-control ps-form__input"
                    />
                    <div class="input-group-append">
                      <a
                        class="fa fa-eye-slash toogle-password"
                        href="javascript: void(0);"
                        @click="passwordConfirmationHidden = !passwordConfirmationHidden"
                      ></a>
                    </div>
                  </div>
                  <div class="text-danger">
                    <div v-for="error in v$.password_confirmation.$errors" :key="error">
                      {{ $t("PASSWORD_CONFIRMATION") + " " + $t(error.$validator) }}
                    </div>
                  </div>
                </div>
                <div class="ps-form__group">
                  <label class="ps-form__label" for="exampleInputEmail1"
                    >{{ $t("PHONE") }}<span class="text-danger">*</span></label
                  >
                  <input
                    type="text"
                    class="form-control ps-form__input"
                    v-model="v$.phone.$model"
                  />
                  <div class="text-danger">
                    <div v-for="error in v$.phone.$errors" :key="error">
                      {{ $t("PHONE") + " " + $t(error.$validator) }}
                    </div>
                    <div v-if="!v$.phone.$invalid && phoneExist">
                      {{ $t("EXIST", { field: $t("PHONE") }) }}
                    </div>
                  </div>
                </div>
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
                    >{{ $t("TYPE") }} <span class="text-danger">*</span></label
                  >
                  <div class="select-wrapper">
                    <select class="form-control ps-form__input" v-model="v$.type.$model">
                      <option value="PHARMACIST">{{ $t("PHARMACIST") }}</option>
                      <option value="COSMETICS_STORE">{{ $t("COSMETICS_STORE") }}</option>
                      <option value="DRUG_STORE">{{ $t("DRUG_STORE") }}</option>
                    </select>
                  </div>
                  <div class="text-danger">
                    <div v-for="error in v$.type.$errors" :key="error">
                      {{ $t("TYPE") + " " + $t(error.$validator) }}
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
                      @change="onAreaSelected"
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
                <div class="ps-form__submit">
                  <button type="submit" class="ps-btn ps-btn--warning">
                    {{ $t("SUBMIT") }}
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
import { required, email } from "@vuelidate/validators";
import authClient from "../../../shared/http-clients/auth-client";
import { inject, reactive, toRefs } from "@vue/runtime-core";
import { useI18n } from "vue-i18n";
import phone from "../../../shared/validators/phone-validator";
import strong from "../../../shared/validators/strong-password-validator";
import TokenUtil from "../../../shared/utils/token-util";
import { useRouter } from "vue-router";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({});
    const data = reactive({
      phoneExist: false,
      emailExist: false,
      passwordHidden: true,
      passwordConfirmationHidden: true,
      cities: [],
      areas: [],
    });
    const form = reactive({
      phone: "",
      email: "",
      store_name: "",
      address: "",
      password: "",
      password_confirmation: "",
      type: "",
      city_id: null,
      area_id: null,
    });
    const rules = {
      phone: {
        required,
        phone(value) {
          return phone(value);
        },
      },
      email: { required, email },
      store_name: { required },
      address: { required },
      password: { required, strong },
      password_confirmation: {
        required,
        sameAsPassword(value) {
          if (!value) {
            return true;
          }
          return form.password == value;
        },
      },
      type: { required },
      city_id: { required },
      area_id: { required },
    };
    const v$ = useVuelidate(rules, form);
    let store = inject("store");
    let toast = inject("toast");
    const router = useRouter();
    created();
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      data.phoneExist = false;
      data.emailExist = false;
      store.showLoader = true;
      register();
    }
    function onCitySelected() {
      let city = getSelectedCity();
      data.areas = city.areas;
      if (!city.available) toast.warning(t("NOT_AVIALABLE_NOW"));
    }
    function onAreaSelected() {
      let area = getSelectedArea();
      if (!area.available) toast.warning(t("NOT_AVIALABLE_NOW"));
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
    function getSelectedArea() {
      let area = null;
      data.areas.forEach((_area) => {
        if (form.area_id == _area.id) {
          return (area = _area);
        }
      });
      return area;
    }
    function created() {
      store.showLoader = true;
      authClient
        .getCitiesWithAreas(getForm())
        .then((response) => {
          store.showLoader = false;
          data.cities = response.data;
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function register() {
      authClient
        .register(getForm())
        .then((response) => {
          store.showLoader = false;
          TokenUtil.set(response.data.access_token);
          router.push("/verify-email");
          store.currentUser = TokenUtil.getUser();
        })
        .catch((error) => {
          store.showLoader = false;
          data.phoneExist = error.response.data.errors.phone ? true : false;
          data.emailExist = error.response.data.errors.email ? true : false;
        });
    }

    function getForm() {
      return {
        email: form.email,
        phone: form.phone,
        store_name: form.store_name,
        address: form.address,
        password: form.password,
        type: form.type,
        city_id: form.city_id,
        area_id: form.area_id,
      };
    }
    return {
      ...toRefs(data),
      ...toRefs(form),
      v$,
      locale,
      save,
      onCitySelected,
      onAreaSelected,
    };
  },
};
</script>

<style scoped lang="scss">
.register-container {
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
}
</style>
