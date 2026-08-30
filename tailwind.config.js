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
                primary: '#A5780A',
                'primary-light': '#B8974F',
                'primary-dark': '#8A6408',
                secondary: '#AC8321',
                'secondary-light': '#B8974F',
                'text-primary': '#372828',
                'text-secondary': '#695956',
                'text-muted': '#B4AEA4',
                surface: 'var(--color-surface)',
                'surface-alt': '#EAEAE9',
                'surface-muted': '#B4AEA4',
                border: '#D5D3CE',
                'border-light': '#EAEAE9',
                white: '#F5F5F5',
            },
            fontFamily: {
                sans: ['Cairo', 'ui-sans-serif', 'system-ui', ...defaultTheme.fontFamily.sans],
                navbar: ['"IBM Plex Sans Arabic"', 'sans-serif'],
            },
        },
    },

    plugins: [forms],
};
