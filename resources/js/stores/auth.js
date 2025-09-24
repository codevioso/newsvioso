import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('admin_token'),
        isLoading: false,
        isInitialized: false,
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
                
                // Add avatar URL if avatar exists
                if (this.user?.avatar) {
                    this.user.avatar_url = this.user.avatar.startsWith('http') 
                        ? this.user.avatar 
                        : `/storage/${this.user.avatar}`;
                }
                
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
                this.clearAuth();
            }
        },

        async fetchUser() {
            if (!this.token) {
                console.log('No token found, skipping user fetch');
                this.isInitialized = true;
                return;
            }
            
            try {
                console.log('Fetching user data...');
                const response = await axios.get('/api/auth/me');
                this.user = response.data.user;
                
                // Add avatar URL if avatar exists
                if (this.user?.avatar) {
                    this.user.avatar_url = this.user.avatar.startsWith('http') 
                        ? this.user.avatar 
                        : `/storage/${this.user.avatar}`;
                }
                
                console.log('User data fetched successfully:', this.user?.name);
            } catch (error) {
                console.error('Fetch user error:', error);
                // If token is invalid, clear it
                this.clearAuth();
            } finally {
                this.isInitialized = true;
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

        async initAuth() {
            console.log('Initializing auth...', { token: this.token ? 'exists' : 'null' });
            
            if (this.token) {
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
                await this.fetchUser();
            } else {
                this.isInitialized = true;
            }
            
            console.log('Auth initialized:', { 
                isAuthenticated: this.isAuthenticated, 
                user: this.user?.name || 'null',
                isInitialized: this.isInitialized 
            });
        },

        clearAuth() {
            this.token = null;
            this.user = null;
            this.isInitialized = true;
            localStorage.removeItem('admin_token');
            delete axios.defaults.headers.common['Authorization'];
        },

        // Check if user should be redirected (for login page)
        shouldRedirectToDashboard() {
            return this.isAuthenticated;
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

        async updateProfile(data) {
            this.isLoading = true;
            try {
                const formData = new FormData();
                formData.append('name', data.name);
                formData.append('email', data.email);
                if (data.avatar) {
                    formData.append('avatar', data.avatar);
                }

                const response = await axios.post('/api/profile', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });
                
                this.user = response.data.user;
                
                // Add avatar URL if avatar exists
                if (this.user?.avatar) {
                    this.user.avatar_url = this.user.avatar.startsWith('http') 
                        ? this.user.avatar 
                        : `/storage/${this.user.avatar}`;
                }
                
                return response.data;
            } catch (error) {
                throw error.response?.data || error;
            } finally {
                this.isLoading = false;
            }
        },

        async changePassword(data) {
            this.isLoading = true;
            try {
                const response = await axios.post('/api/profile/change-password', data);
                return response.data;
            } catch (error) {
                throw error.response?.data || error;
            } finally {
                this.isLoading = false;
            }
        },
    },
});
