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
                primary: {
                    DEFAULT: '#1D293D',
                    50: '#e8eaee',
                    100: '#d1d5dd',
                    600: '#1D293D',
                    700: '#151d2e',
                    800: '#0f1622',
                },
                brand: {
                    DEFAULT: '#0F172B',
                    50: '#e8e9ec',
                    100: '#d1d3d9',
                    200: '#a3a7b3',
                    600: '#0F172B',
                    700: '#0c1222',
                    800: '#090e19',
                },
                navbar: '#16a34a', // green for landing navbar
            },
            boxShadow: {
                'brand': '0 20px 25px -5px rgba(15, 23, 43, 0.08), 0 8px 10px -6px rgba(15, 23, 43, 0.05)',
            },
        },
    },

    plugins: [forms],
};
