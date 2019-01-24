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

mix.styles([
  'public/plugins/bootstrap/css/bootstrap.min.css',
  'public/css/app.css',
] ,'public/css/app.min.css')
.js([
  'public/js/bootstrap/bootstrap.min.js',
  'public/js/app.js',
],'public/js/app.min.js');
