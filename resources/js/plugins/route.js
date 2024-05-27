import { createWebHistory, createRouter } from "vue-router";
import Register from '../components/auth/register';
import Login from '../components/auth/login';
import ForgetPassword from '../components/auth/forget-password';
import ResetPassword from '../components/auth/reset-password';
import EmailVerification from '../components/auth/email-verification';
import ProfileBasic from '../components/profile/basic';
import ProfileOrders from '../components/profile/orders';
import Home from '../components/home/home';
import Cart from '../components/order/cart';
import Order from '../components/order/order';
import ProductDetails from '../components/product-details/product-details';
import ViewAllProduct from '../components/view-all-products/view-all-products';
import AuthenticatedGuard from "../shared/guards/authenticated-guard";
import GuestGuard from "../shared/guards/guest-guard";
import EmailVerifiedGuard from "../shared/guards/email-verified-guard";
import PageNotFound from "../shared/components/page-not-found";
import SuccessCallback from "../components/order/success-callback";
import ErrorCallback from "../components/order/error-callback";
import WebLayout from "../layouts/web-layout.vue";
import DashboardLayout from "../layouts/dashboard-layout.vue";
import CategoryTable from "../components/dashboard/category/category-table.vue";
import CompanyTable from "../components/dashboard/company/company-table.vue";
import PharmacistFormTable from "../components/dashboard/pharmacist-form/pharmacist-form-table.vue";
import ProductTable from "../components/dashboard/product/product-table.vue";
import SupplierTable from "../components/dashboard/supplier/supplier-table.vue";
import PriceTable from "../components/dashboard/price/price-table.vue";
import DealForm from "../components/dashboard/deal/deal-form.vue";
import SliderTable from "../components/dashboard/slider/slider-table.vue";
import OrderTable from "../components/dashboard/order/order-table.vue";
import AdminLogin from "../components/dashboard/login.vue";
import AdminGuestGuard from "../shared/guards/guest-admin-guard";
import AuthenticatedAdminGuard from "../shared/guards/authenticated-admin-guard";
const routes = [
  {
    path: "",
    redirect: "/home"
  },
  //Guest Routes
  {
    path: "",
    beforeEnter: [GuestGuard],
    component: WebLayout,
    children: [
      { path: "register", component: Register },
      { path: "login", component: Login },
      { path: "forget-password", component: ForgetPassword },
      { path: "reset-password/:token", component: ResetPassword },
    ]
  },
  {
    path: "",
    beforeEnter: [EmailVerifiedGuard],
    component: WebLayout,
    children: [
      { path: "/home", component: Home },
      { path: "/best-client-discount-products", component: ViewAllProduct },
      { path: "/product-details/:id", component: ProductDetails },
      //Authenticated Routes
      {
        path: "",
        beforeEnter: [AuthenticatedGuard],
        children: [
          { path: "profile-basic", component: ProfileBasic },
          { path: "profile-orders", component: ProfileOrders },
          { path: "cart", component: Cart },
          { path: "order", component: Order },
          { path: "/order-success", component: SuccessCallback },
          { path: "/order-error", component: ErrorCallback },
        ]
      },
    ]
  },
  {
    path: "/verify-email", beforeEnter: [AuthenticatedGuard]
    , component: WebLayout, children: [{ path: "", component: EmailVerification }]
  },
  //Admin
  {
    path: "/admin",
    beforeEnter:[AuthenticatedAdminGuard],
    component: DashboardLayout,
    children: [
      { path: "categories", component: CategoryTable },
      { path: "companies", component: CompanyTable },
      { path: "pharmacist-from", component: PharmacistFormTable },
      { path: "products", component: ProductTable },
      { path: "suppliers", component: SupplierTable },
      { path: "prices", component: PriceTable },
      { path: "deal", component: DealForm },
      { path: "sliders", component: SliderTable },
      { path: "orders", component: OrderTable },
    ]
  },
  {
    path: "/admin",
    beforeEnter:[AdminGuestGuard],
    children: [
      { path: "login", component: AdminLogin },
    ]
  },
  {
    path: '/:pathMatch(.*)*',
    component: PageNotFound
  },
];
const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    } else {
      return { top: 0, behavior: 'smooth' }
    }
  },
});

export default router;