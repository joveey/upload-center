import './bootstrap';
import 'bootstrap';

import { createApp } from 'vue';

/**
 * Kita akan membuat komponen pertama kita bernama UploadCenter.vue
 * dan mendaftarkannya di sini.
 */
import UploadCenter from './components/UploadCenter.vue';

const app = createApp({});

app.component('upload-center-component', UploadCenter); // <-- Daftarkan komponen

app.mount('#app'); // <-- Beritahu Vue untuk mengontrol elemen dengan id="app"