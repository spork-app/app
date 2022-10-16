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
        require('tailwindcss'),
    ])
    .webpackConfig({
        resolve: {
            alias: {
                '@components': path.resolve(__dirname, 'resources/js/components'),
                '@routes': path.resolve(__dirname, 'resources/js/routes'),
                '@store': path.resolve(__dirname, 'resources/js/store'),
                '@icons': path.resolve(__dirname, 'resources/js/icons'),
                '@': path.resolve(__dirname, 'resources/js'),
                '@vendor': path.resolve(__dirname, 'vendor'),
                '@system': path.resolve(__dirname, 'system'),
                '@core': path.resolve(__dirname, 'vendor/spork/core/resources'),
            }
        },
    })    
    .vue()
    .copy('node_modules/ace-builds/src-min-noconflict/ext-*.js', 'public/js/')
    .copy('node_modules/ace-builds/src-min-noconflict/mode-*.js', 'public/js/')
    .copy('node_modules/ace-builds/src-min-noconflict/keybinding-*.js', 'public/js/')
    .copy('node_modules/ace-builds/src-min-noconflict/theme-*.js', 'public/js/')
    .copy('node_modules/ace-builds/src-min-noconflict/worker-*.js', 'public/js/')
    .copy('resources/sounds/*.ogg', 'public')
    .copy('resources/images/*.png', 'public')
    .js([
        'resources/js/app.js',
        // This must be the last file in the list
        'resources/js/boot.js',
     ], 'public/js')
    .disableNotifications()
