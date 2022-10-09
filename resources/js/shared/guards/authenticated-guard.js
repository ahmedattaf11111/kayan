import TokenUtil from "../utils/token-util";
import authClient from '../http-clients/auth-client';
import global from "../consts/global";
import store from "../../shared/store";
export default (to, from, next) => {
    if (!TokenUtil.get()) {
        next({ path: global.GUEST_REDIRECT })
    }
    else {
        store.showLoader = true;
        return authClient.verifyToken()
            .then(() => { store.showLoader = false; next(); })
            .catch(() => { store.showLoader = false; next({ path: global.GUEST_REDIRECT }) })
    }
}