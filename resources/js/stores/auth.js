import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('admin_token'),
        isLoading: false,
    }),

    getters: {
        isAuthenticated: (state) => !!state.token && !!state.user,
        userRole: (state) => state.user?.role,
        userRoleDisplayName: (state) => state.user?.role_display_name,
    },

    actions: {
        async login(credentials) {
            this.isLoading = true;
            try {
                const response = await axios.post('/api/auth/login', credentials);
                
                this.token = response.data.token;
                this.user = response.data.user;
                
                localStorage.setItem('admin_token', this.token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
                
                return response.data;
            } catch (error) {
                throw error.response?.data || error;
            } finally {
                this.isLoading = false;
            }
        },

        async logout() {
            try {
                if (this.token) {
                    await axios.post('/api/auth/logout');
                }
            } catch (error) {
                console.error('Logout error:', error);
            } finally {
                this.token = null;
                this.user = null;
                localStorage.removeItem('admin_token');
                delete axios.defaults.headers.common['Authorization'];
            }
        },

        async fetchUser() {
            if (!this.token) return;
            
            try {
                const response = await axios.get('/api/auth/me');
                this.user = response.data.user;
            } catch (error) {
                console.error('Fetch user error:', error);
                this.logout();
            }
        },

        async forgotPassword(email) {
            this.isLoading = true;
            try {
                const response = await axios.post('/api/auth/forgot-password', { email });
                return response.data;
            } catch (error) {
                throw error.response?.data || error;
            } finally {
                this.isLoading = false;
            }
        },

        async resetPassword(data) {
            this.isLoading = true;
            try {
                const response = await axios.post('/api/auth/reset-password', data);
                return response.data;
            } catch (error) {
                throw error.response?.data || error;
            } finally {
                this.isLoading = false;
            }
        },

        initAuth() {
            if (this.token) {
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
                this.fetchUser();
            }
        },

        hasRole(role) {
            return this.user?.role === role;
        },

        hasPermissionLevel(requiredLevel) {
            const roleLevels = {
                'super_admin': 3,
                'editor': 2,
                'reporter': 1,
            };
            const userLevel = roleLevels[this.user?.role] || 0;
            return userLevel >= requiredLevel;
        },
    },
});
