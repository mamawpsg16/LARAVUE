<template>
    <Navbar/>
    <div class="p-4">
        <h1 class="text-2xl font-bold text-center">TASKS</h1>
        <!-- <button @click="toggleModal">Modal</button> -->
        <div class="flex justify-between items-center mb-4">
            <div class="flex space-x-2">
                <div class="mt-4 mr-4">
                    <label for="filter" class="mr-2">Filter:</label>
                    <select
                        v-model="filter"
                        id="filter"
                        class="px-2 py-1 border rounded-md"
                        @change="filterStatus"
                    >
                        <option value="all">All</option>
                        <option value="0">Pending</option>
                        <option value="1">Ongoing</option>
                        <option value="2">Complete</option>
                        <option value="3">Cancelled</option>
                    </select>
                </div>

                <div class="mt-4">
                    <label for="sortBy" class="mr-2">Sort By:</label>
                    <select
                        v-model="sortBy"
                        id="sortBy"
                        class="px-2 py-1 border rounded-md"
                    >
                        <option value="default">Default</option>
                        <option value="title">Title</option>
                        <option value="due_date">Due Date</option>
                    </select>
                </div>
                <div class="mt-4">
                    <label for="sortBy" class="mr-2">Sort</label>
                    <select
                        v-model="sort"
                        id="sort"
                        class="px-2 py-1 border rounded-md"
                    >
                        <option value="desc">Descending</option>
                        <option value="asc">Ascending</option>
                    </select>
                </div>
            </div>
            <router-link
                to="/task/create"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-md"
            >
                <svg
                    class="h-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 448 512"
                >
                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path
                        d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"
                    />
                </svg>
            </router-link>
        </div>
        <div class="flex justify-end">
                <p class="text-lg">Count: {{ tasks.length }}</p>
        </div>
        <template v-if="tasks.length">
            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4"
            >
                <div
                    v-for="task in tasks"
                    :key="task.id"
                    class="flex flex-col justify-between p-4 border rounded-md"
                >
                    <div>
                        <div class="flex justify-between">
                            <div>
                                <label for="user" class="text-lg">User: </label>
                                <span v-if="task.user">{{
                                    task.user.username
                                }}</span>
                                <span v-else>Not assigned yet!</span>
                            </div>
                            <select
                                v-model="task.status"
                                id="filter"
                                class="px-2 py-1 border rounded-md"
                                @change="updateTaskStatus(task.id, task.status)"
                            >
                                <option value="0">Pending</option>
                                <option value="1">Ongoing</option>
                                <option value="2">Complete</option>
                                <option value="3">Cancelled</option>
                            </select>
                        </div>
                        <div class="space-y-3">
                            <p class="mt-2">
                                <span class="text-lg">Title: </span>{{ task.title }}
                            </p>
                            <p>
                                <span class="text-lg">Description: </span
                                >{{ task.description }}
                            </p>
                            <p>
                                <span class="text-lg">Due Date: </span
                                >{{ $date.humanReadable(task.due_date) }}
                            </p>
                            <!-- <p class="text-right">
                                <span class="text-lg">Status: </span
                                >{{ task.status_description }}
                            </p> -->
                        </div>
                    </div>
                    <div class="mt-3  flex justify-end">
                        <div class="space-x-2">
                            <router-link
                                :to="`/task/${task.id}`"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-400 hover:bg-gray-500 rounded-md"
                            >
                                <svg
                                    class="h-5"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 576 512"
                                >
                                    <path
                                        d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"
                                    />
                                </svg>
                            </router-link>
                            <!-- <router-link
                                :to="`/task/edit/${task.id}`"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-cyan-500 hover:bg-cyan-600 rounded-md"
                            >
                                <svg class="h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"/></svg>
                            </router-link> -->
                            <button
                                @click="deleteTask(task.id)"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-md"
                            >
                                <svg
                                    class="h-5"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 448 512"
                                >
                                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <path
                                        d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <p v-else class="mt-4">No Tasks Yet...</p>
    </div>
    <!-- <modal title="Filter"  :isModalOpen="isModalOpen">
        <label for="assignee" class="text-lg">Assignee:</label>
                <multiselect
                    v-model="selected_user"
                    :options="users"
                    :allow-empty="true"
                    placeholder="Select a user"
                    label="email"
                    track-by="email"
                    class="mt-2"
                ></multiselect>
    </modal> -->
