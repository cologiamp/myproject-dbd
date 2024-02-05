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

            },
            keyframes: {
                'fade-from-bottom-50': {
                    '0%, 100%': {transform: 'translateY(50px)'},
                    '0%': {opacity: '0', transform: 'translateY(50px)'},
                    '100%': {opacity: '100', transform: 'translateY(0px)'}
                },
                'fade-from-bottom-30': {
                    '0%, 100%': {transform: 'translateY(30px)'},
                    '0%': {opacity: '0', transform: 'translateY(30px)'},
                    '100%': {opacity: '100', transform: 'translateY(0px)'}
                },
                'scale-inwards': {
                    '0%': {opacity: '0', transform: 'scale(0.5)'},
                    '100%': {opacity: '100', transform: 'scale(1)'}
                }
            },
            animation:{
                'fade-from-bottom-50': 'fade-from-bottom-50 1s ease-in 0s 1 normal forwards',
                'fade-from-bottom-30': 'fade-from-bottom-30 1.5s ease-in 0s 1 normal forwards',
                'scale-inwards': 'scale-inwards 1.5s ease-in 0s 1 normal forwards'
            },
            screens: {
                '3xl': '1980px',
                ...defaultTheme.screens
            }
        },
    },

    plugins: [forms, typography],
};
