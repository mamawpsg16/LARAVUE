<template>
    <Navbar />
<div class="container mx-auto px-4 mt-5">
        <!-- <h1 class="text-3xl font-bold my-6">DASHBOARD</h1> -->
        <div class="grid grid-cols-2 gap-4">
            <div
                class="bg-gradient-to-r from-purple-500 to-purple-700 p-6 rounded shadow"
            >
                <h2 class="text-xl font-bold mb-4 text-white text-center">
                    USERS
                </h2>
                <p class="text-lg text-white text-center">
                    {{ registeredUsersCount }}
                </p>
            </div>
            <div
                class="bg-gradient-to-r from-blue-500 to-blue-700 p-10 rounded shadow"
            >
                <h2 class="text-xl font-bold mb-4 text-white text-center">
                    TASKS : {{ Tasks.total }}
                </h2>
                <div class="flex items-center justify-between">
                    <div class="space-x-2">
                        <span class="text-lg font-bold text-white"
                            >Pending:</span
                        >
                        <span class="text-lg text-white">{{
                            Tasks.pending
                        }}</span>
                    </div>
                    <div class="space-x-2">
                        <span class="text-lg font-bold text-white"
                            >Ongoing:</span
                        >
                        <span class="text-lg text-white">{{
                            Tasks.ongoing
                        }}</span>
                    </div>
                    <div class="space-x-2">
                        <span class="text-lg font-bold text-white"
                            >Complete:</span
                        >
                        <span class="text-lg text-white">{{
                            Tasks.complete
                        }}</span>
                    </div>
                    <div class="space-x-2">
                        <span class="text-lg font-bold text-white"
                            >Cancelled:</span
                        >
                        <span class="text-lg text-white">{{
                            Tasks.cancelled
                        }}</span>
                    </div>
                    <!-- <button @click="store.increment">{{ store.unreadCount }}</button> -->
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from "axios";
import { onMounted, ref, reactive } from "vue";
import { getItem } from '../Utils/localStorage.js'
// import { useNotificationStore } from '../stores/notificationStore.js'

// const store = useNotificationStore()

const registeredUsersCount =  ref(0); 
const Tasks = reactive({
     pending : 0, 
     ongoing : 0, 
     complete : 0, 
     cancelled : 0,
     total : 0,
})
const getDashboardInformation = async function () {
    const access_token = getItem("access_token");
    await axios
        .get("/api/dashboard", {
            headers: { Authorization: `Bearer ${access_token}` },
        })
        .then((response) => {
            const { users, tasks } = response.data;
            console.log(tasks,'SHIOT');
            console.log(Tasks.pending)
            Tasks.pending = tasks.pending;
            Tasks.ongoing = tasks.ongoing;
            Tasks.complete = tasks.complete;
            Tasks.cancelled = tasks.cancelled;
            Tasks.total = tasks.tasks;
          
            registeredUsersCount.value = users;
            // totalTasks.value = tasks; 
        })
        .catch((error) => {
            console.log(error);
        });
};

onMounted(() => {
    getDashboardInformation()
});
</script>
