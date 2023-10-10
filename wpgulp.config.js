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

	// Custom Labels
	{
		name: 'AddToCartTextAdminPreview',
		src: './assets/sass/modules/add-to-cart-text/admin/preview.scss',
		destination: './assets/css/modules/add-to-cart-text/admin',
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

	// Quick Social Links
	{
		name: 'quickSocialLinks',
		src: './assets/sass/modules/quick-social-links/quick-social-links.scss',
		destination: './assets/css/modules/quick-social-links',
	},
	{
		name: 'quickSocialLinksAdmin',
		src: './assets/sass/modules/quick-social-links/admin/preview.scss',
		destination: './assets/css/modules/quick-social-links/admin',
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

	// Scroll To Top Button.
	{
		name: 'scrollToTopButton',
		src: './assets/sass/modules/scroll-to-top-button/scroll-to-top-button.scss',
		destination: './assets/css/modules/scroll-to-top-button',
	},
	{
		name: 'scrollToTopButtonAdmin',
		src: './assets/sass/modules/scroll-to-top-button/admin/preview.scss',
		destination: './assets/css/modules/scroll-to-top-button/admin',
	},

	// Auto External Links.
	{
		name: 'autoExternalLinksAdmin',
		src: './assets/sass/modules/auto-external-links/admin/preview.scss',
		destination: './assets/css/modules/auto-external-links/admin'
	},

	// Real Time Search.
	{
		name: 'realTimeSearch',
		src: './assets/sass/modules/real-time-search/real-time-search.scss',
		destination: './assets/css/modules/real-time-search',
	},
	{
		name: 'realTimeSearchAdmin',
		src: './assets/sass/modules/real-time-search/admin/preview.scss',
		destination: './assets/css/modules/real-time-search/admin',
	},

	// Code Snippets.
	{
		name: 'codeSnippetsAdmin',
		src: './assets/sass/modules/code-snippets/admin/preview.scss',
		destination: './assets/css/modules/code-snippets/admin',
	},

	// Cookie Banner.
	{
		name: 'cookieBanner',
		src: './assets/sass/modules/cookie-banner/cookie-banner.scss',
		destination: './assets/css/modules/cookie-banner',
	},
	{
		name: 'cookieBannerAdmin',
		src: './assets/sass/modules/cookie-banner/admin/preview.scss',
		destination: './assets/css/modules/cookie-banner/admin',
	},

	// Cart reserved timer.
	{
		name: 'cartReservedTimer',
		src: './assets/sass/modules/cart-reserved-timer/cart-reserved-timer.scss',
		destination: './assets/css/modules/cart-reserved-timer',
	},
	{
		name: 'cartReservedTimerAdminPreview',
		src: './assets/sass/modules/cart-reserved-timer/admin/preview.scss',
		destination: './assets/css/modules/cart-reserved-timer/admin',
	},

	// Buy X, Get Y.
	{
		name: 'buyXGetY',
		src: './assets/sass/modules/buy-x-get-y/buy-x-get-y.scss',
		destination: './assets/css/modules/buy-x-get-y',
	},
	{
		name: 'buyXGetYAdminPreview',
		src: './assets/sass/modules/buy-x-get-y/admin/preview.scss',
		destination: './assets/css/modules/buy-x-get-y/admin',
	},

	// Advanced Reviews.
	{
		name: 'advancedReviews',
		src: './assets/sass/modules/advanced-reviews/advanced-reviews.scss',
		destination: './assets/css/modules/advanced-reviews',
	},
	{
		name: 'advancedReviewsAdmin',
		src: './assets/sass/modules/advanced-reviews/admin/preview.scss',
		destination: './assets/css/modules/advanced-reviews/admin',
	},


	// Checkout Admin Preview.
	{
		name: 'checkoutAdmin',
		src: './assets/sass/modules/checkout/admin/preview.scss',
		destination: './assets/css/modules/checkout/admin',
	},

	// Countdown timer.
	{
		name: 'countdownTimerAdmin',
		src: './assets/sass/modules/countdown-timer/admin/preview.scss',
		destination: './assets/css/modules/countdown-timer/admin',
	},
	{
		name: 'countdownTimer',
		src: './assets/sass/modules/countdown-timer/countdown-timer.scss',
		destination: './assets/css/modules/countdown-timer',
	},

	// Floating Mini Cart.
	{
		name: 'floatingMiniCartAdmin',
		src: './assets/sass/modules/floating-mini-cart/admin/preview.scss',
		destination: './assets/css/modules/floating-mini-cart/admin',
	},
	{
		name: 'floatingMiniCart',
		src: './assets/sass/modules/floating-mini-cart/floating-mini-cart.scss',
		destination: './assets/css/modules/floating-mini-cart',
	},

	// free-gifts
	{
		name: 'freeGiftsAdmin',
		src: './assets/sass/modules/free-gifts/admin/preview.scss',
		destination: './assets/css/modules/free-gifts/admin',
	},
	{
		name: 'freeGifts',
		src: './assets/sass/modules/free-gifts/free-gifts.scss',
		destination: './assets/css/modules/free-gifts',
	},

	// Frequently Bought Together.
	{
		name: 'frequentlyBoughtTogetherAdmin',
		src: './assets/sass/modules/frequently-bought-together/admin/preview.scss',
		destination: './assets/css/modules/frequently-bought-together/admin',
	},
	{
		name: 'frequentlyBoughtTogether',
		src: './assets/sass/modules/frequently-bought-together/frequently-bought-together.scss',
		destination: './assets/css/modules/frequently-bought-together',
	},

	// Login Popups.
	{
		name: 'loginPopupsAdmin',
		src: './assets/sass/modules/login-popup/admin/preview.scss',
		destination: './assets/css/modules/login-popup/admin',
	},
	{
		name: 'loginPopups',
		src: './assets/sass/modules/login-popup/login-popup.scss',
		destination: './assets/css/modules/login-popup',
	},

	// Product Audio.
	{
		name: 'productAudioAdmin',
		src: './assets/sass/modules/product-audio/admin/preview.scss',
		destination: './assets/css/modules/product-audio/admin',
	},
	{
		name: 'productAudio',
		src: './assets/sass/modules/product-audio/product-audio.scss',
		destination: './assets/css/modules/product-audio',
	},

	// Product Brand Image.
	{
		name: 'productBrandImage',
		src: './assets/sass/modules/product-brand-image/product-brand-image.scss',
		destination: './assets/css/modules/product-brand-image',
	},
	{
		name: 'productBrandImageAdmin',
		src: './assets/sass/modules/product-brand-image/admin/preview.scss',
		destination: './assets/css/modules/product-brand-image/admin',
	},

	// Product Video.
	{
		name: 'productVideoAdmin',
		src: './assets/sass/modules/product-video/admin/preview.scss',
		destination: './assets/css/modules/product-video/admin',
	},
	{
		name: 'productVideo',
		src: './assets/sass/modules/product-video/product-video.scss',
		destination: './assets/css/modules/product-video',
	},

	// Reasons to Buy.
	{
		name: 'reasonsToBuy',
		src: './assets/sass/modules/reasons-to-buy/reasons-to-buy.scss',
		destination: './assets/css/modules/reasons-to-buy',
	},
	{
		name: 'reasonsToBuyAdmin',
		src: './assets/sass/modules/reasons-to-buy/admin/preview.scss',
		destination: './assets/css/modules/reasons-to-buy/admin',
	},

	// Recently Viewed Products.
	{
		name: 'recentlyViewedProducts',
		src: './assets/sass/modules/recently-viewed-products/recently-viewed-products.scss',
		destination: './assets/css/modules/recently-viewed-products',
	},
	{
		name: 'recentlyViewedProductsAdmin',
		src: './assets/sass/modules/recently-viewed-products/admin/preview.scss',
		destination: './assets/css/modules/recently-viewed-products/admin',
	},

	// Size Chart.
	{
		name: 'sizeChartAdmin',
		src: './assets/sass/modules/size-chart/admin/preview.scss',
		destination: './assets/css/modules/size-chart/admin',
	},
	{
		name: 'sizeChart',
		src: './assets/sass/modules/size-chart/size-chart.scss',
		destination: './assets/css/modules/size-chart',
	},

	// Spending goal.
	{
		name: 'spendingGoalAdmin',
		src: './assets/sass/modules/spending-goal/admin/preview.scss',
		destination: './assets/css/modules/spending-goal/admin',
	},
	{
		name: 'spendingGoal',
		src: './assets/sass/modules/spending-goal/spending-goal.scss',
		destination: './assets/css/modules/spending-goal',
	},

	// Sticky add to cart.
	{
		name: 'stickyAddToCart',
		src: './assets/sass/modules/sticky-add-to-cart/sticky-add-to-cart.scss',
		destination: './assets/css/modules/sticky-add-to-cart',
	},
	{
		name: 'stickyAddToCartAdmin',
		src: './assets/sass/modules/sticky-add-to-cart/admin/preview.scss',
		destination: './assets/css/modules/sticky-add-to-cart/admin',
	},

	// Stock Scarcity.
	{
		name: 'stockScarcityAdmin',
		src: './assets/sass/modules/stock-scarcity/admin/preview.scss',
		destination: './assets/css/modules/stock-scarcity/admin',
	},
	{
		name: 'stockScarcity',
		src: './assets/sass/modules/stock-scarcity/stock-scarcity.scss',
		destination: './assets/css/modules/stock-scarcity',
	},

	// Volume discounts.
	{
		name: 'volumeDiscountsAdmin',
		src: './assets/sass/modules/volume-discounts/admin/preview.scss',
		destination: './assets/css/modules/volume-discounts/admin',
	},
	{
		name: 'volumeDiscounts',
		src: './assets/sass/modules/volume-discounts/volume-discounts.scss',
		destination: './assets/css/modules/volume-discounts',
	},

	// Wait list.
	{
		name: 'waitListAdmin',
		src: './assets/sass/modules/wait-list/admin/preview.scss',
		destination: './assets/css/modules/wait-list/admin',
	},
	{
		name: 'waitList',
		src: './assets/sass/modules/wait-list/wait-list.scss',
		destination: './assets/css/modules/wait-list',
	},
	// Wishlist.
	{
		name: 'wishlistButton',
		src: './assets/sass/modules/wishlist/wishlist-button.scss',
		destination: './assets/css/modules/wishlist',
	},
	{
		name: 'wishlistAdmin',
		src: './assets/sass/modules/wishlist/admin/preview.scss',
		destination: './assets/css/modules/wishlist/admin',
	},

	// Product swatches
	{
		name: 'productSwatchesAdminPreview',
		src: './assets/sass/modules/product-swatches/admin/preview.scss',
		destination: './assets/css/modules/product-swatches/admin',
	},

	// Product navigation buttons
	{
		name: 'productNavigationButtonsAdminPreview',
		src: './assets/sass/modules/product-navigation-buttons/admin/preview.scss',
		destination: './assets/css/modules/product-navigation-buttons/admin',
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
	},

	// Scroll To Top Button.
	{
		name: 'scrollToTopButton',
		src: './assets/js/src/modules/scroll-to-top-button/scroll-to-top-button.js',
		destination: './assets/js/modules/scroll-to-top-button',
		file: 'scroll-to-top-button',
	},

	// Auto External Links.
	{
		name: 'autoExternalLinks',
		src: './assets/js/src/modules/auto-external-links/auto-external-links.js',
		destination: './assets/js/modules/auto-external-links',
		file: 'auto-external-links',
	},

	// Real Time Search.
	{
		name: 'realTimeSearch',
		src: './assets/js/src/modules/real-time-search/real-time-search.js',
		destination: './assets/js/modules/real-time-search',
		file: 'real-time-search',
	},

	// Cookie Banner.
	{
		name: 'cookieBanner',
		src: './assets/js/src/modules/cookie-banner/cookie-banner.js',
		destination: './assets/js/modules/cookie-banner',
		file: 'cookie-banner'
	},

	// Countdown timer.
	{
		name: 'countdownTimer',
		src: './assets/js/src/modules/countdown-timer/countdown-timer.js',
		file: 'countdown-timer',
		destination: './assets/js/modules/countdown-timer',
	},
	{
		name: 'countdownTimerAdmin',
		src: './assets/js/src/modules/countdown-timer/admin/preview.js',
		file: 'preview',
		destination: './assets/js/modules/countdown-timer/admin',
	},

	// Floating Mini Cart.
	{
		name: 'floatingMiniCart',
		src: './assets/js/src/modules/floating-mini-cart/floating-mini-cart.js',
		file: 'floating-mini-cart',
		destination: './assets/js/modules/floating-mini-cart',
	},
	// Slide-out cart
	{
		name: 'slideOutCart',
		src: './assets/js/src/modules/slide-out-cart/slide-out-cart.js',
		file: 'slide-out-cart',
		destination: './assets/js/modules/slide-out-cart',
	},

	// Login Popups.
	{
		name: 'loginPopups',
		src: './assets/js/src/modules/login-popup/login-popup.js',
		file: 'login-popup',
		destination: './assets/js/modules/login-popup',
	},

	// Size Chart.
	{
		name: 'sizeChart',
		src: './assets/js/src/modules/size-chart/size-chart.js',
		file: 'size-chart',
		destination: './assets/js/modules/size-chart',
	},
	// Spending goal.
	{
		name: 'spendingGoalAdmin',
		src: './assets/js/src/modules/spending-goal/admin/preview.js',
		file: 'preview',
		destination: './assets/js/modules/spending-goal/admin',
	},
	{
		name: 'spendingGoal',
		src: './assets/js/src/modules/spending-goal/spending-goal.js',
		file: 'spending-goal',
		destination: './assets/js/modules/spending-goal/',
	},
	// Quick social links
	{
		name: 'quickSocialLinksAdmin',
		src: './assets/js/src/modules/quick-social-links/admin/preview.js',
		file: 'preview',
		destination: './assets/js/modules/quick-social-links/admin',
	},
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
	'!**/*{node_modules,node_modules/**/*}',
	'!**/*.code-workspace',
	'!**/*.git',
	'!**/*.svn',
	'!**/*gulpfile.babel.js',
	'!**/*wpgulp.config.js',
	'!**/*.eslintrc.js',
	'!**/*.eslintignore',
	'!**/*.editorconfig',
	'!**/*phpcs.xml.dist',
	'!**/*vscode',
	'!../@(Merchant|merchant)/*.code-workspace',
	'!**/*package.json',
	'!**/*package-lock.json',
	'!**/*assets/img/raw/**/*',
	'!**/*assets/img/raw',
	'!**/*assets/js/src/**/*',
	'!**/*assets/js/src',
	'!**/*tests/**/*',
	'!**/*tests',
	'!**/*e2etests/**/*',
	'!**/*e2etests',
	'!**/*playwright-report/**/*',
	'!**/*playwright-report',
	'!**/*.wp-env.json',
	'!**/*playwright.config.js',
	'!**/*composer.json',
	'!**/*composer.lock',
	'!**/*yarn.lock',
	'!**/*phpcs.xml',
	'!{vendor,vendor/**/*}'
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
