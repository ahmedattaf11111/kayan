<template>
  <section class="ps-section--newsletter" data-background="img/newsletter-bg.jpg">
    <h3 class="ps-section__title">
      {{ $t("JOIN_NEWSLETTER") }}<br />
      {{ $t("TO_GET_ALL_NEW") }}
    </h3>
    <div class="ps-section__content">
      <form @submit.prevent="storeNewsletter">
        <div class="ps-form--subscribe">
          <div class="ps-form__control">
            <input
              v-model="v$.email.$model"
              class="form-control ps-input"
              type="email"
              :class="{
                'is-invalid': v$.email.$error,
              }"
              :placeholder="$t('ENTER_EMAIL')"
            />
            <button class="ps-btn ps-btn--warning">{{ $t("SUBSCRIBE") }}</button>
          </div>
        </div>
      </form>
    </div>
  </section>
</template>
<script>
import homeClient from "../../../shared/http-clients/home-client";
import { inject, reactive, toRefs } from "vue-demi";
import store from "../../../shared/store";
import { required, email } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import { useI18n } from "vue-i18n";
export default {
  setup() {
    const { t, locale } = useI18n({});
    let form = reactive({
      email: "",
    });
    const rules = {
      email: { required, email },
    };
    const v$ = useVuelidate(rules, form);
    const toast = inject("toast");
    //Methods
    function storeNewsletter() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      store.showLoader = true;
      homeClient
        .storeNewsletter(getForm())
        .then((response) => {
          v$.value.$reset();
          form.email = "";
          store.showLoader = false;
          toast.success(t("SUBSCRIBED_SUCCESSFULLY"));
        })
        .catch((error) => {
          if (error.response.data.errors.email) {
            toast.error(t("EXIST", { field: t("EMAIL") }));
          }
          store.showLoader = false;
        });
    }
    //Commons
    function getForm() {
      return {
        email: form.email,
      };
    }
    return { v$, ...toRefs(form), storeNewsletter };
  },
};
</script>

<style lang="scss">
.ps-section--newsletter {
  margin: 5px 0;
}
</style>
