import { reactive } from "vue";
import LangUtil from "./utils/lang-util";
export default reactive({
    showLoader: false,
    currentUser: null,
    cartItemsCount: 0,
    dir: LangUtil.isArabic() ? 'rtl' : 'ltr'
})
