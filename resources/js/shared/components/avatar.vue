<template>
  <div class="user">
    <div class="dropdown">
      <button
        class="btn dropdown-toggle"
        type="button"
        id="dropdownMenuButton"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
      >
      Superadmin 
      </button>
      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <div class="text-container">
          <div class="text">
            admin@admin.com
          </div>
        </div>
        <a class="dropdown-item border-top" href="" @click.prevent="logout">
          <i class="fa fa-power-off"></i>
          <span>{{ $t("LOGOUT") }}</span>
        </a>
      </div>
    </div>
  </div>
</template>
<script>
import authClient from "../http-clients/auth-client";
import global from "../../shared/consts/global";
import TokenUtil from "../../shared/utils/token-util";
import { inject, toRefs, ref } from "vue-demi";
import Lang from "./lang.vue";
export default {
  setup() {
    return toRefs(inject("store"));
  },
  data() {
    return {
      showMenu: false,
    };
  },
  components: {
    Lang,
  },
  methods: {
    toggleMenu(event) {
      event.stopPropagation();
      this.showMenu = !this.showMenu;
    },
    logout() {
      authClient
        .logout()
        .then((response) => {
          TokenUtil.remove();
          this.$router.push(global.GUEST_REDIRECT);
        })
        .catch((error) => {});
    },
  },
  mounted() {
    self = this;
    $(window).click(function () {
      self.showMenu = false;
    });
  },
};
</script>
<style lang="scss">
.user {
  .dropdown {
    .dropdown-menu {
      body[dir="ltr"] & {
        left: unset !important;
        right: 0 !important;
      }
      * {
        color: #373757 !important;
      }
    }
    .text-container {
      padding: 8px 15px 12px 15px;
    }
    button {
      &:hover,
      &:focus {
        border-color: white !important;
      }
    }
    .dropdown-item {
      padding-top:14px;
      span {
        margin: 0 8px;
        position: relative;
        bottom: 2px;
      }
    }
  }
}
</style>
