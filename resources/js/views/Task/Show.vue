<template>
    <div>
      <Navbar />
      <div class="flex justify-center mt-8">
        <div class="w-3/4 bg-white rounded-lg shadow-lg">
          <div class="p-8">
            <div v-if="task.user">
              <p class="text-lg">
                <span class="font-semibold">Username: </span>{{ task.user.username }}
              </p>
            </div>
            <div v-else>
              <p class="text-lg">Not assigned yet!</p>
            </div>
            <div class="space-y-4 mt-4">
              <p>
                <span class="text-lg font-semibold">Title: </span>{{ task.title }}
              </p>
              <p>
                <span class="text-lg font-semibold">Description: </span>{{ task.description }}
              </p>
              <p>
                <span class="text-lg font-semibold">Due Date: </span>{{ $date.humanReadable(task.due_date) }}
              </p>
              <p>
                <span class="text-lg font-semibold">Status: </span>{{ task.status_description }}
              </p>
            </div>
          </div>
          <div class="flex justify-end p-4 bg-gray-100 rounded-b-lg">
            <router-link
              :to="`/task/edit/${task.id}`"
              class="px-4 py-2 text-white font-semibold bg-cyan-500 rounded-md hover:bg-cyan-600"
            >
              Edit Task
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </template>
  
<script setup>
import { ref, onMounted, watch } from "vue";
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
const getTasks = (task_id) => {
    const access_token = localStorage.getItem("bearer_token");
    // const task_id = route.params.id;
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

watch(() => route.params.id, (newTaskId) => {
    getTasks(newTaskId);
});

onMounted(() => {
    const initialTaskId = route.params.id;
    getTasks(initialTaskId);
});
</script>

<style lang="scss" scoped></style>
