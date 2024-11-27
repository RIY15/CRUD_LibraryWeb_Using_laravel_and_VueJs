import { ref, computed, watch } from 'vue'
import { defineStore } from 'pinia'

export const useCounterStore = defineStore('counter', () => {
  const count = ref(0)
  const doubleCount = computed(() => count.value * 2)
  function increment() {
    count.value++
  }

  return { count, doubleCount, increment }
})

export const useUserStore = defineStore('user', () => {
  // State
  const email = ref(localStorage.getItem('email') || ''); // Ambil dari localStorage
  const token = ref(localStorage.getItem('token') || ''); // Ambil dari localStorage
  const thecounter = ref(parseInt(localStorage.getItem('thecounter')) || 0); // Ambil dari localStorage

  // Fungsi untuk mereset data (logout)
  function reset() {
    email.value = '';
    token.value = '';
    thecounter.value = 0;

    // Hapus dari localStorage
    localStorage.removeItem('email');
    localStorage.removeItem('token');
    localStorage.removeItem('thecounter');
  }

  // Watcher untuk menyimpan perubahan ke localStorage
  watch(email, (newValue) => {
    localStorage.setItem('email', newValue);
  });

  watch(token, (newValue) => {
    localStorage.setItem('token', newValue);
  });

  return { email, token, reset };
});

