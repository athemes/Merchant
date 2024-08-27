<?php
/**
 * Merchant_Admin_Modules Class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Merchant_Admin_Modules' ) ) {

	class Merchant_Admin_Modules {

		/**
		 * Upsell modules.
		 *
		 */
		public static $modules_data = array();

		/**
		 * The single class instance.
		 */
		private static $instance = null;

		/**
		 * Instance.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor.
		 */
		public function __construct() {
			self::$modules_data = array(

				// Boost Revenue.
				'pre-orders' => array(
					'pro' => false,
					'section' => 'boost-revenue',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-pre-orders' ),
					'title' => esc_html__( 'Pre-Orders', 'merchant' ),
					'desc' => esc_html__( 'Allow visitors to pre-order products that are either out of stock or not yet released', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/pre-orders/',
				),
				'free-shipping-progress-bar' => array(
					'pro' => true,
					'section' => 'boost-revenue',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'free-shipping-progress-bar' ),
					'title' => esc_html__( 'Free Shipping Bar', 'merchant' ),
					'desc' => esc_html__( 'Display the amount left needed for free shipping', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-free-shipping-progress-bar/',
				),
				'wait-list' => array(
					'pro' => true,
					'section' => 'boost-revenue',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-wait-list' ),
					'title' => esc_html__( 'Waitlist', 'merchant' ),
					'desc' => esc_html__( 'Build waitlists for sold-out items and auto-notify potential customers when items are restocked', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-waitlist/',
				),
				'product-bundles' => array(
					'pro' => true,
					'section' => 'boost-revenue',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-product-bundles' ),
					'title' => esc_html__( 'Product Bundles', 'merchant' ),
					'desc' => esc_html__( 'Create bundles of products to be sold together and boost your average order value', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-product-bundles/',
				),
				'frequently-bought-together' => array(
					'pro' => true,
					'section' => 'boost-revenue',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-frequently-bought-together' ),
					'title' => esc_html__( 'Frequently Bought Together', 'merchant' ),
					'desc' => esc_html__( 'Create bundles of related products that customers can add to their cart with just one click', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-frequently-bought-together/',
				),
				'buy-x-get-y' => array(
					'pro' => true,
					'section' => 'boost-revenue',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-buy-x-get-y' ),
					'title' => esc_html__( 'Buy X, Get Y', 'merchant' ),
					'desc' => esc_html__( 'Create offers where purchasing a specific quantity of Product X triggers a discount on Product Y', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-buy-x-get-y/',
				),
				'volume-discounts' => array(
					'pro' => true,
					'section' => 'boost-revenue',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-volume-discounts' ),
					'title' => esc_html__( 'Bulk Discounts', 'merchant' ),
					'desc' => esc_html__( 'Offer discounts on larger quantity purchases to drive up average order value', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-bulk-discounts/',
				),
				'storewide-sale' => array(
					'pro' => true,
					'section' => 'boost-revenue',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-storewide-sale' ),
					'title' => esc_html__( 'Storewide Sale', 'merchant' ),
					'desc' => esc_html__( 'Create discount campaigns for all your products, specific categories, or specific products', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-storewide-sale/',
				),
				'spending-goal' => array(
					'pro' => true,
					'section' => 'boost-revenue',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-spending-goal' ),
					'title' => esc_html__( 'Spending Discount Goal', 'merchant' ),
					'desc' => esc_html__( 'Motivate higher order values by offering customers discounts for reaching spending goals', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-spending-goal/',
				),
				'free-gifts' => array(
					'pro' => true,
					'section' => 'boost-revenue',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-free-gifts' ),
					'title' => esc_html__( 'Free Gifts', 'merchant' ),
					'desc' => esc_html__( 'Reward shoppers with a gift if they hit a specified spending target or apply a coupon', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-gift-card/',
				),
				// Increase Conversion Rates (Convert More).
				'product-labels' => array(
					'pro' => false,
					'section' => 'convert-more',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-product-labels' ),
					'title' => esc_html__( 'Product Labels', 'merchant' ),
					'desc'  => esc_html__( 'Create customizable product labels with display conditions and color settings', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/product-labels/',
				),
				'quick-view' => array(
					'pro' => false,
					'section' => 'convert-more',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-quick-view' ),
					'title' => esc_html__( 'Quick View', 'merchant' ),
					'desc' => esc_html__( 'Allows users to quickly view product details without leaving the current page', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/quick-view/',
				),
				'added-to-cart-popup' => array(
					'pro' => true,
					'section' => 'convert-more',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'added-to-cart-popup' ),
					'title' => esc_html__( 'Added To Cart Popup', 'merchant' ),
					'desc' => esc_html__( 'Display a dynamic popup with product suggestions when items are added to the cart', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-added-to-cart-popup/',
				),
				'countdown-timer' => array(
					'pro' => true,
					'section' => 'convert-more',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-countdown-timer' ),
					'title' => esc_html__( 'Countdown Timer', 'merchant' ),
					'desc' => esc_html__( 'Create a sense of urgency by displaying a countdown timer on your discounted products', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-countdown-timer/',
				),
				'stock-scarcity' => array(
					'pro' => true,
					'section' => 'convert-more',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-stock-scarcity' ),
					'title' => esc_html__( 'Stock Scarcity', 'merchant' ),
					'desc' => esc_html__( 'Let visitors know that stock is running low on products they are looking at', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-stock-scarcity/',
				),
				'checkout' => array(
					'pro' => true,
					'section' => 'convert-more',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-checkout' ),
					'title'        => esc_html__( 'Checkouts', 'merchant' ),
					'desc'         => esc_html__( 'Choose from three different checkout layouts: Shopify-style, Multi-step or One-page', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-checkouts/',
				),
				'sticky-add-to-cart' => array(
					'pro' => true,
					'section' => 'convert-more',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-sticky-add-to-cart' ),
					'title' => esc_html__( 'Sticky Add To Cart', 'merchant' ),
					'desc' => esc_html__( 'Display a sticky add to cart bar when visitors are scrolling on your product pages', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-sticky-add-to-cart/',
				),
				'recently-viewed-products' => array(
					'pro' => true,
					'section' => 'convert-more',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-recently-viewed-products' ),
					'title' => esc_html__( 'Recently Viewed Products', 'merchant' ),
					'desc' => esc_html__( 'Show recently viewed products on product pages and in the cart', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-recently-viewed-products/',
				),

				// Boost Revenue.

				// Reduce Cart Abandonment.
				'buy-now' => array(
					'pro' => false,
					'section' => 'reduce-abandonment',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-buy-now' ),
					'title' => esc_html__( 'Buy Now', 'merchant' ),
					'desc' => esc_html__( 'Send your customers directly to checkout instead of the cart with Buy Now buttons', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/buy-now/',
				),
				'cart-count-favicon' => array(
					'pro' => false,
					'section' => 'reduce-abandonment',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-cart-count-favicon' ),
					'title' => esc_html__( 'Cart Count Favicon', 'merchant' ),
					'desc' => esc_html__( 'Make your browser tab stand out by showing the number of items in the cart on the favicon', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/cart-count-favicon/',
				),
				'inactive-tab-message' => array(
					'pro' => false,
					'section' => 'reduce-abandonment',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-inactive-tab-message' ),
					'title' => esc_html__( 'Inactive Tab Message', 'merchant' ),
					'desc' => esc_html__( 'Modify the browser tab\'s title when the visitor navigates away from your store', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-inactive-tab-message/',
				),
				'cart-reserved-timer' => array(
					'pro' => true,
					'section' => 'reduce-abandonment',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-cart-reserved-timer' ),
					'title'        => esc_html__( 'Cart Reserved Timer', 'merchant' ),
					'desc'         => esc_html__( 'Create urgency by letting visitors know that the products in cart are reserved for a limited time', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-cart-reserved-timer/',
				),
				'floating-mini-cart' => array(
					'pro' => true,
					'section' => 'reduce-abandonment',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-floating-mini-cart' ),
					'title'        => esc_html__( 'Floating Mini Cart', 'merchant' ),
					'desc'         => esc_html__( 'A cart icon will always be visible and a sliding cart when the customer clicks it', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-floating-mini-cart/',
				),
				'side-cart' => array(
					'pro' => true,
					'section' => 'reduce-abandonment',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-side-cart' ),
					'title'        => esc_html__( 'Side Cart', 'merchant' ),
					'desc'         => esc_html__( 'Show a sliding cart whenever a customer adds a product to the cart', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-side-cart/',
				),

				// Build Trust.
				'payment-logos' => array(
					'pro' => false,
					'section' => 'build-trust',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-payment-logos' ),
					'title' => esc_html__( 'Payment Logos', 'merchant' ),
					'desc' => esc_html__( 'Display the logos of the payment methods you accept on product pages', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/payment-logos/',
				),
				'trust-badges' => array(
					'pro' => false,
					'section' => 'build-trust',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-trust-badges' ),
					'title' => esc_html__( 'Trust Badges', 'merchant' ),
					'desc' => esc_html__( 'Reassure customers by showcasing different badge-shaped store benefits', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/trust-badges/',
				),
				'advanced-reviews' => array(
					'pro' => true,
					'section' => 'build-trust',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-advanced-reviews' ),
					'title' => esc_html__( 'Advanced Reviews', 'merchant' ),
					'desc' => esc_html__( 'Enhance your customer reviews with advanced features including photo uploads and more', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-advanced-reviews/',
				),
				'reasons-to-buy' => array(
					'pro' => true,
					'section' => 'build-trust',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-reasons-to-buy' ),
					'title' => esc_html__( 'Reasons To Buy List', 'merchant' ),
					'desc' => esc_html__( 'Provide customers with a summary of the key features and benefits of your products', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-reasons-to-buy/',
				),
				'quick-social-links' => array(
					'pro' => true,
					'section' => 'build-trust',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-quick-social-links' ),
					'title' => esc_html__( 'Quick Social Links', 'merchant' ),
					'desc' => esc_html__( 'Display floating social media icons to make it easier for your customers to connect with you', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-quick-social-links/',
				),
				'product-brand-image' => array(
					'pro' => true,
					'section' => 'build-trust',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-product-brand-image' ),
					'title'        => esc_html__( 'Product Brand Image', 'merchant' ),
					'desc'         => esc_html__( 'Add brand images to products to instill confidence in potential buyers', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-product-brand-image/',
				),

				// Improve User Experience.
				'animated-add-to-cart' => array(
					'pro' => false,
					'section' => 'improve-experience',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-animated-add-to-cart' ),
					'title' => esc_html__( 'Animated Add to Cart', 'merchant' ),
					'desc' => esc_html__( 'Make your Add To Cart button stand out by adding an animation on mouse over or page load', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/animated-add-to-cart/',
				),
				'add-to-cart-text' => array(
					'pro' => false,
					'section' => 'improve-experience',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-add-to-cart-text' ),
					'title' => esc_html__( 'Add To Cart Text', 'merchant' ),
					'desc' => esc_html__( 'Change your store\'s \'Add to Cart\' text for various product types, as well as individual products', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-add-to-cart-text/',
				),
				'auto-external-links' => array(
					'pro' => false,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-auto-external-links' ),
					'title' => esc_html__( 'Auto External Links', 'merchant' ),
					'desc' => esc_html__( 'Keep users from navigating away from your store by opening external links in a new browser tab', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/auto-external-links/',
				),
				'real-time-search' => array(
					'pro' => false,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-real-time-search' ),
					'title' => esc_html__( 'Real-Time Search', 'merchant' ),
					'desc' => esc_html__( 'Help visitors instantly find the products they\'re looking for by using predictive search', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/real-time-search/',
				),
				'code-snippets' => array(
					'pro' => false,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-code-snippets' ),
					'title' => esc_html__( 'Code Snippets', 'merchant' ),
					'desc' => esc_html__( 'Add code snippets in WordPress without having to edit your theme\'s functions.php file ', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/code-snippets/',
				),
				'scroll-to-top-button' => array(
					'pro' => false,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-scroll-to-top-button' ),
					'title' => esc_html__( 'Scroll to Top Button', 'merchant' ),
					'desc' => esc_html__( 'Help your customers get back easily to the top of the page with a single click', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/scroll-to-top-button/',
				),
				'address-autocomplete' => array(
					'pro'          => true,
					'section'      => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'google-autocomplete' ),
					'title'        => esc_html__( 'Google Address Autocomplete', 'merchant' ),
					'desc'         => esc_html__( 'Streamline your checkout process and reduce user errors by autocompleting the address fields', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-google-address-autocomplete/',
				),
				'size-chart' => array(
					'pro' => true,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-size-chart' ),
					'title'        => esc_html__( 'Size Chart', 'merchant' ),
					'desc'         => esc_html__( 'Reduce returns and increase sales by showing a size chart on specific products or all products', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-size-chart/',
				),
				'product-swatches' => array(
					'pro' => true,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-product-swatches' ),
					'title'        => esc_html__( 'Variation Swatches', 'merchant' ),
					'desc'         => esc_html__( 'Display variable product options as customizable color/image icons, buttons, or dropdowns', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-variation-swatches/',
				),
				'wishlist' => array(
					'pro' => true,
					'section' => 'improve-experience',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-wishlist' ),
					'title' => esc_html__( 'Wishlist', 'merchant' ),
					'desc' => esc_html__( 'Allow customers to easily save products they are interested in for later', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-wishlist/',
				),
				'product-navigation-links' => array(
					'pro' => true,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-product-navigation-links' ),
					'title'        => esc_html__( 'Product Navigation Links', 'merchant' ),
					'desc'         => esc_html__( 'Enable easy navigation from one product to the next with next/previous links', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-product-navigation-links/',
				),
				'product-video' => array(
					'pro' => true,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-product-video' ),
					'title'        => esc_html__( 'Product Video', 'merchant' ),
					'desc'         => esc_html__( 'Upload video to be viewed in product galleries and on archive pages', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-product-video/',
				),
				'product-audio' => array(
					'pro' => true,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-product-audio' ),
					'title'        => esc_html__( 'Product Audio', 'merchant' ),
					'desc'         => esc_html__( 'Upload audio to be listened to in product galleries and on archive pages', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-product-audio/',
				),
				'login-popup' => array(
					'pro' => true,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-login-popup' ),
					'title'        => esc_html__( 'Login Popup', 'merchant' ),
					'desc'         => esc_html__( 'Allow users to log in with a simple pop up without navigating to a new page', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-login-popup/',
				),
				'clear-cart' => array(
					'pro'          => false,
					'section'      => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-clear-cart' ),
					'title'        => esc_html__( 'Clear Cart', 'merchant' ),
					'desc'         => esc_html__( 'Display a clear cart button to let customers empty their carts and start fresh', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-clear-cart/',
				),
				
				// Protect Your Store.
				'agree-to-terms-checkbox' => array(
					'pro' => false,
					'section' => 'protect-your-store',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-agree-to-terms-checkbox' ),
					'title' => esc_html__( 'Agree to Terms Checkbox', 'merchant' ),
					'desc' => esc_html__( 'Get customers to agree to your Terms & Conditions as part of the checkout process', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/agree-to-terms-checkbox/',
				),
				'cookie-banner' => array(
					'pro' => false,
					'section' => 'protect-your-store',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-cookie-banner' ),
					'title' => esc_html__( 'Cookie Banner', 'merchant' ),
					'desc' => esc_html__( 'Inform your visitors that the site uses cookies via a dismissable banner', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/cookie-banner/',
				),
			);
		}

		/**
		 * Get modules.
		 */
		public static function get_modules() {

			$modules = array(

				'boost-revenue' => array(
					'title' => esc_html__( 'Boost Revenue', 'merchant' ),
					'modules' => array(),
				),

				'convert-more' => array(
					'title' => esc_html__( 'Convert More', 'merchant' ),
					'modules' => array(),
				),

				'reduce-abandonment' => array(
					'title' => esc_html__( 'Reduce Cart Abandonment', 'merchant' ),
					'modules' => array(),
				),

				'build-trust' => array(
					'title' => esc_html__( 'Build Trust', 'merchant' ),
					'modules' => array(),
				),

				'improve-experience' => array(
					'title' => esc_html__( 'Improve Experience', 'merchant' ),
					'modules' => array(),
				),

				'protect-your-store' => array(
					'title' => esc_html__( 'Protect Store', 'merchant' ),
					'modules' => array(),
				),

			);

			/**
			 * Hook: merchant_modules
			 *
			 * @since 1.0
			 */
			$modules = apply_filters( 'merchant_modules', $modules );

			return $modules;
		}

		/**
		 * Get upsell modules.
		 *
		 */
		public static function get_upsell_modules() {
			return array_filter( self::$modules_data, function( $module ){
				return isset( $module[ 'pro' ] ) && $module[ 'pro' ] ? $module : false;
			} );
		}

		/**
		 * Add upsell to modules.
		 *
		 */
		public static function add_upsell_modules( $modules ) {
			if ( defined( 'MERCHANT_PRO_VERSION' ) ) {
				return $modules;
			}

			foreach ( self::get_upsell_modules() as $module_id => $module_data ) {
				$modules[ $module_data[ 'section' ] ][ 'modules' ][ $module_id ] = $module_data;
			}

			return $modules;
		}

		/**
		 * Get module info.
		 */
		public static function get_module_info( $module ) {

			$modules = self::get_modules();

			$maybe_found = array_column( array_column( $modules, 'modules' ), $module );

			if ( ! empty( $maybe_found ) && ! empty( $maybe_found[0] ) ) {
				return $maybe_found[0];
			}

			return false;
		}
	}

	Merchant_Admin_Modules::instance();

}
