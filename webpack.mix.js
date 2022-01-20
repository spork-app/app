const mix = require('laravel-mix');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('autoprefixer'),
        require('tailwindcss')
    ])
    .webpackConfig({
        resolve: {
            alias: {
                '@components': path.resolve(__dirname, 'resources/js/components'),
                '@routes': path.resolve(__dirname, 'resources/js/routes'),
                '@store': path.resolve(__dirname, 'resources/js/store'),
                '@icons': path.resolve(__dirname, 'resources/js/icons'),
                '@': path.resolve(__dirname, 'resources/js'),
                '@system': path.resolve(__dirname, 'system'),
            }
        }
    })
    .vue()
    .js([
        'resources/js/app.js',
        // This must be the last file in the list
        'resources/js/boot.js',
     ], 'public/js')
    .disableNotifications()
