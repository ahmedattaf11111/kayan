
const BASE_URL = `products`;
export default {
    getBiggestClientDiscountProducts(
        categoryId,
        name,
        effectiveMaterial,
        pharmacologicalFormId,
        companyId,
        page,
        pageSize
    ) {
        return axios.get(`${BASE_URL}/biggest-client-discount?page=${page}&page_size=${pageSize}
        &name=${name ? name : ""}
        &effective_material=${effectiveMaterial ? effectiveMaterial : ""}`
            , {
                params: {
                    category_ids: categoryId,
                    company_ids: companyId,
                    pharmacological_form_ids:pharmacologicalFormId
                }
            });
    },
    getDeal() {
        return axios.get(`${BASE_URL}/deals`);
    },
    getBestSellers() {
        return axios.get(`${BASE_URL}/best-sellers`);
    },
    getMostPopulars() {
        return axios.get(`${BASE_URL}/most-populars`);
    },
    getCategories() {
        return axios.get(`${BASE_URL}/categories`);
    },
    getProductDetails(productId) {
        return axios.get(`${BASE_URL}/${productId}`);
    },
    getBoughtProducts() {
        return axios.get(`${BASE_URL}/bought`);
    }
}