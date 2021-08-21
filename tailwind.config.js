const colors = require('tailwindcss/colors')

module.exports = {
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/scripts/**/*.{js,ts}',
        './resources/views/**/*.{vue,blade.php}',
    ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
        colors: {
            gray: colors.warmGray,
        },
        transitionProperty: {
            'height': 'height',
        },
        spacing: {
            '-10xl': '1000px',
        },
        width: {
            '1/24': '0.04167%',
            '2/24': '0.08333%',
            '3/24': '0.125%',
            '4/24': '0.16667%',
            '5/24': '0.20833%',
            '6/24': '0.25%',
            '7/24': '0.29167%',
            '8/24': '0.33333%',
            '9/24': '0.375%',
            '10/24': '0.41667%',
            '11/24': '0.45833%',
            '12/24': '0.5%',
        },
        screens: {
            'print': {'raw': 'print'},
        }
    },
  },
  variants: {
    extend: {
        opacity: ['disabled'],
        cursor: ['disabled'],
        display: ['group-hover', 'hover'],
    }
  },
  plugins: [],
}
