<template>
  <div class="flex items-center justify-center px-4 sm:px-6 lg:px-8 mt-20">
    <div class="w-1/3 bg-white p-6 rounded-lg shadow-md border border-gray-200">

      <!-- Judul -->
      <h1 class="text-2xl font-bold text-center mb-6 text-gray-900">Login</h1>

      <!-- Form -->
      <form @submit.prevent="login" class="space-y-5">

        <!-- Input Email -->
        <div>
          <label for="username" class="block text-sm font-medium text-gray-700">
            Email
          </label>
          <input
            id="username"
            type="email"
            v-model="username"
            placeholder="Masukkan email"
            required
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          />
        </div>

        <!-- Input Password -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">
            Password
          </label>
          <input
            id="password"
            type="password"
            v-model="password"
            placeholder="Masukkan password"
            required
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          />
        </div>

        <!-- Tombol Login -->
        <button
          type="submit"
          class="w-full bg-blue-600 text-white py-2 px-4 rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
          Login
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useUserStore } from '../stores/counter';

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

const therouter = useRouter();
const auth = ref(null);
const username = ref('');
const password = ref('');
const store = useUserStore();

const customConfig = {
  'Content-Type': 'application/json',
};

const bodyParameters = computed(() => ({
  email: username.value,
  password: password.value,
}));

function login() {
  axios({
    url: 'http://localhost/web_Programming/laravel/perpus/public/api/login',
    method: 'post',
    data: bodyParameters.value,
    headers: customConfig,
  })
    .then((response) => {
      auth.value = response.data;
      if (auth.value.success === true) {
        console.log(response.data)
        store.email = username.value;
        store.token = auth.value.data;
        store.thecounter++;
        therouter.push('/menu');
      }
    })
    .catch((error) => {
      console.log('AJAX' + error);
      alert("email atau password salah")
    });
}
</script>

<style scoped>
/* Mengatur form tetap terlihat proporsional */
.card {
  max-width: 400px;
}
</style>
