<template>
    <div class="flex items-center justify-end">
        <!-- Desktop: Inline buttons (visible on larger screens) -->
        <div class="hidden md:flex items-center space-x-1">
            <!-- Edit Button -->
            <router-link
                v-if="canEdit"
                :to="`/secure/administrator/articles/${article.id}/edit`"
                class="inline-flex items-center p-2 text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900/20"
                title="Edit Article"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </router-link>

            <!-- Preview Button -->
            <router-link
                :to="`/secure/administrator/articles/${article.id}/preview`"
                class="inline-flex items-center p-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 rounded-md hover:bg-indigo-50 dark:hover:bg-indigo-900/20"
                title="Preview Article"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </router-link>

            <!-- Delete Button -->
            <button
                v-if="canDelete"
                @click="$emit('delete', article)"
                class="inline-flex items-center p-2 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20"
                title="Delete Article"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>

            <!-- Disabled Delete Button (for published articles) -->
            <button
                v-else-if="!canDelete && article.status === 'published'"
                disabled
                class="inline-flex items-center p-2 text-gray-400 cursor-not-allowed rounded-md"
                title="Cannot delete published articles"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>

        <!-- Mobile/Tablet: Dropdown menu (visible on smaller screens) -->
        <div class="md:hidden relative" @click.stop>
            <button
                @click="toggleDropdown"
                class="inline-flex items-center p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-md"
                title="More actions"
                :aria-expanded="dropdownOpen"
                aria-haspopup="true"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div
                v-show="dropdownOpen"
                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-200 dark:border-gray-700"
                @click.away="dropdownOpen = false"
                role="menu"
                aria-orientation="vertical"
            >
                <!-- Edit Option -->
                <router-link
                    v-if="canEdit"
                    :to="`/secure/administrator/articles/${article.id}/edit`"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors duration-200"
                    @click="dropdownOpen = false"
                    role="menuitem"
                >
                    <svg class="w-4 h-4 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Article
                </router-link>

                <!-- Preview Option -->
                <router-link
                    :to="`/secure/administrator/articles/${article.id}/preview`"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors duration-200"
                    @click="dropdownOpen = false"
                    role="menuitem"
                >
                    <svg class="w-4 h-4 mr-3 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Preview Article
                </router-link>

                <!-- Delete Option -->
                <button
                    v-if="canDelete"
                    @click="handleDelete"
                    class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200"
                    role="menuitem"
                >
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Article
                </button>

                <!-- Disabled Delete Option -->
                <div
                    v-else-if="!canDelete && article.status === 'published'"
                    class="flex items-center px-4 py-2 text-sm text-gray-400 dark:text-gray-500 cursor-not-allowed"
                    role="menuitem"
                    aria-disabled="true"
                >
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Cannot delete published
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useAuthStore } from '../../stores/auth';

const props = defineProps({
    article: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['delete']);

const authStore = useAuthStore();
const dropdownOpen = ref(false);

const canEdit = computed(() => {
    // Allow editing for super_admin, editor, and reporter
    return authStore.hasRole('super_admin') || 
           authStore.hasRole('editor') || 
           authStore.hasRole('reporter');
});

const canDelete = computed(() => {
    // Only super_admin and editor can delete
    // Cannot delete published articles
    return (authStore.hasRole('super_admin') || authStore.hasRole('editor')) && 
           props.article.status !== 'published';
});

const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
};

const handleDelete = () => {
    dropdownOpen.value = false;
    emit('delete', props.article);
};
</script>
