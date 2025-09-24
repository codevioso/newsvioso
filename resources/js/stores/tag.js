import { defineStore } from 'pinia';
import axios from 'axios';

export const useTagStore = defineStore('tag', {
    state: () => ({
        tags: [],
        currentTag: null,
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
        filteredTags: (state) => state.tags,
        hasTags: (state) => state.tags.length > 0,
        totalPages: (state) => state.pagination.last_page,
        currentPage: (state) => state.pagination.current_page,
        totalTags: (state) => state.pagination.total,
    },

    actions: {
        async fetchTags(params = {}) {
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

                const response = await axios.get('/api/settings/tags', {
                    params: queryParams
                });

                if (response.data.success) {
                    this.tags = response.data.data.data;
                    this.pagination = {
                        current_page: response.data.data.current_page,
                        last_page: response.data.data.last_page,
                        per_page: response.data.data.per_page,
                        total: response.data.data.total,
                    };
                } else {
                    throw new Error(response.data.message || 'Failed to fetch tags');
                }
            } catch (error) {
                this.error = error.response?.data?.message || error.message || 'Failed to fetch tags';
                console.error('Fetch tags error:', error);
            } finally {
                this.isLoading = false;
            }
        },

        async fetchTag(id) {
            this.isLoading = true;
            this.error = null;
            
            try {
                const response = await axios.get(`/api/settings/tags/${id}`);
                
                if (response.data.success) {
                    this.currentTag = response.data.data;
                    return response.data.data;
                } else {
                    throw new Error(response.data.message || 'Failed to fetch tag');
                }
            } catch (error) {
                this.error = error.response?.data?.message || error.message || 'Failed to fetch tag';
                console.error('Fetch tag error:', error);
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async createTag(tagData) {
            this.isLoading = true;
            this.error = null;
            
            try {
                const response = await axios.post('/api/settings/tags', tagData);

                if (response.data.success) {
                    // Add the new tag to the list
                    this.tags.unshift(response.data.data);
                    this.pagination.total += 1;
                    return response.data.data;
                } else {
                    throw new Error(response.data.message || 'Failed to create tag');
                }
            } catch (error) {
                this.error = error.response?.data?.message || error.message || 'Failed to create tag';
                console.error('Create tag error:', error);
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async updateTag(id, tagData) {
            this.isLoading = true;
            this.error = null;
            
            try {
                const response = await axios.put(`/api/settings/tags/${id}`, tagData);

                if (response.data.success) {
                    // Update the tag in the list
                    const index = this.tags.findIndex(tag => tag.id === id);
                    if (index !== -1) {
                        this.tags[index] = response.data.data;
                    }
                    
                    // Update current tag if it's the same
                    if (this.currentTag && this.currentTag.id === id) {
                        this.currentTag = response.data.data;
                    }
                    
                    return response.data.data;
                } else {
                    throw new Error(response.data.message || 'Failed to update tag');
                }
            } catch (error) {
                this.error = error.response?.data?.message || error.message || 'Failed to update tag';
                console.error('Update tag error:', error);
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async deleteTag(id) {
            this.isLoading = true;
            this.error = null;
            
            try {
                const response = await axios.delete(`/api/settings/tags/${id}`);
                
                if (response.data.success) {
                    // Remove the tag from the list
                    this.tags = this.tags.filter(tag => tag.id !== id);
                    this.pagination.total -= 1;
                    
                    // Clear current tag if it's the same
                    if (this.currentTag && this.currentTag.id === id) {
                        this.currentTag = null;
                    }
                    
                    return true;
                } else {
                    throw new Error(response.data.message || 'Failed to delete tag');
                }
            } catch (error) {
                this.error = error.response?.data?.message || error.message || 'Failed to delete tag';
                console.error('Delete tag error:', error);
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

        clearCurrentTag() {
            this.currentTag = null;
        },
    },
});
