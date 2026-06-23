import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                geg: {
                    yellow: '#FFD500',
                    'yellow-dark': '#E6A000',
                    'yellow-light': '#FFF3B0',
                    black: '#0F0F0F',
                    success: '#059669',
                    danger: '#dc2626',
                },
            },
        },
    },

    plugins: [forms],
};
