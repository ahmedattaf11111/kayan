const BASE_URL = `filter`;
export default {
    getCompanies() {
        return axios.get(`${BASE_URL}/companies`);
    },
    getCategories() {
        return axios.get(`${BASE_URL}/categories`);
    },
    getPharmacologicalForms() {
        return axios.get(`${BASE_URL}/pharmacological-forms`);
    },
}