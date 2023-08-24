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

// Styles to process.
const styles = [

	// Core.
	{
		name: 'core',
		src: './assets/sass/merchant.scss',
		destination: './assets/css',
	},

	// Admin.
	{
		name: 'admin',
		src: './assets/sass/admin/admin.scss',
		destination: './assets/css/admin',
	},

	// Metabox.
	{
		name: 'metabox',
		src: './assets/sass/admin/metabox.scss',
		destination: './assets/css/admin',
	},

	// Grid.
	{
		name: 'grid',
		src: './assets/sass/grid.scss',
		destination: './assets/css',
	},

	// Carousel.
	{
		name: 'carousel',
		src: './assets/sass/carousel.scss',
		destination: './assets/css',
	},

	// Pagination.
	{
		name: 'pagination',
		src: './assets/sass/pagination.scss',
		destination: './assets/css',
	},

	// Buy Now.
	{
		name: 'buyNow',
		src: './assets/sass/modules/buy-now/buy-now.scss',
		destination: './assets/css/modules/buy-now',
	},
	{
		name: 'buyNowAdmin',
		src: './assets/sass/modules/buy-now/admin/preview.scss',
		destination: './assets/css/modules/buy-now/admin',
	},

	// Animated Add To Cart.
	{
		name: 'animatedAddToCart',
		src: './assets/sass/modules/animated-add-to-cart/animated-add-to-cart.scss',
		destination: './assets/css/modules/animated-add-to-cart',
	},
	{
		name: 'animatedAddToCartAdmin',
		src: './assets/sass/modules/animated-add-to-cart/admin/preview.scss',
		destination: './assets/css/modules/animated-add-to-cart/admin',
	},

	// Quick View.
	{
		name: 'quickView',
		src: './assets/sass/modules/quick-view/quick-view.scss',
		destination: './assets/css/modules/quick-view',
	},
	{
		name: 'quickViewAdmin',
		src: './assets/sass/modules/quick-view/admin/preview.scss',
		destination: './assets/css/modules/quick-view/admin',
	},

	// Product Labels.
	{
		name: 'productLabels',
		src: './assets/sass/modules/product-labels/product-labels.scss',
		destination: './assets/css/modules/product-labels',
	},
	{
		name: 'productLabelsAdmin',
		src: './assets/sass/modules/product-labels/admin/preview.scss',
		destination: './assets/css/modules/product-labels/admin',
	},

	// Product Labels.
	{
		name: 'paymentLogos',
		src: './assets/sass/modules/payment-logos/payment-logos.scss',
		destination: './assets/css/modules/payment-logos',
	},
	{
		name: 'paymentLogosAdmin',
		src: './assets/sass/modules/payment-logos/admin/preview.scss',
		destination: './assets/css/modules/payment-logos/admin',
	},

	// Trust Badges.
	{
		name: 'trustBadges',
		src: './assets/sass/modules/trust-badges/trust-badges.scss',
		destination: './assets/css/modules/trust-badges',
	},
	{
		name: 'trustBadgesAdmin',
		src: './assets/sass/modules/trust-badges/admin/preview.scss',
		destination: './assets/css/modules/trust-badges/admin',
	},

	// Pre Orders.
	{
		name: 'preOrders',
		src: './assets/sass/modules/pre-orders/pre-orders.scss',
		destination: './assets/css/modules/pre-orders',
	},
	{
		name: 'preOrdersAdmin',
		src: './assets/sass/modules/pre-orders/admin/preview.scss',
		destination: './assets/css/modules/pre-orders/admin',
	},

	// Cart Count Favicon.
	{
		name: 'cartCountFaviconAdmin',
		src: './assets/sass/modules/cart-count-favicon/admin/preview.scss',
		destination: './assets/css/modules/cart-count-favicon/admin',
	},

	// Inactive Tab Message.
	{
		name: 'inactiveTabMessageAdmin',
		src: './assets/sass/modules/inactive-tab-message/admin/preview.scss',
		destination: './assets/css/modules/inactive-tab-message/admin',
	},

	// Agree To Terms Checkbox.
	{
		name: 'agreeToTermsCheckbox',
		src: './assets/sass/modules/agree-to-terms-checkbox/agree-to-terms-checkbox.scss',
		destination: './assets/css/modules/agree-to-terms-checkbox',
	},
	{
		name: 'agreeToTermsCheckboxAdmin',
		src: './assets/sass/modules/agree-to-terms-checkbox/admin/preview.scss',
		destination: './assets/css/modules/agree-to-terms-checkbox/admin',
	},

];

// Scripts to process.
const scripts = [

	// Core.
	{
		name: 'core',
		src: './assets/js/src/merchant.js',
		destination: './assets/js',
		file: 'merchant',
	},

	// Admin.
	{
		name: 'admin',
		src: './assets/js/src/admin/admin.js',
		destination: './assets/js/admin',
		file: 'admin',
	},

	// Metabox.
	{
		name: 'metabox',
		src: './assets/js/src/admin/metabox.js',
		destination: './assets/js/admin',
		file: 'merchant-metabox',
	},

	// Preview.
	{
		name: 'preview',
		src: './assets/js/src/admin/preview.js',
		destination: './assets/js/admin',
		file: 'merchant-preview',
	},

	// Carousel.
	{
		name: 'carousel',
		src: './assets/js/src/carousel.js',
		destination: './assets/js',
		file: 'carousel',
	},

	// Pagination.
	{
		name: 'pagination',
		src: './assets/js/src/pagination.js',
		destination: './assets/js',
		file: 'pagination',
	},

	// Scroll Direction.
	{
		name: 'scrollDirection',
		src: './assets/js/src/scroll-direction.js',
		destination: './assets/js',
		file: 'scroll-direction',
	},

	// Toggle Class.
	{
		name: 'toggleClass',
		src: './assets/js/src/toggle-class.js',
		destination: './assets/js',
		file: 'toggle-class',
	},

	// Custom Add To Cart Button.
	{
		name: 'customAddToCartButton',
		src: './assets/js/src/custom-addtocart-button.js',
		destination: './assets/js',
		file: 'custom-addtocart-button',
	},

	// Quick View.
	{
		name: 'quickView',
		src: './assets/js/src/modules/quick-view/quick-view.js',
		destination: './assets/js/modules/quick-view',
		file: 'quick-view',
	},

	// Pre Orders.
	{
		name: 'preOrders',
		src: './assets/js/src/modules/pre-orders/pre-orders.js',
		destination: './assets/js/modules/pre-orders',
		file: 'pre-orders',
	},

	// Cart Count Favicon.
	{
		name: 'cartCountFavicon',
		src: './assets/js/src/modules/cart-count-favicon/cart-count-favicon.js',
		destination: './assets/js/modules/cart-count-favicon',
		file: 'cart-count-favicon',
	},

	// Inactive Tab Message.
	{
		name: 'inactiveTabMessage',
		src: './assets/js/src/modules/inactive-tab-message/inactive-tab-message.js',
		destination: './assets/js/modules/inactive-tab-message',
		file: 'inactive-tab-message',
	}

];

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

	// Style options.
	styles,

	// Script options.
	scripts,

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
