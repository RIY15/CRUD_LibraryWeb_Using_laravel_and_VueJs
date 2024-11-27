<script setup>
    import { ref, computed, onMounted } from 'vue'
    import { useRouter, useRoute } from 'vue-router'
    import axios from 'axios'
    import { useUserStore } from '../stores/counter'

    const isbn_readonly = ref(false)
    const theroute = useRoute()
    const therouter = useRouter()
    const store = useUserStore()
    const isbn = ref('')
    const judul = ref('')
    const pengarang = ref('')
    const tahun = ref(2020)

    const customConfig = {
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' + store.token
    }

    const thedata = computed(() =>{
    return {'isbn': isbn.value,'judul': judul.value, 'pengarang': pengarang.value,'tahun': tahun.value}
    })

    function save(){
    const store_or_update = theroute.params.theisbn !== undefined ? 'update' : 'store'
    axios({ url: 'http://localhost/web_Programming/laravel/perpus/public/api/book/' + store_or_update,
            method: 'post',
            data: thedata.value,
            headers: customConfig
            }).then(response => {
            console.log(response.data)//only for development
            if(response.data.success === true)
            {
                alertify.alert('Information', 'Data has been saved!', function(){ alertify.success('OK'); });
                therouter.push('/buku')
            }
            })
            .catch(error => {
            console.log('AJAX' + error)
            })
            .finally()
    }

    onMounted(()=>{
        if(theroute.params.theisbn !== undefined)
        {
        isbn_readonly.value = true
        axios({ url: 'http://localhost/web_Programming/laravel/perpus/public/api/book/show/' + theroute.params.theisbn,
            method: 'get',
            headers: customConfig
            }).then(response => {
            console.log(response.data)//only for development
            if(response.data.success === true)
            {
                isbn.value = response.data.data.isbn
                judul.value = response.data.data.judul
                pengarang.value = response.data.data.pengarang
                tahun.value = response.data.data.tahun
            }
        })
        .catch(error => {
            console.log('AJAX' + error)
        })
        .finally()
        }
    })
</script>

<template>
    <div class="container mt-5">
      <!-- Form Container -->
      <div class="card">
        <div class="card-header text-center">
          <h3>BOOK FORM</h3>
        </div>
        <div class="card-body">
          <form @submit.prevent="save">
            <!-- ISBN -->
            <div class="form-group mb-3">
              <label for="isbn">ISBN</label>
              <input
                type="text"
                id="isbn"
                v-model="isbn"
                class="form-control"
                :readonly="isbn_readonly"
                required
              />
            </div>
  
            <!-- Judul -->
            <div class="form-group mb-3">
              <label for="judul">Judul</label>
              <input
                type="text"
                id="judul"
                v-model="judul"
                class="form-control"
                required
              />
            </div>
  
            <!-- Pengarang -->
            <div class="form-group mb-3">
              <label for="pengarang">Pengarang</label>
              <input
                type="text"
                id="pengarang"
                v-model="pengarang"
                class="form-control"
                required
              />
            </div>
  
            <!-- Tahun -->
            <div class="form-group mb-3">
              <label for="tahun">Tahun Terbit</label>
              <input
                type="number"
                id="tahun"
                v-model="tahun"
                class="form-control"
                required
              />
            </div>
  
            <!-- Buttons -->
            <div class="d-flex justify-content-between">
              <button type="submit" class="btn btn-success">
                <i class="fa fa-save"></i> SAVE
              </button>
              <button
                type="button"
                @click="therouter.push('/buku')"
                class="btn btn-danger"
              >
                <i class="fa fa-times"></i> CANCEL
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
  


