import { defineStore } from 'pinia';
import axios from 'axios';

export const useArticleStore = defineStore('article', {
    state: () => ({
        articles: [],
        article: null,
        categories: [],
        tags: [],
        filters: {
            search: '',
            status: '',
            category_id: '',
            author_id: '',
            date_from: '',
            date_to: '',
            sort_by: 'created_at',
            sort_order: 'desc',
        },
        pagination: {
            current_page: 1,
            last_page: 1,
            per_page: 15,
            total: 0,
        },
        isLoading: false,
        error: null,
        success: null,
    }),

    getters: {
        filteredArticles: (state) => state.articles,
        articleById: (state) => (id) => state.articles.find(article => article.id === id),
        publishedArticles: (state) => state.articles.filter(article => article.status === 'published'),
        draftArticles: (state) => state.articles.filter(article => article.status === 'draft'),
        scheduledArticles: (state) => state.articles.filter(article => article.status === 'scheduled'),
    },

    actions: {
        async fetchArticles() {
            this.isLoading = true;
            this.error = null;

            try {
                const params = {
                    page: this.pagination.current_page,
                    per_page: this.pagination.per_page,
                    ...this.filters,
                };

                // Remove empty filters
                Object.keys(params).forEach(key => {
                    if (params[key] === '' || params[key] === null || params[key] === undefined) {
                        delete params[key];
                    }
                });

                const response = await axios.get('/api/articles', { params });

                if (response.data.success) {
                    this.articles = response.data.data.data;
                    this.pagination = {
                        current_page: response.data.data.current_page,
                        last_page: response.data.data.last_page,
                        per_page: response.data.data.per_page,
                        total: response.data.data.total,
                    };
                } else {
                    this.error = response.data.message || 'Failed to fetch articles';
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch articles';
                console.error('Error fetching articles:', error);
            } finally {
                this.isLoading = false;
            }
        },

        async fetchArticle(id) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await axios.get(`/api/articles/${id}`);

                if (response.data.success) {
                    this.article = response.data.data;
                } else {
                    this.error = response.data.message || 'Failed to fetch article';
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch article';
                console.error('Error fetching article:', error);
            } finally {
                this.isLoading = false;
            }
        },

        async createArticle(articleData) {
            this.isLoading = true;
            this.error = null;
            this.success = null;

            try {
                const formData = new FormData();
                
                // Append all fields to FormData
                Object.keys(articleData).forEach(key => {
                    if (key === 'tags' && Array.isArray(articleData[key])) {
                        // Handle tags array
                        articleData[key].forEach((tag, index) => {
                            formData.append(`tags[${index}]`, tag);
                        });
                    } else if (key === 'featured_image' && articleData[key] instanceof File) {
                        // Handle file upload
                        formData.append(key, articleData[key]);
                    } else if (articleData[key] !== null && articleData[key] !== undefined) {
                        formData.append(key, articleData[key]);
                    }
                });

                const response = await axios.post('/api/articles', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });

                if (response.data.success) {
                    this.article = response.data.data;
                    this.success = response.data.message || 'Article created successfully';
                    return { success: true, data: response.data.data };
                } else {
                    this.error = response.data.message || 'Failed to create article';
                    return { success: false, errors: response.data.errors };
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create article';
                console.error('Error creating article:', error);
                return { 
                    success: false, 
                    errors: error.response?.data?.errors || { general: [this.error] }
                };
            } finally {
                this.isLoading = false;
            }
        },

        async updateArticle(id, articleData) {
            this.isLoading = true;
            this.error = null;
            this.success = null;

            try {
                const formData = new FormData();
                
                // Append all fields to FormData
                Object.keys(articleData).forEach(key => {
                    if (key === 'tags' && Array.isArray(articleData[key])) {
                        // Handle tags array
                        articleData[key].forEach((tag, index) => {
                            formData.append(`tags[${index}]`, tag);
                        });
                    } else if (key === 'featured_image' && articleData[key] instanceof File) {
                        // Handle file upload
                        formData.append(key, articleData[key]);
                    } else if (articleData[key] !== null && articleData[key] !== undefined) {
                        formData.append(key, articleData[key]);
                    }
                });

                const response = await axios.post(`/api/articles/${id}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });

                if (response.data.success) {
                    this.article = response.data.data;
                    this.success = response.data.message || 'Article updated successfully';
                    
                    // Update the article in the articles list
                    const index = this.articles.findIndex(article => article.id === id);
                    if (index !== -1) {
                        this.articles[index] = response.data.data;
                    }
                    
                    return { success: true, data: response.data.data };
                } else {
                    this.error = response.data.message || 'Failed to update article';
                    return { success: false, errors: response.data.errors };
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to update article';
                console.error('Error updating article:', error);
                return { 
                    success: false, 
                    errors: error.response?.data?.errors || { general: [this.error] }
                };
            } finally {
                this.isLoading = false;
            }
        },

        async deleteArticle(id) {
            this.isLoading = true;
            this.error = null;
            this.success = null;

            try {
                const response = await axios.delete(`/api/articles/${id}`);

                if (response.data.success) {
                    this.success = response.data.message || 'Article deleted successfully';
                    
                    // Remove the article from the articles list
                    this.articles = this.articles.filter(article => article.id !== id);
                    
                    return { success: true };
                } else {
                    this.error = response.data.message || 'Failed to delete article';
                    return { success: false };
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to delete article';
                console.error('Error deleting article:', error);
                return { success: false };
            } finally {
                this.isLoading = false;
            }
        },

        async bulkDeleteArticles(ids) {
            this.isLoading = true;
            this.error = null;
            this.success = null;

            try {
                const response = await axios.post('/api/articles/bulk-delete', { ids });

                if (response.data.success) {
                    this.success = response.data.message || 'Articles deleted successfully';
                    
                    // Remove the articles from the articles list
                    this.articles = this.articles.filter(article => !ids.includes(article.id));
                    
                    return { success: true };
                } else {
                    this.error = response.data.message || 'Failed to delete articles';
                    return { success: false };
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to delete articles';
                console.error('Error bulk deleting articles:', error);
                return { success: false };
            } finally {
                this.isLoading = false;
            }
        },

        async uploadImage(file) {
            try {
                const formData = new FormData();
                formData.append('image', file);

                const response = await axios.post('/api/articles/upload-image', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });

                if (response.data.success) {
                    return { success: true, data: response.data.data };
                } else {
                    return { success: false, error: response.data.message };
                }
            } catch (error) {
                console.error('Error uploading image:', error);
                return { 
                    success: false, 
                    error: error.response?.data?.message || 'Failed to upload image' 
                };
            }
        },

        async fetchCategories() {
            try {
                const response = await axios.get('/api/settings/categories');
                if (response.data.success) {
                    this.categories = response.data.data.data;
                }
            } catch (error) {
                console.error('Error fetching categories:', error);
            }
        },

        async fetchTags() {
            try {
                const response = await axios.get('/api/settings/tags');
                if (response.data.success) {
                    this.tags = response.data.data.data;
                }
            } catch (error) {
                console.error('Error fetching tags:', error);
            }
        },

        setFilters(filters) {
            this.filters = { ...this.filters, ...filters };
        },

        setPage(page) {
            this.pagination.current_page = page;
        },

        clearError() {
            this.error = null;
        },

        clearSuccess() {
            this.success = null;
        },

        clearMessages() {
            this.error = null;
            this.success = null;
        },

        async publishArticle(articleId) {
            try {
                const response = await axios.put(`/api/admin/articles/${articleId}/publish`);
                if (response.data.success) {
                    this.success = 'Article published successfully';
                    // Update the article in the list if it exists
                    const articleIndex = this.articles.findIndex(a => a.id === articleId);
                    if (articleIndex !== -1) {
                        this.articles[articleIndex] = response.data.data;
                    }
                    // Update single article if it's the current one
                    if (this.article && this.article.id === articleId) {
                        this.article = response.data.data;
                    }
                    return response.data;
                } else {
                    this.error = response.data.message || 'Failed to publish article';
                    throw new Error(response.data.message);
                }
            } catch (error) {
                console.error('Error publishing article:', error);
                this.error = error.response?.data?.message || 'Failed to publish article';
                throw error;
            }
        },

        async scheduleArticle(articleId, scheduledAt) {
            try {
                const response = await axios.put(`/api/admin/articles/${articleId}/schedule`, {
                    scheduled_at: scheduledAt
                });
                if (response.data.success) {
                    this.success = 'Article scheduled successfully';
                    // Update the article in the list if it exists
                    const articleIndex = this.articles.findIndex(a => a.id === articleId);
                    if (articleIndex !== -1) {
                        this.articles[articleIndex] = response.data.data;
                    }
                    // Update single article if it's the current one
                    if (this.article && this.article.id === articleId) {
                        this.article = response.data.data;
                    }
                    return response.data;
                } else {
                    this.error = response.data.message || 'Failed to schedule article';
                    throw new Error(response.data.message);
                }
            } catch (error) {
                console.error('Error scheduling article:', error);
                this.error = error.response?.data?.message || 'Failed to schedule article';
                throw error;
            }
        },
    },
});
