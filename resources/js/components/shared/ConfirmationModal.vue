<template>
    <div v-if="show" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 transition-opacity duration-300">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800 transform transition-all duration-300">
            <div class="mt-3 text-center">
                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full" :class="iconBgClass">
                    <svg class="h-6 w-6" :class="iconClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="iconPath" />
                    </svg>
                </div>
                
                <!-- Title -->
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mt-4">
                    {{ title }}
                </h3>
                
                <!-- Message -->
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ message }}
                    </p>
                </div>
                
                <!-- Actions -->
                <div class="flex justify-center space-x-4 mt-4">
                    <button
                        @click="$emit('cancel')"
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 transition-colors duration-200"
                    >
                        {{ cancelText }}
                    </button>
                    <button
                        @click="$emit('confirm')"
                        :disabled="loading"
                        class="px-4 py-2 text-white text-base font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        :class="confirmButtonClass"
                    >
                        <span v-if="loading" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ loadingText }}
                        </span>
                        <span v-else>{{ confirmText }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        required: true
    },
    message: {
        type: String,
        required: true
    },
    type: {
        type: String,
        default: 'danger', // danger, warning, info, success
        validator: (value) => ['danger', 'warning', 'info', 'success'].includes(value)
    },
    confirmText: {
        type: String,
        default: 'Confirm'
    },
    cancelText: {
        type: String,
        default: 'Cancel'
    },
    loading: {
        type: Boolean,
        default: false
    },
    loadingText: {
        type: String,
        default: 'Processing...'
    }
});

const emit = defineEmits(['confirm', 'cancel']);

const iconBgClass = computed(() => {
    switch (props.type) {
        case 'danger':
            return 'bg-red-100 dark:bg-red-900/20';
        case 'warning':
            return 'bg-yellow-100 dark:bg-yellow-900/20';
        case 'info':
            return 'bg-blue-100 dark:bg-blue-900/20';
        case 'success':
            return 'bg-green-100 dark:bg-green-900/20';
        default:
            return 'bg-gray-100 dark:bg-gray-900/20';
    }
});

const iconClass = computed(() => {
    switch (props.type) {
        case 'danger':
            return 'text-red-600 dark:text-red-400';
        case 'warning':
            return 'text-yellow-600 dark:text-yellow-400';
        case 'info':
            return 'text-blue-600 dark:text-blue-400';
        case 'success':
            return 'text-green-600 dark:text-green-400';
        default:
            return 'text-gray-600 dark:text-gray-400';
    }
});

const iconPath = computed(() => {
    switch (props.type) {
        case 'danger':
            return 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z';
        case 'warning':
            return 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z';
        case 'info':
            return 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
        case 'success':
            return 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z';
        default:
            return 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
    }
});

const confirmButtonClass = computed(() => {
    switch (props.type) {
        case 'danger':
            return 'bg-red-600 hover:bg-red-700 focus:ring-red-500';
        case 'warning':
            return 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500';
        case 'info':
            return 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500';
        case 'success':
            return 'bg-green-600 hover:bg-green-700 focus:ring-green-500';
        default:
            return 'bg-gray-600 hover:bg-gray-700 focus:ring-gray-500';
    }
});
</script>
