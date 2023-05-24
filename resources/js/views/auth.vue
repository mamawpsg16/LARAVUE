// import axios from 'axios';

// const apiClient = axios.create({
//   withCredentials: true, // important for Sanctum CSRF protection
// });
<script setup>
import { ref } from 'vue';
import { getCookie } from '../Utils/cookie.js';
import { onMounted } from 'vue';

const auth = ref([]);
const getAuth = function() {
  auth.value = [
    getCookie('access_token'),
    getCookie('user_id')
  ];
};

onMounted(() =>{
  getAuth();
})

</script>

 
  // checkAuth() {
  //   const token = localStorage.getItem('bearer_token'); // Retrieve the bearer token from storage
  //   if (token) {
  //     apiClient.defaults.headers.common['Authorization'] = `Bearer ${token}`;
  //     return apiClient.get('/api/user');
  //   } else {
  //     return Promise.reject(new Error('Bearer token not available'));
  //   }
  // },
  
  // logout() {
  //   // Get the bearer token from local storage
  //   const bearerToken = localStorage.getItem('bearer_token');
  //   console.log(bearerToken);
  //   // Clear the bearer token from local storage
  //   localStorage.removeItem('bearer_token');

  //   // Remove the bearer token from the default headers
  //   delete apiClient.defaults.headers.common['Authorization'];

  //   // Optional: Make a request to the backend to revoke the access token
  //   return apiClient.post('/api/logout', null, {
  //     headers: {
  //       Authorization: `Bearer ${bearerToken}`,
  //     },
  //   });
  // }

