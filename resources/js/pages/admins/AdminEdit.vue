<template>
    <AdminLayout>
        <div class="max-w-2xl mx-auto space-y-6">
            <!-- Header -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Admin</h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Update administrator information
                            </p>
                        </div>
                        <router-link
                            to="/secure/administrator/admins"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Admins
                        </router-link>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="adminStore.isLoading && !adminStore.currentAdmin" class="flex justify-center items-center py-12">
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
            <div v-if="adminStore.currentAdmin" class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                <form @submit.prevent="submitForm" class="p-6 space-y-6">
                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            :class="{ 'border-red-500 dark:border-red-400': errors.name }"
                            placeholder="Enter full name"
                        />
                        <p v-if="errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ errors.name[0] }}
                        </p>
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            :class="{ 'border-red-500 dark:border-red-400': errors.email }"
                            placeholder="Enter email address"
                        />
                        <p v-if="errors.email" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ errors.email[0] }}
                        </p>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            New Password
                            <span class="text-gray-500 text-xs">(leave blank to keep current password)</span>
                        </label>
                        <div class="relative">
                            <input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                class="block w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                :class="{ 'border-red-500 dark:border-red-400': errors.password }"
                                placeholder="Enter new password"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            >
                                <svg v-if="showPassword" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                </svg>
                                <svg v-else class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <p v-if="errors.password" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ errors.password[0] }}
                        </p>
                    </div>

                    <!-- Confirm Password Field -->
                    <div v-if="form.password">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Confirm New Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                :type="showConfirmPassword ? 'text' : 'password'"
                                :required="!!form.password"
                                class="block w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                :class="{ 'border-red-500 dark:border-red-400': errors.password_confirmation }"
                                placeholder="Confirm new password"
                            />
                            <button
                                type="button"
                                @click="showConfirmPassword = !showConfirmPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            >
                                <svg v-if="showConfirmPassword" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                </svg>
                                <svg v-else class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <p v-if="errors.password_confirmation" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ errors.password_confirmation[0] }}
                        </p>
                    </div>

                    <!-- Role Field -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="role"
                            v-model="form.role"
                            required
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            :class="{ 'border-red-500 dark:border-red-400': errors.role }"
                        >
                            <option value="">Select a role</option>
                            <option v-for="role in roles" :key="role.value" :value="role.value">
                                {{ role.label }}
                            </option>
                        </select>
                        <p v-if="errors.role" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ errors.role[0] }}
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
                                Active (admin can login and access the system)
                            </label>
                        </div>
                    </div>

                    <!-- Avatar Field -->
                    <div>
                        <label for="avatar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Profile Picture
                        </label>
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <img
                                    :src="avatarPreview || adminStore.currentAdmin.avatar_url"
                                    alt="Avatar preview"
                                    class="h-16 w-16 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600"
                                />
                            </div>
                            <div class="flex-1">
                                <input
                                    id="avatar"
                                    ref="avatarInput"
                                    type="file"
                                    accept="image/*"
                                    @change="handleAvatarChange"
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300"
                                />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    PNG, JPG, GIF up to 2MB
                                </p>
                            </div>
                        </div>
                        <p v-if="errors.avatar" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ errors.avatar[0] }}
                        </p>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <router-link
                            to="/secure/administrator/admins"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                        >
                            Cancel
                        </router-link>
                        <button
                            type="submit"
                            :disabled="adminStore.isLoading"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            <span v-if="adminStore.isLoading" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Updating...
                            </span>
                            <span v-else>Update Admin</span>
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
import { useAdminStore } from '../../stores/admin';
import AdminLayout from '../../components/layouts/AdminLayout.vue';

const route = useRoute();
const router = useRouter();
const adminStore = useAdminStore();

// Reactive data
const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: '',
    is_active: true,
    avatar: null,
});

const errors = ref({});
const successMessage = ref('');
const errorMessage = ref('');
const showPassword = ref(false);
const showConfirmPassword = ref(false);
const avatarPreview = ref('');
const avatarInput = ref(null);
const roles = ref([]);

// Methods
const handleAvatarChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            errorMessage.value = 'Avatar file size must be less than 2MB';
            return;
        }

        // Validate file type
        if (!file.type.startsWith('image/')) {
            errorMessage.value = 'Avatar must be an image file';
            return;
        }

        form.avatar = file;
        
        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            avatarPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        form.avatar = null;
        avatarPreview.value = '';
    }
};

const clearMessages = () => {
    successMessage.value = '';
    errorMessage.value = '';
    errors.value = {};
};

const populateForm = () => {
    if (adminStore.currentAdmin) {
        form.name = adminStore.currentAdmin.name;
        form.email = adminStore.currentAdmin.email;
        form.role = adminStore.currentAdmin.role;
        form.is_active = adminStore.currentAdmin.is_active;
        form.password = '';
        form.password_confirmation = '';
        form.avatar = null;
    }
};

const submitForm = async () => {
    clearMessages();
    
    try {
        await adminStore.updateAdmin(route.params.id, form);
        
        successMessage.value = 'Admin updated successfully!';
        
        // Redirect after a short delay
        setTimeout(() => {
            router.push('/secure/administrator/admins');
        }, 2000);
        
    } catch (error) {
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
        } else {
            errorMessage.value = error.response?.data?.message || error.message || 'Failed to update admin';
        }
    }
};

// Lifecycle
onMounted(async () => {
    try {
        // Fetch roles
        await adminStore.fetchRoles();
        roles.value = adminStore.roles;
        
        // Fetch admin data
        await adminStore.fetchAdmin(route.params.id);
        populateForm();
        
    } catch (error) {
        errorMessage.value = 'Failed to load admin data';
        console.error('Failed to initialize admin edit:', error);
    }
});

// Watch for admin data changes
watch(() => adminStore.currentAdmin, () => {
    if (adminStore.currentAdmin) {
        populateForm();
    }
});
</script>
