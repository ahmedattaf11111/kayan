const BASE_URL = `sliders`;
export default {
    getSliders() {
        return axios.get(`${BASE_URL}`);
    },
}