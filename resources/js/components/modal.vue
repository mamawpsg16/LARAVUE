<template>
  <div>
    <!-- Modal -->
    <div v-if="isModalOpen" class="fixed z-10 inset-0 overflow-y-auto flex items-center justify-center">
      <!-- <div class="fixed inset-0 transition-opacity">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
      </div> -->

      <div class="bg-stone-200 max-w-md  rounded-lg shadow-xl p-6  mx-auto relative">
        <!-- Modal content -->
        <h2 class="text-2xl font-bold mb-4">{{ title }}</h2>
        <slot></slot>
        <br/>
        <button @click="isModalOpen = false">X</button>
      </div>
    </div>
  </div>
</template>
  
  <script setup>

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    isModalOpen:{
        type: Boolean,
        default:false
    }
});

  </script>
  
  <style>
  /* Add any custom styles or override Tailwind CSS classes here */
  </style>
  