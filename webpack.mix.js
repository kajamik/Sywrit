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

/*mix.js('resources/js/app.js', 'public/js')
.sass('resource/sass/theme.scss', 'public/css');*/

mix.combine([
  'public/plugins/bootstrap/css/bootstrap.min.css',
  'public/css/app.css',
] ,'public/css/app.min.css')
// print
.styles([
  'public/css/print.css',
], 'public/css/print.min.css')
// admin
.styles([
  'public/css/toolbox/_dex.css',
], 'public/css/toolbox/_dex.min.css');

mix.combine([
  'public/js/jquery-3.2.1.min.js',
  'public/js/bootstrap/bootstrap.js',
  'public/js/app.js',
],'public/js/__dfg.js');

mix.minify([
  'public/js/core/bootstrap-material-design.min.js',
  'public/js/toolbox/_dex.js',
], 'public/js/toolbox/_dex.min.js');
