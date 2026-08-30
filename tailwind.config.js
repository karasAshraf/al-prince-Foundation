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
                primary: '#A38B54',
                'primary-light': '#B49C6E',
                'primary-dark': '#8A734A',
                secondary: '#766868',
                'secondary-light': '#979290',
                'text-primary': '#3D342A',
                'text-secondary': '#5C5450',
                'text-muted': '#979290',
                surface: '#EAEAE9',
                'surface-alt': '#AEA19F',
                'surface-muted': '#AFACA3',
                border: '#B7B5B3',
                'border-light': '#C5C2C0',
                white: '#FFFFFF',
            },
            fontFamily: {
                sans: ['Cairo', 'ui-sans-serif', 'system-ui', ...defaultTheme.fontFamily.sans],
                navbar: ['"IBM Plex Sans Arabic"', 'sans-serif'],
            },
        },
    },

    plugins: [forms],
};
