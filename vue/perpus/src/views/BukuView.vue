<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useUserStore } from '../stores/counter';
import Sidebar from '@/components/Sidebar.vue';
import { storeToRefs } from 'pinia';
import alertify from 'alertifyjs';
import 'alertifyjs/build/css/alertify.css';

// Data utama
const books = ref([]);
const store = useUserStore();
const { token } = storeToRefs(store);
const therouter = useRouter();

const customConfig = {
  Authorization: `Bearer ${token.value}`,
};

// Pagination State
const currentPage = ref(1);
const ncount = ref(0); // Total data
const per_page = 10; // Data per halaman
const cari = ref('');

// Total halaman
const totalPages = computed(() => Math.ceil(ncount.value / per_page));

// Nomor urut di tabel
const nomor = computed(() => {
  return currentPage.value === 1 ? 1 : (currentPage.value - 1) * per_page + 1;
});

// Visible pages logic
const visiblePages = computed(() => {
  const total = totalPages.value;
  const current = currentPage.value;

  if (total <= 7) {
    return Array.from({ length: total }, (_, i) => i + 1);
  }

  const pages = [];
  if (current > 3) pages.push(1); // Halaman pertama
  if (current > 5) pages.push('...'); // Ellipsis awal
  const start = Math.max(1, current-2);
  const end = Math.min(total, current + 2);
  for (let i = start; i <= end; i++) {
    pages.push(i);
  }
  if (current < total - 3) pages.push('...'); // Ellipsis akhir
  if (current < total - 2) pages.push(total); // Halaman terakhir

  return pages;
});

// Fetch data
function refreshdata() {
  // Reset halaman ke 1 saat data baru di-load
  currentPage.value = 1;

  const start_data = currentPage.value === 1 ? 0 : (currentPage.value - 1) * per_page;
  const thedata = { start: start_data, limit: per_page, cari: cari.value };

  axios({
    url: 'http://localhost/web_Programming/laravel/perpus/public/api/book/cari',
    method: 'post',
    headers: customConfig,
    data: thedata,
  })
    .then((response) => {
      if (response.data.success === true) {
        books.value = response.data.data.buku;
        ncount.value = response.data.data.count;
      }
    })
    .catch((error) => {
      console.error('AJAX Error: ' + error);
    });
}

// Pindah halaman
function changePage(page) {
  if (page === '...') return; // Jika tombol ellipsis
  currentPage.value = page;

  // Refresh data saat halaman berubah
  const start_data = (currentPage.value - 1) * per_page;
  const thedata = { start: start_data, limit: per_page, cari: cari.value };

  axios({
    url: 'http://localhost/web_Programming/laravel/perpus/public/api/book/cari',
    method: 'post',
    headers: customConfig,
    data: thedata,
  })
    .then((response) => {
      if (response.data.success === true) {
        books.value = response.data.data.buku;
      }
    })
    .catch((error) => {
      console.error('AJAX Error: ' + error);
    });
}

// Fungsi pencarian
function docari() {
  refreshdata();
}

// Delete dialog
function delete_dialog(id) {
  alertify
    .confirm(
      'Confirmation',
      'Are you sure you want to delete this data?',
      () => hapus(id),
      () => console.log('Deletion cancelled')
    )
    .set({
      labels: { ok: 'Yes, Delete', cancel: 'No, Keep' },
      padding: true,
      closableByDimmer: false,
      transition: 'fade',
      theme: 'bootstrap',
    });
}

// Delete request
function hapus(id) {
  axios({
    url: `http://localhost/web_Programming/laravel/perpus/public/api/book/delete/${id}`,
    method: 'get',
    headers: customConfig,
  })
    .then((response) => {
      if (response.data.success === true) {
        alertify.alert('Information', 'Data has been deleted!', () => {
          alertify.success('OK');
          refreshdata();
        });
      } else {
        alertify.alert('Error', 'Failed to delete the book. Please try again.');
      }
    })
    .catch((error) => {
      console.error('AJAX Error: ' + error);
      alertify.alert('Error', 'An error occurred while trying to delete the book.');
    });
}

