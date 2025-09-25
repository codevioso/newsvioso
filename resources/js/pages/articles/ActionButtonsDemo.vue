<template>
    <AdminLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Action Buttons Demo
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Testing the action buttons functionality with different screen sizes
                    </p>
                </div>
                <button
                    @click="toggleScreenSize"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Toggle Mobile View
                </button>
            </div>

            <!-- Screen Size Indicator -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm text-blue-800 dark:text-blue-200">
                        <strong>Current View:</strong> {{ isMobileView ? 'Mobile/Tablet (Dropdown Menu)' : 'Desktop (Inline Buttons)' }}
                    </span>
                </div>
            </div>

            <!-- Demo Articles -->
            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Sample Articles</h2>
                
                <!-- Desktop Table View -->
                <div v-if="!isMobileView" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Article
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Author
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="article in demoArticles" :key="article.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ article.title }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ article.excerpt }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="getStatusBadgeClass(article.status)"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    >
                                        {{ getStatusText(article.status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ article.author }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <ActionButtons
                                        :article="article"
                                        @delete="deleteArticle"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div v-else class="space-y-4">
                    <div 
                        v-for="article in demoArticles" 
                        :key="article.id" 
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4"
                    >
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center space-x-3 flex-1 min-w-0">
                                <div class="flex-shrink-0 h-12 w-12 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        {{ article.title }}
                                    </h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                                        {{ article.excerpt }}
                                    </p>
                                </div>
                            </div>
                            <ActionButtons
                                :article="article"
                                @delete="deleteArticle"
                            />
                        </div>

                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Status:</span>
                                <span
                                    :class="getStatusBadgeClass(article.status)"
                                    class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                                >
                                    {{ getStatusText(article.status) }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Author:</span>
                                <span class="ml-1 text-gray-900 dark:text-white">{{ article.author }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Results -->
            <div v-if="actionResult" class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm text-green-800 dark:text-green-200">{{ actionResult }}</span>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <ConfirmationModal
            :show="showDeleteModal"
            title="Delete Article"
            :message="deleteMessage"
            type="danger"
            confirm-text="Delete"
            cancel-text="Cancel"
            :loading="deleting"
            loading-text="Deleting..."
            @confirm="confirmDelete"
            @cancel="showDeleteModal = false"
        />
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AdminLayout from '../../components/layouts/AdminLayout.vue';
import ActionButtons from '../../components/articles/ActionButtons.vue';
import ConfirmationModal from '../../components/shared/ConfirmationModal.vue';

// Demo data
const demoArticles = ref([
    {
        id: 1,
        title: "Getting Started with Vue 3 Composition API",
        excerpt: "Learn how to use the new Composition API in Vue 3 to build more maintainable and reusable components.",
        status: "published",
        author: "John Doe"
    },
    {
        id: 2,
        title: "Advanced Tailwind CSS Techniques",
        excerpt: "Discover advanced techniques for using Tailwind CSS to create beautiful and responsive user interfaces.",
        status: "draft",
        author: "Jane Smith"
    },
    {
        id: 3,
        title: "Building REST APIs with Laravel",
        excerpt: "A comprehensive guide to building robust REST APIs using Laravel framework with proper validation and error handling.",
        status: "scheduled",
        author: "Mike Johnson"
    }
]);

// State
const isMobileView = ref(false);
const showDeleteModal = ref(false);
const articleToDelete = ref(null);
const deleting = ref(false);
const actionResult = ref('');

const deleteMessage = computed(() => {
    if (articleToDelete.value) {
        return `Are you sure you want to delete "${articleToDelete.value.title}"? This action will soft delete the article and cannot be undone.`;
    }
    return 'Are you sure you want to delete this article?';
});

const toggleScreenSize = () => {
    isMobileView.value = !isMobileView.value;
};

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'published':
            return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
        case 'scheduled':
            return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
        case 'draft':
            return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
        default:
            return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
    }
};

const getStatusText = (status) => {
    switch (status) {
        case 'published':
            return 'Published';
        case 'scheduled':
            return 'Scheduled';
        case 'draft':
            return 'Draft';
        default:
            return status;
    }
};

const deleteArticle = (article) => {
    articleToDelete.value = article;
    showDeleteModal.value = true;
};

const confirmDelete = async () => {
    if (articleToDelete.value) {
        deleting.value = true;
        try {
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 1000));
            
            // Remove article from demo data
            const index = demoArticles.value.findIndex(a => a.id === articleToDelete.value.id);
            if (index !== -1) {
                demoArticles.value.splice(index, 1);
            }
            
            actionResult.value = `Article "${articleToDelete.value.title}" has been deleted successfully.`;
            showDeleteModal.value = false;
            articleToDelete.value = null;
            
            // Clear result message after 3 seconds
            setTimeout(() => {
                actionResult.value = '';
            }, 3000);
        } catch (error) {
            console.error('Error deleting article:', error);
            actionResult.value = 'Error deleting article. Please try again.';
        } finally {
            deleting.value = false;
        }
    }
};
</script>
