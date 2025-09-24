<template>
    <AdminLayout>
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Change Password</h1>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Update your account password for better security
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

            <!-- Security Notice -->
            <div class="mb-8 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                            Password Security Tips
                        </h3>
                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Use at least 8 characters with a mix of letters, numbers, and symbols</li>
                                <li>Avoid using personal information or common words</li>
                                <li>Consider using a password manager for better security</li>
                            </ul>
                        </div>
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
                        <h3 class="text-sm font-medium text-green-800 dark:text-green-200">
                            Password Updated Successfully
                        </h3>
                        <div class="mt-2 text-sm text-green-700 dark:text-green-300">
                            <p>{{ successMessage }}</p>
                        </div>
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
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                            Password Update Failed
                        </h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                            <p>{{ errorMessage }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Change Password Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg transition-all duration-300">
                <form @submit.prevent="handleChangePassword" class="px-6 py-8">
                    <div class="space-y-6">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Current Password
                            </label>
                            <div class="relative">
                                <input
                                    id="current_password"
                                    v-model="form.current_password"
                                    :type="showCurrentPassword ? 'text' : 'password'"
                                    required
                                    class="block w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 text-gray-900 dark:text-white bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                    :class="{ 'border-red-500 dark:border-red-400': errors.current_password }"
                                    placeholder="Enter your current password"
                                />
                                <button
                                    type="button"
                                    @click="showCurrentPassword = !showCurrentPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                >
                                    <svg v-if="showCurrentPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                    </svg>
                                    <svg v-else class="h-5 w-5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            <p v-if="errors.current_password" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ errors.current_password[0] }}
                            </p>
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                New Password
                            </label>
                            <div class="relative">
                                <input
                                    id="password"
                                    v-model="form.password"
                                    :type="showPassword ? 'text' : 'password'"
                                    required
                                    class="block w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 text-gray-900 dark:text-white bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                    :class="{ 'border-red-500 dark:border-red-400': errors.password }"
                                    placeholder="Enter your new password"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                >
                                    <svg v-if="showPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                    </svg>
                                    <svg v-else class="h-5 w-5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Password Strength Indicator -->
                            <div class="mt-2">
                                <div class="flex items-center space-x-2">
                                    <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div 
                                            class="h-2 rounded-full transition-all duration-300"
                                            :class="getPasswordStrengthClass()"
                                            :style="{ width: getPasswordStrength() + '%' }"
                                        ></div>
                                    </div>
                                    <span class="text-xs font-medium" :class="getPasswordStrengthTextClass()">
                                        {{ getPasswordStrengthText() }}
                                    </span>
                                </div>
                            </div>
                            
                            <p v-if="errors.password" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ errors.password[0] }}
                            </p>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Confirm New Password
                            </label>
                            <div class="relative">
                                <input
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    :type="showConfirmPassword ? 'text' : 'password'"
                                    required
                                    class="block w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 text-gray-900 dark:text-white bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                    :class="{ 'border-red-500 dark:border-red-400': errors.password_confirmation }"
                                    placeholder="Confirm your new password"
                                />
                                <button
                                    type="button"
                                    @click="showConfirmPassword = !showConfirmPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                >
                                    <svg v-if="showConfirmPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                    </svg>
                                    <svg v-else class="h-5 w-5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            <p v-if="errors.password_confirmation" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ errors.password_confirmation[0] }}
                            </p>
                        </div>

                        <!-- Password Match Indicator -->
                        <div v-if="form.password_confirmation" class="flex items-center space-x-2">
                            <svg 
                                class="h-5 w-5 transition-all duration-200"
                                :class="form.password === form.password_confirmation ? 'text-green-500' : 'text-red-500'"
                                fill="currentColor" 
                                viewBox="0 0 20 20"
                            >
                                <path v-if="form.password === form.password_confirmation" fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                <path v-else fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <span 
                                class="text-sm transition-all duration-200"
                                :class="form.password === form.password_confirmation ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"
                            >
                                {{ form.password === form.password_confirmation ? 'Passwords match' : 'Passwords do not match' }}
                            </span>
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
                            :disabled="isLoading || form.password !== form.password_confirmation"
                            class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-105 disabled:transform-none"
                        >
                            <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ isLoading ? 'Updating...' : 'Update Password' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';
import AdminLayout from '../../components/layouts/AdminLayout.vue';

const router = useRouter();
const authStore = useAuthStore();

const form = reactive({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const errors = ref({});
const isLoading = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

const showCurrentPassword = ref(false);
const showPassword = ref(false);
const showConfirmPassword = ref(false);

const getPasswordStrength = () => {
    const password = form.password;
    if (!password) return 0;
    
    let strength = 0;
    if (password.length >= 8) strength += 20;
    if (password.length >= 12) strength += 20;
    if (/[a-z]/.test(password)) strength += 20;
    if (/[A-Z]/.test(password)) strength += 20;
    if (/[0-9]/.test(password)) strength += 10;
    if (/[^A-Za-z0-9]/.test(password)) strength += 10;
    
    return Math.min(strength, 100);
};

const getPasswordStrengthText = () => {
    const strength = getPasswordStrength();
    if (strength < 30) return 'Weak';
    if (strength < 60) return 'Fair';
    if (strength < 80) return 'Good';
    return 'Strong';
};

const getPasswordStrengthClass = () => {
    const strength = getPasswordStrength();
    if (strength < 30) return 'bg-red-500';
    if (strength < 60) return 'bg-yellow-500';
    if (strength < 80) return 'bg-blue-500';
    return 'bg-green-500';
};

const getPasswordStrengthTextClass = () => {
    const strength = getPasswordStrength();
    if (strength < 30) return 'text-red-600 dark:text-red-400';
    if (strength < 60) return 'text-yellow-600 dark:text-yellow-400';
    if (strength < 80) return 'text-blue-600 dark:text-blue-400';
    return 'text-green-600 dark:text-green-400';
};

const handleChangePassword = async () => {
    errors.value = {};
    isLoading.value = true;
    successMessage.value = '';
    errorMessage.value = '';

    try {
        const response = await authStore.changePassword(form);
        successMessage.value = response.message;
        
        // Reset form
        form.current_password = '';
        form.password = '';
        form.password_confirmation = '';
        
        // Redirect after success
        setTimeout(() => {
            router.push('/secure/administrator/profile');
        }, 3000);
    } catch (error) {
        if (error.errors) {
            errors.value = error.errors;
        } else {
            errorMessage.value = error.message || 'Failed to change password. Please try again.';
        }
    } finally {
        isLoading.value = false;
    }
};
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
