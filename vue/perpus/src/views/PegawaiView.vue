<script setup>
import Sidebar from '@/components/Sidebar.vue';
import { useUserStore } from '../stores/counter'
import MenuView from './MenuView.vue';

const store = useUserStore()

function increase(){
  store.thecounter++
}
</script>

<template>
  <h2>Pegawai</h2>
</template>

