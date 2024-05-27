const BASE_URL = `admin-orders`;
export default {
    getOrders(pageNo, pageSize, order_status, payemnt_status, payment_method, text) {
        return axios.get(`${BASE_URL}?page=${pageNo}&page_size=${pageSize}
        &order_status=${order_status}
        &payment_status=${payemnt_status}
        &payment_method=${payment_method}
        &text=${text}`);
    },
    getAllOrders() {
        return axios.get(`${BASE_URL}`);
    },
    markStatusAsCompleted(id) {
        return axios.get(`${BASE_URL}/mark-status-as-completed/${id}`);
    }
}