<template>
    <div class="flex items-center space-x-2">
        <router-link
            v-for="link in links"
            :key="link.id"
            :to="link.url"
            :class="{ 'text-blue-500': isActive(link.url) }"
            class="text-white"
            >{{ link.name }}</router-link
        >

        <div class="relative">
            <div class="flex space-x-4">
                <button
                    @click="toggleNotificationDropdown"
                    class="flex items-center text-gray-100 focus:outline-none"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        class="bi bi-bell"
                        viewBox="0 0 16 16"
                    >
                        <path
                            d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"
                        />
                    </svg>
                    <span
                        v-if="store.unreadCount"
                        class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full"
                        >{{ store.unreadCount }}</span
                    >
                </button>
                <button
                    @click="toggleDropdown"
                    class="flex items-center text-gray-100 focus:outline-none"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </button>
            </div>
            <div
                v-if="isDropdownOpen"
                @click="closeDropdown"
                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 shadow-lg py-2 rounded z-10"
            >
                <router-link
                    class="block px-4 py-2 text-gray-800 hover:bg-blue-500 hover:text-white"
                    :class="{ 'text-blue-500': isActive('/profile') }"
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

            <!-- Notifications Dropdown -->
            <div
                v-if="isNotificationDropdownOpen"
                @click="closeNotificationsDropdown"
                class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 shadow-lg py-2 rounded z-10"
            >
                <!-- {{ notifications.value }} -->
                <template v-if="notifications.value?.length > 0">
                    <div
                        v-for="notification in notifications.value"
                        :key="notification.id"
                        :class="{ ' bg-gray-200': notification.read_at }"
                        class="block px-4 py-2 text-gray-700 hover:text-gray-950"
                    >
                        <a
                            class="hover:cursor-pointer"
                            @click="
                                markAsRead(
                                    notification.id,
                                    notification.data.id
                                )
                            "
                            >Task Assigned : {{ notification.data.title }}
                        </a>
                    </div>
                    <div class="flex justify-center">
                        <button class="text-blue-500" @click="markAllAsRead">
                            Mark all as read
                        </button>
                    </div>
                </template>
                <template v-else>
                    <div class="text-gray-800 px-4 py-2">No notifications</div>
                </template>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, inject, reactive } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";
import { getItem } from "../../Utils/localStorage";
import { useNotificationStore } from "../../stores/notificationStore.js";
const notifications = reactive({});
const store = useNotificationStore();
const $localStorage = inject("$localStorage");
const route = useRoute();
const router = useRouter();
const isDropdownOpen = ref(false);
const isNotificationDropdownOpen = ref(false);

const links = [{ id: 1, name: "Register", url: "/register" }];

// const unread_count = ref(0);
const activeLink = ref("");

const isActive = (url) => {
    return route.path === url;
};

const closeNotificationsDropdown = function () {
    isNotificationDropdownOpen.value = false;
};

const toggleNotificationDropdown = async function () {
    isNotificationDropdownOpen.value = !isNotificationDropdownOpen.value;
    isDropdownOpen.value = false;
    console.log("toggle notifications");
    console.log(isNotificationDropdownOpen.value);
    if (isNotificationDropdownOpen.value) {
        await axios
            .get("/api/notifications", {
                headers: {
                    Authorization: `Bearer ${getItem("access_token")}`,
                },
                params: { user_id: getItem("user_id") },
            })
            .then((response) => {
                const { all_notifications, unread } = response.data;
                store.unreadCount = unread;
                notifications.value = all_notifications;
                // console.log(notifications);
                // unread_count.value = unread
            })
            .catch((error) => {
                console.log(error);
            });
    }
};

const getUnreadNotificationCount = async function () {
    try {
        const response = await axios.get("/api/unread-notifications", {
            headers: {
                Authorization: `Bearer ${getItem("access_token")}`,
            },
        });
        store.unreadCount = response.data;
        // unread_count.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

const toggleDropdown = function () {
    isDropdownOpen.value = !isDropdownOpen.value;
    if (isDropdownOpen.value) {
        isNotificationDropdownOpen.value = false;
    }
};

const closeDropdown = function () {
    isDropdownOpen.value = false;
};

const markAsRead = async function (notification_id, task_id) {
    console.log("shit");
    await axios
        .post(
            "/api/markAsRead",
            { notification_id: notification_id },
            {
                headers: {
                    Authorization: `Bearer ${getItem("access_token")}`,
                },
            }
        )
        .then((response) => {
            // router.push(`/task/${task_id}`);
            store.unreadCount = response.data;
            router.push({ name: "task-show", params: { id: task_id } });
            console.log(response);
        })
        .catch((error) => {
            console.log(error);
        });
};
const markAllAsRead = async function () {
    console.log("shit");
    await axios
        .post(
            "/api/markAllAsRead",
            {},
            {
                headers: {
                    Authorization: `Bearer ${getItem("access_token")}`,
                },
            }
        )
        .then((response) => {
            store.unreadCount = response.data;

            // router.push(`/task/${task_id}`);
            // router.push({ name: 'task-show', params: { id: task_id } });
            console.log(response);
        })
        .catch((error) => {
            console.log(error);
        });
};

const logout = function () {
    let user_id = $localStorage.getItem("user_id");
    let access_token = $localStorage.getItem("access_token");
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

onMounted(() => {
    // console.log(store.unreadCount = 2,'UNREAD COUNT');
    // try {
    //     isAuthenticated.value = await auth.isUserAuthenticated();
    // } catch (error) {
    //     console.error(error);
    //     isAuthenticated.value = false;
    // }
    getUnreadNotificationCount();
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
