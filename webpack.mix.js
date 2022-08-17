const mix = require('laravel-mix');
let fs = require('fs');

let getFiles = function (dir) {
    // get all 'files' in this directory
    // filter directories
    return fs.readdirSync(dir).filter((file) => {
        return fs.statSync(`${dir}/${file}`).isFile();
    });
};

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
    // theme css
    .sass('resources/css/theme/sass/style.scss', 'public/css/theme/')
    .sass('resources/css/theme/sass/style.dark.scss', 'public/css/theme/')
    .sass('resources/css/theme/sass/plugins.scss', 'public/css/theme/')
    .sass('resources/css/theme/sass/plugins.dark.scss', 'public/css/theme/')
    // application custom css
    .sass('resources/css/app/app.scss', 'public/css/app')
    // application general js file
    .js('resources/js/app.js', 'public/js/app/')
    // vendors files
    .copy('node_modules/jquery/dist/jquery.min.*', 'public/vendors/jquery/')
    .copy('node_modules/jquery-validation/dist/jquery.validate.min.js', 'public/vendors/jquery-validate/')
    .copy('node_modules/jquery-validation/dist/additional-methods.min.js', 'public/vendors/jquery-validate/')
    .copy('node_modules/sweetalert2/dist/sweetalert2.min.*', 'public/vendors/sweetalert2/')
    .copy('node_modules/select2/dist/js/select2.full.min.js', 'public/vendors/select2/select2.min.js')
    .copy('node_modules/select2/dist/css/select2.min.css', 'public/vendors/select2/')
    .copy('node_modules/bootstrap-icons/font/bootstrap-icons.css', 'public/vendors/bootstrap-icons/')
    .copy('node_modules/bootstrap-icons/font/fonts/', 'public/vendors/bootstrap-icons/fonts/')
    .copy('node_modules/dayjs/dayjs.min.js', 'public/vendors/dayjs/')
    .copy('node_modules/daterangepicker/daterangepicker.*', 'public/vendors/daterangepicker/')
    .copy('node_modules/daterangepicker/moment.min.js', 'public/vendors/daterangepicker/')
    .copy('node_modules/axios/dist/axios.min.js', 'public/vendors/axios/')
    .copy('node_modules/toastr/build/toastr.min.*', 'public/vendors/toastr/')
    .copy('node_modules/cropperjs/dist/cropper.min.*', 'public/vendors/cropperjs/')
    .copy('resources/vendors/datatables/*.css', 'public/vendors/datatables/')
    .copy('resources/vendors/datatables/*.js', 'public/vendors/datatables/')
    .copy('resources/vendors/datatables/images/', 'public/vendors/datatables/images/')
    .copy('resources/vendors/datatables/plugins/', 'public/vendors/datatables/plugins/')
    .copy('resources/vendors/i18next/i18next.min.js', 'public/vendors/i18next/')
    .copy('resources/vendors/jquery-ui/jquery-ui-1.13.2.custom/jquery-ui.min.css', 'public/vendors/jquery-ui/')
    .copy(['resources/media/'], 'public/media') // copy icons
    .copy(['resources/langjs/'], 'public/lang') // copy json translations for js localization
    .copy(['resources/js/custom/'], 'public/js/custom/') // copy custom js
    .copy('node_modules/dropzone/dist/dropzone-min.js', 'public/vendors/dropzone/')
    .copy('node_modules/dropzone/dist/dropzone.css', 'public/vendors/dropzone/')
    .copy('resources/js/theme/vendors/plugins/dropzone.init.js', 'public/js/custom/dropzone/') // copy custom js

    .sourceMaps();

// application pages

let appResFolder = 'resources/js/app/';

let appFolders = ['authentication', 'account', 'users'];
// let appFolders = ['permissions', 'pages'];
for (let folder of appFolders) {
    getFiles(appResFolder + folder).forEach(function (filepath) {
        mix.js(appResFolder + folder + '/' + filepath, 'js/app/' + folder);
    });
}

let appModulesFolders = {
    balancesheet: ['targets', 'monthly-balance', 'daily-balance', 'services', 'statistics'],
};

// let appModulesFolders = {
//     balancesheet: ['targets'],
// };

