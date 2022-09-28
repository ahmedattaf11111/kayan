const BASE_URL = `cart`;
export default {
    addToCart(formValue) {
        return axios.post(`${BASE_URL}`, formValue);
    },
    updateCartQuantity(formValue) {
        return axios.put(`${BASE_URL}`, formValue);
    },
}