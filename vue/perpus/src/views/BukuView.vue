<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useUserStore } from '../stores/counter';
import Sidebar from '@/components/Sidebar.vue';
import { storeToRefs } from 'pinia';
import alertify from 'alertifyjs';
import 'alertifyjs/build/css/alertify.css';

const books = ref([]);
const store = useUserStore();
const { token } = storeToRefs(store);
const therouter = useRouter();

const customConfig = {
  Authorization: `Bearer ${token.value}`,
};

const currentPage = ref(1);
const ncount = ref(0);
const per_page = 8;
const cari = ref('');

const totalPages = computed(() => Math.ceil(ncount.value / per_page));

// const visiblePages = computed(() => {
//   const start = Math.max(1, currentPage.value - 2);
//   const end = Math.min(totalPages.value, currentPage.value + 2);
//   const pages = [];
//   for (let i = start; i <= end; i++) {
//     pages.push(i);
//   }
//   return pages;
// });

const nomor = computed(() => {
  return currentPage.value === 1 ? 1 : (currentPage.value - 1) * per_page + 1;
});

function docari() {
  currentPage.value = 1;
  refreshdata();
}

function changePage(page) {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
    refreshdata();
  }
}

function refreshdata() {
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
      console.log('AJAX' + error);
    });
}

onMounted(() => {
  refreshdata();
});

function delete_dialog(id) {
  alertify
    .confirm(
      'Confirmation',
      'Are you sure you want to delete this data?',
      () => {
        hapus(id);
      },
      () => {
        console.log('Deletion cancelled');
      }
    )
    .set({
      labels: { ok: 'Yes, Delete', cancel: 'No, Keep' },
      padding: true,
      closableByDimmer: false,	
      transition: 'fade',
      theme: 'bootstrap',
    });
}

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
      console.log('AJAX Error: ' + error);
      alertify.alert('Error', 'An error occurred while trying to delete the book.');
    });
}
</script>

<template>
    <div class="flex flex-col">
      <!-- Sidebar -->
      <Sidebar />
  
      <!-- Container untuk Daftar Buku -->
      <div class="container mt-5 w-full">
        <!-- Judul -->
        <div class="text-center">
          <h2 class="mb-4 text-3xl">Daftar Buku</h2>
        </div>
  
        <!-- Tombol Add Book dan Search -->
        <div class="d-flex justify-content-between mb-3">
          <!-- Search Input -->
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
  
          <!-- Add Book Button -->
          <router-link to="/formbuku">
            <button type="button" class="btn btn-primary">
              <font-awesome-icon :icon="['fas', 'folder-plus']" /> Add Book
            </button>
          </router-link>
        </div>
  
        <!-- Tabel Daftar Buku -->
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <!-- Header Tabel -->
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
  
            <!-- Isi Tabel -->
            <tbody>
              <tr v-for="(item, index) in books" :key="item.isbn">
                <td class="text-center">{{ index + nomor }}</td>
                <td>{{ item.isbn }}</td>
                <td>{{ item.judul }}</td>
                <td>{{ item.pengarang }}</td>
                <td class="text-center">{{ item.tahun }}</td>
                <td class="text-center">
                  <router-link :to="{ name: 'bukuview', params: { theisbn: item.isbn } }">
                    <button type="button" class="btn btn-outline-success btn-sm">
                      <font-awesome-icon :icon="['fas', 'eye']" /> View
                    </button>
                  </router-link>
                </td>
                <td class="text-center">
                  <button
                    type="button"
                    @click="delete_dialog(item.isbn)"
                    class="btn btn-outline-danger btn-sm"
                  >
                    <font-awesome-icon :icon="['fas', 'trash']" /> Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
  
        <!-- Pagination -->
        <div class="style-pagination">
            <vue-awesome-paginate 
                :total-items="ncount" 
                v-model="currentPage" 
                @page-change="changePage"
                :items-per-page="per_page"
                :max-pages-shown="5">
                
                <!-- Tombol Prev -->
                <template #prev-button>
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="black" width="15" height="15" viewBox="0 0 24 24" >
                    <path d="M8.122 24l-4.122-4 8-8-8-8 4.122-4 11.878 12z" />
                    </svg>
                    Prev
                </span>
                </template>

                <!-- Tombol Next -->
                <template #next-button>
                <span class="flex items-center"> 
                    Next
                    <svg xmlns="http://www.w3.org/2000/svg" fill="black" width="15" height="15" viewBox="0 0 24 24">
                    <path d="M8.122 24l-4.122-4 8-8-8-8 4.122-4 11.878 12z" />
                    </svg>
                </span>
                </template>
            </vue-awesome-paginate>
            </div>
      </div>
    </div>
</template>

<style>
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























