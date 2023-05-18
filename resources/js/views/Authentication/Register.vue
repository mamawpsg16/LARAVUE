<template>
  <div>
    REGISTER
    <p v-if="isRegistered"> {{  success_message }}</p>
    <Form>
      <div>
        <Label label="Username " />
        <Input
          placeholder="Enter your firstname"
          v-model="user_account.username"
          name="username"
        />
        <p v-if="errors.username">{{ errors.username[0] }}</p>
      </div>
      <div>
        <Label label="Email " />
        <Input
          placeholder="Enter your email"
          v-model="user_account.email"
          name="email"
        />
        <p v-if="errors.email">{{ errors.email[0] }}</p>
      </div>
      <div>
        <Label label="Password " />
        <Input
          type="password"
          placeholder="Enter your password"
          v-model="user_account.password"
          name="password"
        />
        <p v-if="errors.password">{{ errors.password[0] }}</p>
      </div>
      <div>
        <Label label="Password Confirmation " />
        <Input
          type="password"
          placeholder="Enter password confirmation"
          v-model="user_account.password_confirmation"
          name="password_confirmation"
        />
        <p v-if="errors.password_confirmation">
          {{ errors.password_confirmation[0] }}
        </p>
      </div>
      <button type="button" @click.prevent="register">Register</button>
    </Form>
    <!-- <template v-if="hasErrors">
      <div v-for="(fieldErrors, fieldName) in errors" :key="fieldName"> -->
        <!-- {{ fieldErrors }} -->
        <!-- <p v-for="error in fieldErrors" :key="error">
          {{ error }}
        </p>
      </div>
    </template> -->
    <!-- <ul>
      <li v-for="user in users" :key="user.id">
        {{ user.email }}
      </li>
    </ul> -->
    <!-- <p>{{ count }}</p>
    <button @click="increment">Increment</button>
    <button @click="decrement">Increment</button> -->
  </div>
</template>

<script setup>
import Form from "../../components/Form/Form.vue";
import Label from "../../components/Form/Label.vue";
import Input from "../../components/Form/Input.vue";
import { ref, onMounted, reactive, computed, onBeforeUnmount } from "vue";
import { useRouter } from 'vue-router';
import axios from "axios";
import { CLOSING } from "ws";

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
      const { success, access_token } = response.data;
      if (success) {
        console.log('shit');
        localStorage.setItem('bearer_token', access_token);
        // success_message.value = response.data.success
        router.push({ name: 'dashboard' });
        // Success, redirect or show a success message
      }
    })
    .catch((error) => {
      // Handle request error
      if (error.response.status === 422) {
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