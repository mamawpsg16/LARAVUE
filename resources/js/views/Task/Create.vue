<template>
    <Navbar />
    <form class="container mx-auto w-3/6 h-full mt-2">
        <div
            v-show="selected_user.profile_picture"
            class="flex items-center justify-center"
        >
            <div class="flex flex-col">
                <div class="w-40 h-40 rounded-full overflow-hidden">
                    <img
                        :src="selected_user.profile_picture"
                        alt="Profile Picture"
                        class="object-cover w-full h-full"
                    />
                </div>
            </div>
        </div>

        <div class="flex flex-col h-full">
            <div>
                <label for="assignee" class="text-lg">Assignee:</label>
                <multiselect
                    v-model="selected_user"
                    :options="users"
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
import Multiselect from "vue-multiselect";
import DatePicker from "vue-datepicker-next";

const date = ref(new Date());
const router = useRouter();
const errors = ref([]);
const selected_user = ref([]);
const task = reactive({
    title: "",
    description: "",
    due_date: new Date(),
});
const user_id = ref(null);
const users = ref([]);
const $localStorage = inject("$localStorage");

const store = () => {
    const access_token = $localStorage.getItem("bearer_token");

    axios
        .post(
            "/api/tasks",
            {
                title: task.title,
                description: task.description,
                due_date: new Date(task.due_date).toDateString(),
                user_id: user_id.value,
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

                showCustomToast("success", "Task Created!", {
                    // position: 'bottom-right',
                });
                router.push({ name: "tasks" });
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

// const removeAssignedUser = function(removedOption, id){
// }

const selectAssignedUser = function (selectedOption, id) {
    user_id.value = selectedOption.id;
};

const removeAssignedUser = function (removedOption, id) {
    selected_user.value = [];
};

onMounted(() => {
    getUsers();
});
</script>

<style lang="scss" scoped></style>
