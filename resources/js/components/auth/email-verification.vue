<template>
  <div class="email-verification-container">
    <div class="ps-account">
      <div class="container">
        <div class="row">
          <div class="mx-auto col-md-8 col-lg-6">
            <!--Form-->
            <form @submit.prevent="verifyEmail">
              <div class="ps-form--review">
                <h2 class="ps-form__title">{{ $t("EMAIL_VERIFICATION") }}</h2>
                <div class="ps-form__group">
                  <label class="ps-form__label"
                    >{{ $t("TOKEN") }} <span class="text-danger">*</span></label
                  >
                  <input
                    v-model="v$.verification_code.$model"
                    type="text"
                    class="form-control ps-form__input"
                  />
                  <div class="text-danger">
                    <div v-for="error in v$.verification_code.$errors" :key="error">
                      {{ $t("TOKEN") + " " + $t(error.$validator) }}
                    </div>
                    <div v-if="!v$.verification_code.$invalid && invalidVerificationCode">
                      {{ $t("TOKEN") + " " + $t("INVALID") }}
                    </div>
                  </div>
                </div>
                <div class="ps-form__submit">
                  <button class="ps-btn ps-btn--warning">{{ $t("SUBMIT") }}</button>
                  <a class="mt-4" href="" @click.prevent="resendToken">
                    {{ $t("RESEND") + " " + $t("TOKEN") }}
                  </a>
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
import { required } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import authClient from "../../shared/http-clients/auth-client";
import global from "../../shared/global";
export default {
  setup() {
    return { v$: useVuelidate() };
  },
  data: function () {
    return {
      verification_code: "",
      invalidVerificationCode: false,
    };
  },
  methods: {
    verifyEmail() {
      if (this.v$.$invalid) {
        this.v$.$touch();
        return;
      }
      this.invalidVerificationCode = false;
      this.store.showLoader = true;
      authClient
        .verifyEmail(this.getForm())
        .then((response) => {
          this.store.showLoader = false;
          this.$toast.success(this.$t("EMAIL_VERIFIED_SUCCESSFULLY"));
          this.$router.push(global.AUTH_REDIRECT);
        })
        .catch((error) => {
          this.invalidVerificationCode = true;
          this.store.showLoader = false;
        });
    },
    resendToken() {
      this.store.showLoader = true;
      authClient
        .resendToken()
        .then((response) => {
          this.store.showLoader = false;
          this.$toast.success(this.$t("EMAIL_SENT_SUCCESSFULLY"));
        })
        .catch((error) => {
          this.store.showLoader = false;
          console.log(error.response);
        });
    },
    //Commons
    getForm() {
      return {
        verification_code: this.verification_code,
      };
    },
  },
  inject: ["store"],
  validations() {
    return {
      verification_code: { required },
    };
  },
};
</script>

<style lang="scss" scoped>
.email-verification-container {
  margin-top: 100px;
  .ps-form__submit {
    display: flex;
    justify-content: space-between;
  }
}
</style>
