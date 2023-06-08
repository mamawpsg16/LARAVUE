<template>
    <Navbar />
    <div class="container mx-auto px-4 mt-5">
        <custom-button @button-click="handleClick"></custom-button>
        {{ message }}
        <custom-input v-model="input_value"></custom-input>
        {{ input_value }}
        <!-- <button @click="toggleModal">Open Modal</button> -->
        <!-- <BaseModal :modalClass="customModalClass" v-model="isModalOpen">
            <h2 class="text-2xl mb-4">Modal Title</h2>
            <div class="mt-4">
                <label for="name" class="text-lg">Name :</label>
                <input
                    id="name"
                    type="text"
                    placeholder="Enter name"
                    class="w-full px-4 py-2 mt-1 border rounded-md"
                />
            </div>
            <div class="mt-4">
                <label for="name" class="text-lg">Route :</label>
                <input
                    id="route"
                    type="text"
                    placeholder="Enter route"
                    class="w-full px-4 py-2 mt-1 border rounded-md"
                />
                <span class="text-red-500"></span>
            </div>
            <div class="flex justify-end">
                <button
                    @click.prevent="store"
                    class="px-4 py-2 mt-4 font-bold text-white bg-blue-500 rounded-md"
                >
                    Save
                </button>
            </div>
        </BaseModal> -->
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
import { getItem } from "../Utils/localStorage.js";
import CustomButton from '../components/Form/Button.vue'
import CustomInput from '../components/Form/Input.vue'
// import { useNotificationStore } from '../stores/notificationStore.js'
// import BaseModal from "../components/BaseModal.vue";
// const customModalClass = ref('w-full md:w-2/3 lg:w-1/2');
const input_value = ref(null);
const message = ref(null);
const handleClick = (data) =>{
    console.log(data);
    message.value = data;
}
const registeredUsersCount = ref(0);
// const isModalOpen = ref(false);
// onClickOutside(modal, () =>(isModalOpen.value = false))

// const toggleModal = function(e){
//     isModalOpen.value = !isModalOpen.value;
// }
const Tasks = reactive({
    pending: 0,
    ongoing: 0,
    complete: 0,
    cancelled: 0,
    total: 0,
});
const getDashboardInformation = async function () {
    const access_token = getItem("access_token");
    await axios
        .get("/api/dashboard", {
            headers: { Authorization: `Bearer ${access_token}` },
        })
        .then((response) => {
            const { users, tasks } = response.data;
            console.log(tasks, "SHIOT");
            console.log(Tasks.pending);
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
    getDashboardInformation();
});
</script>
