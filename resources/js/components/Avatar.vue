<template>
    <div 
        class="relative inline-flex items-center justify-center overflow-hidden rounded-full bg-gradient-to-br from-blue-500 to-purple-600 shadow-lg transition-all duration-200"
        :class="sizeClasses"
    >
        <!-- Avatar Image -->
        <img
            v-if="avatarUrl"
            :src="avatarUrl"
            :alt="altText"
            class="h-full w-full object-cover"
            @error="handleImageError"
        />
        
        <!-- Fallback Initials -->
        <div v-else class="text-white font-bold select-none" :class="textSizeClasses">
            {{ initials }}
        </div>
        
        <!-- Hover Overlay (optional) -->
        <div 
            v-if="showHoverOverlay"
            class="absolute inset-0 rounded-full bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center"
        >
            <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    user: {
        type: Object,
        default: null
    },
    name: {
        type: String,
        default: ''
    },
    avatar: {
        type: String,
        default: null
    },
    size: {
        type: String,
        default: 'md', // xs, sm, md, lg, xl, 2xl
        validator: (value) => ['xs', 'sm', 'md', 'lg', 'xl', '2xl'].includes(value)
    },
    showHoverOverlay: {
        type: Boolean,
        default: false
    }
});

const imageError = ref(false);

const avatarUrl = computed(() => {
    if (imageError.value) return null;
    
    // Check if avatar is a full URL or a path
    if (props.avatar) {
        if (props.avatar.startsWith('http')) {
            return props.avatar;
        } else {
            return `/storage/${props.avatar}`;
        }
    }
    
    // Check user object for avatar
    if (props.user?.avatar) {
        if (props.user.avatar.startsWith('http')) {
            return props.user.avatar;
        } else {
            return `/storage/${props.user.avatar}`;
        }
    }
    
    return null;
});

const displayName = computed(() => {
    return props.user?.name || props.name || 'User';
});

const initials = computed(() => {
    const name = displayName.value;
    if (!name) return 'U';
    
    const words = name.trim().split(' ');
    if (words.length === 1) {
        return words[0].charAt(0).toUpperCase();
    }
    
    return (words[0].charAt(0) + words[words.length - 1].charAt(0)).toUpperCase();
});

const altText = computed(() => {
    return `${displayName.value}'s avatar`;
});

const sizeClasses = computed(() => {
    const sizes = {
        xs: 'h-6 w-6',
        sm: 'h-8 w-8',
        md: 'h-10 w-10',
        lg: 'h-12 w-12',
        xl: 'h-16 w-16',
        '2xl': 'h-24 w-24'
    };
    return sizes[props.size];
});

const textSizeClasses = computed(() => {
    const sizes = {
        xs: 'text-xs',
        sm: 'text-sm',
        md: 'text-base',
        lg: 'text-lg',
        xl: 'text-xl',
        '2xl': 'text-2xl'
    };
    return sizes[props.size];
});

const handleImageError = () => {
    imageError.value = true;
};
</script>
