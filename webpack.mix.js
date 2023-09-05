const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');
require('laravel-mix-purgecss');

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
    .sass('resources/sass/frontendapp.scss', 'public/css')
    .sass('resources/sass/style.scss', 'public/css')
    .sass('resources/sass/custom.scss', 'public/css')
    .sass('resources/sass/backendapp.scss', 'public/css')
    .sass('resources/sass/backendcustom.scss', 'public/css')
    .sass('resources/sass/sb-admin-2.scss', 'public/css');

mix.version();