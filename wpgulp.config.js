/**
 * WPGulp Configuration File
 *
 * 1. Edit the variables as per your project requirements.
 * 2. In paths you can add <<glob or array of globs>>.
 *
 * @package WPGulp
 */

module.exports = {

	// Project options.
	projectURL: 'https://ignition.local', // Local project URL of your already running WordPress site. Could be something like wpgulp.local or localhost:3000 depending upon your local WordPress setup.
	productURL: './', // Theme/Plugin URL. Leave it like it is, since our gulpfile.js lives in the root folder.
	browserAutoOpen: false,
	injectChanges: true,

	//Template Part sass concat and place into sass folder for compiling
	otherStyles: ['./template-parts/**/*.scss', './inc/**/*.scss'], //getting any sass from the template parts folder for concatenation
	otherStylesDestination: './assets/sass/wordpress', //put it all into one big file inside wordpress folder
	otherStylesFiles: '_template-parts', //name of sass concat file


	// Style options.
	styleSRC: './assets/sass/*.scss', // Path to main .scss file.
	styleDestination: './dist', // Path to place the compiled CSS file. Default set to root folder.
	outputStyle: 'compressed', // Available options → 'compact' or 'compressed' or 'nested' or 'expanded'
	errLogToConsole: true,
	precision: 10,

	// JS Vendor options.
	jsVendorSRC: './assets/js/vendor/*.js', // Path to JS vendor folder.
	jsVendorDestination: './dist', // Path to place the compiled JS vendors file.
	jsVendorFile: 'vendor', // Compiled JS vendors file name. Default set to vendors i.e. vendors.js.

	noConcatScripts: ['./assets/js/*.js', './template-parts/**/*.js', './inc/**/*.js', '!**/_?*.js'], //scripts that should be processed but not concatenated

	// JS Custom options.
	jsCustomSRC: './assets/js/custom/*.js', // Path to JS custom scripts folder.
	jsCustomDestination: './dist', // Path to place the compiled JS custom scripts file.
	jsCustomFile: 'custom', // Compiled JS custom file name. Default set to custom i.e. custom.js.

	//JS files in other places that start with an underscore will be added to to custom.js
	templatePartsScripts: './template-parts/**/_?*.js',
	incScripts: './inc/**/_?*.js',


	// Images options.
	imgSRC: './assets/images/raw/*', // Source folder of images which should be optimized and watched. You can also specify types e.g. raw/**.{png,jpg,gif} in the glob.
	imgDST: './dist', // Destination folder of optimized images. Must be different from the imagesSRC folder.

	// Watch files paths.
	watchStyles: './assets/sass/**/*.scss', // Path to all *.scss files inside css folder and inside them.
	watchJsVendor: './assets/js/vendor/*.js', // Path to all vendor JS files.
	watchJsCustom: ['./assets/js/custom/*.js','./template-parts/**/_?*.js', './inc/**/_?*.js' ], // Path to all custom JS files.
	watchPhp: './**/*.php', // Path to all PHP files.
	watchNoConcatScripts: ['./assets/js/[^_]*.js','./template-parts/**/[^_]*.js', './inc/**/[^_]*.js'],
	// Translation options.
	textDomain: 'ignition', // Your textdomain here.
	translationFile: 'ignition.pot', // Name of the translation file.
	translationDestination: './languages', // Where to save the translation files.
	packageName: 'ignition', // Package name.
	bugReport: 'https://AhmadAwais.com/contact/', // Where can users report bugs.
	lastTranslator: 'Ahmad Awais <your_email@email.com>', // Last translator Email ID.
	team: 'AhmadAwais <your_email@email.com>', // Team's Email ID.

	// Browsers you care about for autoprefixing. Browserlist https://github.com/ai/browserslist
	// The following list is set as per WordPress requirements. Though, Feel free to change.
	BROWSERS_LIST: [
		'last 2 version',
		'> 1%',
		'ie >= 11',
		'last 1 Android versions',
		'last 1 ChromeAndroid versions',
		'last 2 Chrome versions',
		'last 2 Firefox versions',
		'last 2 Safari versions',
		'last 2 iOS versions',
		'last 2 Edge versions',
		'last 2 Opera versions'
	]
};
