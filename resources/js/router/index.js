import { createRouter, createWebHistory } from 'vue-router';
import PageNotFound from '../views/PageNotFound.vue';
import Dashboard from '../views/Dashboard.vue';
import Register from '../views/Authentication/Register.vue';
import Login from '../views/Authentication/Login.vue';
import TaskIndex from '../views/Task/Index.vue'
import TaskCreate from '../views/Task/Create.vue'
import TaskShow from '../views/Task/Show.vue'
import TaskEdit from '../views/Task/Edit.vue'
import Profile from '../views/Authentication/Profile.vue'
import UserRoleIndex from '../views/UserRole/Index.vue'
import UserRoleCreate from '../views/UserRole/Create.vue'
import UserRoleShow from '../views/UserRole/Show.vue'
import UserRoleEdit from '../views/UserRole/Edit.vue'
import ModuleIndex from '../views/Module/Index.vue'
import ModuleCreate from '../views/Module/Create.vue'
import ModuleShow from '../views/Module/Show.vue'
import ModuleEdit from '../views/Module/Edit.vue'
import PermissionIndex from '../views/Permission/Index.vue'
import PermissionCreate from '../views/Permission/Create.vue'
import PermissionShow from '../views/Permission/Show.vue'
import PermissionEdit from '../views/Permission/Edit.vue'
import RoleAccessIndex from '../views/RoleAccess/Index.vue'
import RoleAccessCreate from '../views/RoleAccess/Create.vue'
import RoleAccessShow from '../views/RoleAccess/Show.vue'
import RoleAccessEdit from '../views/RoleAccess/Edit.vue'
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
    component: Register,
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { requiresGuest: true }
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
  {
    path: '/profile',
    name: 'profile',
    component: Profile,
    meta: { requiresAuth: true }
  },
  {
    path: '/user-roles',
    name: 'user-roles',
    component: UserRoleIndex,
    meta: { requiresAuth: true }
  },
  {
    path: '/user-role/create',
    name: 'user-role-create',
    component: UserRoleCreate,
    meta: { requiresAuth: true }
  },
  {
    path: '/user-role/:id',
    name: 'user-role-show',
    component: UserRoleShow,
    meta: { requiresAuth: true }
  },
  {
    path: '/user-role/edit/:id',
    name: 'user-role-edit',
    component: UserRoleEdit,
    meta: { requiresAuth: true }
  },
  {
    path: '/modules',
    name: 'modules',
    component: ModuleIndex,
    meta: { requiresAuth: true }
  },
  {
    path: '/module/create',
    name: 'module-create',
    component: ModuleCreate,
    meta: { requiresAuth: true }
  },
  {
    path: '/module/:id',
    name: 'module-show',
    component: ModuleShow,
    meta: { requiresAuth: true }
  },
  {
    path: '/module/edit/:id',
    name: 'module-edit',
    component: ModuleEdit,
    meta: { requiresAuth: true }
  },
  {
    path: '/permissions',
    name: 'permissions',
    component: PermissionIndex,
    meta: { requiresAuth: true }
  },
  {
    path: '/permission/create',
    name: 'permission-create',
    component: PermissionCreate,
    meta: { requiresAuth: true }
  },
  {
    path: '/permission/:id',
    name: 'permission-show',
    component: PermissionShow,
    meta: { requiresAuth: true }
  },
  {
    path: '/permission/edit/:id',
    name: 'permission-edit',
    component: PermissionEdit,
    meta: { requiresAuth: true }
  },
  {
    path: '/role-access',
    name: 'role-access',
    component: RoleAccessIndex,
    meta: { requiresAuth: true }
  },
  {
    path: '/role-access/create',
    name: 'role-access-create',
    component: RoleAccessCreate,
    meta: { requiresAuth: true }
  },
  {
    path: '/role-access/:id',
    name: 'role-access-show',
    component: RoleAccessShow,
    meta: { requiresAuth: true }
  },
  {
    path: '/role-access/edit/:id',
    name: 'role-access-edit',
    component: RoleAccessEdit,
    meta: { requiresAuth: true }
  },

];

const router = createRouter({
  history: createWebHistory(),
  routes
});


export default router;
