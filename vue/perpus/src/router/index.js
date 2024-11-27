import { createRouter, createWebHistory } from 'vue-router';
import { useUserStore } from '../stores/counter'; // Import Pinia store
import MenuView from '../views/MenuView.vue';
import Login from '../views/Login.vue';
import PegawaiView from '../views/PegawaiView.vue';
import BarangView from '../views/BarangView.vue';
import BukuView from '@/views/BukuView.vue';
import FormBukuView from '@/views/FormBukuView.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: Login,
    },
    {
      path: '/menu',
      name: 'menu',
      component: MenuView,
      meta: { requiresAuth: true }, // Hanya bisa diakses setelah login
    },
    {
      path: '/barang',
      name: 'barang',
      component: BarangView,
      meta: { requiresAuth: true }, 
    },
    {
      path: '/pegawai',
      name: 'pegawai',
      component: PegawaiView,
      meta: { requiresAuth: true }, 
    },
    {
      path: '/buku',
      name: 'buku',
      component: BukuView,
      meta: { requiresAuth: true }, 
    },
    {
      path: '/formbuku',
      name: 'formbuku',
      component: FormBukuView,
      meta: { requiresAuth: true }, 
    },
    {
      path: '/bukuview/:theisbn',
      name: 'bukuview',
      component: FormBukuView,
      meta: { requiresAuth: true }, 
    },
  ],
});

// Navigation Guard untuk memeriksa autentikasi
router.beforeEach((to, from, next) => {
  const store = useUserStore(); // Ambil store Pinia untuk memeriksa status login

  // Jika rute membutuhkan autentikasi dan user belum login
  if (to.meta.requiresAuth && !store.token) {
    return next({ name: 'login' }); // Redirect ke halaman login
  }

  // Jika sudah login atau rute tidak butuh autentikasi, lanjutkan navigasi
  next();
});

export default router;
