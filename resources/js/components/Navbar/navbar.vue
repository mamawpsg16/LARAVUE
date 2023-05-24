<template>
    <nav class="bg-gray-900">
        <div
            class="container mx-auto px-4 flex items-center justify-between h-16"
        >
            <router-link to="/" class="text-white font-semibold text-xl"
                >Logo</router-link
            >
            <component :is="isAuthenticated ? authenticated : guest"></component>
        </div>
    </nav>
</template>

<script setup>
import { ref, onMounted  } from "vue";
import authenticated from "./authenticated.vue";
import guest from "./guest.vue";
import auth from "../../Utils/auth.js";
const isAuthenticated = ref(false);

onMounted(async () => {
    try {
        isAuthenticated.value = await auth.isUserAuthenticated();
    } catch (error) {
        isAuthenticated = false;
    }
});


</script>
