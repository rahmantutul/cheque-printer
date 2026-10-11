import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                primary: '#FF2D20',
                'primary-dark': '#D9241A',
                admin: '#1E3A8A',
                user: '#0F766E',
                surface: '#FFFFFF',
                'surface-alt': '#F8FAFC',
                border: '#E2E8F0',
                text: '#0F172A',
                'text-muted': '#64748B',
                success: '#16A34A',
                warning: '#F59E0B',
                danger: '#DC2626',
            },
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            borderRadius: {
                DEFAULT: '0.5rem',
                lg: '0.75rem',
            },
        },
    },
    plugins: [forms],
};
