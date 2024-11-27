<script setup>
import { useUserStore } from '../stores/counter'
import MenuView from './MenuView.vue';

const store = useUserStore()

function increase(){
  store.thecounter++
}
</script>

<template>
    <MenuView/>
</template>

