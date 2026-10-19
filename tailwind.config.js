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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    navy: '#1C3D5A',
                    sky: '#00A3E0',
                    sage: '#48BB78',
                    crimson: '#E53E3E',
                    charcoal: '#2D3748',
                    offwhite: '#F7FAFC'
                }
            }
        },
    },

    plugins: [forms],
};
