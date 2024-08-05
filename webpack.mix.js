const mix = require('laravel-mix');

// Define how you want to compile your assets here
mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');
