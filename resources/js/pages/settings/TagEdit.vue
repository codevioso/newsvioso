<template>
    <AdminLayout>
        <div class="max-w-2xl mx-auto space-y-6">
            <!-- Header -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Tag</h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Update tag information
                            </p>
                        </div>
                        <router-link
                            to="/secure/administrator/settings/tags"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Tags
                        </router-link>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="tagStore.isLoading && !tagStore.currentTag" class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>

            <!-- Error Message -->
            <div v-if="errorMessage" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800 dark:text-red-200">{{ errorMessage }}</p>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            <div v-if="successMessage" class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-800 dark:text-green-200">{{ successMessage }}</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div v-if="tagStore.currentTag" class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                <form @submit.prevent="submitForm" class="p-6 space-y-6">
                    <!-- Title Field -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tag Title <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="title"
                            v-model="form.title"
                            type="text"
                            required
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            :class="{ 'border-red-500 dark:border-red-400': errors.title }"
                            placeholder="Enter tag title"
                        />
                        <p v-if="errors.title" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ errors.title[0] }}
                        </p>
                    </div>

                    <!-- Status Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status
                        </label>
                        <div class="flex items-center">
                            <input
                                id="is_active"
                                v-model="form.is_active"
                                type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded transition-colors"
                            />
                            <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-white">
                                Active (tag is available for use)
                            </label>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <router-link
                            to="/secure/administrator/settings/tags"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                        >
                            Cancel
                        </router-link>
                        <button
                            type="submit"
                            :disabled="tagStore.isLoading"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            <span v-if="tagStore.isLoading" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Updating...
                            </span>
                            <span v-else>Update Tag</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useTagStore } from '../../stores/tag';
import AdminLayout from '../../components/layouts/AdminLayout.vue';

const route = useRoute();
const router = useRouter();
const tagStore = useTagStore();

// Reactive data
const form = reactive({
    title: '',
    is_active: true,
});

const errors = ref({});
const successMessage = ref('');
const errorMessage = ref('');

// Methods
const clearMessages = () => {
    successMessage.value = '';
    errorMessage.value = '';
    errors.value = {};
};

const populateForm = () => {
    if (tagStore.currentTag) {
        form.title = tagStore.currentTag.title;
        form.is_active = tagStore.currentTag.is_active;
    }
};

const submitForm = async () => {
    clearMessages();
    
    try {
        await tagStore.updateTag(route.params.id, form);
        
        successMessage.value = 'Tag updated successfully!';
        
        // Redirect after a short delay
        setTimeout(() => {
            router.push('/secure/administrator/settings/tags');
        }, 2000);
        
    } catch (error) {
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
        } else {
            errorMessage.value = error.response?.data?.message || error.message || 'Failed to update tag';
        }
    }
};

// Lifecycle
onMounted(async () => {
    try {
        // Fetch tag data
        await tagStore.fetchTag(route.params.id);
        populateForm();
        
    } catch (error) {
        errorMessage.value = 'Failed to load tag data';
        console.error('Failed to initialize tag edit:', error);
    }
});

// Watch for tag data changes
watch(() => tagStore.currentTag, () => {
    if (tagStore.currentTag) {
        populateForm();
    }
});
</script>
