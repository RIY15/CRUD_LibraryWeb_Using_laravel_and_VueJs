<script setup>
import Sidebar from '@/components/Sidebar.vue';
</script>

<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Main Content -->
    <div class="flex-1 p-6 space-y-6">

      <div class="bg-white rounded-lg shadow-md border border-gray-300 p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Buku</h1>
        <p class="text-gray-600 mb-4">Untuk Create, Update, Delete Buku pada Database</p>
        <router-link
          to="/buku"
          class="inline-block px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300"
        >
          Kunjungi
        </router-link>
      </div>
      
    </div>
  </div>
</template>

