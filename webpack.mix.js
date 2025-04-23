const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')    
    .postCss('resources/css/app.css', 'public/css', [
        require("tailwindcss"),
    ])
    .copy('node_modules/tabulator-tables/dist/css', 'public/css/tabulator')
    .copy('node_modules/tabulator-tables/dist/js', 'public/js/tabulator')
    // .postCss('vendor/filament/filament/resources/css/app.css', 'public/css', [
    //     require("tailwindcss"),
    // ])
    .sourceMaps()
    ;
