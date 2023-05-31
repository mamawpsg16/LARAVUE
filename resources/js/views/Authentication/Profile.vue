<template>
    <Navbar />
    <div class="flex items-center justify-center mt-2">
        <div class="flex flex-col">
            <div class="w-40 h-40 rounded-full overflow-hidden">
                <img
                    :src="imagePreviewUrl || user.profile_picture"
                    alt="Profile Picture"
                    class="object-cover w-full h-full"
                />
            </div>
            <div class="flex items-center justify-center mt-4">
                <input type="file" @change="handleFileUpload" />
            </div>
        </div>
    </div>

    <form class="container mx-auto w-3/6 h-full mt-2">
        <div class="flex flex-col h-full">
            <div class="mt-4">
                <label for="title" class="text-lg">Username :</label>
                <input
                    id="title"
                    type="text"
                    placeholder="Enter Username"
                    v-model="user.username"
                    class="w-full px-4 py-2 mt-1 border rounded-md"
                />
                <span class="text-red-500"></span>
            </div>
            <div class="mt-4">
                <label for="title" class="text-lg">Email :</label>
                <input
                    id="title"
                    type="email"
                    placeholder="Enter Email"
                    v-model="user.email"
                    class="w-full px-4 py-2 mt-1 border rounded-md"
                />
                <span class="text-red-500"></span>
            </div>

            <div class="mt-4">
                <label for="due-date" class="text-lg">Birth Date :</label>
                <!-- <input
          id="due-date"
          type="date"
          placeholder="Enter due date"
          v-model="task.due_date"
          class="w-full px-4 py-2 mt-1 border rounded-md"
        /> -->
                <span class="text-red-500"></span>
                <date-picker
                    v-model:value="user.birth_date"
                    style="
                        width: 100%;
                        height: 2.5rem;
                        margin-top: 0.25rem;
                        border-radius: 0.375rem;
                    "
                ></date-picker>
            </div>

            <div class="mt-4">
                <label for="description" class="text-lg">About :</label>
                <textarea
                    id="description"
                    placeholder="Enter description"
                    v-model="user.about"
                    class="w-full px-4 py-2 mt-1 border rounded-md"
                ></textarea>
                <span class="text-red-500"></span>
            </div>

            <div class="flex justify-end">
                <button @click.prevent="updateProfile"
                    class="px-4 py-2 mt-4 font-bold text-white bg-blue-500 rounded-md"
                >
                    Update
                </button>
            </div>
        </div>
    </form>
</template>

<script setup>
import axios from "axios";
import { ref, onMounted, inject } from "vue";
import DatePicker from "vue-datepicker-next";
import { showCustomToast } from "../../Utils/toast.js";
import { useRouter } from 'vue-router';

const $localStorage = inject("$localStorage");
const access_token = $localStorage.getItem("access_token");
const user = ref([]);
const newProfile = ref('');
const imagePreviewUrl = ref('');
const router = useRouter();
const handleFileUpload = function (event) {
    const file = event.target.files[0];
    newProfile.value = file;
    const reader = new FileReader();
    reader.onload = () => {
        imagePreviewUrl.value = reader.result;
    };
    reader.readAsDataURL(file);
};

const getUserDetails = function () {
    let access_token = $localStorage.getItem("access_token");

    axios
        .get("/api/profile", {
            headers: {
                Authorization: `Bearer ${access_token}`,
            },
        })
        .then((response) => {
            user.value = response.data;
            if (user.value.birth_date) {
                user.value.birth_date = new Date(user.value.birth_date);
            }
        })
        .catch((error) => {
        });
};

const updateProfile = function () {
  const formData = new FormData();
  formData.append('username', user.value.username);
  formData.append('email', user.value.email);
  formData.append('about', user.value.about);
  console.log(user.value.birth_date,'BIRTH DATE');
  formData.append('birth_date', user.value.birth_date ? new Date(user.value.birth_date).toDateString() : null);
  formData.append('profile_picture', newProfile.value);

  axios
    .post('/api/profile', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        Authorization: `Bearer ${access_token}`,
      },
    })
    .then((response) => {
        user.value = response.data.user
        console.log(user.value.birth_date);
        if (user.value.birth_date) {
            user.value.birth_date = new Date(user.value.birth_date);
        }
        showCustomToast("success", "Profile Updated!", {});
        // Reset the input file
        const input = document.querySelector('input[type="file"]');
        input.value = '';

        // Reset the image preview
        imagePreviewUrl.value = '';
    })
    .catch((error) => {
      console.log(error.response);
      // Handle error response
    });
};


onMounted(() => {
    getUserDetails();
});
</script>

<style lang="scss" scoped></style>
