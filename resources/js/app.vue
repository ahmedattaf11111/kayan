<template>
  <Loader />
  <div class="ps-page">
    <Header />
    <router-view />
    <Footer />
  </div>
</template>
<script>
import authClient from "./shared/http-clients/auth-client";
import TokenUtil from "./shared/utils/token-util";
import Loader from "./shared/components/loader.vue";
import Header from "./shared/components/header.vue";
import Footer from "./shared/components/footer.vue";
import { inject, onMounted } from "vue-demi";
export default {
  components: {
    Loader,
    Header,
    Footer,
  },
  setup() {
    const store = inject("store");
    onMounted(() => {
      if (TokenUtil.get()) {
        authClient
          .getCurrentUser()
          .then((response) => {
            store.currentUser = response.data;
          })
          .catch((error) => {
            console.log("err", error.response);
          });
      }
    });
  },
};
</script>
<style lang="scss"></style>
