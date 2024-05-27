<template>
  <Loader />
  <div class="dashboard-layout">
    <sidebar-menu
      theme="white-theme"
      :collapsed="collapsed"
      widthCollapsed="65px"
      width="250px"
      :rtl="true"
      :menu="menu"
      :hideToggle="hideToggle"
    >
      <template v-slot:header>
        <div class="image">
          <img src="/images/logo.png" />
          <span class="name">{{ $t("LOGO_NAME") }}</span>
        </div>
      </template>
    </sidebar-menu>
    <div
      :class="{ expandedMargin: !collapsed, collapsedMargin: collapsed }"
      class="content"
    >
      <div class="simple-nav">
        <ul>
          <li>
            <a class="visit-store" href="/home">
              {{ $t("VISIT_STORE") }}
              <i class="fa fa-globe"></i>
            </a>
          </li>
          <li><avatar class="avatar" /></li>
        </ul>
      </div>
      <router-view />
    </div>
  </div>
</template>
<script>
import Avatar from "../shared/components/avatar.vue";
import Lang from "../shared/components/lang.vue";
import { SidebarMenu } from "vue-sidebar-menu";
import "vue-sidebar-menu/dist/vue-sidebar-menu.css";
import Loader from "../shared/components/loader.vue";

export default {
  components: {
    Lang,
    Avatar,
    SidebarMenu,
    Loader,
  },
  data() {
    return {
      collapsed: false,
      hideToggle: false,
      menu: [],
    };
  },
  computed: {
    dir() {
      return this.store.dir;
    },
  },
  watch: {
    dir: {
      handler(value) {
        this.menu = [
          {
            href: "/admin/categories",
            title: this.$t("CATEGORIES"),
            icon: "fa fa-link",
          },
          {
            href: "/admin/companies",
            title: this.$t("COMPANIES"),
            icon: "fa fa-link",
          },
          {
            href: "/admin/pharmacist-from",
            title: this.$t("PHARMACIST_FORMS"),
            icon: "fa fa-link",
          },
          {
            href: "/admin/products",
            title: this.$t("PRODUCTS"),
            icon: "fa fa-link",
          },
          {
            href: "/admin/suppliers",
            title: this.$t("SUPPLIERS"),
            icon: "fa fa-link",
          },
          {
            href: "/admin/prices",
            title: this.$t("PRICE"),
            icon: "fa fa-link",
          },
          {
            href: "/admin/deal",
            title: this.$t("ALL_DEALS"),
            icon: "fa fa-link",
          },
          {
            href: "/admin/sliders",
            title: this.$t("SLIDER"),
            icon: "fa fa-link",
          },
          {
            href: "/admin/orders",
            title: this.$t("ORDERS"),
            icon: "fa fa-link",
          },
        ];
      },
      immediate: true,
    },
  },
  created() {
    this.myEventHandler("init");
    window.addEventListener("resize", this.myEventHandler);
  },
  methods: {
    myEventHandler(e) {
      let element = e == "init" ? window : e.currentTarget;
      if (element.innerWidth <= 820) {
        this.collapsed = true;
        this.hideToggle = true;
      } else {
        this.collapsed = false;
        this.hideToggle = false;
      }
    },
  },
  inject: ["store"],
};
</script>
<style lang="scss">
.dashboard-layout {
  .visit-store {
    gap: 11px;
    display: flex;
    text-decoration: none;
    color: black;
    align-items: center;
    i {
      position: relative;
      top: 1px;
    }
  }
  .v-sidebar-menu {
    i.vsm--icon {
      background: none !important;
      width: 20px !important;
      color: #fff !important;
    }
    .vsm--item {
      * {
        font-size: 16px !important;
      }
    }
    .image {
      background: #182444 !important;
      padding: 16px 20px;
      span.name {
        margin: 0 18px;
        position: relative;
        top: 3px;
        font-size: 22px !important;
      }
      img {
        width: 40px;
        height: 40px;
        position: relative;
        bottom: 5px;
      }
    }
    @media (max-width: "820px") {
      .image {
        display: none;
      }
    }
    *,
    .vsm--toggle-btn {
      color: #fff !important;
    }

    &,
    .vsm--toggle-btn {
      background: #0f1a34;
    }
    .vsm--dropdown {
      background: #091023 !important;
    }
    .vsm--link_open,
    .vsm--link:hover {
      background: #091023 !important;
      * {
        font-size: 16px;
        color: #fff !important;
      }
    }
    .vsm--link {
      padding: 10px 15px;
    }
    .vsm--link_active {
      .vsm--title span,
      i.vsm--icon {
        color: #acb6c4 !important;
      }
    }
  }
  .avatar {
    margin-bottom: 5px;
  }

  // body[dir="ltr"] & {
  //   .v-sidebar-menu {
  //     border-right: 1px solid #dee2e6;
  //   }

  //   .expandedMargin {
  //     margin-left: 250px;
  //   }

  //   .collapsedMargin {
  //     margin-left: 65px;
  //   }
  // }

  body[dir="rtl"] & {
    .expandedMargin {
      margin-right: 250px;
    }

    .collapsedMargin {
      margin-right: 65px;
    }

    .v-sidebar-menu {
      border-left: 1px solid #dee2e6;
    }
  }

  .content {
    transition: 0.3s ease;

    .simple-nav {
      display: flex;
      justify-content: flex-end;
      background: #fff;
      z-index: 990;
      ul {
        list-style: none;
        margin: 0;
        padding: 15px;
        list-style: none;
        margin: 0;
        padding: 19px 15px 10px 15px;
        li {
          padding: 0 10px;
          display: inline-block;
        }
      }
    }
  }
}
</style>
