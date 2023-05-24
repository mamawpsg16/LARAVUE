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
import Navbar from '../components/Navbar/navbar.vue';

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
