<template>
    <Navbar />
    <form class="container mx-auto w-3/6 h-full mt-2">
        <div class="flex justify-start mb-4">
            <router-link
                to="/role-access"
                class="px-4 py-2 text-white bg-amber-400 rounded-md hover:bg-amber-500"
            >
                <i class="fa-solid fa-arrow-left"></i>
            </router-link>
        </div>
        <!-- <div
            v-if="selected_user?.length > 0"
            class="flex items-center justify-center"
        >
            <div class="flex space-x-2">
                <div class="w-20 h-20 rounded-full overflow-hidden"     
                    v-for="user in selected_user"
                    :key="user.id">
                    <img
                        :src="user.profile_picture"
                        alt="Profile Picture"
                        class="object-cover w-full h-full"
                    />
                </div>
            </div>
        </div> -->

        <div class="flex flex-col h-full">
            <!-- <div>
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
            </div> -->
            <div class="flex justify-center items-center">
                <label for="name" class="text-lg">Name:</label>
                <input
                    id="name"
                    type="text"
                    placeholder="Enter name"
                    v-model="role.name"
                    class="w-full px-4 py-2 mt-1 border rounded-md"
                />
            </div>
            <span v-if="errors.name" class="text-red-500">{{
                errors.name[0]
            }}</span>

            <table class="table table-sm table-bordered">
                <thead class="thead-global">
                    <tr>
                        <th>Modules</th>
                        <th>Permissions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(module, r) in modules" :key="r">
                        <td>{{ module.name }}</td>
                        <td>
                            <multiselect
                                v-model="module.selected_permissions"
                                :data-id="module.name"
                                :multiple="true"
                                :options="permissions"
                                :allow-empty="true"
                                placeholder="Select a permission"
                                :max-height="600"
                                label="name"
                                track-by="name"
                                class="mt-2"
                            >
                            </multiselect>
                        </td>
                    </tr>
                    <span
                        v-if="errors.module_permissions"
                        class="text-red-500"
                        >{{ errors.module_permissions[0] }}</span
                    >
                </tbody>
            </table>
            <!-- <div>
                <label for="assignee" class="text-lg">Users:</label>
                <multiselect
                    v-model="selected_user"
                    :multiple="true"
                     :preserve-search="true"
                    :options="users"
                    :allow-empty="true"
                    placeholder="Select a user"
                    @select="selectAssignedUser"
                    @remove="removeAssignedUser"
                    label="email"
                    track-by="email"
                    class="mt-2"
                ></multiselect>
            </div> -->
            <div class="flex justify-end">
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
import { reactive, ref, inject } from "vue";
import { onMounted } from "vue";
import Multiselect from "vue-multiselect";
import DatePicker from "vue-datepicker-next";
import auth from "../../Utils/auth.js";
import { useRoute, useRouter } from "vue-router";
const route = useRoute();

const date = ref(new Date());
const router = useRouter();
const errors = ref([]);
const selected_user = ref([]);
const selected_role = ref([]);
const name = ref(null);
const selected_permissions = ref([]);
const selected_modules = ref([]);
const task = reactive({
    title: "",
    description: "",
    due_date: new Date(),
});
const user_id = ref([]);
const module_permissions = ref([]);
const role_id = ref(null);
const users = ref([]);
const roles = ref([]);
const modules = ref([]);
const permissions = ref([]);
const selected_module_permissions = ref([]);
const role = ref([]);

const $localStorage = inject("$localStorage");

const getDetails = async function () {
    let access_token = $localStorage.getItem("access_token");
    const role_id = route.params.id;
    await axios
        .get(`/api/role-access/edit/${role_id}`, {
            headers: {
                Authorization: `Bearer ${access_token}`,
            },
        })
        .then((response) => {
            const {
                modules_,
                permissions_,
                roles_,
                selected_module_permissions_,
                selected_role,
            } = response.data;
            // roles.value = roles_;
            modules.value = modules_;
            permissions.value = permissions_;
            selected_module_permissions.value = selected_module_permissions_;
            role.value = selected_role;
      
            // Iterate over selected_module_permissions
            selected_module_permissions._rawValue.forEach((selectedPermission) => {
  // Find the module based on module_id
  const module = modules.value.find((module) => module.id === selectedPermission.module_id);
  
  // Add selected_permission to the module
  if (module) {
    if (!module.selected_permissions) {
      module.selected_permissions = []; // Create an array if it doesn't exist
    }
    module.selected_permissions.push(selectedPermission); // Add the selectedPermission to the array
  }
});

            // users.value = response.data;
            // console.log(response.data);
        })
        .catch((error) => {
            console.log(error);
        });
};

const update = () => {
    // console.log(modules._rawValue);
    // return false;
    // Create a new array containing modules with selected permissions
    const modules_with_permissions = modules._rawValue
  .map((module) => {
    console.log(module, 'MODULE');

    const selectedPermissionIds = module.selected_permissions
      ? [module.selected_permissions]
      : [];

    return {
      module_id: module.id,
      selected_permission_ids: Array.from(selectedPermissionIds).flat(),
    };
  })
  .filter((module) => module.selected_permission_ids.length > 0);
 // Filter out modules that don't have any selected permissions
        // console.log(modules_with_permissions);
        // return false;
    const role_id = route.params.id;
    const access_token = $localStorage.getItem("bearer_token");
    axios
        .put(
            `/api/role-access/${role_id}`,
            {
                name: role.value.name,
                module_permissions: modules_with_permissions,
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
            router.push(`/role-access/${role_id}`)
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

// const selectAssignedUser = function (selectedOption, id) {
//     user_id.value.push(selectedOption.id);
// };

// const removeAssignedUser = function (removedOption, id) {
//     selected_user.value = selected_user.value.filter((user) => user.id !== removedOption.id);
//     user_id.value       = selected_user.value.filter((user) => user.id !== removedOption.id).map((user) => user.id);
// };

onMounted(() => {
    // getUsers();
    getDetails();
});
</script>

<style lang="scss" scoped></style>
