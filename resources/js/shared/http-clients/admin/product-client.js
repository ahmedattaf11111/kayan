const BASE_URL = `admin-products`;
export default {
    store(formValue) {
        return axios.post(`${BASE_URL}`, formValue);
    },
    update(formValue) {
        return axios.post(`${BASE_URL}/update`, formValue);
    },
    delete(id) {
        return axios.delete(`${BASE_URL}/${id}`);
    },
    getProducts(pageNo,pageSize,text) {
        return axios.get(`${BASE_URL}?page=${pageNo}&page_size=${pageSize}&text=${text}`);
    },
    getAllProducts(){
        return axios.get(`${BASE_URL}`);
    },
    getCategories(){
        return axios.get(`${BASE_URL}/categories`);
    },
    getCompanies(){
        return axios.get(`${BASE_URL}/companies`);
    },
    getPharmacistForms(){
        return axios.get(`${BASE_URL}/pharmacist-forms`);
    }
}