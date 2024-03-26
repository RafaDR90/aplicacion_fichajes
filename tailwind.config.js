import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'primary': '',
                'primary-light': '#F0F9FF',
                'primary-strong': '#2563EB',
                'gris-light': '#F3F4F6',
                'gris-borde': '#D1D5DB',
                'azul-fondo-btn': '#3B82F6',
                'azul-fondo-btn-hover': '#2563EB',
                'azul-fondo-btn-pulse': '#1D4CBA',
              }
        },
    },

    plugins: [forms],
};
