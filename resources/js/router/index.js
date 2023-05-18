import { createRouter, createWebHistory } from 'vue-router';
import PageNotFound from '../views/PageNotFound.vue';
import Dashboard from '../views/Dashboard.vue';
import Register from '../views/Authentication/Register.vue';
import Login from '../views/Authentication/Login.vue';
import TaskIndex from '../views/Task/Index.vue'
import TaskCreate from '../views/Task/Create.vue'
import TaskShow from '../views/Task/Show.vue'
import TaskEdit from '../views/Task/Edit.vue'

const routes = [
  {
    path: '/:catchAll(.*)', // Match any route that is not defined
    name: 'page-not-found',
    component: PageNotFound
  },
  {
    path: '/',
    name: 'dashboard',
    component: Dashboard,
    meta: { requiresAuth: true } // assuming this route requires authentication
  },
  {
    path: '/register',
    name: 'register',
    component: Register
  },
  {
    path: '/login',
    name: 'login',
    component: Login
  },
  {
    path: '/tasks',
    name: 'tasks',
    component: TaskIndex,
    meta: { requiresAuth: true }
  },
  {
    path: '/task/create',
    name: 'task-create',
    component: TaskCreate,
    meta: { requiresAuth: true }
  },
  {
    path: '/task/:id',
    name: 'task-show',
    component: TaskShow,
    meta: { requiresAuth: true }
  },
  {
    path: '/task/edit/:id',
    name: 'task-edit',
    component: TaskEdit,
    meta: { requiresAuth: true }
  },

];

const router = createRouter({
  history: createWebHistory(),
  routes
});


export default router;
