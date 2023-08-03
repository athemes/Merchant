/**
 * WPGulp Configuration File
 */

// General options.
const projectURL      = 'http://localhost/merchant';
const productURL      = './';
const browserAutoOpen = false;
const injectChanges   = true;
const outputStyle     = 'compressed';
const errLogToConsole = true;
const precision       = 10;

// Core.
const styleDestination = './assets/css';
const styleSRC         = './assets/sass/merchant.scss';

const scriptDestination      = './assets/js';
const scriptSRC              = './assets/js/src/merchant.js';
const scriptFile             = 'merchant';

// Admin.
const adminStyleDestination = './assets/css/admin';
const adminStyleSRC         = './assets/sass/admin/admin.scss';

const adminScriptDestination = './assets/js/admin';
const adminScriptSRC         = './assets/js/src/admin/admin.js';
const adminScriptFile        = 'admin';

// Grid.
const gridStyleDestination = './assets/css';
const gridStyleSRC         = './assets/sass/grid.scss';

// Carousel.
const carouselStyleDestination = './assets/css';
const carouselStyleSRC         = './assets/sass/carousel.scss';

const carouselScriptDestination = './assets/js/';
const carouselScriptSRC         = './assets/js/src/carousel.js';
const carouselScriptFile        = 'carousel';

// Pagination.
const paginationStyleDestination = './assets/css';
const paginationStyleSRC         = './assets/sass/pagination.scss';

const paginationScriptDestination = './assets/js/';
const paginationScriptSRC         = './assets/js/src/pagination.js';
const paginationScriptFile        = 'pagination';

// Scroll Direction.
const scrollDirectionScriptDestination = './assets/js/';
const scrollDirectionScriptSRC         = './assets/js/src/scroll-direction.js';
const scrollDirectionScriptFile        = 'scroll-direction';

// Toggle Class.
const toggleClassScriptDestination = './assets/js/';
const toggleClassScriptSRC         = './assets/js/src/toggle-class.js';
const toggleClassScriptFile        = 'toggle-class';

// Custom Add To Cart Button.
const customAddToCartButtonScriptDestination = './assets/js/';
const customAddToCartButtonScriptSRC         = './assets/js/src/custom-addtocart-button.js';
const customAddToCartButtonScriptFile        = 'custom-addtocart-button';

// Metabox.
const metaboxCssDestination = './assets/css/admin';
const metaboxCssSRC         = './assets/sass/admin/metabox.scss';

const metaboxJsDestination = './assets/js/admin';
const metaboxJsSRC         = './assets/js/src/admin/metabox.js';
const metaboxJsFile        = 'merchant-metabox';

// Watch options.
const watchStyles  = './assets/sass/**/*.scss';
const watchScripts = './assets/js/src/**/*.js';
const watchPhp     = './**/*.php';

// Zip options.
const zipName        = 'merchant.zip';
const zipDestination = './../';
const zipIncludeGlob = ['../@(Merchant|merchant)/**/*'];
const zipIgnoreGlob  = [
	'!../@(Merchant|merchant)/**/*{node_modules,node_modules/**/*}',
	'!../@(Merchant|merchant)/**/*.git',
	'!../@(Merchant|merchant)/**/*.svn',
	'!../@(Merchant|merchant)/**/*gulpfile.babel.js',
	'!../@(Merchant|merchant)/**/*wpgulp.config.js',
	'!../@(Merchant|merchant)/**/*.eslintrc.js',
	'!../@(Merchant|merchant)/**/*.eslintignore',
	'!../@(Merchant|merchant)/**/*.editorconfig',
	'!../@(Merchant|merchant)/**/*phpcs.xml.dist',
	'!../@(Merchant|merchant)/**/*vscode',
	'!../@(Merchant|merchant)/*.code-workspace',
	'!../@(Merchant|merchant)/**/*package.json',
	'!../@(Merchant|merchant)/**/*package-lock.json',
	'!../@(Merchant|merchant)/**/*assets/img/raw/**/*',
	'!../@(Merchant|merchant)/**/*assets/img/raw',
	'!../@(Merchant|merchant)/**/*assets/js/src/**/*',
	'!../@(Merchant|merchant)/**/*assets/js/src',
	'!../@(Merchant|merchant)/**/*tests/**/*',
	'!../@(Merchant|merchant)/**/*tests',
	'!../@(Merchant|merchant)/**/*e2etests/**/*',
	'!../@(Merchant|merchant)/**/*e2etests',
	'!../@(Merchant|merchant)/**/*playwright-report/**/*',
	'!../@(Merchant|merchant)/**/*playwright-report',
	'!../@(Merchant|merchant)/**/*.wp-env.json',
	'!../@(Merchant|merchant)/**/*playwright.config.js',
	'!../@(Merchant|merchant)/**/*composer.json',
	'!../@(Merchant|merchant)/**/*composer.lock',
	'!../@(Merchant|merchant)/**/*phpcs.xml',
	'!../@(Merchant|merchant)/{vendor,vendor/**/*}'
];

// Translation options.
const textDomain             = 'merchant';
const translationFile        = 'merchant.pot';
const translationDestination = './languages';

// Others.
const packageName    = 'merchant';
const bugReport      = 'https://athemes.com/contact/';
const lastTranslator = 'aThemes <team@athemes.com>';
const team           = 'aThemes <team@athemes.com>';
const BROWSERS_LIST  = ['last 2 version', '> 1%'];

// Export.
module.exports = {

	// General options.
	projectURL,
	productURL,
	browserAutoOpen,
	injectChanges,
	outputStyle,
	errLogToConsole,
	precision,

	// Core.
	styleDestination,
	styleSRC,

	scriptDestination,
	scriptSRC,
	scriptFile,

	// Admin.
	adminStyleDestination,
	adminStyleSRC,

	adminScriptDestination,
	adminScriptSRC,
	adminScriptFile,

	// Metabox.
	metaboxCssDestination,
	metaboxCssSRC,

	metaboxJsDestination,
	metaboxJsSRC,
	metaboxJsFile,

	// Grid.
	gridStyleDestination,
	gridStyleSRC,

	// Carousel.
	carouselStyleDestination,
	carouselStyleSRC,

	carouselScriptDestination,
	carouselScriptSRC,
	carouselScriptFile,

	// Pagination.
	paginationStyleDestination,
	paginationStyleSRC,

	paginationScriptDestination,
	paginationScriptSRC,
	paginationScriptFile,

	// Scroll Direction.
	scrollDirectionScriptDestination,
	scrollDirectionScriptSRC,
	scrollDirectionScriptFile,

	// Toggle Class.
	toggleClassScriptDestination,
	toggleClassScriptSRC,
	toggleClassScriptFile,

	// Custom Add To Cart Button.
	customAddToCartButtonScriptDestination,
	customAddToCartButtonScriptSRC,
	customAddToCartButtonScriptFile,

	// Watch options.
	watchStyles,
	watchScripts,
	watchPhp,

	// Zip options.
	zipName,
	zipDestination,
	zipIncludeGlob,
	zipIgnoreGlob,

	// Translation options.
	textDomain,
	translationFile,
	translationDestination,

	// Others.
	packageName,
	bugReport,
	lastTranslator,
	team,
	BROWSERS_LIST,

};
