import { defineStore } from 'pinia';
import axios from 'axios';

export const useCategoryStore = defineStore('category', {
    state: () => ({
        categories: [],
        currentCategory: null,
        pagination: {
            current_page: 1,
            last_page: 1,
            per_page: 15,
            total: 0,
        },
        filters: {
            search: '',
            status: '',
            date_from: '',
            date_to: '',
            sort_by: 'created_at',
            sort_order: 'desc',
        },
        isLoading: false,
        error: null,
    }),

    getters: {
        filteredCategories: (state) => state.categories,
        hasCategories: (state) => state.categories.length > 0,
        totalPages: (state) => state.pagination.last_page,
        currentPage: (state) => state.pagination.current_page,
        totalCategories: (state) => state.pagination.total,
    },

    actions: {
        async fetchCategories(params = {}) {
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

                const response = await axios.get('/api/secure/admin/settings/categories', {
                    params: queryParams
                });

                if (response.data.success) {
                    this.categories = response.data.data.data;
                    this.pagination = {
                        current_page: response.data.data.current_page,
                        last_page: response.data.data.last_page,
                        per_page: response.data.data.per_page,
                        total: response.data.data.total,
                    };
                } else {
                    throw new Error(response.data.message || 'Failed to fetch categories');
                }
            } catch (error) {
                this.error = error.response?.data?.message || error.message || 'Failed to fetch categories';
                console.error('Fetch categories error:', error);
            } finally {
                this.isLoading = false;
            }
        },

        async fetchCategory(id) {
            this.isLoading = true;
            this.error = null;
            
            try {
                const response = await axios.get(`/api/secure/admin/settings/categories/${id}`);
                
                if (response.data.success) {
                    this.currentCategory = response.data.data;
                    return response.data.data;
                } else {
                    throw new Error(response.data.message || 'Failed to fetch category');
                }
            } catch (error) {
                this.error = error.response?.data?.message || error.message || 'Failed to fetch category';
                console.error('Fetch category error:', error);
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async createCategory(categoryData) {
            this.isLoading = true;
            this.error = null;
            
            try {
                const response = await axios.post('/api/secure/admin/settings/categories', categoryData);

                if (response.data.success) {
                    // Add the new category to the list
                    this.categories.unshift(response.data.data);
                    this.pagination.total += 1;
                    return response.data.data;
                } else {
                    throw new Error(response.data.message || 'Failed to create category');
                }
            } catch (error) {
                this.error = error.response?.data?.message || error.message || 'Failed to create category';
                console.error('Create category error:', error);
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async updateCategory(id, categoryData) {
            this.isLoading = true;
            this.error = null;
            
            try {
                const response = await axios.put(`/api/secure/admin/settings/categories/${id}`, categoryData);

                if (response.data.success) {
                    // Update the category in the list
                    const index = this.categories.findIndex(category => category.id === id);
                    if (index !== -1) {
                        this.categories[index] = response.data.data;
                    }
                    
                    // Update current category if it's the same
                    if (this.currentCategory && this.currentCategory.id === id) {
                        this.currentCategory = response.data.data;
                    }
                    
                    return response.data.data;
                } else {
                    throw new Error(response.data.message || 'Failed to update category');
                }
            } catch (error) {
                this.error = error.response?.data?.message || error.message || 'Failed to update category';
                console.error('Update category error:', error);
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async deleteCategory(id) {
            this.isLoading = true;
            this.error = null;
            
            try {
                const response = await axios.delete(`/api/secure/admin/settings/categories/${id}`);
                
                if (response.data.success) {
                    // Remove the category from the list
                    this.categories = this.categories.filter(category => category.id !== id);
                    this.pagination.total -= 1;
                    
                    // Clear current category if it's the same
                    if (this.currentCategory && this.currentCategory.id === id) {
                        this.currentCategory = null;
                    }
                    
                    return true;
                } else {
                    throw new Error(response.data.message || 'Failed to delete category');
                }
            } catch (error) {
                this.error = error.response?.data?.message || error.message || 'Failed to delete category';
                console.error('Delete category error:', error);
                throw error;
            } finally {
                this.isLoading = false;
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
                status: '',
                date_from: '',
                date_to: '',
                sort_by: 'created_at',
                sort_order: 'desc',
            };
        },

        clearError() {
            this.error = null;
        },

        clearCurrentCategory() {
            this.currentCategory = null;
        },
    },
});
