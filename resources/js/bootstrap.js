import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * PENTING: Konfigurasi ini memastikan Axios mengirim cookies dan CSRF token
 */
window.axios.defaults.withCredentials = true;
window.axios.defaults.withXSRFToken = true;

/**
 * Set base URL
 */
window.axios.defaults.baseURL = window.location.origin;

/**
 * Tambahkan CSRF token dari meta tag ke setiap request
 */
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Add request interceptor untuk debugging
 */
window.axios.interceptors.request.use(
    config => {
        console.log('Making request to:', config.url);
        console.log('Request headers:', config.headers);
        return config;
    },
    error => {
        console.error('Request error:', error);
        return Promise.reject(error);
    }
);

/**
 * Add response interceptor untuk error handling
 */
window.axios.interceptors.response.use(
    response => response,
    error => {
        console.error('Response error:', error.response);
        return Promise.reject(error);
    }
);