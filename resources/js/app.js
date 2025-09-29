import './bootstrap';
import 'bootstrap';

import { createApp } from 'vue';
console.log('Nilai axios.defaults.withCredentials adalah:', window.axios.defaults.withCredentials);
console.log(axios.defaults.headers.common);
/**
 * Kita akan membuat komponen pertama kita bernama UploadCenter.vue
 * dan mendaftarkannya di sini.
 */
import UploadCenter from './components/UploadCenter.vue';

const app = createApp({});

app.component('upload-center-component', UploadCenter); // <-- Daftarkan komponen

app.mount('#app'); // <-- Beritahu Vue untuk mengontrol elemen dengan id="app"