<template>
  <div class="dashboard-layout">
    <Loader />
    <sidebar-menu
      theme="white-theme"
      :collapsed="collapsed"
      widthCollapsed="65px"
      width="250px"
      :rtl="true"
      :menu="menu"
      :hideToggle="hideToggle"
      @update:collapsed="collapsed = !collapsed"
    />
    <div
      :class="{ expandedMargin: !collapsed, collapsedMargin: collapsed }"
      class="content"
    >
      <div class="simple-nav">
        <ul>
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
    Loader
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
            header: this.$t("Main Navigation"),
            hiddenOnCollapse: true,
          },
          {
            href: "/admin/categories",
            title: this.$t("CATEGORIES"),
            icon: "fa fa-chart-area",
          },
          {
            href: "/admin/companies",
            title: this.$t("COMPANIES"),
            icon: "fa fa-chart-area",
          },
          {
            href: "/admin/pharmacist-from",
            title: this.$t("PHARMACIST_FORMS"),
            icon: "fa fa-chart-area",
          },
          {
            href: "/admin/products",
            title: this.$t("PRODUCTS"),
            icon: "fa fa-chart-area",
          },
          {
            href: "/admin/suppliers",
            title: this.$t("SUPPLIERS"),
            icon: "fa fa-chart-area",
          },
          {
            href: "/admin/prices",
            title: this.$t("PRICE"),
            icon: "fa fa-chart-area",
          },
          {
            href: "/admin/deal",
            title: this.$t("ALL_DEALS"),
            icon: "fa fa-chart-area",
          },
          {
            href: "/admin/sliders",
            title: this.$t("SLIDER"),
            icon: "fa fa-chart-area",
          }
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
  .v-sidebar-menu {
    i.vsm--icon {
      background: none !important;
    }
    .vsm--header {
      text-align: center;
    }
    *,
    .vsm--toggle-btn {
      color: #bdbdc7 !important;
    }
    &,
    .vsm--dropdown,
    .vsm--toggle-btn {
      background: #363a57 !important;
    }
    .vsm--link_open,
    .vsm--link:hover {
      background: #6d85fb !important;
      * {
        color: #fff !important;
      }
    }
    .vsm--link_active {
      * {
        color: #fff !important;
      }
    }
  }
  .avatar {
    margin-bottom: 5px;
  }
  body[dir="ltr"] & {
    .v-sidebar-menu {
      border-right: 1px solid #dee2e6;
    }
    .expandedMargin {
      margin-left: 250px;
    }
    .collapsedMargin {
      margin-left: 65px;
    }
  }
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
      background: #ffffff;
      z-index: 990;
      box-shadow: 0 5px 20px rgb(0 0 0 / 10%);
      ul {
        list-style: none;
        margin: 0;
        padding: 15px;
        li {
          padding: 0 10px;
          display: inline-block;
        }
      }
    }
  }
}
</style>
