<template>
  <Loader />
  <div class="ps-page">
    <Header />
    <router-view />
  </div>
</template>
<script>
import authClient from "../shared/http-clients/auth-client";
import cartClient from "../shared/http-clients/cart-client";
import TokenUtil from "../shared/utils/token-util";
import Loader from "../shared/components/loader.vue";
import Header from "../shared/components/header.vue";
import Footer from "../shared/components/footer.vue";
import { inject, onMounted,toRefs } from "vue-demi";
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
        store.showLoader = true;
        getCurrentUser();
        getCartItemsCount();
      }
    });
    //Commons
    function getCurrentUser() {
      authClient
        .getCurrentUser()
        .then((response) => {
          console.log(response.data)
          store.currentUser = response.data;
          store.showLoader = false;
        })
        .catch((error) => {
          console.log("err", error.response);
        });
    }
    function getCartItemsCount() {
      cartClient
        .getCartItemsCount()
        .then((response) => {
          store.cartItemsCount = response.data;
          store.showLoader = false;
        })
        .catch((error) => {
          console.log("err", error.response);
        });
    }
    return {...toRefs(store)};
  },
};
</script>

<style scoped></style>
