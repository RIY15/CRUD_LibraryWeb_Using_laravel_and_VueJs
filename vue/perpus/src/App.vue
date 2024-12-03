<script setup>
import { ref, computed } from 'vue'
import FooterView from './views/FooterView.vue'
import MenuView from './views/MenuView.vue'

import { useRoute } from 'vue-router'
import Sidebar from './components/Sidebar.vue';

const theroute = useRoute()
const isnotlogin = computed(() =>{
  return theroute.name != 'login' ? true : false
})

</script>

<template>
    <div class="container">  
        <Sidebar v-if="isnotlogin"/>
        <div class="row">
             <router-view></router-view>
        </div>
        <FooterView v-if="isnotlogin"/>
    </div> 
</template>

