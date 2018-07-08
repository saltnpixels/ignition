/**
 * Gulpfile.
 *
 * Gulp with WordPress.
 *
 * Implements:
 *      1. Live reloads browser with BrowserSync.
 *      2. CSS: Sass to CSS conversion, error catching, Autoprefixing, Sourcemaps,
 *         CSS minification, and Merge Media Queries.
 *      3. JS: Concatenates & uglifies Vendor and Custom JS files.
 *      4. Images: Minifies PNG, JPEG, GIF and SVG images.
 *      5. Watches files for changes in CSS or JS.
 *      6. Watches files for changes in PHP.
 *      7. Corrects the line endings.
 *      8. InjectCSS instead of browser page reload.
 *      9. Generates .pot file for i18n and l10n.
 *
 * @author Ahmad Awais (@ahmadawais) extended by ignition
 * @version 1.0.3
 */

/**
 * Configuration.
 *
 * Project Configuration for gulp tasks.
 *
 * In paths you can add <<glob or array of globs>>. Edit the variables as per your project requirements.
 */

// START Editing Project Variables.
// Project related.
var project = 'ignition'; // Project Name.
var projectURL = 'ignition.local'; // Project URL. Could be something like localhost:8888.
var productURL = './'; // Theme/Plugin URL. Leave it like it is, since our gulpfile.js lives in the root folder.
var src = './assets';
// Translation related.
var text_domain = 'ignition'; // Your textdomain here.
var destFile = 'ignition.pot'; // Name of the transalation file.
var packageName = 'ignition'; // Package name.
var bugReport = ''; // Where can users report bugs.
var lastTranslator = 'ignition <your_email@email.com>'; // Last translator Email ID.
var team = 'ignition <your_email@email.com>'; // Team's Email ID.
var translatePath = './languages' // Where to save the translation files.

// Style related.
var styleSRC = './assets/sass/*.scss'; // Path to main .scss file.
var styleDestination = './'; // Path to place the compiled CSS file.
// Default set to root folder.

// JS Vendor related.
var jsVendorSRC = './assets/js/vendors'; // Path to JS vendor folder.
var jsVendorDestination = './assets/js/min'; // Path to place the compiled JS vendors file.
var jsVendorFile = 'vendors'; // Compiled JS vendors file name.
// Default set to vendors i.e. vendors.js.

// JS Custom related. these files will have the task run on them.
var jsCustomSRC = ['./assets/js/setup.custom.js', './assets/js/*custom.js']; // Path to JS custom scripts folder. Make setup run first just in case you want to have certain things happen first
var jsCustomDestination = './assets/js/min'; // Path to place the compiled JS custom scripts file.
var jsCustomFile = 'custom'; // Compiled JS custom file name.
var jsNotCustomSRC = ['./assets/js/*.js', '!./assets/js/*custom.js']; //admin js files for back end or what not meant to be lumped into the big custom one
// Default set to custom i.e. custom.js.

// Images related.
var imagesSRC = './assets/images/raw/**/*.{png,jpg,gif,svg}'; // Source folder of images which should be optimized.
var imagesDestination = './assets/images/'; // Destination folder of optimized images. Must be different from the imagesSRC folder.

// Watch files paths. These files will trigger gulp to runs the tasks, not necessarily on them though.
var styleWatchFiles = 'assets/sass/*.scss'; // Path to all *.scss files inside css folder and inside them.
var vendorJSWatchFiles = 'assets/js/vendor/*.js'; // Path to all vendor JS files.
var customJSWatchFiles = 'assets/js/*.js'; // Path to all custom JS files.
var projectPHPWatchFiles = '**/*.php'; // Path to all PHP files.
var projectHTMLWatchFiles = '**/*.html'; // Path to all html files.

var useBootstrap = false;


// Browsers you care about for autoprefixing.
// Browserlist https        ://github.com/ai/browserslist
const AUTOPREFIXER_BROWSERS = [
    'last 2 version',
    '> 1%',
    'ie >= 9',
    'ie_mob >= 10',
    'ff >= 30',
    'chrome >= 34',
    'safari >= 7',
    'opera >= 23',
    'ios >= 7',
    'android >= 4',
    'bb >= 10'
];

// STOP Editing Project Variables.

/**
 * Load Plugins.
 *
 * Load gulp plugins and passing them semantic names.
 */
var gulp = require('gulp'); // Gulp of-course

// CSS related plugins.
var sass = require('gulp-sass'); // Gulp plugin for Sass compilation.
var sourcemaps = require('gulp-sourcemaps');
var minifycss = require('gulp-uglifycss'); // Minifies CSS files.
var autoprefixer = require('gulp-autoprefixer'); // Autoprefixing magic.
var mmq = require('gulp-merge-media-queries'); // Combine matching media queries into one media query definition.

// JS related plugins.
var concat = require('gulp-concat'); // Concatenates JS files
var uglify = require('gulp-uglify'); // Minifies JS files

