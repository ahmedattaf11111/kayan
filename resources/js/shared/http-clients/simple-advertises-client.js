const BASE_URL = `simple-advertises`;
export default {
    getSimpleAdvertises() {
        return axios.get(`${BASE_URL}`);
    },
}