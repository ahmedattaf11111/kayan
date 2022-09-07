const BASE_URL = `categories`;
export default {
    getCategories() {
        return axios.get(`${BASE_URL}`);
    },
}