import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                background: '#F5F2EB',
                primary: '#AC8322',
                secondary: '#EBE5D7',
                accent: '#3D2D2D',
                'text-primary': '#3D2D2D',
            },
            fontFamily: {
                sans: ['Cairo', 'ui-sans-serif', 'system-ui', ...defaultTheme.fontFamily.sans],
                navbar: ['Cairo', 'sans-serif'],
            },
        },
    },

    plugins: [forms],
};
