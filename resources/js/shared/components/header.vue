<template>
  <button class="btn scroll-top"><i class="fa fa-angle-double-up"></i></button>
  <div class="ps-menu--slidebar">
    <div class="ps-menu__content">
      <Filter />
    </div>
    <div class="ps-menu__footer">
      <div class="ps-menu__item">
        <div class="ps-menu__contact">
          Need help? <strong>0020 500 - MYMEDI - 000</strong>
        </div>
      </div>
    </div>
  </div>
  <header class="ps-header ps-header--3 ps-header--4">
    <div class="ps-noti">
      <div class="container">
        <p class="m-0">
          Due to the <strong>COVID 19 </strong>epidemic, orders may be processed with a
          slight delay
        </p>
      </div>
      <a class="ps-noti__close"><i class="icon-cross"></i></a>
    </div>
    <div class="ps-header__middle">
      <div class="container">
        <div class="ps-logo">
          <router-link to="/home">
            <img src="/assets/img/Logo_Web.svg" alt />
            <img class="sticky-logo" src="/assets/img/Logo_Web.svg" alt />
          </router-link>
        </div>
        <a class="ps-menu--sticky" href="#"><i class="fa fa-bars"></i></a>
        <div class="ps-header__right">
          <ul class="ps-header__icons">
            <li>
              <a class="ps-header__item open-search" href="#"
                ><i class="icon-magnifier"></i
              ></a>
            </li>
            <li>
              <router-link
                v-if="currentUser"
                class="ps-header__item"
                to="/profile-basic"
                id="cart-mini"
              >
                <i class="fa fa-user"></i>
              </router-link>
            </li>
            <li>
              <router-link v-if="!currentUser" to="/login" class="ps-header__item">
                <i class="icon-user"></i>
              </router-link>
              <a @click="logout" v-if="currentUser" class="ps-header__item" href="#">
                <i class="fa fa-sign-out"></i>
              </a>
            </li>
            <li>
              <router-link class="ps-header__item" to="/cart" id="cart-mini">
                <i class="icon-cart-empty"></i
                ><span class="badge">{{ cartItemsCount }}</span>
              </router-link>
            </li>
          </ul>
          <div class="ps-header__search">
            <div class="ps-search-table">
              <div class="input-group">
                <input
                  class="form-control ps-input"
                  type="text"
                  v-model="name"
                  :placeholder="$t('SEARCH_FOR_PRODUCTS')"
                />
                <div class="input-group-append">
                  <router-link
                    :to="{ path: '/best-client-discount-products', force: true }"
                    @click="onSearchClicked"
                    href="#"
                    ><i class="fa fa-search"></i
                  ></router-link>
                </div>
              </div>
            </div>
          </div>
          <div class="ps-header__menu">
            <ul class="menu">
              <li class="has-mega-menu">
                <a href="#">
                  <i class="fa fa-filter" aria-hidden="true"></i>{{ $t("FILTER")
                  }}<span class="sub-toggle"><i class="fa fa-chevron-down"></i></span
                ></a>
                <div class="mega-menu">
                  <div class="container">
                    <Filter />
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </header>
  <header class="ps-header ps-header--4 ps-header--mobile">
    <div class="ps-header__middle">
      <div class="container">
        <div class="ps-header__left">
          <ul class="ps-header__icons">
            <li>
              <a class="ps-header__item open-search" href="#"
                ><i class="fa fa-search"></i
              ></a>
            </li>
          </ul>
        </div>
        <div class="ps-logo">
          <router-link to="/home">
            <img src="/assets/img/Logo_Web.svg" alt />
          </router-link>
        </div>
        <div class="ps-header__right">
          <ul class="ps-header__icons">
            <li>
              <a class="ps-header__item" href="#"
                ><i class="icon-cart-empty"></i><span class="badge">2</span></a
              >
            </li>
          </ul>
        </div>
      </div>
    </div>
  </header>
  <div class="ps-search">
    <div class="ps-search__content ps-search--mobile">
      <a class="ps-search__close" href="#" id="close-search"
        ><i class="icon-cross"></i
      ></a>
      <h3>Search</h3>
      <div class="ps-search-table">
        <div class="input-group">
          <input
            class="form-control ps-input"
            type="text"
            placeholder="Search for products"
          />
          <div class="input-group-append">
            <router-link
              :to="{ path: '/best-client-discount-products', force: true }"
              @click="onSearchClicked"
              href="#"
              ><i class="fa fa-search"></i
            ></router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="ps-navigation--footer">
    <div class="ps-nav__item">
      <a href="#" id="open-menu"> <i class="fa fa-filter" aria-hidden="true"></i> </a
      ><a href="#" id="close-menu"><i class="icon-cross"></i></a>
    </div>
    <div class="ps-nav__item">
      <router-link to="/home"><i class="icon-home2"></i></router-link>
    </div>
    <div class="ps-nav__item">
      <router-link v-if="currentUser" class="ps-header__item" to="/profile-basic">
        <i class="fa fa-user"></i>
      </router-link>
    </div>
    <div class="ps-nav__item">
      <router-link
        v-if="!currentUser"
        to="/login"
        class="ps-header__item"
        href="#"
        id="login-modal"
      >
        <i class="icon-user"></i>
      </router-link>
      <a @click="logout" v-if="currentUser" class="ps-header__item" href="#">
        <i class="fa fa-sign-out"></i>
      </a>
    </div>
    <div class="ps-nav__item">
      <router-link to="/cart"
        ><i class="icon-cart-empty"></i><span class="badge">2</span>
      </router-link>
    </div>
  </div>
</template>

<script>
import { inject, onMounted, toRefs } from "vue-demi";
import Filter from "./filter";
import authClient from "../http-clients/auth-client";
import TokenUtil from "../../shared/utils/token-util";
import global from "../../shared/consts/global";
import { useRouter } from "vue-router";
import From from "../../shared/consts/from";
import productStore from "../../components/view-all-products/store";
import {
  closeNoti,
  stickyMenu,
  subMenuToggle,
  toggleMobileMenu,
  toggleSearchVisibility,
} from "../../custom";
export default {
  components: {
    Filter,
  },
  setup() {
    const store = inject("store");
    const router = useRouter();
    onMounted(() => {
      stickyMenu();
      toggleMobileMenu();
      subMenuToggle();
      toggleSearchVisibility();
      closeNoti();
    });
    function onSearchClicked() {
      productStore.from = From.SEARCH;
    }
    function logout(event) {
      event.preventDefault();
      store.showLoader = true;
      authClient.logout().then(() => {
        store.showLoader = false;
        TokenUtil.remove();
        router.push(global.GUEST_REDIRECT);
        store.currentUser = null;
        store.cartItemsCount = 0;
      });
    }
    return { logout, ...toRefs(store), onSearchClicked, ...toRefs(productStore) };
  },
};
</script>

<style lang="scss">
.scroll-top {
  z-index: 9997 !important;
}
.ps-menu--slidebar {
  z-index: 9998 !important;
}
.ps-navigation--footer {
  z-index: 9999 !important;
}
.ps-header__middle {
  img {
    width: 200px;
  }
}
.ps-header--sticky {
  z-index: 1000 !important;
}
@media screen and (min-width: 821px) {
  .mega-menu {
    width: 600px !important;
    right: 665px !important;
  }
}
</style>
