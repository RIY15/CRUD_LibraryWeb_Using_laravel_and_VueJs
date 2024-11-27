<script setup>
    import { ref, onMounted } from 'vue'
    import axios from 'axios'
    import { useRouter } from 'vue-router'
    import { useUserStore } from '../stores/counter'
    import MenuView from './MenuView.vue';
    import { storeToRefs } from 'pinia'
    import alertify from 'alertifyjs';
    import 'alertifyjs/build/css/alertify.css'; // Ensure styles are loaded


    const books = ref([])  // Holds the list of books
    const store = useUserStore()
    const { token } = storeToRefs(store);
    const therouter = useRouter()

    // Setup Authorization Header with token
    const customConfig = {
        Authorization: `Bearer ${token.value}`
    }

    // Function to refresh the book list
    function refreshdata() {
    axios({
        url: 'http://localhost/web_Programming/laravel/perpus/public/api/book/buku',
        method: 'get',
        headers: customConfig
    }).then(response => {
        console.log(response.data) // Development log
        if(response.data.success === true) {
        books.value = response.data.data
        }
    })
    .catch(error => {
        console.log('AJAX Error: ' + error)
    })
    .finally(() => {
        // Optional: Add cleanup code if necessary
    })
    }

    // Fetch data on component mount
    onMounted(() => {
    refreshdata()  // Correctly call refreshdata
    })

    // Function to handle deletion confirmation
    function delete_dialog(id) {
        alertify.confirm(
            'Confirmation',
            'Are you sure you want to delete this data?', 
            () => { 
                hapus(id)  // If confirmed, call hapus function
            },
            () => {
                console.log('Deletion cancelled') // Optionally handle the cancellation action
            }
        ).set({
            labels: { ok: 'Yes, Delete', cancel: 'No, Keep' },  // Customize button labels
            padding: true,  // Enable padding for better appearance
            closableByDimmer: false,  // Prevent closing by clicking outside the dialog
            transition: 'fade',  // Set transition effect (fade or slide)
            theme: 'bootstrap'  // Optional: use Bootstrap styling for alertify (can be customized)
        });
        console.log('Delete ID: ' + id);
    }

    // Function to delete the book data
    function hapus(id) {
        axios({
            url: `http://localhost/web_Programming/laravel/perpus/public/api/book/delete/${id}`,
            method: 'get',
            headers: customConfig
        }).then(response => {
            if (response.data.success === true) {
                alertify.alert('Information', 'Data has been deleted!', () => {
                    alertify.success('OK');
                    refreshdata();  // Refresh the book list after deletion
                }).set({
                    transition: 'fade',  // Optional: Add fade effect for alert
                    theme: 'bootstrap'  // Optional: Add Bootstrap theme to alertify dialog
                });
            } else {
                alertify.alert('Error', 'Failed to delete the book. Please try again.').set({
                    transition: 'fade', 
                    theme: 'bootstrap'
                });
            }
        })
        .catch(error => {
            console.log('AJAX Error: ' + error);
            alertify.alert('Error', 'An error occurred while trying to delete the book.').set({
                transition: 'fade', 
                theme: 'bootstrap'
            });
        });
    }
</script>

<template>
    <MenuView />
  
    <!-- Container untuk Daftar Buku -->
    <div class="container mt-5 w-1/2">
      <!-- Judul -->
      <h2 class="mb-4">Daftar Buku</h2>
  
      <!-- Tombol Add Book -->
      <div class="d-flex justify-content-end mb-3">
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
            <!-- Looping data dari books -->
            <tr v-for="(item, index) in books" :key="item.isbn">
                <!-- Kolom No. -->
                <td class="text-center">{{ index + 1 }}</td>
                <!-- Kolom ISBN -->
                <td>{{ item.isbn }}</td>
                <!-- Kolom Judul -->
                <td>{{ item.judul }}</td>
                <!-- Kolom Pengarang -->
                <td>{{ item.pengarang }}</td>
                <!-- Kolom Tahun -->
                <td class="text-center">{{ item.tahun }}</td>
                <!-- Kolom Tombol View -->
                <td class="text-center">
                <router-link :to="{ name: 'bukuview', params: { theisbn: item.isbn } }">
                    <button type="button" class="btn btn-outline-success btn-sm">
                    <font-awesome-icon :icon="['fas', 'eye']" /> View
                    </button>
                </router-link>
                </td>
                <!-- Kolom Tombol Delete -->
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
    </div>
  </template>
  
  