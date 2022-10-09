<template>
  <div class="login-container">
    <div class="ps-account">
      <div class="container">
        <div class="row">
          <div class="mx-auto col-md-8 col-lg-6">
            <form @submit.prevent="login">
              <div class="ps-form--review">
                <h2 class="ps-form__title">{{ $t("LOGIN") }}</h2>
                <div class="ps-form__group">
                  <label class="ps-form__label">{{ $t("EMAIL") }} </label>
                  <input
                    v-model="v$.email.$model"
                    class="form-control ps-form__input"
                    type="text"
                  />
                  <div class="text-danger">
                    <div v-for="error in v$.email.$errors" :key="error">
                      {{ $t("EMAIL") + " " + $t(error.$validator) }}
                    </div>
                  </div>
                </div>
                <div class="ps-form__group password">
                  <label class="ps-form__label">{{ $t("PASSWORD") }} </label>
                  <div class="input-group">
                    <input
                      v-model="v$.password.$model"
                      class="form-control ps-form__input"
                      :type="passwordHidden ? 'password' : 'text'"
                    />
                    <div class="input-group-append">
                      <a
                        class="fa fa-eye-slash toogle-password"
                        href="javascript: vois(0);"
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
                <div class="forget-password">
                  <routerLink class="ps-account__link" to="/forget-password">{{
                    $t("FORGET_PASSWORD_?")
                  }}</routerLink>
                </div>
                <div class="ps-form__submit">
                  <button class="ps-btn ps-btn--warning">{{ $t("LOGIN") }}</button>
                </div>
                <div class="footer">
                  <h2 class="ps-form__title text-center">
                    {{ $t("DONT_HAVE_ACCOUNT?") }}
                  </h2>
                  <div class="links">
                    <router-link to="/register">{{ $t("REGISTER_NOW") }}</router-link>
                    <router-link to="/home">{{ $t("CONTINUE_AS_GUEST") }}</router-link>
                  </div>
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
import { required, email } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import authClient from "../../shared/http-clients/auth-client";
import TokenUtil from "../../shared/utils/token-util";
import global from "../../shared/consts/global";
import cartClient from "../../shared/http-clients/cart-client";
export default {
  setup() {
    return { v$: useVuelidate() };
  },
  data: function () {
    return {
      email: "",
      password: "",
      passwordHidden: true,
    };
  },
  methods: {
    login() {
      if (this.v$.$invalid) {
        this.v$.$touch();
        return;
      }
      this.store.showLoader = true;
      authClient
        .login(this.getForm())
        .then((response) => {
          TokenUtil.set(response.data.access_token);
          this.getCurrentUser();
          this.getCartItemsCount();
          this.$router.push(global.AUTH_REDIRECT);
        })
        .catch((error) => {
          this.$toast.error(this.$t("LOGIN_FAILED"));
        });
    },
    //Commons
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
    getForm() {
      return {
        email: this.email,
        password: this.password,
      };
    },
  },
  validations() {
    return {
      email: { required, email },
      password: {
        required,
      },
    };
  },
  inject: ["store"],
};
</script>

<style lang="scss" scoped>
.login-container {
  margin-top: 50px;
  .password {
    margin-bottom: 0 !important;
  }
  .forget-password {
    display: flex;
    justify-content: right;
    a {
      margin-right: auto;
      text-decoration: none;
      margin-top: 5px !important;
      margin-bottom: 25px;
    }
  }
  .footer {
    margin-top: 35px;
    .ps-form__title {
      font-size: 20px;
    }
    .links {
      font-size: 18px;
      a {
        padding: 0 30px;
      }
      display: flex;
      justify-content: space-between;
    }
  }
}
</style>
