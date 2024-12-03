// Import file CSS utama
import './assets/main.css'

// Import Vue dan pustaka lainnya
import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

// Import Bootstrap
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

// Import Tailwind
import './assets/tailwind.css';

// import library for pagination
import VueAwesomePaginate from "vue-awesome-paginate";
import "../node_modules/vue-awesome-paginate/dist/style.css";

// Import Font Awesome
import { library } from '@fortawesome/fontawesome-svg-core'; // Library untuk menyimpan ikon
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'; // Komponen Vue untuk ikon
import { faUserSecret, faFolderPlus, faEye, faTrash, faSave, faSignOutAlt, faUser, faBook, faCartPlus, faListAlt, faCaretDown, faBars, faTimes } from '@fortawesome/free-solid-svg-icons'; // Ikon yang digunakan

// Tambahkan ikon ke library Font Awesome
library.add(faUserSecret, faFolderPlus, faEye, faTrash, faSave, faSignOutAlt, faUser, faBook, faCartPlus, faListAlt, faCaretDown, faBars, faTimes );

// Buat aplikasi Vue
const app = createApp(App);

// Gunakan plugin dan komponen global
app.use(createPinia());
app.use(router);
app.use(VueAwesomePaginate)
app.component('font-awesome-icon', FontAwesomeIcon); // Daftarkan komponen Font Awesome

// Mount aplikasi ke elemen HTML
app.mount('#app');
