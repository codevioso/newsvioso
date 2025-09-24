<template>
    <AdminLayout>
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Profile</h1>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Manage your account settings and preferences
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <router-link
                            to="/secure/administrator/profile/update"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Profile
                        </router-link>
                    </div>
                </div>
            </div>

            <!-- Profile Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg transition-all duration-300 hover:shadow-xl">
                <div class="px-6 py-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:space-x-8">
                        <!-- Avatar Section -->
                        <div class="flex-shrink-0 mb-6 lg:mb-0">
                            <div class="relative group">
                                <Avatar 
                                    :user="user" 
                                    size="2xl"
                                    :show-hover-overlay="true"
                                />
                            </div>
                        </div>

                        <!-- User Information -->
                        <div class="flex-1 min-w-0">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                        Full Name
                                    </label>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ user?.name || 'Not provided' }}
                                    </p>
                                </div>

                                <!-- Email -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                        Email Address
                                    </label>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ user?.email || 'Not provided' }}
                                    </p>
                                </div>

                                <!-- Role -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                        Role
                                    </label>
                                    <div class="flex items-center space-x-2">
                                        <span 
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium transition-all duration-200"
                                            :class="getRoleBadgeClass(user?.role)"
                                        >
                                            <div class="w-2 h-2 rounded-full mr-2" :class="getRoleDotClass(user?.role)"></div>
                                            {{ user?.role_display_name || 'Unknown' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Member Since -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                        Member Since
                                    </label>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ user?.created_at || 'Unknown' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Update Profile Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg transition-all duration-300 hover:shadow-xl hover:scale-105">
                    <div class="px-6 py-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-lg bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                    Update Profile
                                </h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Edit your personal information and avatar
                                </p>
                            </div>
                            <div class="ml-4">
                                <router-link
                                    to="/secure/administrator/profile/update"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900 hover:bg-blue-200 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                                >
                                    Edit
                                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Change Password Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg transition-all duration-300 hover:shadow-xl hover:scale-105">
                    <div class="px-6 py-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-lg bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                    Change Password
                                </h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Update your account password
                                </p>
                            </div>
                            <div class="ml-4">
                                <router-link
                                    to="/secure/administrator/profile/change/password"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900 hover:bg-green-200 dark:hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200"
                                >
                                    Change
                                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '../../stores/auth';
import AdminLayout from '../../components/layouts/AdminLayout.vue';
import Avatar from '../../components/Avatar.vue';

const authStore = useAuthStore();
const user = ref(null);

const getRoleBadgeClass = (role) => {
    const classes = {
        'super_admin': 'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200',
        'editor': 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
        'reporter': 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
    };
    return classes[role] || 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200';
};

const getRoleDotClass = (role) => {
    const classes = {
        'super_admin': 'bg-purple-500',
        'editor': 'bg-blue-500',
        'reporter': 'bg-green-500',
    };
    return classes[role] || 'bg-gray-500';
};

const fetchProfile = async () => {
    try {
        const response = await axios.get('/api/profile');
        user.value = response.data.user;
    } catch (error) {
        console.error('Error fetching profile:', error);
    }
};

onMounted(() => {
    // Initialize with auth store user data
    user.value = authStore.user;
    fetchProfile();
});
</script>
