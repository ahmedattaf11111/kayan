
const BASE_URL = `products`;
export default {

    getBiggestClientDiscountProducts(categroyId, categoryLevel, pageNo, pageSize) {
        return axios.get(`${BASE_URL}/biggest-client-discount/${categroyId}/${categoryLevel}
        ?page=${pageNo}&page_size=${pageSize}`);
    },

    getDealProducts() {
        return axios.get(`${BASE_URL}/deal`);
    },

    getMainWithSubCategories() {
        return axios.get(`${BASE_URL}/main-with-sub-categories`);
    },

}