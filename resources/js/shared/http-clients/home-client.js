const BASE_URL = `home`;
export default {
    storeNewsletter($formValue) {
        return axios.post(`${BASE_URL}/newsletters`, $formValue);
    },
    getSimpleAdvertises() {
        return axios.get(`${BASE_URL}/simple-advertises`);
    },
    getSliders() {
        return axios.get(`${BASE_URL}/sliders`);
    },
}