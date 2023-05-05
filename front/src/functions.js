import axios from 'axios'

axios.defaults.withCredentials = true;
axios.defaults.headers.post['Content-Type'] = 'application/json';
axios.defaults.baseURL = import.meta.env.VITE_API_URL;


const request = function(url, method = 'get', data = {}) {
    axios[method](url)
        .then(function (response) {
            console.log(response);
            return new Promise((resolve) => {
                resolve({
                    statusCode: response.status,
                    data: response.data
                })
            });
        })
        .catch(function (error) {
            console.log(error);
        })
        .finally(function () {
            
        });
}


const isAuth = async function() {
    request('auth/is-auth').then((resp) => {
        console.log(resp)
    })
}


export {
    request,
    isAuth,
}