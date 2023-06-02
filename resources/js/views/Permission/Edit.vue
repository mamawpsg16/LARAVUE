<template>
    <Navbar />
    <form class="container mx-auto w-3/6 h-full mt-2">
        <div class="flex justify-start mb-4">
            <router-link
                :to="`/permission/${permission.id}`"
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
                    v-model="permission.name"
                    class="w-full px-4 py-2 mt-1 border rounded-md"
                />
                <span v-if="errors.name" class="text-red-500">{{
                    errors.name[0]
                }}</span>
            </div>
            <div class="flex justify-end space-x-1">
                <router-link
                    :to="`/permission/${permission.id}`"
                    class="px-4 py-2 mt-4 font-bold text-white bg-gray-500 rounded-md"
                >
                    Cancel
                </router-link>
                <button
                    @click.prevent="update"
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
import { useRoute, useRouter } from "vue-router";
import { reactive, ref, inject } from "vue";
import { onMounted } from "vue";
import Multiselect from "vue-multiselect";
import DatePicker from "vue-datepicker-next";

const router = useRouter();
const route = useRoute();
const errors = ref([]);
const permission = ref([]);
const $localStorage = inject("$localStorage");

const getPermissions = () => {
    const access_token = $localStorage.getItem("bearer_token");
    const permission_id = route.params.id;
    axios
        .get(`/api/permissions/${permission_id}`, {
            headers: {
                Authorization: `Bearer ${access_token}`,
            },
        })
        .then((response) => {
            permission.value = response.data;
            permission.value.due_date = new Date(permission.value.due_date);
            user_id.value = response.data.user_id;
            selected_user.value = response.data.user;
        });
};


const update = () => {
    const access_token = $localStorage.getItem("bearer_token");
    // console.log(access_token);
    // return false;
    axios
        .put(
            `/api/permissions/${permission.value.id}`,
            {
                name: permission.value.name,
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

                showCustomToast("success", "Permission Updated!", {
                    // position: 'bottom-right',
                });
                // console.log(permission.value.id);
                // router.push({ name: '/permission/',  params: { id: permission.value.id } });
                router.push(`/permission/${permission.value.id}`);
                // router.push("/permissions");
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
    getPermissions();
});
</script>

<style lang="scss" scoped></style>
