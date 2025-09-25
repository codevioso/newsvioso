<template>
    <AdminLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Create Article
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Create a new article for your news site
                    </p>
                </div>
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

            <!-- Success/Error Messages -->
            <div v-if="articleStore.success" class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                            {{ articleStore.success }}
                        </p>
                    </div>
                </div>
            </div>

            <div v-if="articleStore.error" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">
                            {{ articleStore.error }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form @submit.prevent="submitForm" class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <!-- Basic Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Basic Information</h3>
                        
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="title"
                                v-model="form.title"
                                type="text"
                                required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                :class="{ 'border-red-500': errors.title }"
                                placeholder="Enter article title"
                            />
                            <p v-if="errors.title" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ errors.title[0] }}
                            </p>
                        </div>


                        <!-- Excerpt -->
                        <div>
                            <label for="excerpt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Excerpt
                            </label>
                            <textarea
                                id="excerpt"
                                v-model="form.excerpt"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                :class="{ 'border-red-500': errors.excerpt }"
                                placeholder="Brief description of the article"
                            ></textarea>
                            <p v-if="errors.excerpt" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ errors.excerpt[0] }}
                            </p>
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="category_id"
                                v-model="form.category_id"
                                required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                :class="{ 'border-red-500': errors.category_id }"
                            >
                                <option value="">Select a category</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.title }}
                                </option>
                            </select>
                            <p v-if="errors.category_id" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ errors.category_id[0] }}
                            </p>
                        </div>

                        <!-- Tags -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tags
                            </label>
                            <div class="space-y-2">
                                <div v-for="tag in availableTags" :key="tag.id" class="flex items-center">
                                    <input
                                        :id="`tag-${tag.id}`"
                                        :value="tag.id"
                                        v-model="form.tags"
                                        type="checkbox"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    />
                                    <label :for="`tag-${tag.id}`" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        {{ tag.title }}
                                    </label>
                                </div>
                            </div>
                            <p v-if="errors.tags" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ errors.tags[0] }}
                            </p>
                        </div>

                        <!-- Featured Image -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Featured Image
                            </label>
                            <div class="space-y-4">
                                <input
                                    ref="featuredImageInput"
                                    type="file"
                                    accept="image/*"
                                    @change="handleFeaturedImageChange"
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-200"
                                />
                                <div v-if="featuredImagePreview" class="mt-2">
                                    <img
                                        :src="featuredImagePreview"
                                        alt="Featured image preview"
                                        class="h-32 w-32 object-cover rounded-lg border border-gray-300 dark:border-gray-600"
                                    />
                                </div>
                            </div>
                            <p v-if="errors.featured_image" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ errors.featured_image[0] }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Content</h3>
                    
                    <!-- Rich Text Editor -->
                    <div class="border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm overflow-hidden">
                        <QuillEditor
                            ref="quillEditor"
                            v-model:content="form.content"
                            :options="quillOptions"
                            content-type="html"
                            class="quill-editor"
                            @update:content="onContentChange"
                        />
                    </div>
                    <p v-if="errors.content" class="mt-1 text-sm text-red-600 dark:text-red-400">
                        {{ errors.content[0] }}
                    </p>
                </div>

                <!-- Publishing Options -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Publishing Options</h3>
                    
                    <div class="space-y-4">
                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="status"
                                v-model="form.status"
                                required
                                @change="onStatusChange"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                :class="{ 'border-red-500': errors.status }"
                            >
                                <option value="draft">Draft</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="published">Published</option>
                            </select>
                            <p v-if="errors.status" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ errors.status[0] }}
                            </p>
                        </div>

                        <!-- Scheduled At -->
                        <div v-if="form.status === 'scheduled'">
                            <label for="scheduled_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Scheduled Date & Time <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="scheduled_at"
                                v-model="form.scheduled_at"
                                type="datetime-local"
                                required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                :class="{ 'border-red-500': errors.scheduled_at }"
                            />
                            <p v-if="errors.scheduled_at" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ errors.scheduled_at[0] }}
                            </p>
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input
                                id="is_active"
                                :checked="form.is_active == 1"
                                @change="form.is_active = form.is_active == 1 ? 0 : 1"
                                type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            />
                            <label for="is_active" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                Active
                            </label>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">SEO Settings</h3>
                    
                    <div class="space-y-4">
                        <!-- Meta Title -->
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Meta Title
                            </label>
                            <input
                                id="meta_title"
                                v-model="form.meta_title"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                :class="{ 'border-red-500': errors.meta_title }"
                                placeholder="SEO title for search engines"
                            />
                            <p v-if="errors.meta_title" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ errors.meta_title[0] }}
                            </p>
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Meta Description
                            </label>
                            <textarea
                                id="meta_description"
                                v-model="form.meta_description"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                :class="{ 'border-red-500': errors.meta_description }"
                                placeholder="Brief description for search engines"
                            ></textarea>
                            <p v-if="errors.meta_description" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ errors.meta_description[0] }}
                            </p>
                        </div>

                        <!-- Meta Keywords -->
                        <div>
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Meta Keywords
                            </label>
                            <input
                                id="meta_keywords"
                                v-model="form.meta_keywords"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                :class="{ 'border-red-500': errors.meta_keywords }"
                                placeholder="Comma-separated keywords"
                            />
                            <p v-if="errors.meta_keywords" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ errors.meta_keywords[0] }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4">
                    <router-link
                        to="/secure/administrator/articles"
                        class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500"
                    >
                        Cancel
                    </router-link>
                    <button
                        type="submit"
                        :disabled="articleStore.isLoading"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="articleStore.isLoading" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Creating...
                        </span>
                        <span v-else>Create Article</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted, onBeforeUnmount, computed } from 'vue';
