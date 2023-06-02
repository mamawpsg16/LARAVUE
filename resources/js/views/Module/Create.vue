<template>
    <Navbar />
    <form class="container mx-auto w-3/6 h-full mt-2">
        <div class="flex justify-start mb-4">
            <router-link
                to="/modules"
                class="px-4 py-2 text-white bg-amber-400 rounded-md hover:bg-amber-500"
            >
                <i class="fa-solid fa-arrow-left"></i>
            </router-link>
        </div>
        <div class="flex flex-col h-full">
            <div class="mt-4">
                <label for="name" class="text-lg">Name :</label>
                <input
                    id="name"
                    type="text"
                    placeholder="Enter name"
                    v-model="module.name"
                    class="w-full px-4 py-2 mt-1 border rounded-md"
                />
                <span v-if="errors.name" class="text-red-500">{{
                    errors.name[0]
                }}</span>
            </div>
            <div class="mt-4">
                <label for="name" class="text-lg">Route :</label>
                <input
                    id="route"
                    type="text"
                    placeholder="Enter route"
                    v-model="module.route"
                    class="w-full px-4 py-2 mt-1 border rounded-md"
                />
                <span v-if="errors.route" class="text-red-500">{{
                    errors.route[0]
                }}</span>
            </div>
            <div class="mt-4">
                <label for="name" class="text-lg">Icon :</label>
                <input
                    id="icon"
                    type="text"
                    placeholder="Enter icon"
                    v-model="module.icon"
                    class="w-full px-4 py-2 mt-1 border rounded-md"
                />
                <span v-if="errors.icon" class="text-red-500">{{
                    errors.icon[0]
                }}</span>
            </div>
            <div class="mt-4">
                <label for="name" class="text-lg">Description :</label>
                <input
                    id="icon"
                    type="text"
                    placeholder="Enter icon"
                    v-model="module.description"
                    class="w-full px-4 py-2 mt-1 border rounded-md"
                />
                <span v-if="errors.description" class="text-red-500">{{
                    errors.description[0]
                }}</span>
            </div>
            <div class="flex justify-end ">
                <button
                    @click.prevent="store"
                    class="px-4 py-2 mt-4 font-bold text-white bg-blue-500 rounded-md"
                >
                    Save
                </button>
            </div>
        </div>
    </form>
</template>

<script setup>
import Input from "../../components/Form/Input.vue";
import Form from "../../components/Form/Form.vue";
import Label from "../../components/Form/Label.vue";
import axios from "axios";
import { showCustomToast } from "../../Utils/toast.js";
import { useRouter } from "vue-router";
import { reactive, ref, inject } from "vue";
import { onMounted } from "vue";

const router = useRouter();
const errors = ref([]);
const module = reactive({
    name: "",
    route: "",
    description: "",
    icon: "",
});

const $localStorage = inject("$localStorage");


const store = () => {
    const access_token = $localStorage.getItem("bearer_token");
    axios
        .post(
            "/api/modules",
            {
                name: module.name,
                route: module.route,
                description: module.description,
                icon: module.icon
            },
            {
                headers: {
                    Authorization: `Bearer ${access_token}`,
                },
            }
        )
        .then((response) => {
            console.log(response);
            // Check if the response contains success message
            if (response.data) {
                // Store the token in local storage

                showCustomToast("success", "Module Created!", {
                    // position: 'bottom-right',
                });
                router.push({ name: "modules" });
                // Success, redirect or show a success message
            }
        })
        .catch((error) => {
            console.log(error.response);
            // Handle request error
            if (error.response?.status === 422) {
                // Handle validation errors
                console.log("ERROR");
                console.log(error.response.data.errors);
                console.log("SHIT");
                if (error.response.data.errors.not_exists?.length) {
                    showCustomToast(
                        "error",
                        error.response.data.errors.not_exists,
                        {}
                    );
                }
                console.log(error.response.data.errors);
                errors.value = error.response.data.errors;
            } else {
                // Handle other errors
                console.log(error);
            }
        });
};



onMounted(() => {
    // getUsers();
});
</script>

<style lang="scss" scoped></style>
