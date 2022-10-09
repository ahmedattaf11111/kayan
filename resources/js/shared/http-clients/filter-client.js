const BASE_URL = `filter`;
export default {
    getSuppliers() {
        return axios.get(`${BASE_URL}/suppliers`);
    },
    getPharmacologicalForms() {
        return axios.get(`${BASE_URL}/pharmacological-forms`);
    },
}