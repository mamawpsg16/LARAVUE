<!-- resources/js/components/Navbar.vue -->
<template>
    <div class="flex items-center space-x-4">
        <router-link
            v-for="link in links"
            :key="link.id"
            :to="link.url"
            :class="{ 'text-blue-300': isActive(link.url) }"
            class="text-gray-100"
            >{{ link.name }}</router-link
        >
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();

const links = [
    { id: 1, name: "Login", url: "/login"},
    { id: 2, name: "Register", url: "/register" },
];

const activeLink = ref("");

const isActive = (url) => {
    return route.path === url;
};

</script>
