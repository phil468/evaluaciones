import colors from 'tailwindcss/colors' 
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography' 

const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {    
    content: [
        ,"./src/**/*.{html,js}",
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php', 
    ],
    darkMode: 'class',
    presets: [
        // require('./vendor/wireui/wireui/tailwind.config.js')
    ],
    theme: {
        extend: {
            colors: { 
                danger: colors.rose,
                primary: {
                    '50': '#E0F7FA',  // Cambia estos valores a los que desees
                    '100': '#B2EBF2', // Cambia estos valores a los que desees
                    '200': '#80DEEA', // Cambia estos valores a los que desees
                    '300': '#4DD0E1', // Cambia estos valores a los que desees
                    '400': '#26C6DA', // Cambia estos valores a los que desees
                    '500': '#00BCD4', // Cambia estos valores a los que desees
                    '600': '#00ACC1', // Cambia estos valores a los que desees
                    '700': '#0097A7', // Cambia estos valores a los que desees
                    '800': '#00838F', // Cambia estos valores a los que desees
                    '900': '#006064', // Cambia estos valores a los que desees
                },                
                success: colors.green,
                warning: colors.yellow,
            }, 
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/aspect-ratio'),
        require('@tailwindcss/typography'),
        // forms, 
        // typography, 
    ],
};
