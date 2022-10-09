<template>
  <div class="user">
    <a href="" class="menu-toggler border" @click.prevent="toggleMenu" type="button">
      <img v-if="currentUser && currentUser.image" :src="currentUser.image" />
      <img v-else src="../../../../public/assets/images/empty-image.png" />
    </a>
    <div v-if="showMenu" class="menu text-center pt-3 border shadow">
      <img
        v-if="currentUser && currentUser.image"
        class="border"
        :src="currentUser.image"
      />
      <img class="border" v-else src="../../../../public/assets/images/empty-image.png" />
      <div>
        <router-link to="/profile">
          <i class="fa fa-edit text-secondary"></i>
        </router-link>
      </div>
      <div class="header">
        <div class="mt-1">
          {{ currentUser ? currentUser.first_name + " " + currentUser.last_name : "" }}
        </div>
        <div class="email mb-1">
          {{ currentUser ? currentUser.email : "" }}
        </div>
      </div>
      <hr class="hr" />
      <div class="footer">
        <router-link
          class="text-secondary"
          to="/verify-email"
          v-if="currentUser && !currentUser.email_verified_at"
        >
          {{ $t("VERIFY_EMAIL") }}
        </router-link>
        <a class="text-secondary" href="" @click.prevent="logout">{{ $t("LOGOUT") }}</a>
      </div>
    </div>
  </div>
</template>
<script>
import authClient from "../../shared/http-clients/auth-client";
import global from "../../shared/consts/global";
import TokenUtil from "../../shared/utils/token-util";
import { inject, toRefs, ref } from "vue-demi";
export default {
  setup() {
    return toRefs(inject("store"));
  },
  data() {
    return {
      showMenu: false,
    };
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
          this.currentUser = null;
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
  position: relative;
  display: inline-block;
  .menu {
    img {
      display: inline-block;
      width: 80px;
      height: 80px;
      border-radius: 50%;
    }
  }
  .menu-toggler {
    display: inline-block;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    img {
      width: 100%;
      height: 100%;
      overflow: hidden;
      border-radius: 50%;
    }
  }
  [dir="ltr"] & {
    padding-left: 20px;
    .menu {
      right: 30px;
    }
  }
  [dir="rtl"] & {
    padding-right: 20px;
    .menu {
      left: 30px;
    }
  }
  .menu {
    padding-bottom: 20px;
    position: absolute;
    top: 32px;
    width: 300px;
    overflow: visible;
    z-index: 999;
    background-color: white;
    border-radius: 10px;
    .header {
      .email {
        color: #5f6368;
      }
    }
    .footer {
      a {
        display: inline-block;
        text-decoration: none;
        width: 100px;
        background: none;
        padding: 7px;
        border: 1px solid #bebebf;
        border-radius: 5px;
        margin: 5px;
      }
    }
  }
}
</style>