import { useRouter } from 'vue-router';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import Quill from 'quill';
import ImageUploader from 'quill-image-uploader';
import { useArticleStore } from '../../stores/article';
import AdminLayout from '../../components/layouts/AdminLayout.vue';

const router = useRouter();
const articleStore = useArticleStore();

// Form data
const form = reactive({
    title: '',
    excerpt: '',
    content: '',
    category_id: '',
    tags: [],
    featured_image: null,
    status: 'draft',
    scheduled_at: '',
    meta_title: '',
    meta_description: '',
    meta_keywords: '',
    is_active: 1
});

// Reactive data
const errors = ref({});
const featuredImagePreview = ref(null);
const featuredImageInput = ref(null);

// Computed properties
const categories = computed(() => articleStore.categories);
const availableTags = computed(() => articleStore.tags);

// Register image uploader
Quill.register('modules/imageUploader', ImageUploader);

// Quill editor configuration
const quillOptions = {
    theme: 'snow',
    placeholder: 'Start writing your article...',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'script': 'sub'}, { 'script': 'super' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
            [{ 'direction': 'rtl' }],
            [{ 'size': ['small', false, 'large', 'huge'] }],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'font': [] }],
            [{ 'align': [] }],
            ['link', 'image', 'video'],
            ['blockquote', 'code-block'],
            ['clean']
        ],
        imageUploader: {
            upload: async (file) => {
                try {
                    const result = await articleStore.uploadImage(file);
                    if (result.success) {
                        return result.data.url;
                    } else {
                        throw new Error(result.error || 'Upload failed');
                    }
                } catch (error) {
                    console.error('Image upload error:', error);
                    throw new Error('Failed to upload image');
                }
            }
        }
    }
};

const quillEditor = ref(null);

// Methods

const handleFeaturedImageChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.featured_image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            featuredImagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const onStatusChange = () => {
    if (form.status === 'scheduled' && !form.scheduled_at) {
        // Set default scheduled time to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        tomorrow.setHours(9, 0, 0, 0);
        form.scheduled_at = tomorrow.toISOString().slice(0, 16);
    }
};

const onContentChange = (content) => {
    form.content = content;
};

const submitForm = async () => {
    errors.value = {};
    articleStore.clearMessages();

    const result = await articleStore.createArticle(form);
    
    if (result.success) {
        router.push('/secure/administrator/articles');
    } else {
        errors.value = result.errors || {};
    }
};

// Lifecycle
onMounted(async () => {
    await Promise.all([
        articleStore.fetchCategories(),
        articleStore.fetchTags()
    ]);
});

// No cleanup needed for Quill
</script>
