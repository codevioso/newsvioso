import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { createRouter, createWebHistory } from 'vue-router';
import App from './components/App.vue';
import { useAuthStore } from './stores/auth';
import { useThemeStore } from './stores/theme';

// Import pages
import Login from './pages/auth/Login.vue';
import ForgotPassword from './pages/auth/ForgotPassword.vue';
import ResetPassword from './pages/auth/ResetPassword.vue';
import Dashboard from './pages/Dashboard.vue';
import Profile from './pages/profile/Profile.vue';
import ProfileUpdate from './pages/profile/ProfileUpdate.vue';
import ChangePassword from './pages/profile/ChangePassword.vue';

const routes = [
    {
        path: '/secure/administrator',
        redirect: '/secure/administrator/dashboard'
    },
    {
        path: '/secure/administrator/login',
        name: 'login',
        component: Login,
        meta: { requiresGuest: true }
    },
    {
        path: '/secure/administrator/forgot-password',
        name: 'forgot-password',
        component: ForgotPassword,
        meta: { requiresGuest: true }
    },
    {
        path: '/secure/administrator/reset-password',
        name: 'reset-password',
        component: ResetPassword,
        meta: { requiresGuest: true }
    },
    {
        path: '/secure/administrator/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    },
    {
        path: '/secure/administrator/profile',
        name: 'profile',
        component: Profile,
        meta: { requiresAuth: true }
    },
    {
        path: '/secure/administrator/profile/update',
        name: 'profile-update',
        component: ProfileUpdate,
        meta: { requiresAuth: true }
    },
    {
        path: '/secure/administrator/profile/change/password',
        name: 'change-password',
        component: ChangePassword,
        meta: { requiresAuth: true }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Navigation guards
router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();
    const themeStore = useThemeStore();
    
    // Initialize theme
    themeStore.initTheme();

    // Wait for auth initialization if not already done
    if (!authStore.isInitialized) {
        await authStore.initAuth();
    }

    // Check authentication requirements
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next('/secure/administrator/login');
    } else if (to.meta.requiresGuest && authStore.isAuthenticated) {
        next('/secure/administrator/dashboard');
    } else {
        next();
    }
});

const app = createApp(App);
const pinia = createPinia();

app.use(pinia);
app.use(router);
app.mount('#app');
