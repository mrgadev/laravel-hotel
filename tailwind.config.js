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
                jakarta: ['Plus Jakarta Sans', 'sans-serif'],
                sora: ['Sora', 'sans-serif']
            },
            width: {
                a4: '210mm',
            },
            height: {
                a4: '297mm',
            },
            backgroundImage: {
                'hero-bg': "url('../img/Screenshot 2024-10-17 204712.png')",
            },
            colors: {
                'primary': {
                    100: '#eadfd6',
                    200: '#d5bfad',
                    300: '#c1a085',
                    400: '#ac805c',
                    500: '#976033',
                    600: '#794d29',
                    700: '#5b3a1f',
                    800: '#3c2614',
                    900: '#1e130a',
                },
            },

            animation: {
                'infinite-scroll': 'infinite-scroll 25s linear infinite',
            },
            keyframes: {
                'infinite-scroll': {
                    from: {transform: 'translateX(0)'},
                    to: {transform: 'translateX(-100%)'},
                }
            }
        },
       
    },

    plugins: [
        forms,
        // require('flowbite-typography')({
        //     wysiwyg: true,
        // }),
    ],
};
