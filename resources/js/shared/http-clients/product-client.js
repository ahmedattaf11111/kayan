
const BASE_URL = `products`;
export default {
    getBiggestClientDiscountProducts(
        categoryId,
        categoryLevel,
        name,
        effectiveMaterial,
        pharmacologicalFormId,
        supplierId,
        discount,
        page,
        pageSize
    ) {
        return axios.get(`${BASE_URL}/biggest-client-discount?page=${page}&page_size=${pageSize}
        &category_id=${categoryId ? categoryId : ""}
        &category_level=${categoryLevel ? categoryLevel : ""}
        &name=${name ? name : ""}
        &effective_material=${effectiveMaterial ? effectiveMaterial : ""}
        &pharmacological_form_id=${pharmacologicalFormId ? pharmacologicalFormId : ""}
        &supplier_id=${supplierId ? supplierId : ""}
        &discount=${discount ? discount : ""}`
        );
    },

    getDealProducts() {
        return axios.get(`${BASE_URL}/deal`);
    },

    getMainWithSubCategories() {
        return axios.get(`${BASE_URL}/main-with-sub-categories`);
    },

    getProductDetails(productId){
        return axios.get(`${BASE_URL}/${productId}`);
    }
}