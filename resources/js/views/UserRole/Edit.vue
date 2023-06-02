<template>
    <Navbar />
    <form class="container mx-auto w-3/6 h-full mt-2">
        <div class="flex justify-start mb-4">
            <router-link
                :to="`/user-role/${selected_role.id}`"
                class="px-4 py-2 text-white bg-amber-400 rounded-md hover:bg-amber-500"
            >
                <i class="fa-solid fa-arrow-left"></i>
            </router-link>
        </div>
        <div class="flex flex-col h-full">
            <div class="mt-4">
                <label for="role" class="text-lg">Roles:</label>
                <multiselect
                    v-model="selected_role"
                    :options="roles"
                    :allow-empty="true"
                    placeholder="Select a role"
                    @select="selectedRole"
                    @remove="removedRole"
                    label="name"
                    track-by="name"
                    class="mt-2"
                ></multiselect>
                <span v-if="errors.role" class="text-red-500">{{
                    errors.role[0]
                }}</span>
            </div>
            <div class="mt-4">
                <label for="assignee" class="text-lg">Users:</label>
                <multiselect
                    v-model="selected_users"
                    :multiple="true"
                    :preserve-search="true"
                    :options="users"
                    :allow-empty="true"
                    placeholder="Select users"
                    @select="selectAssignedUser"
                    @remove="removeAssignedUser"
                    label="email"
                    track-by="email"
                    class="mt-2"
                ></multiselect>
                <span v-if="errors.user" class="text-red-500">{{
                    errors.user[0]
                }}</span>
            </div>
            <div class="flex justify-end">
                <button
                    @click.prevent="update"
                    class="px-4 py-2 mt-4 font-bold text-white bg-blue-500 rounded-md"
                >
                    Update
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
import { reactive, ref, inject } from "vue";
import { onMounted } from "vue";
import Multiselect from "vue-multiselect";
import DatePicker from "vue-datepicker-next";
import auth from "../../Utils/auth.js";
import { useRoute, useRouter } from "vue-router";
const route = useRoute();

const router = useRouter();
const errors = ref([]);
const selected_users = ref([]);
const users = ref([]);
const selected_role = ref([]);
const role_id = ref(null);
const user_ids = ref([]);
const roles = ref([]);

const $localStorage = inject("$localStorage");

const getDetails = async function () {
    let access_token = $localStorage.getItem("access_token");
    const role_id = route.params.id;
    await axios
        .get(`/api/user-roles/edit/${role_id}`, {
            headers: {
                Authorization: `Bearer ${access_token}`,
            },
        })
        .then((response) => {
            const {
                user_roles_,
                role_,
            } = response.data;
            // roles.value = roles_;
            selected_users.value = user_roles_;
            selected_role.value = role_;
            // const selected_role_id = parseInt(role_.id)
            // role_id = selected_role_id;
            user_ids.value = selected_users.value.map(user => user.id);
     
        })
        .catch((error) => {
            console.log(error);
        });
};

const getUsers = async function () {
    let access_token = $localStorage.getItem("access_token");
    await axios
        .get("/api/users", {
            headers: {
                Authorization: `Bearer ${access_token}`,
            },
        })
        .then((response) => {
            users.value = response.data;
            console.log(response.data);
        })
        .catch((error) => {
            console.log(error);
        });
};
const getRoles = async function () {
    let access_token = $localStorage.getItem("access_token");
    await axios
        .get("/api/getRoles", {
            headers: {
                Authorization: `Bearer ${access_token}`,
            },
        })
        .then((response) => {
            roles.value = response.data;
            console.log(response.data);
        })
        .catch((error) => {
            console.log(error);
        });
};

const selectedRole = function (selectedOption, id) {
    role_id.value = selectedOption.id;
};

const removedRole = function (removedOption, id) {
    selected_role.value = [];
    role_id.value = null;
};

const selectAssignedUser = function (selectedOption, id) {
    user_ids.value = selected_users.value.map((user) => user.id); // Update the user_ids array with the updated selected_users array
};

const removeAssignedUser = function (removedOption, id) {
    selected_users.value = selected_users.value.filter((user) => user.id !== removedOption.id);
    user_ids.value = selected_users.value.map((user) => user.id);
};


const update = () => {
    // console.log(modules._rawValue);
    // return false;
    // Create a new array containing modules with selected permissions
 
    console.log('PASOK');
    const role_id = route.params.id;
    const access_token = $localStorage.getItem("bearer_token");
    axios
        .put(
            `/api/user-roles/${role_id}`,
            {
                role: selected_role.value.id,
                user: user_ids.value,
            },
            {
                headers: {
                    Authorization: `Bearer ${access_token}`,
                },
            }
        )
        .then((response) => {
            showCustomToast("success", "Role Access Created!", {
                // position: 'bottom-right',
            });
            router.push(`/user-role/${role_id}`)
            // Success, redirect or show a success message
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
    getDetails();
    getUsers();
    getRoles();
});
</script>

<style lang="scss" scoped></style>