Object.keys(appModulesFolders).forEach((moduleName) => {
    for (let folder of appModulesFolders[moduleName]) {
        getFiles(appResFolder + moduleName + '/' + folder).forEach(function (filepath) {
            mix.js(appResFolder + moduleName + '/' + folder + '/' + filepath, 'js/app/' + moduleName + '/' + folder);
        });
    }
});

// mix theme js files from folders ... but this doesn't get the order properly
// mix.combine(['resources/js/theme/components/*.js', 'resources/js/theme/layout/*.js'], 'public/js/theme/scripts.js')
//     .sourceMaps()

// mix theme js files in correct order
mix.combine(
    [
        'resources/js/theme/components/util.js',
        'resources/js/theme/components/blockui.js',
        'resources/js/theme/components/cookie.js',
        'resources/js/theme/components/dialer.js',
        'resources/js/theme/components/drawer.js',
        'resources/js/theme/components/event-handler.js',
        'resources/js/theme/components/feedback.js',
        'resources/js/theme/components/image-input.js',
        'resources/js/theme/components/menu.js',
        'resources/js/theme/components/password-meter.js',
        'resources/js/theme/components/scroll.js',
        'resources/js/theme/components/scrolltop.js',
        'resources/js/theme/components/search.js',
        'resources/js/theme/components/stepper.js',
        'resources/js/theme/components/sticky.js',
        'resources/js/theme/components/swapper.js',
        'resources/js/theme/components/toggle.js',
        'node_modules/smooth-scroll/dist/smooth-scroll.js',

        'resources/js/theme/layout/app.js',
        'resources/js/theme/layout/aside.js',
        'resources/js/theme/layout/explore.js',
        'resources/js/theme/layout/search.js',
        // 'resources/js/theme/layout/toolbar.js', // not used

        'resources/js/theme/vendors/plugins/select2.init.js',
        'resources/js/theme/vendors/plugins/sweetalert2.init.js',
    ],
    'public/js/theme/scripts.js'
).sourceMaps();

// --------------- partial compilation, no reason to compile all files (copied from above) ------------------ //

// mix.copy(['resources/media/'], 'public/media');

// mix.copy('node_modules/bootstrap-icons/font/bootstrap-icons.css', 'public/vendors/bootstrap-icons/')
//     .copy('node_modules/bootstrap-icons/font/fonts/', 'public/vendors/bootstrap-icons/fonts/');

// mix.js('resources/js/app.js', 'public/js/app');
// mix.copy(['resources/langjs/'], 'public/lang/');
// mix.copy('resources/vendors/jquery-ui/jquery-ui-1.13.2.custom/', 'public/vendors/jquery-ui/');
// mix.copy('node_modules/jquery-validation/dist/jquery.validate.min.js', 'public/vendors/jquery-validate/')
//     .copy('node_modules/jquery-validation/dist/additional-methods.min.js', 'public/vendors/jquery-validate/');

// mix.copy(
//     "node_modules/select2/dist/js/select2.full.min.js",
//     "public/vendors/select2/select2.min.js"
// ).copy(
//     "node_modules/select2/dist/css/select2.min.css",
//     "public/vendors/select2/"
// );

// mix.sass('resources/css/app/app.scss', 'public/css/app');
// mix.copy('node_modules/jquery/dist/jquery.min.*', 'public/vendors/jquery/');
//     .copy('node_modules/sweetalert2/dist/sweetalert2.min.js', 'public/vendors/sweetalert2/')
//     .copy('node_modules/sweetalert2/dist/sweetalert2.min.css', 'public/vendors/sweetalert2/');

// mix.copy('resources/vendors/datatables/*.css', 'public/vendors/datatables/')
//     .copy('resources/vendors/datatables/*.js', 'public/vendors/datatables/')
//     .copy('resources/vendors/datatables/images/', 'public/vendors/datatables/images/');
// mix.copy('resources/vendors/datatables/plugins/', 'public/vendors/datatables/plugins/');
// mix.copy(['resources/langjs/'], 'public/lang'); // copy json translations for js localization
//  mix.copy('node_modules/toastr/build/toastr.min.*', 'public/vendors/toastr/');
// mix.copy(['resources/js/custom/'], 'public/js/custom/');

// mix.copy('node_modules/cropperjs/dist/cropper.min.*', 'public/vendors/cropperjs/');
