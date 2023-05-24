require('./bootstrap');

import { createApp } from 'vue';
import App from './views/App.vue';
import router from './router';
import Toast from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { dateFormat, humanReadable } from './Utils/date.js';
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
// import Loading from './Utils/loading.js';
import auth from './Utils/auth.js';
import { createPinia } from 'pinia'
const pinia = createPinia()
// import { getCookie, setCookie, deleteCookie } from './Utils/cookie.js'
import { setItem, getItem, removeItem } from './Utils/localStorage.js'
import 'vue-multiselect/dist/vue-multiselect.css'
import 'vue-datepicker-next/index.css';
import Navbar from './components/Navbar/navbar.vue'

const app = createApp(App);
app.use(pinia)
app.component('Navbar', Navbar);

app.provide('$localStorage', {
  setItem,
  getItem,
  removeItem
});

// app.config.globalProperties.$cookie = {
//     getCookie,
//     setCookie
// };

app.config.globalProperties.$date = {
    dateFormat,
    humanReadable,
};

// app.config.globalProperties.$showToast = showCustomToast;

NProgress.configure({ easing: 'ease', speed: 400,  showSpinner: false });


router.beforeEach(async (to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    try {
      let isUserAuthenticated = await auth.isUserAuthenticated();
      console.log(isUserAuthenticated,'YES YES YOW');
      if (!isUserAuthenticated) {  
        next('/login'); // User is authenticated, redirect to the dashboard or any other authenticated route
        console.log('DASHBOARD');
      } else {
        next(); // User is not authenticated, proceed to the route
      }
    } catch (error) {
      console.log(error);
      next('/login'); // User is not authenticated, redirect to the login page
    }
  } else if (to.matched.some(record => record.meta.requiresGuest)) {
    console.log('GUEST')
    try {
      let isUserAuthenticated = await auth.isUserAuthenticated();
      if (isUserAuthenticated) {
        next('/'); // User is authenticated, redirect to the dashboard or any other authenticated route
        console.log('DASHBOARD');
      } else {
        next(); // User is not authenticated, proceed to the route
      }
    } catch (error) {
      console.log('ERROR');
      next(); // Handle error and proceed to the route
    }
  }else{
    next(); 
  }
  
  NProgress.set(0.4);
});




router.afterEach((to, from) => {
  NProgress.done();
});
app.use(Toast);
app.use(router).mount('#app');
