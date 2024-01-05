import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import colors from "tailwindcss/colors.js";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['K2D', ...defaultTheme.fontFamily.sans],
            },
            colors:{
                'aaron': {
                    '50': '#ecf5ff',
                    '100': '#ddecff',
                    '200': '#c1dbff',
                    '300': '#9cc2ff',
                    '400': '#00B3DD',
                    '500': '#5866CF',
                    '600': '#4654BE',
                    '700': '#313FA7',
                    '800': '#2438af',
                    '900': '#1C245F',
                    '950': '#0b0f28',
                },
                'pink': colors.pink,
                'slate': colors.slate

            }
        },
    },

    plugins: [forms, typography],
};