// Lifecycle hook
onMounted(() => {
  refreshdata();
});
</script>

<template>
  <div class="flex flex-col mb-5">

    <!-- Container -->
    <div class="container mt-5 w-full">
      <!-- Judul -->
      <div class="text-center">
        <h2 class="mb-4 text-3xl">Daftar Buku</h2>
      </div>

      <router-link to="/menu">
        <div class="bg-gray-700 w-20 p-2 text-center mb-4 rounded-xl">
          <h3 class="text-2xl text-white">Back</h3>
        </div>
      </router-link>

      <!-- Search dan Add Book -->
      <div class="d-flex justify-content-between mb-3">
        <div class="input-group w-50">
          <input
            type="text"
            class="form-control"
            placeholder="Search books..."
            v-model="cari"
            @keyup.enter="docari"
          />
          <button @click="docari" class="btn btn-primary">Search</button>
        </div>

        <router-link to="/formbuku">
          <button type="button" class="btn btn-primary">
            Add Book
          </button>
        </router-link>
      </div>

      <!-- Tabel -->
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-light">
            <tr>
              <th class="text-center">No.</th>
              <th>ISBN</th>
              <th>Judul</th>
              <th>Pengarang</th>
              <th class="text-center">Tahun</th>
              <th class="text-center">View</th>
              <th class="text-center">Delete</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in books" :key="item.isbn">
              <td class="text-center">{{ index + nomor }}</td>
              <td>{{ item.isbn }}</td>
              <td>{{ item.judul }}</td>
              <td>{{ item.pengarang }}</td>
              <td class="text-center">{{ item.tahun }}</td>
              <td class="text-center">
                <router-link :to="{ name: 'bukuview', params: { theisbn: item.isbn } }">
                  <button class="btn btn-outline-success btn-sm">View</button>
                </router-link>
              </td>
              <td class="text-center">
                <button
                  @click="delete_dialog(item.isbn)"
                  class="btn btn-outline-danger btn-sm"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="style-pagination text-center mt-4">
        <button
          class="btn btn-secondary"
          :disabled="currentPage === 1"
          @click="changePage(currentPage - 1)"
        >
          &lt; Prev
        </button>
        <button
          v-for="page in visiblePages"
          :key="page"
          class="btn"
          :class="{
            'btn-primary': currentPage === page,
            'btn-outline-secondary': currentPage !== page && page !== '...'
          }"
          @click="changePage(page)"
        >
          {{ page }}
        </button>
        <button
          class="btn btn-secondary"
          :disabled="currentPage === totalPages"
          @click="changePage(currentPage + 1)"
        >
          Next &gt;
        </button>
      </div>
    </div>
  </div>
</template>

<style>

.style-pagination .btn {
  margin: 0 5px;
}

.style-pagination .pagination-container {
  background-color: #f0f0f0;
  border-radius: 5px;
  padding: 10px 0px;
}
.style-pagination .paginate-buttons {
  width: 40px;
  height: 40px;
  margin-inline: 5px;
  cursor: pointer;
  border: none;
  background-color: transparent;
  border-radius: 2px;
}
.style-pagination .back-button {
  width: 70px;
}
.style-pagination .next-button {
  width: 70px;
}
.style-pagination .back-button svg {
  transform: rotate(180deg);
}
.style-pagination .active-page {
  background-color: #2980b9;
  color: #fff;
}

.style-pagination .paginate-buttons:hover {
  background-color: #e5e5e5;
}
.style-pagination .active-page:hover {
  background-color: #3b8cc3;
  color: #fff;
}
.style-pagination .back-button:active,
.style-pagination .next-button:active {
  background-color: #dedede;
}

</style>
























