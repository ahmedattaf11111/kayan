const BASE_URL = `deal`;
export default {
    saveDeal(formValue) {
        return axios.post(`${BASE_URL}`, formValue);
    },
    getDeal(){
        return axios.get(`${BASE_URL}`);
    },
    getSuppliers(){
        return axios.get(`${BASE_URL}/suppliers`);
    },
    getProducts(){
        return axios.get(`${BASE_URL}/products`);
    }
}