// Image realted plugins.
var imagemin = require('gulp-imagemin'); // Minify PNG, JPEG, GIF and SVG images with imagemin.

// Utility related plugins.
var rename = require('gulp-rename'); // Renames files E.g. style.css -> style.min.css
var lineec = require('gulp-line-ending-corrector'); // Consistent Line Endings for non UNIX systems. Gulp Plugin for Line Ending Corrector (A utility that makes sure your files have consistent line endings)
var filter = require('gulp-filter'); // Enables you to work on a subset of the original files by filtering them using globbing.
var sourcemaps = require('gulp-sourcemaps'); // Maps code in a compressed file (E.g. style.css) back to it’s original position in a source file (E.g. structure.scss, which was later combined with other css files to generate style.css)
var notify = require('gulp-notify'); // Sends message notification to you
var browserSync = require('browser-sync').create(); // Reloads browser and injects CSS. Time-saving synchronised browser testing.
var reload = browserSync.reload; // For manual browser reload.
var wpPot = require('gulp-wp-pot'); // For generating the .pot file.
var sort = require('gulp-sort'); // Recommended to prevent unnecessary changes in pot-file.
var babel = require('gulp-babel');


// Move some JS Files to assets/js from node modules for using bootstrap if wanted.
if (useBootstrap) {
    gulp.task('moveJS', function () {
        return gulp.src(['node_modules/bootstrap/dist/js/bootstrap.min.js', 'node_modules/popper.js/dist/umd/popper.min.js'])
            .pipe(gulp.dest("assets/js/min"))
            .pipe(browserSync.stream());
    });
}


/**
 * Task: `browser-sync`.
 *
 * Live Reloads, CSS injections, Localhost tunneling.
 *
 * This task does the following:
 *    1. Sets the project URL
 *    2. Sets inject CSS
 *    3. You may define a custom port
 *    4. You may want to stop the browser from openning automatically
 */
gulp.task('browser-sync', function () {
    browserSync.init({

        // For more options
        // @link http://www.browsersync.io/docs/options/

        // Project URL. if you have one disable the server option below
        proxy: projectURL,

        // `true` Automatically open the browser with BrowserSync live server.
        // `false` Stop the browser from automatically opening.
        open: false,

        // Inject CSS changes.
        // Comment it to reload browser for every CSS change.
        injectChanges: true,

        //if not running on a server
        //server: "./",

        // Use a specific port (instead of the one auto-detected by Browsersync).
        //port: 8080,

    });
});


/**
 * Task: `styles`.
 *
 * Compiles Sass, Autoprefixes it and Minifies CSS.
 *
 * This task does the following:
 *    1. Gets the source scss file
 *    2. Compiles Sass to CSS
 *    3. Writes Sourcemaps for it
 *    4. Autoprefixes it and generates style.css
 *    5. Renames the CSS file with suffix .min.css
 *    6. Minifies the CSS file and generates style.min.css
 *    7. Injects CSS or reloads the browser via browserSync
 */
gulp.task('styles', function () {
    gulp.src( styleSRC )
        .pipe( sourcemaps.init() )
        .pipe( sass( {
            errLogToConsole: true,
            //outputStyle: 'compact',
             outputStyle: 'compressed',
            // outputStyle: 'nested',
            // outputStyle: 'expanded',
            precision: 10
        } ) )
        .on('error', console.error.bind(console))
        .pipe( sourcemaps.write( { includeContent: false } ) )
        //.pipe( sourcemaps.init( { loadMaps: true } ) )
        .pipe( autoprefixer( AUTOPREFIXER_BROWSERS ) )

        //.pipe( sourcemaps.write ( './' ) )
        .pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
        .pipe( gulp.dest( styleDestination ) )

        .pipe( filter( '**/*.css' ) ) // Filtering stream to only css files
        //.pipe( mmq( { log: true } ) ) // Merge Media Queries only for .min.css version. break when using @supports

        .pipe( browserSync.stream() ) // Reloads style.css if that is enqueued.

        .pipe( rename( { suffix: '.min' } ) )
        .pipe( minifycss( {
            maxLineLen: 10
        }))
        .pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
        .pipe( gulp.dest( styleDestination ) )

        .pipe( filter( '**/*.css' ) ) // Filtering stream to only css files
        .pipe( browserSync.stream() )// Reloads style.min.css if that is enqueued.
        .pipe( notify( { message: 'TASK: "styles" Completed! 💯', onLast: true } ) )
});



/**
 * Task: `vendorJS`.
 *
 * Concatenate and uglify vendor JS scripts.
 *
 * This task does the following:
 *     1. Gets the source folder for JS vendor files
 *     2. Concatenates all the files and generates vendors.js
 *     3. Renames the JS file with suffix .min.js
 *     4. Uglifes/Minifies the JS file and generates vendors.min.js
 */
