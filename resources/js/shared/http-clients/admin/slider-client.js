const BASE_URL = `sliders`;
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
    getSliders(pageNo,pageSize,text) {
        return axios.get(`${BASE_URL}?page=${pageNo}&page_size=${pageSize}&text=${text}`);
    },
    getAllSliders(){
        return axios.get(`${BASE_URL}`);
    },
    getProducts(){
        return axios.get(`${BASE_URL}/products`);
    },
}