import TokenUtil from './utils/token-util';
export default function () {
    //Before response
    axios.defaults.baseURL = 'http://localhost:8000/api';
    axios.interceptors.request.use(request => {
        if (TokenUtil.get()) {
            request.headers.common.Authorization = `Bearer ${TokenUtil.get()}`;
        }
        return request;
    });
    //After response
    axios.interceptors.response.use((response) => {
        return response;
    }, (error) => {
        return Promise.reject(error);
    })
}