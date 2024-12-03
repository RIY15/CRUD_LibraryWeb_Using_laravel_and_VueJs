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
          console.log(response.data) // only for development
          if (response.data.success === true) {
            store.reset()
            therouter.push('/')
          }
      })
      .catch(error => {
        console.log('AJAX' + error)
      })
}

// State untuk dropdown
const isDropdownOpen = ref(false);

// Toggle dropdown
function toggleDropdown() {
  isDropdownOpen.value = !isDropdownOpen.value;
}

// State untuk sidebar
const isSidebarOpen = ref(false);

// Toggle sidebar
function toggleSidebar() {
  isSidebarOpen.value = !isSidebarOpen.value;
}
</script>

<template>
  <div>
    <!-- Hamburger Button -->
    <button
      @click="toggleSidebar"
      class="p-2 bg-gray-900 text-white fixed top-2 left-2 z-50 rounded-md focus:outline-none focus:ring focus:ring-gray-600"
    >
      <font-awesome-icon :icon="isSidebarOpen ? 'times' : 'bars'" class="w-6 h-6" />
    </button>

    <!-- Sidebar Container -->
    <div
      :class="{ '-translate-x-full': !isSidebarOpen }"
      class="bg-gray-900 text-white fixed h-full w-64 p-4 flex flex-col justify-between top-0 left-0 transform transition-transform duration-300 overflow-y-auto z-10"
    >
      <!-- User Info -->
      <div>
        <div class="flex items-center space-x-3 mb-6 mt-10">
          <!-- Profile Icon -->
          <font-awesome-icon icon="user" class="text-gray-300 w-6 h-6" />
          <span class="font-semibold">{{ email || 'User Email' }}</span>
        </div>

        <!-- Menu List -->
        <nav>
          <ul class="space-y-2">
            <!-- Pegawai -->
            <li>
              <router-link
                to="/pegawai"
                class="flex items-center space-x-2 hover:text-gray-300"
              >
                <font-awesome-icon icon="list-alt" class="w-4 h-4" />
                <span>PEGAWAI</span>
              </router-link>
            </li>

            <!-- Barang -->
            <li>
              <router-link
                to="/barang"
                class="flex items-center space-x-2 hover:text-gray-300"
              >
                <font-awesome-icon icon="cart-plus" class="w-4 h-4" />
                <span>BARANG</span>
              </router-link>
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
                  <router-link to="/buku" class="block hover:text-gray-300">
                    Buku
                  </router-link>
                </li>
                <!-- <li>
                  <router-link to="/peminjaman" class="block hover:text-gray-300">
                    Peminjaman
                  </router-link>
                </li>
                <li>
                  <router-link to="/pembelian" class="block hover:text-gray-300">
                    Pembelian
                  </router-link>
                </li> -->
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

/* Sidebar transition */
.transform {
  transition: transform 0.3s ease-in-out;
}

/* Sidebar hidden state */
.-translate-x-full {
  transform: translateX(-100%);
}


</style>

