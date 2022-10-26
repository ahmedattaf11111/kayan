const CART_BASE_URL = `cart`;
const PAYMENT_BASE_URL = `payment`;
export default {
    addToCart(formValue) {
        return axios.post(`${CART_BASE_URL}`, formValue);
    },
    updateCartQuantity(formValue) {
        return axios.put(`${CART_BASE_URL}`, formValue);
    },
    removeCartItem(productId, supplierId) {
        return axios.delete(`${CART_BASE_URL}?product_id=${productId}
        &supplier_id=${supplierId ? supplierId : ''}`);
    },
    getUserCart() {
        return axios.get(`${CART_BASE_URL}`);
    },
    getCartItemsCount() {
        return axios.get(`${CART_BASE_URL}/count`);
    },
    cachePayment(formValue) {
        return axios.post(`${PAYMENT_BASE_URL}/cash`, formValue);
    },
    onlinePayment(formValue) {
        return axios.post(`${PAYMENT_BASE_URL}/online`, formValue);
    }
}