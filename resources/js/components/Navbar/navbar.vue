<template>
    <div>
        <nav class="bg-gray-900">
            <div
                class="container mx-auto px-4 flex items-center justify-between h-16"
            >
                <div class="flex items-center space-x-5">
                    <button v-show="isAuthenticated" @click="toggleSidebar" class="text-white">
                        <span v-html="toggleButton"></span>
                    </button>
                    <router-link to="/" class="text-white font-semibold text-xl"
                        >Dashboard</router-link
                    >
                </div>
                <component
                    :is="isAuthenticated ? authenticated : guest"
                ></component>
            </div>
        </nav>
        <Sidebar :sidebarOpen="sidebarOpen" @toggle-sidebar="toggleSidebar" />
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import authenticated from "./authenticated.vue";
import guest from "./guest.vue";
import auth from "../../Utils/auth.js";

const hamburgerSvg = ` <svg
                            class="block h-6 w-6"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>`;
const arrowSvg =
    '<svg class="block h-6 w-6" style="color: rgb(245, 245, 245);" xmlns="http://www.w3.org/2000/svg"  fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" fill="#f5f5f5"></path> </svg>';
const toggleButton = ref(hamburgerSvg);

const isAuthenticated = ref(false);

const sidebarOpen = ref(false);

const toggleSidebar = function () {
    sidebarOpen.value = !sidebarOpen.value;
    const svg = sidebarOpen.value ? arrowSvg : hamburgerSvg;
    toggleButton.value = svg;
};

onMounted(async () => {
    try {
        isAuthenticated.value = await auth.isUserAuthenticated();
    } catch (error) {
        isAuthenticated.value = false;
    }
});
</script>
