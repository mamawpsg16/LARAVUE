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



const app = createApp(App);
// app.component('Loading', Loading);
// const loading = ref(false);

app.config.globalProperties.$date = {
    dateFormat,
    humanReadable,
};
// app.config.globalProperties.$showToast = showCustomToast;

NProgress.configure({ easing: 'ease', speed: 400,  showSpinner: false });


// router.beforeResolve((to, from, next) => {
//   if (to.name) {
//     // loading.value = true;
//     NProgress.start().set(0.4);
//   }
//   next();
// });
router.beforeEach(async (to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
      // Check if the user is authenticated
      try {
        await auth.checkAuth();
        next(); // User is authenticated, proceed to the route
      } catch (error) {
        next('/login'); // User is not authenticated, redirect to the login page
      }
    } else {
      next(); // Route does not require authentication, proceed to the route
    }
});
router.beforeEach(async (to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // Check if the user is authenticated
    if (to.name === 'login') {
      next(); // Skip authentication check for the login route
    } else {
      try {
        await auth.checkAuth();
        next(); // User is authenticated, proceed to the route
      } catch (error) {
        next('/login'); // User is not authenticated, redirect to the login page
      }
    }
  } else {
    next(); // Route does not require authentication, proceed to the route
  }
  NProgress.set(0.4);
});


router.afterEach((to, from) => {
  NProgress.done();
});
app.use(Toast);
app.use(router).mount('#app');
