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
import TestTailwind from './pages/TestTailwind.vue';

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
        path: '/secure/administrator/test',
        name: 'test-tailwind',
        component: TestTailwind
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Navigation guards
router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();
    const themeStore = useThemeStore();
    
    // Initialize theme
    themeStore.initTheme();

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
