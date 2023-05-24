<template>
  <Navbar />
  <div class="flex justify-center mt-5">
    <div class="w-1/2">
      <h2 class="text-center text-2xl font-bold mb-4">REGISTER</h2>
      <p v-if="isRegistered" class="text-center text-green-500">{{ success_message }}</p>
      <Form>
        <div class="mb-4">
          <Label label="Username" />
          <Input
            placeholder="Enter your firstname"
            v-model="user_account.username"
            name="username"
            class="w-full px-4 py-2 mt-1 border rounded-md"
          />
          <p v-if="errors.username" class="text-red-500">{{ errors.username[0] }}</p>
        </div>
        <div class="mb-4">
          <Label label="Email" />
          <Input
            placeholder="Enter your email"
            v-model="user_account.email"
            name="email"
            class="w-full px-4 py-2 mt-1 border rounded-md"
          />
          <p v-if="errors.email" class="text-red-500">{{ errors.email[0] }}</p>
        </div>
        <div class="mb-4">
          <Label label="Password" />
          <Input
            type="password"
            placeholder="Enter your password"
            v-model="user_account.password"
            name="password"
            class="w-full px-4 py-2 mt-1 border rounded-md"
          />
          <p v-if="errors.password" class="text-red-500">{{ errors.password[0] }}</p>
        </div>
        <div class="mb-4">
          <Label label="Password Confirmation" />
          <Input
            type="password"
            placeholder="Enter password confirmation"
            v-model="user_account.password_confirmation"
            name="password_confirmation"
            class="w-full px-4 py-2 mt-1 border rounded-md"
          />
          <p v-if="errors.password_confirmation" class="text-red-500">{{ errors.password_confirmation[0] }}</p>
        </div>
        <div class="flex justify-end">
          <button
            type="button"
            @click.prevent="register"
            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
          >
            Register
          </button>
        </div>
      </Form>
    </div>
  </div>
</template>

<script setup>
import Form from "../../components/Form/Form.vue";
import Label from "../../components/Form/Label.vue";
import Input from "../../components/Form/Input.vue";
import { ref, onMounted, reactive, computed, onBeforeUnmount } from "vue";
import { useRouter } from 'vue-router';
import axios from "axios";
import showCustomToast from "../../Utils/toast";
const router = useRouter();
const user_account = reactive({
  username: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const users = ref([]);
const count = ref(0);
const errors = ref([]);
const success_message = ref('');
/** METHODS */
// const getUsers = () =>
//   axios.get("/api/shit").then((response) => {
//     users.value = response.data;
//   });

const increment = () => count.value++;
const decrement = () => (count.value > 0 ? count.value-- : count.value);

const register = () => {
  axios
    .post("/api/register", user_account)
    .then((response) => {
      // Check if the response contains success message
      const { success } = response.data;
      if (success) {
        showCustomToast("success", "You are now registered, Try logging in!", {});
        // success_message.value = response.data.success
        router.push({ name: 'dashboard' });
        // Success, redirect or show a success message
      }
    })
    .catch((error) => {
      console.log(error.response);
      // Handle request error
      if (error.response?.status === 422) {
        // Handle validation errors
        errors.value = error.response.data.errors;
      } else {
        // Handle other errors
        console.log(error);
      }
    });
};

/** COMPUTED */
const hasErrors = computed(() => {
  return Object.keys(errors.value).length > 0;
});

const isRegistered = computed(() =>{
    return (success_message.value && !hasErrors.value);
})
/** LIFECYCLE HOOKS */
// onBeforeUnmount(resetUserAccountState);

onMounted(() => {
  //   getUsers();
  //   resetUserAccountState();
});
</script>   

<style lang="scss" scoped>
</style>