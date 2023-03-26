const BASE_URL = `prices`;
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
    getPrices(pageNo,pageSize,text) {
        return axios.get(`${BASE_URL}?page=${pageNo}&page_size=${pageSize}&text=${text}`);
    },
    getAllPrices(){
        return axios.get(`${BASE_URL}`);
    },
    getSuppliers(){
        return axios.get(`${BASE_URL}/suppliers`);
    },
    getProducts(){
        return axios.get(`${BASE_URL}/products`);
    }
}