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
                 sans: ['Teachers', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    theme: {
        extend: {
            colors: {
                olive: '#584C08',
                cream: '#FFFAE0', // Note: Di gambar warnanya sedikit lebih gelap (seperti #E5DCC3), tapi saya ikuti instruksi #FFFAE0
                mustard: '#D3B514',
            }
        },
    },

    plugins: [forms],
};
