<template>
    <Navbar />
    <div class="flex justify-center mt-5">
      <div class="w-1/2">
        <h2 class="text-center text-2xl font-bold mb-4">LOGIN</h2>
        <p v-if="isLoggedIn" class="text-center text-green-500">{{ success_message }}</p>
        <Form>
          <div>
            <Label label="Email" />
            <Input
              placeholder="Enter your email"
              v-model="user_account.email"
              name="email"
              class="w-full px-4 py-2 mt-1 border rounded-md"
            />
            <span v-if="errors.email" class="error">{{ errors.email[0] }}</span>
          </div>
          <div>
            <Label label="Password" />
            <Input
              type="password"
              placeholder="Enter your password"
              v-model="user_account.password"
              name="password"
              class="w-full px-4 py-2 mt-1 border rounded-md"
            />
            <span v-if="errors.password" class="error">{{ errors.password[0] }}</span>
          </div>
          <div class="flex justify-end">
              <button
                type="button"
                @click.prevent="login"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mt-2"
              >
                Login
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
import { useRouter } from "vue-router";
import { ref, onMounted, reactive, computed, onBeforeUnmount, inject } from "vue";
import axios from "axios";
import { useAuthStore } from "../../stores/Auth.js";
// import { setCookie } from "../../Utils/cookie.js";

// import { toast } from 'vue3-toastify';
import { showCustomToast } from "../../Utils/toast.js";

const storeAuthDetails = useAuthStore();
const $localStorage = inject('$localStorage');

const user_account = reactive({
    email: "",
    password: "",
});

const users = ref([]);
const errors = ref([]);
const success_message = ref("");
const router = useRouter();

// const showAlert = function() {
//   $swal.fire({
//       title: 'Error!',
//       text: 'Do you want to continue',
//       icon: 'error',
//       confirmButtonText: 'Cool'
//   })
//   }
// const showToast = function() {
//   toast.success("Wow so easy !", {
//       autoClose: 1000,
//       limit: 2,
//       pauseOnHover:false
//     }); // To
// }

const login = () => {
    axios
        .post("/api/login", user_account)
        .then((response) => {
            console.log(response);
            const { user_id, access_token } = response.data;
            $localStorage.setItem("user_id", user_id);
            $localStorage.setItem("access_token", access_token);
            showCustomToast("success", "You are now logged in!", {});
            router.push({ name: "dashboard" });
        })
        .catch((error) => {
            console.log(error.response);
            // Handle request error
            if (error.response?.status === 422) {
                // Handle validation errors
                console.log("ERROR");
                console.log(error.response.data.errors);
                console.log("SHIT");
               
                console.log(error.response.data.errors);
                errors.value = error.response.data.errors;
            }else if(error.response?.status === 401){
                if (error.response.data.errors.not_exists?.length) {
                    errors.value = [];
                    showCustomToast(
                        "error",
                        error.response.data.errors.not_exists,
                        {}
                    );
                }
            }else {
                // Handle other errors
                console.log(error);
            }
        });
};

onMounted(() => {
    console.log(storeAuthDetails.$state.auth_user);
});
/** COMPUTED */
const hasErrors = computed(() => {
    return Object.keys(errors.value).length > 0;
});

const isLoggedIn = computed(() => {
    return success_message.value && !hasErrors.value;
});
</script>

<style scoped>
.error {
    color: red;
}
</style>
