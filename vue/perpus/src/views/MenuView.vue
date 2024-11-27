<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useUserStore } from '../stores/counter'
import { storeToRefs, createPinia } from 'pinia'

const pini = createPinia()
const store = useUserStore()
const { email, token } = storeToRefs(store);
const therouter = useRouter()

const customConfig = {
   Authorization: `Bearer ${token.value}`
}
function logout(){
  axios({ url: 'http://localhost/web_Programming/laravel/perpus/public/api/logout',
          method: 'get',
          headers: customConfig
        }).then(response => {
          console.log(response.data)//only for development
          if(response.data.success === true)
          {
            store.reset()
            therouter.push('/')
          }
      })
      .catch(error => {
        console.log('AJAX' + error)
      })
      .finally()
}

// State untuk dropdown
const isDropdownOpen = ref(false);

// Toggle dropdown
function toggleDropdown() {
  isDropdownOpen.value = !isDropdownOpen.value;
}

</script>

<template>
  <div class="relative">
    <!-- Sidebar Container -->
    <div class="bg-gray-900 text-white h-screen w-64 p-4 flex flex-col justify-between absolute left-0 top-0">
      <!-- User Info -->
      <div>
        <div class="flex items-center space-x-3 mb-6">
          <!-- Profile Icon -->
          <font-awesome-icon icon="user" class="text-gray-300 w-6 h-6" />
          <span class="font-semibold">{{ email || 'User Email' }}</span>
        </div>

        <!-- Menu List -->
        <nav>
          <ul class="space-y-2">
            <!-- Pegawai -->
            <li>
              <a href="/pegawai" class="flex items-center space-x-2 hover:text-gray-300">
                <font-awesome-icon icon="list-alt" class="w-4 h-4" />
                <span>PEGAWAI</span>
              </a>
            </li>

            <!-- Barang -->
            <li>
              <a href="/barang" class="flex items-center space-x-2 hover:text-gray-300">
                <font-awesome-icon icon="cart-plus" class="w-4 h-4" />
                <span>BARANG</span>
              </a>
            </li>

            <!-- Perpustakaan Dropdown -->
            <li>
              <div
                @click="toggleDropdown"
                class="flex items-center justify-between cursor-pointer hover:text-gray-300"
              >
                <div class="flex items-center space-x-2">
                  <font-awesome-icon icon="book" class="w-4 h-4" />
                  <span>PERPUSTAKAAN</span>
                </div>
                <font-awesome-icon
                  icon="caret-down"
                  :class="{ 'rotate-180': isDropdownOpen }"
                  class="h-5 w-5 transition-transform duration-200"
                />
              </div>
              <!-- Dropdown Content -->
              <ul
                v-if="isDropdownOpen"
                class="mt-2 pl-6 space-y-2 text-gray-400"
              >
                <li>
                  <a href="/buku" class="block hover:text-gray-300">
                    Buku
                  </a>
                </li>
                <li>
                  <a href="/peminjaman" class="block hover:text-gray-300">
                    Peminjaman
                  </a>
                </li>
                <li>
                  <a href="/pembelian" class="block hover:text-gray-300">
                    Pembelian
                  </a>
                </li>
              </ul>
            </li>

            <!-- Logout -->
            <li>
              <button
                @click="logout"
                class="w-full text-left flex items-center space-x-2 hover:text-red-500"
              >
                <font-awesome-icon icon="sign-out-alt" class="w-4 h-4" />
                <span>LOGOUT</span>
              </button>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Rotate dropdown icon */
.rotate-180 {
  transform: rotate(180deg);
}
</style>
