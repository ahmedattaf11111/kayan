import TokenUtil from './utils/token-util';
import store from '../shared/store';
export default function () {
    //Before response
    axios.defaults.baseURL = 'http://localhost:8000/api';
    axios.interceptors.request.use(request => {
        store.showLoader = true;
        if (TokenUtil.get()) {
            request.headers.common.Authorization = `Bearer ${TokenUtil.get()}`;
        }
        return request;
    });
    //After response
    axios.interceptors.response.use((response) => {
        store.showLoader = false;
        return response;
    }, (error) => {
        store.showLoader = false;

        return Promise.reject(error);
    })
}