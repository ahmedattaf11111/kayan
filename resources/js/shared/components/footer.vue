<template>
  <footer v-if="topFooterSections||needHelp||ourStore" class="ps-footer ps-footer--2 ps-footer--4">
    <div class="ps-footer--top">
      <div class="container">
        <div class="row m-0">
          <div class="col-12 col-sm-4 p-0">
            <p
              v-if="topFooterSections && topFooterSections.first_section"
              class="text-center"
            >
              <a class="ps-footer__link" @click.prevent="" href="">
                <i class="icon-wallet"></i> {{ topFooterSections.first_section }}
              </a>
            </p>
          </div>
          <div class="col-12 col-sm-4 p-0">
            <p
              v-if="topFooterSections && topFooterSections.second_section"
              class="text-center"
            >
              <a class="ps-footer__link" @click.prevent="" href="">
                <i class="icon-wallet"></i> {{ topFooterSections.second_section }}
              </a>
            </p>
          </div>
          <div class="col-12 col-sm-4 p-0">
            <p
              v-if="topFooterSections && topFooterSections.third_section"
              class="text-center"
            >
              <a class="ps-footer__link" @click.prevent="" href="">
                <i class="icon-wallet"></i> {{ topFooterSections.third_section }}
              </a>
            </p>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="ps-footer__middle">
        <div class="row m-0">
          <div class="col-12 col-md-6 p-0">
            <div class="row m-0">
              <div class="col-6 p-0">
                <div class="ps-footer--block">
                  <h5 class="ps-block__title">{{ $t("AUTHENTICATION") }}</h5>
                  <ul class="ps-block__list">
                    <li>
                      <router-link to="/home">{{ $t("HOME") }}</router-link>
                    </li>
                    <li>
                      <router-link to="/login">{{ $t("LOGIN") }}</router-link>
                    </li>
                    <li>
                      <router-link to="/register">{{ $t("REGISTER") }}</router-link>
                    </li>
                    <li>
                      <router-link to="/forget-password">{{
                        $t("RESET_PASSWORD")
                      }}</router-link>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-6 p-0">
                <div class="ps-footer--block">
                  <h5 class="ps-block__title">{{ $t("ACCOUNT_INFORMATION") }}</h5>
                  <ul class="ps-block__list">
                    <li>
                      <router-link to="/best-client-discount-products">{{
                        $t("BEST_CLIENT_DISCOUNT_PRODUCTS")
                      }}</router-link>
                    </li>
                    <li>
                      <router-link to="/profile-basic">{{
                        $t("PROFILE_SETTINGS")
                      }}</router-link>
                    </li>
                    <li>
                      <router-link to="/profile-orders">{{
                        $t("ORDERS")
                      }}</router-link>
                    </li>
                    <li>
                      <router-link to="/cart">{{ $t("CART") }}</router-link>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 p-0">
            <div class="row">
              <div v-if="needHelp" class="col-12 col-md-7">
                <div class="ps-footer--contact">
                  <h5 class="ps-footer__title">{{ $t("NEED_HELP") }}</h5>
                  <div class="ps-footer__fax">
                    <i class="icon-telephone"></i> {{ needHelp.header }}
                  </div>
                  <p class="ps-footer__work">
                    {{ needHelp.start_work_deadline }}<br />{{
                      needHelp.end_work_deadline
                    }}
                  </p>
                  <hr />
                  <p>
                    <a class="ps-footer__email" @click.prevent="" href="">
                      <i class="icon-envelope"></i
                      ><span
                        class="__cf_email__"
                        data-cfemail="6e0d01001a0f0d1a2e0b160f031e020b400d0103"
                        >[{{ needHelp.email }}]</span
                      >
                    </a>
                  </p>
                </div>
              </div>
              <div v-if="ourStore" class="col-12 col-md-5">
                <div class="ps-footer--address">
                  <div class="ps-logo">
                    <a href="index.html">
                      <img src="/assets/img/sticky-logo.png" alt /><img
                        class="logo-white"
                        src="/assets/img/logo-white.png"
                        alt /><img
                        class="logo-black"
                        src="/assets/img/Logo-black.png"
                        alt /><img
                        class="logo-white-all"
                        src="/assets/img/logo-white1.png"
                        alt /><img
                        class="logo-green"
                        src="/assets/img/logo-green.png"
                        alt
                    /></a>
                  </div>
                  <div class="ps-footer__title">{{ $t("OUR_STORE") }}</div>
                  <p>{{ ourStore.first_line }}<br />{{ ourStore.second_line }}</p>
                  <p>
                    <a
                      target="_blank"
                      href="https://www.google.com/maps/place/Arlington,+TX,+USA/@32.701968,-97.2054529,12z/data=!3m1!4b1!4m8!1m2!2m1!1s1487+Rocky+Horse+Carrefour+Arlington,+TX+16819!3m4!1s0x864e62d2e40898d3:0xb5ef6ac1fa05351!8m2!3d32.735687!4d-97.1080656"
                      >{{ $t("SHOW_MAP") }}</a
                    >
                  </p>
                  <ul class="ps-social">
                    <li v-if="ourStore.facebook">
                      <a
                        target="_blank"
                        class="ps-social__link facebook"
                        :href="ourStore.facebook"
                        ><i class="fa fa-facebook"> </i
                        ><span class="ps-tooltip">{{ $t("FACEBOOK") }}</span></a
                      >
                    </li>
                    <li v-if="ourStore.instgram">
                      <a
                        target="_blank"
                        class="ps-social__link instagram"
                        :href="ourStore.instgram"
                        ><i class="fa fa-instagram"></i
                        ><span class="ps-tooltip">{{ $t("INSTGRAM") }}</span></a
                      >
                    </li>
                    <li v-if="ourStore.youtube">
                      <a
                        target="_blank"
                        class="ps-social__link youtube"
                        :href="ourStore.youtube"
                        ><i class="fa fa-youtube-play"></i
                        ><span class="ps-tooltip">{{ $t("YOUTUBE") }}</span></a
                      >
                    </li>
                    <li v-if="ourStore.twitter">
                      <a
                        target="_blank"
                        class="ps-social__link linkedin"
                        :href="ourStore.twitter"
                        ><i class="fa fa-twitter"></i
                        ><span class="ps-tooltip">{{ $t("TWITTER") }}</span></a
                      >
                    </li>
                    <li v-if="ourStore.linked_in">
                      <a
                        target="_blank"
                        class="ps-social__link linkedin"
                        :href="ourStore.linked_in"
                        ><i class="fa fa-linkedin"></i
                        ><span class="ps-tooltip">{{ $t("LINKED_IN") }}</span></a
                      >
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="ps-footer--bottom">
        <div class="row">
          <div class="col-12 col-md-6">
            <p>{{ $t("ALL_RIGHTS_RESERVED") }}</p>
          </div>
          <div class="col-12 col-md-6 text-right">
            <img src="/assets/img/payment.png" alt /><img
              class="payment-light"
              src="/assets/img/payment-light.png"
              alt
            />
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
import { onMounted, reactive, toRefs } from "vue-demi";
import footerClient from "../../shared/http-clients/footer-client";
export default {
  setup() {
    const data = reactive({
      topFooterSections: null,
      needHelp: null,
      ourStore: null,
    });
    onMounted(() => {
      footerClient.getTopFooterSections().then((response) => {
        data.topFooterSections = response.data;
      });
      footerClient.getNeedHelp().then((response) => {
        data.needHelp = response.data;
      });
      footerClient.getOurStore().then((response) => {
        data.ourStore = response.data;
      });
    });
    return { ...toRefs(data) };
  },
};
</script>

<style lang="scss" scoped>
.ps-footer__link:hover,
.ps-footer__email:hover {
  cursor: text;
}
</style>
