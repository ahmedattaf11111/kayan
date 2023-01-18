const BASE_URL = `footer`;
export default {
    getTopFooterSections() {
        return axios.get(`${BASE_URL}/top-sections`);
    },
    getNeedHelp() {
        return axios.get(`${BASE_URL}/need-help`);
    },
    getOurStore() {
        return axios.get(`${BASE_URL}/our-store`);
    },
}