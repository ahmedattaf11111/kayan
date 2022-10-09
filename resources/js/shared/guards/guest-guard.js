import TokenUtil from "../utils/token-util";
import authClient from '../http-clients/auth-client';
import global from "../consts/global";
export default (to, from, next) => {
    if (!TokenUtil.get()) {
        next()
    }
    else {
        return authClient.verifyToken()
            .then(() => { next({ path: global.AUTH_REDIRECT }) })
            .catch(() => { next() })
    }
}
