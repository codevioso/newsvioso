Update <template>
    <AdminLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Article Preview
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Preview how your article will appear to readers
                    </p>
                </div>
                <div class="flex space-x-3">
                    <router-link
                        :to="`/secure/administrator/articles/${article?.id}/edit`"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Article
                    </router-link>
                    <router-link
                        to="/secure/administrator/articles"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Articles
                    </router-link>
                </div>
            </div>

            <!-- Article Status & Actions -->
            <div v-if="article" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- Status Badge -->
                        <span
                            :class="getStatusBadgeClass(article.status)"
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                        >
                            {{ getStatusText(article.status) }}
                        </span>

                        <!-- Published Date -->
                        <div v-if="article.published_at" class="text-sm text-gray-500 dark:text-gray-400">
                            <span class="font-medium">Published:</span> {{ formatDate(article.published_at) }}
                        </div>

                        <!-- Scheduled Date -->
                        <div v-if="article.scheduled_at && article.status === 'scheduled'" class="text-sm text-gray-500 dark:text-gray-400">
                            <span class="font-medium">Scheduled:</span> {{ formatDate(article.scheduled_at) }}
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        <!-- Publish Now Button -->
                        <button
                            v-if="canPublish && article.status !== 'published'"
                            @click="publishNow"
                            :disabled="publishing"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <svg v-if="!publishing" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <svg v-else class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ publishing ? 'Publishing...' : 'Publish Now' }}
                        </button>

                        <!-- Schedule Button -->
                        <button
                            v-if="canSchedule && article.status !== 'published'"
                            @click="showScheduleModal = true"
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Schedule
                        </button>
                    </div>
                </div>
            </div>

            <!-- Article Preview -->
            <div v-if="article" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Article Header -->
                <div class="p-8 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ article.title }}
                    </h1>
                    
                    <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400 mb-6">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            By {{ article.author?.name || 'Unknown Author' }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ formatDate(article.created_at) }}
                        </div>
                        <div v-if="article.category" class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            {{ article.category.title }}
                        </div>
                    </div>

                    <!-- Featured Image -->
                    <div v-if="article.featured_image_url" class="mb-6">
                        <img
                            :src="article.featured_image_url"
                            :alt="article.title"
                            class="w-full max-h-96 object-contain rounded-lg shadow-lg bg-gray-50 dark:bg-gray-700"
                        />
                    </div>

                    <!-- Excerpt -->
                    <div v-if="article.excerpt" class="text-lg text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                        {{ article.excerpt }}
                    </div>
                </div>

                <!-- Article Content -->
                <div class="p-8">
                    <div 
                        class="prose prose-lg dark:prose-invert max-w-none"
                        v-html="article.content"
                    ></div>
                </div>

                <!-- Tags -->
                <div v-if="article.tags && article.tags.length > 0" class="px-8 pb-8">
                    <div class="flex flex-wrap gap-2">
                        <span
                            v-for="tag in article.tags"
                            :key="tag.id"
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200"
                        >
                            #{{ tag.title }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-else-if="articleStore.isLoading" class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>

            <!-- Error State -->
            <div v-else class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Article not found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    The article you're looking for doesn't exist or has been deleted.
                </p>
            </div>
        </div>

        <!-- Schedule Modal -->
        <ConfirmationModal
            :show="showScheduleModal"
            title="Schedule Article"
            message="Choose when to publish this article."
            type="info"
            confirm-text="Schedule"
            cancel-text="Cancel"
            :loading="scheduling"
            loading-text="Scheduling..."
            @confirm="confirmSchedule"
            @cancel="showScheduleModal = false"
        >
            <template #default>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Schedule Date & Time
                    </label>
                    <input
                        v-model="scheduleDateTime"
                        type="datetime-local"
                        :min="minDateTime"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                    />
                </div>
            </template>
        </ConfirmationModal>
    </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useArticleStore } from '../../stores/article';
import { useAuthStore } from '../../stores/auth';
import AdminLayout from '../../components/layouts/AdminLayout.vue';
import ConfirmationModal from '../../components/shared/ConfirmationModal.vue';

const route = useRoute();
const articleStore = useArticleStore();
const authStore = useAuthStore();

// Reactive data
const showScheduleModal = ref(false);
const scheduleDateTime = ref('');
const publishing = ref(false);
const scheduling = ref(false);

// Computed properties
const article = computed(() => articleStore.article);

const canPublish = computed(() => {
    return authStore.hasRole('super_admin') || authStore.hasRole('editor');
});

const canSchedule = computed(() => {
    return authStore.hasRole('super_admin') || authStore.hasRole('editor');
});

const minDateTime = computed(() => {
    const now = new Date();
    now.setMinutes(now.getMinutes() + 1); // At least 1 minute in the future
    return now.toISOString().slice(0, 16);
});

// Methods
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

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const publishNow = async () => {
    if (!article.value) return;
    
    publishing.value = true;
    try {
        await articleStore.publishArticle(article.value.id);
        // Refresh article data
        await articleStore.fetchArticle(article.value.id);
    } catch (error) {
        console.error('Error publishing article:', error);
    } finally {
        publishing.value = false;
    }
};

const confirmSchedule = async () => {
    if (!article.value || !scheduleDateTime.value) return;
    
    scheduling.value = true;
    try {
        await articleStore.scheduleArticle(article.value.id, scheduleDateTime.value);
        // Refresh article data
        await articleStore.fetchArticle(article.value.id);
        showScheduleModal.value = false;
        scheduleDateTime.value = '';
    } catch (error) {
        console.error('Error scheduling article:', error);
    } finally {
        scheduling.value = false;
    }
};

// Lifecycle
onMounted(async () => {
    const articleId = route.params.id;
    if (articleId) {
        await articleStore.fetchArticle(articleId);
    }
});
</script>
