import en from './en.json';
import ar from './ar.json';
import {createI18n} from 'vue-i18n';
const messages = {
    en: en,
    ar: ar
}
// 2. Create i18n instance with options
export default createI18n({
    locale:"ar", // set locale
    messages, // set locale messages,
    legacy: false,
})
