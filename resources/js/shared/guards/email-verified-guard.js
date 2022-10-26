import TokenUtil from "../utils/token-util";
import authClient from '../http-clients/auth-client';
export default (to, from, next) => {
    if (!TokenUtil.get()) {
        next()
    }
    else {
        return authClient.userVerified()
            .then(() => { next() })
            .catch(() => { next({ path: "/verify-email" }) })
    }
}
