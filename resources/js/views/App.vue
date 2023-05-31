<template>
  <div v-cloak>
    <!-- Your loading screen or placeholder content -->
    <!-- <div v-if="!isDomReady" class="loading-screen">
     Show loading screen or placeholder content here -->
    <!-- </div> -->
    <!-- Your actual content -->
    <!-- <div v-else> --> 
      <router-view>
      </router-view>
    <!-- </div> -->
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import auth  from '../Utils/auth.js'
import { useRouter } from 'vue-router';

const router = useRouter();
const checkAuthentication = function(){
    const invalidToken = auth.isAccessTokenInvalid();
    if(invalidToken) {
        router.push({name:'login'});
    }
}

onMounted(() =>{
  checkAuthentication();
})
</script>

<style>
[v-cloak] {
  display: none;
}

.loading-screen {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}
</style>
