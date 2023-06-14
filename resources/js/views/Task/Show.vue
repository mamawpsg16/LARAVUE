<template>
    <div>
        <Navbar />
        <BaseModal v-model="isModalOpen">
            <template #modal-title>Edit : {{ task.title }}</template>
            <edit @close-modal="emitCloseModal"></edit>
        </BaseModal>
        <template v-if="task_exists">
            <div class="flex flex-col items-center justify-center mt-8">
                <div class="flex justify-between w-2/4">
                    <router-link
                        to="/tasks"
                        class="px-4 py-2 text-white bg-amber-400 rounded-md hover:bg-amber-500"
                    >
                        <i class="fa-solid fa-arrow-left"></i>
                    </router-link>
                    <button
                        @click="toggleModal"
                        class="px-4 py-2 text-white  rounded-md text-white bg-blue-500 hover:bg-blue-600 rounded-md"
                    >
                        <svg
                            class="h-4"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512"
                        >
                            <path
                                d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"
                            />
                        </svg>
                    </button>
                </div>
                <div class="bg-white rounded-lg shadow-lg space-y-2 w-2/4">
                    <div
                        v-if="task.users?.length"
                        class="flex justify-center space-x-1"
                    >
                        <div
                            class="w-20 h-20 rounded-full overflow-hidden"
                            v-for="user in task.users"
                            :key="user.id"
                        >
                            <img
                                :src="user.profile_picture"
                                alt="Profile Picture"
                                class="object-cover w-full h-full"
                            />
                        </div>
                    </div>
                    <div class="space-y-4 mt-4">
                        <label for="user" class="text-lg">User/s: </label>
                        <span v-if="task.users?.length">{{
                            getUsersAsString(task.users)
                        }}</span>
                        <span v-else>Not assigned yet!</span>
                    </div>
                    <div class="space-y-4 mt-4">
                        <p>
                            <span class="text-lg">Title: </span>{{ task.title }}
                        </p>
                        <p>
                            <span class="text-lg">Description: </span>
                            <span class="text-center">
                                {{ task.description }}
                            </span>
                        </p>
                        <p>
                            <span class="text-lg">Due Date: </span
                            >{{ $date.humanReadable(task.due_date) }}
                        </p>
                        <p>
                            <span class="text-lg">Status: </span
                            >{{ task.status_description }}
                        </p>
                        <comment
                            :task_id="task.id"
                            :comments="task.comments"
                        ></comment>
                    </div>
                    <div class="flex justify-end p-4 bg-gray-100 rounded-b-lg">
                        <!-- <router-link
                            :to="`/task/edit/${task.id}`"
                            class="px-4 py-2 text-white bg-cyan-500 rounded-md hover:bg-cyan-600"
                        >
                            <svg
                                class="h-5"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 512 512"
                            >
                                <path
                                    d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"
                                />
                            </svg>
                        </router-link> -->
                    </div>
                </div>
            </div>
        </template>
        <div v-else class="min-h-screen flex justify-center items-center">
            <div class="font-bold">TASK NOT FOUND !</div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, reactive } from "vue";
import { useRoute } from "vue-router";
// import Input from '../../components/Form/Input.vue';
import Label from "../../components/Form/Label.vue";
// import Input from '@js/components/Form/Input.vue';
import axios from "axios";
import { showCustomToast } from "../../Utils/toast.js";
import Edit from "./Edit.vue";
import BaseModal from "../../components/BaseModal.vue";
import { useRouter } from "vue-router";
import { useTaskStore } from "../../stores/TaskStore.js";
// import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import comment from "./Comments.vue";
import PageNotFound from "../PageNotFound.vue";

// const editor = ClassicEditor;
// const $localStorage = inject("$localStorage");
// const access_token = $localStorage.getItem("access_token");
const editorData = ref("");
const editorConfig = reactive({});
const taskStore = useTaskStore();
const isModalOpen = ref(false);
const toggleModal = function () {
    isModalOpen.value = !isModalOpen.value;
};

const emitCloseModal = (data) => {
    isModalOpen.value = data;
};
const defaultImage =
    "http://laravue-practical.local/storage/defaults/no-profile.png";
const route = useRoute();
const router = useRouter();
const task_exists = ref(true);
const task = ref({});
const getTasks = async (task_id) => {
    try {
        const access_token = localStorage.getItem("bearer_token");
        const response = await axios.get(`/api/tasks/${task_id}`, {
            headers: {
                Authorization: `Bearer ${access_token}`,
            },
        });
        task.value = response.data;
    } catch (error) {
        if (error.response && error.response.status == 404) {
            task_exists.value = false;
        }
    }
};


const getUsersAsString = function (users) {
    return users.map((user) => user.username).join(", ");
};

watch(
    () => route.params.id,
    (newTaskId) => {
        getTasks(newTaskId);
    }
);

watch(
    () => taskStore.$state.updated,
    (newValue) => {
        if (newValue) {
            getTasks(route.params.id);
        }
    }
);

onMounted(() => {
    const initialTaskId = route.params.id;
    getTasks(initialTaskId);
    // getUserDetails();
});
</script>

<style lang="scss" scoped></style>