</template>

<script setup>
import { ref, onMounted, watch, inject } from "vue";
import axios from "axios";
import Label from "../../components/Form/Label.vue";
import Swal from "../../Utils/swal.js";
import { showCustomToast } from "../../Utils/toast.js";
import { useRouter } from "vue-router";
import modal from '../../components/modal.vue'
import Multiselect from "vue-multiselect";

const router = useRouter();
const users = ref()
const tasks = ref([]);
const filter = ref("all"); // Default filter option
const sortBy = ref("default"); // Default sort option
const sort = ref("desc"); // Default sort option
const $localStorage = inject("$localStorage");
const all_tasks = ref([]);
const access_token = localStorage.getItem("bearer_token");
const isModalOpen = ref(false);
const getTasks = () => {
    const access_token = $localStorage.getItem("bearer_token");
    const user_id = $localStorage.getItem("user_id");
    const url = "/api/tasks";
    const params = {
        // filter: filter.value,
        sort: sort.value,
        sortBy: sortBy.value,
        user_id: user_id,
    };
    axios
        .get(url, {
            headers: {
                Authorization: `Bearer ${access_token}`,
            },
            params: params,
        })
        .then((response) => {
            tasks.value = response.data;
        });
};
const getUpdatedTaskDescription = (id) => {
    const status = [
        { id: 0, status: "Pending" },
        { id: 1, status: "Ongoing" },
        { id: 2, status: "Complete" },
        { id: 3, status: "Cancelled" },
    ];

    const task_description = status.filter((status) => status.id == id);
    return task_description[0].status;
};
const updateTaskStatus = function (id, status) {
    axios
        .post(
            "/api/updateTaskStatus",
            { id: id, status: status },
            { headers: { Authorization: `Bearer ${access_token}` } }
        )
        .then((response) => {
            const updatedTask = tasks.value.filter((task) => task.id == id);

            const status = getUpdatedTaskDescription(updatedTask[0].status);
            updatedTask[0].status_description = status;
            showCustomToast("success", "Task Status Updated!");
            // router.push({ name: "tasks" });
            // console.log(response);
        })
        .catch((error) => {
            console.log(error);
        });
};

const filterStatus = function (e) {
    if (all_tasks.value.length === 0) {
        // Store the original tasks array if it hasn't been stored yet
        all_tasks.value = tasks.value;
    }
    console.log(e.target.value, e.target.value != 'all');
    if(e.target.value != 'all'){
      const filtered_tasks = all_tasks.value.filter(task => parseInt(task.status) === parseInt(e.target.value));
     return tasks.value = filtered_tasks;
    }
    tasks.value = all_tasks.value;
  
};

// const toggleModal = function () {
//     isModalOpen.value = !isModalOpen.value;
// };
const deleteTask = function (task_id) {
    Swal("Delete Task!", "Are you sure?", "warning", "Yes", {
        showCancelButton: true,
        cancelButtonText: "No",
        customClass: {
            confirmButton: "btn-success",
            cancelButton: "btn-danger",
        },
    }).then((result) => {
        if (result.value) {
            const access_token = localStorage.getItem("bearer_token");
            axios
                .delete(`/api/tasks/${task_id}`, {
                    headers: {
                        Authorization: `Bearer ${access_token}`,
                    },
                })
                .then((response) => {
                    // Remove the deleted task from the tasks array
                    tasks.value = tasks.value.filter(
                        (task) => task.id !== task_id
                    );
                    showCustomToast("success", "Task Deleted!");
                    // router.push({ name: "tasks" });
                    // console.log(response);
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    });
};

onMounted(() => {
    getTasks();
});

// Watch for changes in filter and sortBy options
watch([sortBy, sort], () => {
    getTasks();
});
</script>
<style scoped>
.task-container {
    margin-top: 15px;
}
</style>
