import { createWebHistory, createRouter } from "vue-router";
import Register from '../components/web/auth/register';
import Login from '../components/web/auth/login';
import ForgetPassword from '../components/web/auth/forget-password';
import ResetPassword from '../components/web/auth/reset-password';
import EmailVerification from '../components/web/auth/email-verification';
import Profile from '../components/web/auth/profile';
import Home from '../components/web/home/home';
import AuthenticatedGuard from "../shared/guards/authenticated-guard";
import GuestGuard from "../shared/guards/guest-guard";
import PageNotFound from "../shared/components/page-not-found";
const routes = [
  {
    path: "",
    redirect: "/home"
  },
  { path: "/home", component: Home },
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
});
export default router;