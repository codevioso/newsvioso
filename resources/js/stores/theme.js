import { defineStore } from 'pinia';
import { useDark, useToggle } from '@vueuse/core';

export const useThemeStore = defineStore('theme', {
    state: () => ({
        isDark: useDark(),
    }),

    getters: {
        themeClass: (state) => state.isDark ? 'dark' : 'light',
    },

    actions: {
        toggleTheme() {
            this.isDark = !this.isDark;
            this.saveTheme();
        },

        setTheme(isDark) {
            this.isDark = isDark;
            this.saveTheme();
        },

        saveTheme() {
            localStorage.setItem('admin_theme', this.isDark ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', this.isDark);
        },

        initTheme() {
            const savedTheme = localStorage.getItem('admin_theme');
            if (savedTheme) {
                this.isDark = savedTheme === 'dark';
            } else {
                this.isDark = false; // Default to light mode
            }
            this.saveTheme();
        },
    },
});
