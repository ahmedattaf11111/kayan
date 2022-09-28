const BASE_URL = `filter`;
export default {
    getSuppliers() {
        return axios.get(`${BASE_URL}/suppliers`);
    },
    getCompanies() {
        return axios.get(`${BASE_URL}/companies`);
    },
    getPharmacologicalForms() {
        return axios.get(`${BASE_URL}/pharmacological-forms`);
    },
}