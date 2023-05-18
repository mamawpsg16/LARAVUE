<template>
    <div>
      LOGIN
      <p v-if="isLoggedIn"> {{  success_message }}</p>
      <Form>
        <div>
          <Label label="Email " />
          <Input
            placeholder="Enter your email"
            v-model="user_account.email"
            name="email"
          />
          <br/>
          <span v-if="errors.email" class="error">{{ errors.email[0] }}</span>
        </div>
        <div>
          <Label label="Password " />
          <Input
            type="password"
            placeholder="Enter your password"
            v-model="user_account.password"
            name="password"
          />
          <br/>
          <span v-if="errors.password" class="error">{{ errors.password[0] }}</span>
        </div>
        <button type="button" @click.prevent="login">Login</button>
        <button @click.prevent="showAlert">Show Alert</button>
        <button @click.prevent="showToast">Show Toast</button>
      </Form>
    </div>
  </template>
  
  <script setup>
  import Form from "../../components/Form/Form.vue";
  import Label from "../../components/Form/Label.vue";
  import Input from "../../components/Form/Input.vue";
  import { useRouter } from 'vue-router'
  import { ref, onMounted, reactive, computed, onBeforeUnmount } from "vue";
  import axios from "axios";

  // import { toast } from 'vue3-toastify';
  import { showCustomToast } from '../../Utils/toast.js';


  const user_account = reactive({
    email: "",
    password: "",
  });
  
  const users = ref([]);
  const errors = ref([]);
  const success_message = ref('');
  const router = useRouter()

  const showAlert = function() {
    $swal.fire({
        title: 'Error!',
        text: 'Do you want to continue',
        icon: 'error',
        confirmButtonText: 'Cool'
    })
    }
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
        const { success, access_token } = response.data;
        // Check if the response contains success message
        if (success) {
            console.log('SHIT');
            // Store the token in local storage
            localStorage.setItem('bearer_token', access_token);
            showCustomToast('success', 'You are now logged in!', {
            });
            router.push({ name: 'dashboard' });
          // Success, redirect or show a success message
        }
      })
      .catch((error) => {
        console.log(error.response);
        // Handle request error
        if (error.response?.status === 422) {
          // Handle validation errors
          console.log('ERROR');
          console.log(error.response.data.errors);
          console.log('SHIT');
          if(error.response.data.errors.not_exists?.length){
            showCustomToast('error',error.response.data.errors.not_exists,{ })
          }
          console.log(error.response.data.errors);
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
  
  const isLoggedIn = computed(() =>{
      return (success_message.value && !hasErrors.value);
  })
  </script>   
  
  <style scoped>
  .error{
    color:red;
  }
  </style>