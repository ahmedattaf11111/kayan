import { createWebHistory, createRouter } from "vue-router";
import Register from '../components/auth/register';
import Login from '../components/auth/login';
import ForgetPassword from '../components/auth/forget-password';
import ResetPassword from '../components/auth/reset-password';
import EmailVerification from '../components/auth/email-verification';
import Profile from '../components/auth/profile';
import Home from '../components/home/home';
import Cart from '../components/order/cart';
import Order from '../components/order/order';
import ProductDetails from '../components/product-details/product-details';
import ViewAllProduct from '../components/view-all-products/view-all-products';
import AuthenticatedGuard from "../shared/guards/authenticated-guard";
import GuestGuard from "../shared/guards/guest-guard";
import PageNotFound from "../shared/components/page-not-found";
import SuccessCallback from "../components/order/success-callback";
import ErrorCallback from "../components/order/error-callback";
const routes = [
  {
    path: "",
    redirect: "/home"
  },
  { path: "/home", component: Home },
  { path: "/best-client-discount-products", component: ViewAllProduct },
  { path: "/product-details/:id", component: ProductDetails },
  //Guest Routes
  {
    path: "",
    beforeEnter: [GuestGuard],
    children: [
      { path: "register", component: Register },
      { path: "login", component: Login },
      { path: "forget-password", component: ForgetPassword },
      { path: "reset-password/:token", component: ResetPassword },
    ]
  },
  //Authenticated Routes
  {
    path: "",
    beforeEnter: [AuthenticatedGuard],
    children: [
      { path: "verify-email", component: EmailVerification },
      { path: "profile", component: Profile },
      { path: "cart", component: Cart },
      { path: "order", component: Order },
      { path: "/order-success", component: SuccessCallback },
      { path: "/order-error", component: ErrorCallback },
    ]
  },
  {
    path: '/:pathMatch(.*)*',
    component: PageNotFound
  }
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