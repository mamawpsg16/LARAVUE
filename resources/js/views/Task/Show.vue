<template>
    <div>
        <h1>{{ task.title }}</h1>
        <p>{{ task.title }}</p>
        <p>{{ task.description }}</p>
        <p>{{ $date.humanReadable(task.due_date) }}</p>
        <router-link :to="`/task/edit/${task.id}`">Edit</router-link> &nbsp;
        <button @click="deleteTask">Delete</button>
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
import Swal from "../../Utils/swal.js";
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

const deleteTask = async function () {
    const access_token = localStorage.getItem("bearer_token");

    Swal("Delete Task!", "Are you really really sure?", "warning", "Yes", {
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
                .delete(`/api/tasks/${task.value.id}`, {
                    headers: {
                        Authorization: `Bearer ${access_token}`,
                    },
                })
                .then((response) => {
                    showCustomToast("success", "Task Deleted!");
                    router.push({ name: "tasks" });
                    console.log(response);
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
</script>

<style lang="scss" scoped></style>
