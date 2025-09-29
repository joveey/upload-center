import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Baris di bawah ini adalah kunci untuk autentikasi Sanctum SPA.
 * Ini memberitahu Axios untuk secara otomatis mengirim cookie session
 * setiap kali membuat request ke backend Anda.
 */
window.axios.defaults.withCredentials = true;