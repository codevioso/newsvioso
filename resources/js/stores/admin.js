import { defineStore } from 'pinia';
import axios from 'axios';

export const useAdminStore = defineStore('admin', {
    state: () => ({
        admins: [],
        currentAdmin: null,
        roles: [],
        pagination: {
            current_page: 1,
            last_page: 1,
            per_page: 15,
            total: 0,
        },
        filters: {
            search: '',
            role: '',
            status: '',
            sort_by: 'created_at',
            sort_order: 'desc',
        },
        isLoading: false,
        error: null,
    }),

    getters: {
        filteredAdmins: (state) => state.admins,
        hasAdmins: (state) => state.admins.length > 0,
        totalPages: (state) => state.pagination.last_page,
        currentPage: (state) => state.pagination.current_page,
        totalAdmins: (state) => state.pagination.total,
    },

    actions: {
        async fetchAdmins(params = {}) {
            this.isLoading = true;
            this.error = null;
            
            try {
                const queryParams = {
                    ...this.filters,
                    ...params,
                };

                // Remove empty values
                Object.keys(queryParams).forEach(key => {
                    if (queryParams[key] === '' || queryParams[key] === null) {
                        delete queryParams[key];
                    }
                });

                const response = await axios.get('/api/secure/admin/admins', {
                    params: queryParams
                });

                if (response.data.success) {
                    this.admins = response.data.data.data;
                    this.pagination = {
                        current_page: response.data.data.current_page,
                        last_page: response.data.data.last_page,
                        per_page: response.data.data.per_page,
                        total: response.data.data.total,
                    };
                } else {
                    throw new Error(response.data.message || 'Failed to fetch admins');
                }
            } catch (error) {
                this.error = error.response?.data?.message || error.message || 'Failed to fetch admins';
                console.error('Fetch admins error:', error);
            } finally {
                this.isLoading = false;
            }
        },

        async fetchAdmin(id) {
            this.isLoading = true;
            this.error = null;
            
            try {
                const response = await axios.get(`/api/secure/admin/admins/${id}`);
                
                if (response.data.success) {
                    this.currentAdmin = response.data.data;
                    return response.data.data;
                } else {
                    throw new Error(response.data.message || 'Failed to fetch admin');
                }
            } catch (error) {
                this.error = error.response?.data?.message || error.message || 'Failed to fetch admin';
                console.error('Fetch admin error:', error);
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async createAdmin(adminData) {
            this.isLoading = true;
            this.error = null;
            
            try {
                const formData = new FormData();
                formData.append('name', adminData.name);
                formData.append('email', adminData.email);
                formData.append('password', adminData.password);
                formData.append('password_confirmation', adminData.password_confirmation);
                formData.append('role', adminData.role);
                formData.append('is_active', adminData.is_active ? '1' : '0');
                
                if (adminData.avatar) {
                    formData.append('avatar', adminData.avatar);
                }

                const response = await axios.post('/api/secure/admin/admins', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });

                if (response.data.success) {
                    // Add the new admin to the list
                    this.admins.unshift(response.data.data);
                    this.pagination.total += 1;
                    return response.data.data;
                } else {
                    throw new Error(response.data.message || 'Failed to create admin');
                }
            } catch (error) {
                this.error = error.response?.data?.message || error.message || 'Failed to create admin';
                console.error('Create admin error:', error);
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async updateAdmin(id, adminData) {
            this.isLoading = true;
            this.error = null;
            
            try {
                const formData = new FormData();
                formData.append('name', adminData.name);
                formData.append('email', adminData.email);
                formData.append('role', adminData.role);
                formData.append('is_active', adminData.is_active ? '1' : '0');
                
                if (adminData.password) {
                    formData.append('password', adminData.password);
                    formData.append('password_confirmation', adminData.password_confirmation);
                }
                
                if (adminData.avatar) {
                    formData.append('avatar', adminData.avatar);
                }

                const response = await axios.post(`/api/secure/admin/admins/${id}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                    params: { _method: 'PUT' }
                });

                if (response.data.success) {
                    // Update the admin in the list
                    const index = this.admins.findIndex(admin => admin.id === id);
                    if (index !== -1) {
                        this.admins[index] = response.data.data;
                    }
                    
                    // Update current admin if it's the same
                    if (this.currentAdmin && this.currentAdmin.id === id) {
                        this.currentAdmin = response.data.data;
                    }
                    
                    return response.data.data;
                } else {
                    throw new Error(response.data.message || 'Failed to update admin');
                }
            } catch (error) {
                this.error = error.response?.data?.message || error.message || 'Failed to update admin';
                console.error('Update admin error:', error);
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async deleteAdmin(id) {
            this.isLoading = true;
            this.error = null;
            
            try {
                const response = await axios.delete(`/api/secure/admin/admins/${id}`);
                
                if (response.data.success) {
                    // Remove the admin from the list
                    this.admins = this.admins.filter(admin => admin.id !== id);
                    this.pagination.total -= 1;
                    
                    // Clear current admin if it's the same
                    if (this.currentAdmin && this.currentAdmin.id === id) {
                        this.currentAdmin = null;
                    }
                    
                    return true;
                } else {
                    throw new Error(response.data.message || 'Failed to delete admin');
                }
            } catch (error) {
                this.error = error.response?.data?.message || error.message || 'Failed to delete admin';
                console.error('Delete admin error:', error);
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async fetchRoles() {
            try {
                const response = await axios.get('/api/secure/admin/roles');
                
                if (response.data.success) {
                    this.roles = response.data.data;
                    return response.data.data;
                } else {
                    throw new Error(response.data.message || 'Failed to fetch roles');
                }
            } catch (error) {
                this.error = error.response?.data?.message || error.message || 'Failed to fetch roles';
                console.error('Fetch roles error:', error);
                throw error;
            }
        },

        setFilter(key, value) {
            this.filters[key] = value;
        },

        setFilters(filters) {
            this.filters = { ...this.filters, ...filters };
        },

        clearFilters() {
            this.filters = {
                search: '',
                role: '',
                status: '',
                sort_by: 'created_at',
                sort_order: 'desc',
            };
        },

        clearError() {
            this.error = null;
        },

        clearCurrentAdmin() {
            this.currentAdmin = null;
        },
    },
});
