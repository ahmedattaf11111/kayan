<template>
  <div class="reset-password-container">
    <div class="ps-account">
      <div class="container">
        <div class="row">
          <div class="mx-auto col-md-8 col-lg-6">
            <!--Form-->
            <form @submit.prevent="resetPassword">
              <div class="ps-form--review">
                <div class="header">
                  <h2 class="ps-form__title">{{ $t("RESET_PASSWORD") }}</h2>
                  <router-link class="mt-3" to="/login">{{ $t("CANCEL") }}</router-link>
                </div>
                <div class="ps-form__group">
                  <label class="ps-form__label">{{ $t("PASSWORD") }} </label>
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
                      >
                      </a>
                    </div>
                  </div>
                  <div class="text-danger">
                    <div v-for="error in v$.password.$errors" :key="error">
                      {{ $t("PASSWORD") + " " + $t(error.$validator) }}
                    </div>
                  </div>
                </div>
                <div class="ps-form__group">
                  <label class="ps-form__label">{{ $t("PASSWORD_CONFIRMATION") }} </label>
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
                <div class="ps-form__submit">
                  <button class="ps-btn ps-btn--warning">
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
import strong from "../../shared/validators/strong-password-validator";
import { required, sameAs } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import authClient from "../../shared/http-clients/auth-client";
import cartClient from "../../shared/http-clients/cart-client";
import global from "../../shared/consts/global";
import TokenUtil from "../../shared/utils/token-util";
export default {
  setup() {
    return { v$: useVuelidate() };
  },
  data: function () {
    return {
      password: "",
      password_confirmation: "",
      passwordHidden: true,
      passwordConfirmationHidden: true,
    };
  },
  methods: {
    resetPassword() {
      if (this.v$.$invalid) {
        this.v$.$touch();
        return;
      }
      this.store.showLoader = true;
      authClient
        .resetPassword(this.getForm())
        .then((response) => {
          TokenUtil.set(response.data.access_token);
          this.getCurrentUser();
          this.getCartItemsCount();
          this.$router.push(global.AUTH_REDIRECT);
        })
        .catch((error) => {
          this.store.showLoader = false;
          this.$toast.error(this.$t("TOKEN") + " " + this.$t("INVALID"));
        });
    },
    //Commons
    getForm() {
      return {
        password: this.password,
        token: this.$route.params.token,
      };
    },
    getCurrentUser() {
      authClient
        .getCurrentUser()
        .then((response) => {
          this.store.currentUser = response.data;
          this.store.showLoader = false;
        })
        .catch((error) => {
          console.log("err", error.response);
        });
    },
    getCartItemsCount() {
      cartClient
        .getCartItemsCount()
        .then((response) => {
          this.store.cartItemsCount = response.data;
          this.store.showLoader = false;
        })
        .catch((error) => {
          console.log("err", error.response);
        });
    },
  },
  inject: ["store"],
  validations() {
    return {
      password: { required, strong },
      password_confirmation: {
        required,
        sameAsPassword(value) {
          if (!value) {
            return true;
          }
          return this.password == value;
        },
      },
    };
  },
};
</script>

<style lang="scss" scoped>
.reset-password-container {
  margin-top: 50px;
  .header {
    display: flex;
    justify-content: space-between;
  }
}
</style>
