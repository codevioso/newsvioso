import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { createRouter, createWebHistory } from 'vue-router';
import App from './components/App.vue';
import { useAuthStore } from './stores/auth';
import { useThemeStore } from './stores/theme';
import routes from './routes/index.js';


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
    } else if (to.meta.requiresRole) {
        // Handle multiple roles (comma-separated)
        const requiredRoles = to.meta.requiresRole.split(',');
        const hasRequiredRole = requiredRoles.some(role => authStore.hasRole(role.trim()));
        
        if (!hasRequiredRole) {
            // Redirect to dashboard if user doesn't have any of the required roles
            next('/secure/administrator/dashboard');
            return;
        }
    }
    
    next();
});

const app = createApp(App);
const pinia = createPinia();

app.use(pinia);
app.use(router);
app.mount('#app');
