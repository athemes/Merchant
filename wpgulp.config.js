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

// Style options.
const styleDestination      = './assets/css';
const styleSRC              = './assets/sass/merchant.scss';
const styleRTLSRC           = './assets/sass/merchant-rtl.scss';
const adminStyleDestination = './assets/css/admin';
const adminStyleSRC         = './assets/sass/admin/admin.scss';
const adminStyleRTLSRC      = './assets/sass/admin/admin-rtl.scss';

// Script options.
const scriptDestination      = './assets/js';
const scriptSRC              = './assets/js/src/merchant.js';
const scriptFile             = 'merchant';
const adminScriptDestination = './assets/js/admin';
const adminScriptSRC         = './assets/js/src/admin/admin.js';
const adminScriptFile        = 'admin';

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
	'!../@(Merchant|merchant)/**/*playwright.config.js'
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

	// Style options.
	styleDestination,
	styleSRC,
	styleRTLSRC,
	adminStyleDestination,
	adminStyleSRC,
	adminStyleRTLSRC,

	// Script options.
	scriptDestination,
	scriptSRC,
	scriptFile,
	adminScriptDestination,
	adminScriptSRC,
	adminScriptFile,

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
