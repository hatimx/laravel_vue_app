import './bootstrap';

import Swal from 'sweetalert2';
window.Swal = Swal
const toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
})
window.toast = toast

import { createApp } from 'vue';
import app from './components/App.vue';
import router from './router/index.js';

createApp(app).use(router).mount("#app")