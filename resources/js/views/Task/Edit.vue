<template>
    <!-- <Navbar /> -->
    <form class="container mx-auto min-h-min w-full  mt-5">
        <div v-if="selected_users.length" class="flex justify-center space-x-1">
            <div
                class="w-20 h-20 rounded-full overflow-hidden"
                v-for="selected_user in selected_users"
                :key="selected_user.id"
            >
                <img
                    :src="selected_user.profile_picture"
                    alt="Profile Picture"
                    class="object-cover w-full h-full"
                />
            </div>
        </div>
        <div class="flex flex-col h-full">
            <div>
                <label for="assignee" class="text-lg">Assignee:</label>
                <multiselect
                    v-model="selected_users"
                    :options="users"
                    :multiple="true"
                    :allow-empty="true"
                    placeholder="Select a user"
                    @select="selectAssignedUser"
                    @remove="removeAssignedUser"
                    label="email"
                    track-by="email"
                    class="mt-2"
                ></multiselect>
            </div>

            <div class="mt-4">
                <label for="title" class="text-lg">Title :</label>
                <input
                    id="title"
                    type="text"
                    placeholder="Enter title"
                    v-model="task.title"
                    class="w-full px-4 py-2 mt-1 border rounded-md"
                />
                <span v-if="errors.title" class="text-red-500">{{
                    errors.title[0]
                }}</span>
            </div>

            <div class="mt-4">
                <label for="description" class="text-lg">Description :</label>
                <textarea
                    id="description"
                    placeholder="Enter description"
                    v-model="task.description"
                    class="w-full px-4 py-2 mt-1 border rounded-md"
                ></textarea>
                <span v-if="errors.description" class="text-red-500">{{
                    errors.description[0]
                }}</span>
            </div>

            <div class="mt-4">
                <label for="due-date" class="text-lg">Due Date :</label>
                <!-- <input
          id="due-date"
          type="date"
          placeholder="Enter due date"
          v-model="task.due_date"
          class="w-full px-4 py-2 mt-1 border rounded-md"
        /> -->
                <date-picker
                    v-model:value="task.due_date"
                    style="
                        width: 100%;
                        height: 2.5rem;
                        margin-top: 0.25rem;                       
                        border-radius: 0.375rem;
                    "
                ></date-picker>
                <span v-if="errors.due_date" class="text-red-500">{{
                    errors.due_date[0]
                }}</span>
            </div>
            <div class="flex justify-end space-x-1 mb-5">
                <!-- <router-link
                    :to="`/task/${task.id}`"
                    class="px-4 py-2 mt-4 font-bold text-white bg-gray-500 rounded-md"
                >
                    Cancel
                </router-link> -->
                <button
                    type="button"
                    @click="closeModal"
                    class="px-4 py-2 mt-4 font-bold text-white bg-blue-500 rounded-md"
                >
                    Close
                </button>
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
import { useTaskStore } from '../../stores/TaskStore.js'
const users = ref([]);
const router = useRouter();
const route = useRoute();
const errors = ref([]);
const task = ref([]);
const user_ids = ref(null);
const selected_users = ref([]);
const $localStorage = inject("$localStorage");

const emit = defineEmits(['close-modal']);
const taskStore = useTaskStore();

const closeModal = function(){
    emit('close-modal',false)
}

const getTasks = () => {
    const access_token = $localStorage.getItem("bearer_token");
    const task_id = route.params.id;
    axios
        .get(`/api/tasks/${task_id}`, {
            headers: {
                Authorization: `Bearer ${access_token}`,
            },
        })
        .then((response) => {
            console.log(response.data);
            task.value = response.data;
            task.value.due_date = new Date(task.value.due_date);
            user_ids.value = response.data.users.map(user => user.id);
            selected_users.value = response.data.users;
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
        })
        .catch((error) => {
            console.log(error);
        });
};

const selectAssignedUser = function (selectedOption, id) {
    user_ids.value = selected_users.value.map((user) => user.id); // Update the user_ids array with the updated selected_users array
};

const removeAssignedUser = function (removedOption, id) {
    selected_users.value = selected_users.value.filter(
        (user) => user.id !== removedOption.id
    );
    user_ids.value = selected_users.value.map((user) => user.id);
};

const update = () => {
    const access_token = $localStorage.getItem("bearer_token");
    // console.log(access_token);
    // return false;
    axios
        .put(
            `/api/tasks/${task.value.id}`,
            {
                title: task.value.title,
                description: task.value.description,
                due_date: task.value.due_date ? new Date(task.value.due_date).toDateString() : null ,
                user_ids: user_ids.value,
            },
            {
                headers: {
                    Authorization: `Bearer ${access_token}`,
                },
            }
        )
        .then((response) => {
            // Check if the response contains success message
            if (response.data) {
                // Store the token in local storage

                showCustomToast("success", "Task Updated!", {
                    // position: 'bottom-right',
                });
                taskStore.$state.updated = Math.random() * 50000;
                // console.log(task.value.id);
                // router.push({ name: '/task/',  params: { id: task.value.id } });
                // router.push(`/task/${task.value.id}`);
                // router.push("/tasks");
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
    getTasks();
    getUsers();
});
</script>

<style lang="scss" scoped></style>
