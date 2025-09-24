<template>
    <AdminLayout>
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Update Profile</h1>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Update your personal information and avatar
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <router-link
                            to="/secure/administrator/profile"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Profile
                        </router-link>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            <div v-if="successMessage" class="mb-6 rounded-md bg-green-50 dark:bg-green-900 p-4 transition-all duration-300 animate-fade-in">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                            {{ successMessage }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <div v-if="errorMessage" class="mb-6 rounded-md bg-red-50 dark:bg-red-900 p-4 transition-all duration-300 animate-fade-in">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">
                            {{ errorMessage }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Profile Update Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg transition-all duration-300">
                <form @submit.prevent="handleUpdateProfile" class="px-6 py-8">
                    <div class="space-y-8">
                        <!-- Avatar Section -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                                Profile Avatar
                            </label>
                            <div class="flex items-center space-x-6">
                                <div class="relative group">
                                    <!-- Show preview if new avatar selected -->
                                    <div v-if="avatarPreview" class="h-24 w-24 rounded-full overflow-hidden bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
                                        <img
                                            :src="avatarPreview"
                                            alt="Avatar preview"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>
                                    <!-- Show current avatar or fallback -->
                                    <Avatar 
                                        v-else
                                        :user="user" 
                                        :name="form.name"
                                        size="xl"
                                        :show-hover-overlay="true"
                                    />
                                </div>
                                <div class="flex-1">
                                    <input
                                        ref="avatarInput"
                                        type="file"
                                        accept="image/*"
                                        @change="handleAvatarChange"
                                        class="hidden"
                                    />
                                    <button
                                        type="button"
                                        @click="$refs.avatarInput.click()"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Choose Avatar
                                    </button>
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        JPG, PNG or GIF (max 2MB)
                                    </p>
                                </div>
                            </div>
                            <p v-if="errors.avatar" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ errors.avatar[0] }}
                            </p>
                        </div>

                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Full Name
                            </label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 text-gray-900 dark:text-white bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                :class="{ 'border-red-500 dark:border-red-400': errors.name }"
                                placeholder="Enter your full name"
                            />
                            <p v-if="errors.name" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ errors.name[0] }}
                            </p>
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email Address
                            </label>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 text-gray-900 dark:text-white bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                :class="{ 'border-red-500 dark:border-red-400': errors.email }"
                                placeholder="Enter your email address"
                            />
                            <p v-if="errors.email" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ errors.email[0] }}
                            </p>
                        </div>

                        <!-- Current Role Display -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Current Role
                            </label>
                            <div class="inline-flex items-center px-3 py-2 rounded-md bg-gray-100 dark:bg-gray-700">
                                <div class="w-2 h-2 rounded-full mr-2" :class="getRoleDotClass(user?.role)"></div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ user?.role_display_name || 'Unknown' }}
                                </span>
                            </div>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                Role changes require administrator approval
                            </p>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex items-center justify-end space-x-4">
                        <router-link
                            to="/secure/administrator/profile"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200"
                        >
                            Cancel
                        </router-link>
                        <button
                            type="submit"
                            :disabled="isLoading"
                            class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-105 disabled:transform-none"
                        >
                            <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ isLoading ? 'Updating...' : 'Update Profile' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';
import AdminLayout from '../../components/layouts/AdminLayout.vue';
import Avatar from '../../components/Avatar.vue';

const router = useRouter();
const authStore = useAuthStore();

const user = ref(null);
const form = reactive({
    name: '',
    email: '',
    avatar: null,
});

const errors = ref({});
const isLoading = ref(false);
const successMessage = ref('');
const errorMessage = ref('');
const avatarPreview = ref('');

const getRoleDotClass = (role) => {
    const classes = {
        'super_admin': 'bg-purple-500',
        'editor': 'bg-blue-500',
        'reporter': 'bg-green-500',
    };
    return classes[role] || 'bg-gray-500';
};

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
            errorMessage.value = 'Please select a valid image file';
            return;
        }

        form.avatar = file;
        
        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            avatarPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
        
        errorMessage.value = '';
    }
};

const handleUpdateProfile = async () => {
    errors.value = {};
    isLoading.value = true;
    successMessage.value = '';
    errorMessage.value = '';

    try {
        const response = await authStore.updateProfile(form);
        successMessage.value = response.message;
        
        // Update local user data
        user.value = response.user;
        
        // Redirect after success
        setTimeout(() => {
            router.push('/secure/administrator/profile');
        }, 2000);
    } catch (error) {
        if (error.errors) {
            errors.value = error.errors;
        } else {
            errorMessage.value = error.message || 'Failed to update profile. Please try again.';
        }
    } finally {
        isLoading.value = false;
    }
};

const fetchProfile = async () => {
    try {
        const response = await axios.get('/api/profile');
        user.value = response.data.user;
        form.name = user.value.name;
        form.email = user.value.email;
    } catch (error) {
        console.error('Error fetching profile:', error);
        errorMessage.value = 'Failed to load profile data';
    }
};

onMounted(() => {
    // Initialize with auth store user data
    user.value = authStore.user;
    if (user.value) {
        form.name = user.value.name;
        form.email = user.value.email;
    }
    fetchProfile();
});
</script>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
