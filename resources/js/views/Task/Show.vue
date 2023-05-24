<template>
    <div  class="flex  justify-center">
        <div class="flex flex-col justify-between p-4 border rounded-md mb-4">
            <div>
                <label for="user" class="text-lg">User: </label>
                <span v-if="task.user">{{ task.user.username }}</span>
                <span v-else>Not assigned yet!</span>
                <div class="space-y-4">
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
                    <p>
                        <span class="text-lg">Status: </span
                        >{{ task.status_description }}
                    </p>
                </div>
            </div>
            <div class="mt-4 space-x-2">
                <router-link
                    :to="`/task/edit/${task.id}`"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-cyan-300 hover:bg-cyan-400 rounded-md"
                >
                    <svg class="h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"/></svg>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
// import Input from '../../components/Form/Input.vue';
import Label from "../../components/Form/Label.vue";
// import Input from '@js/components/Form/Input.vue';
import axios from "axios";
import { showCustomToast } from "../../Utils/toast.js";

import { useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();
const task = ref({});
const getTasks = () => {
    const access_token = localStorage.getItem("bearer_token");
    const task_id = route.params.id;
    axios
        .get(`/api/tasks/${task_id}`, {
            headers: {
                Authorization: `Bearer ${access_token}`,
            },
        })
        .then((response) => {
            task.value = response.data;
        });
};

onMounted(() => {
    getTasks();
});
</script>

<style lang="scss" scoped></style>
