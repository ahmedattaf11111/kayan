<template>
  <div class="forget-password-container">
    <div class="ps-account">
      <div class="container">
        <div class="row">
          <div class="mx-auto col-md-8 col-lg-6">
            <form @submit.prevent="forgetPassword">
              <div class="ps-form--review">
                <div class="header">
                  <h2 class="ps-form__title">{{ $t("RESET_PASSWORD") }}</h2>
                  <router-link class="mt-3" to="/login">{{ $t("CANCEL") }}</router-link>
                </div>
                <div class="ps-form__group">
                  <label class="ps-form__label">
                    {{ $t("EMAIL") }}
                  </label>
                  <input
                    v-model="v$.email.$model"
                    type="text"
                    class="form-control ps-form__input"
                  />
                  <div class="text-danger">
                    <div v-for="error in v$.email.$errors" :key="error">
                      {{ $t("EMAIL") + " " + $t(error.$validator) }}
                    </div>
                  </div>
                </div>
                <div class="ps-form__submit">
                  <button class="ps-btn ps-btn--warning">{{ $t("SEND") }}</button>
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
import authClient from "../../../shared/http-clients/auth-client";
export default {
  setup() {
    return { v$: useVuelidate() };
  },
  data: function () {
    return {
      email: "",
    };
  },
  methods: {
    forgetPassword() {
      if (this.v$.$invalid) {
        this.v$.$touch();
        return;
      }
      this.store.showLoader = true;
      authClient
        .forgetPassword(this.email)
        .then((response) => {
          this.store.showLoader = false;
          this.$toast.success(this.$t("EMAIL_SENT_SUCCESSFULLY"));
        })
        .catch((error) => {
          this.store.showLoader = false;
          this.$toast.error(this.$t("EMAIL_NOT_EXIST"));
        });
    },
  },
  inject: ["store"],
  validations() {
    return {
      email: { required, email },
    };
  },
  created() {},
};
</script>

<style lang="scss">
.forget-password-container {
  margin-top: 50px;
  .header {
    display: flex;
    justify-content: space-between;
  }
}
</style>
