const mix = require('laravel-mix');

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

mix.copy(
    'node_modules/@fortawesome/fontawesome-free/webfonts',
    'public/webfonts'
);

mix.copyDirectory('vendor/tinymce/tinymce', 'public/js/tinymce');

mix.js('resources/js/init/tinymce-init.js', 'public/js/init')
    .js('resources/js/init/virtual-select-init.js', 'public/js/init')
    .js('resources/js/ticket-jquery.js', 'public/js')
    .js('resources/js/toaster-message.js', 'public/js')
    .js('resources/js/roles/staff/staff.js', 'public/js/roles/staff')
    .js('resources/js/roles/staff/dependent-dropdown.js', 'public/js/roles/staff')
    .js('resources/js/roles/staff/approver.js', 'public/js/roles/staff')

mix.sass('resources/sass/fontawesome.scss', 'public/css/icons')
    .sass('resources/sass/bootstrap-icons.scss', 'public/css/icons')
    .sass('resources/sass/auth.scss', 'public/css')
    .sass('resources/sass/feedback.scss', 'public/css')
    .sass('resources/sass/roles/user.scss', 'public/css/roles')
    .sass('resources/sass/roles/staff.scss', 'public/css/roles')
    .sass('resources/sass/roles/approver.scss', 'public/css/roles')
    .sass('resources/sass/select/custom-virtual-select.scss', 'public/css/select')