gulp.task('vendorsJs', function () {
    gulp.src(jsVendorSRC)
        .pipe(babel({
            presets: ['env']
        }))
        .pipe(concat(jsVendorFile + '.js'))
        .pipe(lineec()) // Consistent Line Endings for non UNIX systems.
        .pipe(gulp.dest(jsVendorDestination))
        .pipe(rename({
            basename: jsVendorFile,
            suffix: '.min'
        }))
        .pipe(uglify())
        .pipe(lineec()) // Consistent Line Endings for non UNIX systems.
        .pipe(gulp.dest(jsVendorDestination))
        .pipe(notify({message: 'TASK: "vendorsJs" Completed! 💯', onLast: true}));
});


/**
 * Task: `customJS`.
 *
 * Concatenate and uglify custom JS scripts.
 *
 * This task does the following:
 *     1. Gets the source folder for JS custom files
 *     2. Concatenates all the files ending in custom.js and generates custom.js
 *     3. Renames the JS file with suffix .min.js
 *     4. Uglifes/Minifies the JS file and generates custom.min.js
 */
gulp.task('customJS', function () {
    gulp.src(jsCustomSRC)
        .pipe(babel({
            presets: ['env']
        }))
        .pipe(concat(jsCustomFile + '.js'))
        .pipe(lineec()) // Consistent Line Endings for non UNIX systems.
        .pipe(gulp.dest(jsCustomDestination))
        .pipe(rename({
            basename: jsCustomFile,
            suffix: '.min'
        }))
        .pipe(uglify())
        .pipe(lineec()) // Consistent Line Endings for non UNIX systems.
        .pipe(gulp.dest(jsCustomDestination))
        .pipe(notify({message: 'TASK: "customJs" Completed! 💯', onLast: true}));
});


/**
 * Task: `notCustomJS`.
 *
 * Uglify but dont concat admin JS scripts.
 *
 * This task does the following:
 *     1. Gets the source folder for JS admin files
 *     2. Renames the JS file with suffix .min.js
 *     3. Uglifes/Minifies the JS file.
 */
gulp.task('notCustomJS', function () {
    gulp.src(jsNotCustomSRC)
        .pipe(babel({
            presets: ['env']
        }))
        .pipe(lineec()) // Consistent Line Endings for non UNIX systems.
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(uglify())
        .pipe(lineec()) // Consistent Line
        // Endings for non UNIX systems.
        .pipe(gulp.dest(jsCustomDestination))
        .pipe(notify({message: 'TASK: "customJs" Completed! 💯', onLast: true}));
});


/**
 * Task: `images`.
 *
 * Minifies PNG, JPEG, GIF and SVG images.
 *
 * This task does the following:
 *     1. Gets the source of images raw folder
 *     2. Minifies PNG, JPEG, GIF and SVG images
 *     3. Generates and saves the optimized images
 *
 * This task will run only once, if you want to run it
 * again, do it with the command `gulp images`.
 */
gulp.task('images', function () {
    gulp.src(imagesSRC)
        .pipe(imagemin({
            progressive: true,
            optimizationLevel: 3, // 0-7 low-high
            interlaced: true,
            svgoPlugins: [{removeViewBox: false}]
        }))
        .pipe(gulp.dest(imagesDestination))
        .pipe(notify({message: 'TASK: "images" Completed! 💯', onLast: true}));
});


/**
 * WP POT Translation File Generator.
 *
 * * This task does the following:
 *     1. Gets the source of all the PHP files
 *     2. Sort files in stream by path or any custom sort comparator
 *     3. Applies wpPot with the variable set at the top of this file
 *     4. Generate a .pot file of i18n that can be used for l10n to build .mo file
 */
gulp.task('translate', function () {
    return gulp.src(projectPHPWatchFiles)
        .pipe(sort())
        .pipe(wpPot({
            domain: text_domain,
            destFile: destFile,
            package: packageName,
            bugReport: bugReport,
            lastTranslator: lastTranslator,
            team: team
        }))
        .pipe(gulp.dest(destFile))
        .pipe(notify({message: 'TASK: "translate" Completed! 💯', onLast: true}))

});


/**
 * Watch Tasks.
 *
 * Watches for file changes and runs specific tasks.
 * When gulp runs it will run the tasks and then watch some files for changes.
 */
gulp.task('default', ['styles', 'customJS', 'notCustomJS', 'images', 'translate', 'browser-sync'], function () {
    gulp.watch([projectPHPWatchFiles, projectHTMLWatchFiles], reload); // Reload on PHP file changes.

    gulp.watch(styleWatchFiles, ['styles']); // Reload on SCSS file changes.
    //gulp.watch( vendorJSWatchFiles, [ 'vendorsJs', reload ] ); // Reload on vendorsJs file changes.
    gulp.watch(customJSWatchFiles, ['customJS', 'notCustomJS', reload]); // Reload on customJS file changes.

});
