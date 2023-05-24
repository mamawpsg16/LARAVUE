<template>
    <div class="flex items-center space-x-4">
      <router-link
        v-for="link in links"
        :key="link.id"
        :to="link.url"
        :class="{ 'text-blue-300': isActive(link.url) }"
        class="text-gray-100"
      >{{ link.name }}</router-link>
  
      <div class="relative">
        <button
          @click="toggleDropdown"
          class="text-gray-100 focus:outline-none"
        >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 25" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class=" h-5">
        <path d="M22.58 11.08c.06-.42.08-.84.06-1.26l2.3-1.8a1.593 1.593 0 0 0 .38-2.2l-2-3.46a1.607 1.607 0 0 0-2.18-.63l-2.52 1a10.46 10.46 0 0 0-1.5-.9l-.38-2.76A1.605 1.605 0 0 0 16 0h-4a1.605 1.605 0 0 0-1.6 1.39l-.38 2.76a10.46 10.46 0 0 0-1.5.9l-2.52-1a1.607 1.607 0 0 0-2.18.63l-2 3.46a1.593 1.593 0 0 0 .38 2.2l2.3 1.8c-.02.42 0 .84.06 1.26l-2.3 1.8a1.593 1.593 0 0 0-.38 2.2l2 3.46c.3.52.93.77 1.5.63l2.52-1c.37.32.8.59 1.26.8l.38 2.76c.11.8.86 1.39 1.7 1.39h4c.84 0 1.59-.59 1.7-1.39l.38-2.76c.46-.21.88-.48 1.26-.8l2.52 1c.58.14 1.2-.11 1.5-.63l2-3.46a1.593 1.593 0 0 0-.38-2.2l-2.32-1.8zM12 15a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
        </svg>


        </button>
  
        <div
          v-if="isDropdownOpen"
          @click="closeDropdown"
          class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 shadow-lg py-2 rounded-md z-10"
        >
          <router-link
            class="block px-4 py-2 text-gray-800 hover:bg-blue-500 hover:text-white"
            :class="{ 'text-blue-300': isActive('/profile') }"
            to="/profile"
          >
            Profile
          </router-link>
          <router-link
            class="block px-4 py-2 text-gray-800 hover:bg-blue-500 hover:text-white"
            to="/login"
            @click="logout"
          >
            Logout
          </router-link>
        </div>
      </div>
    </div>
  </template>

<script setup>
import { ref, onMounted, inject, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import auth from "../../Utils/auth.js";
import axios from "axios";

const $localStorage = inject("$localStorage");
const isAuthenticated = ref(false);
const route = useRoute();
const router = useRouter();
const isDropdownOpen = ref(false);

const links = [
    { id: 1, name: "Register", url: "/register" },
    { id: 2, name: "Tasks", url: "/tasks"},
];

const activeLink = ref("");

const isActive = (url) => {
    return route.path === url;
};

const logout = function () {
    let user_id = $localStorage.getItem("user_id");
    let access_token = $localStorage.getItem("access_token");

    // Check if the token exists
    if (!access_token) {
        $localStorage.removeItem("user_id");
        router.push({ name: "login" });
        return;
    }

    // Decode the JWT to extract the expiration time
    const tokenParts = access_token.split('.');
    if (tokenParts.length !== 3) {
        // Invalid token format, handle accordingly
        console.error("Invalid token format");
        $localStorage.removeItem("user_id");
        $localStorage.removeItem("access_token");
        router.push({ name: "login" });
        return;
    }

    const base64Url = tokenParts[1];
    const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    const tokenData = JSON.parse(window.atob(base64));
    const expirationTime = tokenData.exp;

    // Check if the token has expired
    const currentTime = Math.floor(Date.now() / 1000); // Get current time in seconds
    if (expirationTime && currentTime >= expirationTime) {
        // Token has expired, perform logout actions
        $localStorage.removeItem("user_id");
        $localStorage.removeItem("access_token");
        router.push({ name: "login" });
        return;
    }

    // Token is valid, proceed with the logout request
    axios
        .post(
            "/api/logout",
            { user_id: user_id },
            {
                headers: {
                    Authorization: `Bearer ${access_token}`,
                },
            }
        )
        .then((response) => {
            console.log(response);
            if (response.data.success) {
                $localStorage.removeItem("user_id");
                $localStorage.removeItem("access_token");
                router.push({ name: "login" });
            }
        })
        .catch((error) => {
            console.error(error);
        });
};

const toggleDropdown = function() {
    console.log('toggle');
      isDropdownOpen.value = !isDropdownOpen.value;
};

const closeDropdown = function() {
    console.log('close');
    isDropdownOpen.value = false;
};

onMounted(() => {
    // try {
    //     isAuthenticated.value = await auth.isUserAuthenticated();
    // } catch (error) {
    //     console.error(error);
    //     isAuthenticated.value = false;
    // }

    activeLink.value = route.path;
});

// const filteredLinks = computed(() => {
//     if (isAuthenticated.value) {
//         return links.filter(
//             (link) => link.showIfAuthenticated || link.url == "/register"
//         );
//     } else {
//         return links.filter((link) => !link.showIfAuthenticated);
//     }
// });
</script>
