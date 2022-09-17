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
              <router-link v-if="!currentUser" to="/login" class="ps-header__item">
                <i class="icon-user"></i>
              </router-link>
              <a @click="logout" v-if="currentUser" class="ps-header__item" href="#">
                <i class="fa fa-sign-out"></i>
              </a>
              <div class="ps-login--modal">
                <form method="get" action="http://nouthemes.net/html/mymedi/do_action">
                  <div class="form-group">
                    <label>Username or Email Address</label>
                    <input class="form-control" type="text" />
                  </div>
                  <div class="form-group">
                    <label>Password</label>
                    <input class="form-control" type="password" />
                  </div>
                  <div class="form-group form-check">
                    <input class="form-check-input" type="checkbox" />
                    <label>Remember Me</label>
                  </div>
                  <button class="ps-btn ps-btn--warning" type="submit">Log In</button>
                </form>
              </div>
            </li>
            <li>
              <a class="ps-header__item" href="#" id="cart-mini"
                ><i class="icon-cart-empty"></i><span class="badge">2</span></a
              >
              <div class="ps-cart--mini">
                <ul class="ps-cart__items">
                  <li class="ps-cart__item">
                    <div class="ps-product--mini-cart">
                      <a class="ps-product__thumbnail" href="product-default.html"
                        ><img src="img/products/055.jpg" alt="alt"
                      /></a>
                      <div class="ps-product__content">
                        <a class="ps-product__name" href="product-default.html"
                          >Somersung Sonic X2500 Pro White</a
                        >
                        <p class="ps-product__meta">
                          <span class="ps-product__price">$399.99</span>
                        </p>
                      </div>
                      <a class="ps-product__remove" href="javascript: void(0)"
                        ><i class="icon-cross"></i
                      ></a>
                    </div>
                  </li>
                  <li class="ps-cart__item">
                    <div class="ps-product--mini-cart">
                      <a class="ps-product__thumbnail" href="product-default.html"
                        ><img src="img/products/001.jpg" alt="alt"
                      /></a>
                      <div class="ps-product__content">
                        <a class="ps-product__name" href="product-default.html"
                          >Digital Thermometer X30-Pro</a
                        >
                        <p class="ps-product__meta">
                          <span class="ps-product__sale">$77.65</span
                          ><span class="ps-product__is-price">$80.65</span>
                        </p>
                      </div>
                      <a class="ps-product__remove" href="javascript: void(0)"
                        ><i class="icon-cross"></i
                      ></a>
                    </div>
                  </li>
                </ul>
                <div class="ps-cart__total"><span>Subtotal </span><span>$399</span></div>
                <div class="ps-cart__footer">
                  <a class="ps-btn ps-btn--outline" href="shopping-cart.html">View Cart</a
                  ><a class="ps-btn ps-btn--warning" href="checkout.html">Checkout</a>
                </div>
              </div>
            </li>
          </ul>
          <div class="ps-header__search">
            <form action="http://nouthemes.net/html/mymedi/do_action" method="post">
              <div class="ps-search-table">
                <div class="input-group">
                  <input
                    class="form-control ps-input"
                    type="text"
                    placeholder="Search for products"
                  />
                  <div class="input-group-append">
                    <a href="#"><i class="fa fa-search"></i></a>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="ps-header__menu">
            <ul class="menu">
              <li class="has-mega-menu">
                <a href="#">
                  <i class="fa fa-filter" aria-hidden="true"></i>Filter<span
                    class="sub-toggle"
                    ><i class="fa fa-chevron-down"></i></span
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
      <form action="http://nouthemes.net/html/mymedi/do_action" method="post">
        <div class="ps-search-table">
          <div class="input-group">
            <input
              class="form-control ps-input"
              type="text"
              placeholder="Search for products"
            />
            <div class="input-group-append">
              <a href="#"><i class="fa fa-search"></i></a>
            </div>
          </div>
        </div>
      </form>
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
      <a href="shopping-cart.html"
        ><i class="icon-cart-empty"></i><span class="badge">2</span></a
      >
    </div>
  </div>
