const BASE_URL = `newsletters`;
export default {
    store($formValue) {
        return axios.post(`${BASE_URL}`, $formValue);
    },
}