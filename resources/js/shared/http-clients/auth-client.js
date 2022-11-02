const BASE_URL = `auth`;
export default {
    register(formValue) {
        return axios.post(`${BASE_URL}/register`, formValue);
    },
    login(formValue) {
        return axios.post(`${BASE_URL}/login`, formValue);
    },
    verifyToken() {
        return axios.get(`${BASE_URL}/verify-token`);
    },
    forgetPassword(email) {
        return axios.get(`${BASE_URL}/forget-password/${email}`);
    },
    resetPassword(formValue) {
        return axios.post(`${BASE_URL}/reset-password`, formValue);
    },
    verifyEmail(formValue) {
        return axios.post(`${BASE_URL}/verify-email`, formValue);
    },
    resendToken() {
        return axios.get(`${BASE_URL}/resend-verification-code`);
    },
    logout() {
        return axios.get(`${BASE_URL}/logout`);
    },
    updateProfile(formValue) {
        return axios.put(`${BASE_URL}/update-profile`, formValue);
    },
    updateProfileImage(formValue) {
        return axios.post(`${BASE_URL}/update-profile-image`, formValue);
    },
    deleteProfileImage() {
        return axios.delete(`${BASE_URL}/delete-profile-image`);
    },
    getProfileStatistics() {
        return axios.get(`${BASE_URL}/profile-statistics`);
    },
    getProfileOrders(page, pageSize, from, to) {
        return axios.get(`${BASE_URL}/profile-orders?
        page=${page}
        &page_size=${pageSize}
        &from=${from ? from : ""}
        &to=${to ? to : ""}`);
    },
    getCurrentUser() {
        return axios.get(`${BASE_URL}/current-user`);
    },
    getCitiesWithAreas() {
        return axios.get(`${BASE_URL}/cities-with-areas`);
    },
    userVerified() {
        return axios.get(`${BASE_URL}/user-verified`);
    }
}