</template>

<script>
import { inject, onMounted, toRefs } from "vue-demi";
import Filter from "./filter";
import authClient from "../http-clients/auth-client";
import TokenUtil from "../../shared/utils/token-util";
import global from "../../shared/global";
import { useRouter } from "vue-router";
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
    function logout(event) {
      event.preventDefault();
      store.showLoader = true;
      authClient.logout().then(() => {
        store.showLoader = false;
        TokenUtil.remove();
        router.push(global.GUEST_REDIRECT);
        store.currentUser = null;
      });
    }
    //Commons
    function toggleSearchVisibility() {
      $(".open-search").on("click", function (event) {
        event.preventDefault();
        $(".ps-search").addClass("active");
      });
      $("#close-search").on("click", function (event) {
        event.preventDefault();
        $(".ps-search").removeClass("active");
      });
    }
    function closeNoti() {
      $(".ps-noti__close").on("click", function (event) {
        event.preventDefault();
        $(".ps-noti").hide();
      });
    }
    function subMenuToggle() {
      $(".menu--mobile .sub-toggle").on("click", function (e) {
        e.preventDefault();
        var current = $(this).parent("li");
        current.children(".sub-menu").slideToggle(350);
        current.siblings().find(".sub-menu").slideUp(350);
        current.toggleClass("active");
      });

      $(".ps-language-currency .sub-toggle").on("click", function (e) {
        e.preventDefault();
        var current = $(this).parent("li");

        current.children(".sub-menu").slideToggle(350);
        current.toggleClass("active");
      });

      // $('.ps-menu__sticky #menu-slide').on('click', function (e) {
      //     e.preventDefault();
      //     $('.ps-menu--slidebar').addClass('active');
      //     $('#open-menu').parent().addClass('active');
      // });

      $(".menu-slide").on("click", function (e) {
        e.preventDefault();
        $(".ps-menu--slidebar").addClass("active");
        $("#open-menu").parent().addClass("active");
      });

      $("#open-menu-top").on("click", function (e) {
        e.preventDefault();
        $(".ps-menu--slidebar").addClass("active");
        $(this).parent().addClass("active");
        $(".ps-header--mobile").addClass("slidebar-active");
      });

      $("#close-menu-top").on("click", function (e) {
        e.preventDefault();
        $(".ps-menu--slidebar").removeClass("active");
        $(this).parent().removeClass("active");
        $(".ps-header--mobile").removeClass("slidebar-active");
      });
    }

    function toggleMobileMenu() {
      $("#open-menu").on("click", function (e) {
        e.preventDefault();
        $(".ps-menu--slidebar").addClass("active");
        $(this).parent().addClass("active");
      });

      $("#close-menu").on("click", function (e) {
        e.preventDefault();
        $(".ps-menu--slidebar").removeClass("active");
        $(this).parent().removeClass("active");
      });
    }
    function stickyMenu() {
      $(window).scroll(function (event) {
        var scroll = $(window).scrollTop();
        var innerWidth = $(window).innerWidth();
        if (scroll > 100 && innerWidth > 760) {
          $(".ps-header").addClass("ps-header--sticky");
        } else if (scroll > 300 && innerWidth < 760) {
          $(".ps-header").addClass("ps-header--sticky");
        } else {
          $(".ps-header").removeClass("ps-header--sticky");
        }
        if (scroll > 100) {
          $(".scroll-top").show();
        } else {
          $(".scroll-top").hide();
        }
      });
      $(".scroll-top").on("click", function (e) {
        e.preventDefault();
        $("html,body").animate({ scrollTop: 0 }, 500);
      });
    }
    return { logout, ...toRefs(store) };
  },
};
</script>

<style lang="scss">
.ps-header--mobile {
  z-index: 2 !important;
}
.ps-menu--slidebar {
  z-index: 3 !important;
}
.scroll-top {
  z-index: 4 !important;
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
