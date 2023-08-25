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

				// Convert More.
				'buy-now' => array(
					'pro' => false,
					'section' => 'convert-more',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-buy-now' ),
					'title' => esc_html__( 'Buy Now', 'merchant' ),
					'desc' => esc_html__( 'Increasing average order value by sending your customers directly to checkout instead of cart', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/buy-now/'
				),
				'animated-add-to-cart' => array(
					'pro' => false,
					'section' => 'convert-more',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-animated-add-to-cart' ),
					'title' => esc_html__( 'Animated Add to Cart', 'merchant' ),
					'desc' => esc_html__( 'Stands out Add to Cart button by adding a subtle animation on the mouse over', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/animated-add-to-cart/'
				),
				'quick-view' => array(
					'pro' => false,
					'section' => 'convert-more',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-quick-view' ),
					'title' => esc_html__( 'Quick View', 'merchant' ),
					'desc' => esc_html__( 'Allows users to quickly view product details without leaving the current page', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/quick-view/'
				),
				'product-labels' => array(
					'pro' => false,
					'section' => 'convert-more',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-product-labels' ),
					'title' => esc_html__( 'Product Labels', 'merchant' ),
					'desc'  => esc_html__( 'Increase conversion rate with different badges on top of your product image', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/product-labels/'
				),
				'sticky-add-to-cart' => array(
					'pro' => true,
					'section' => 'convert-more',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-sticky-add-to-cart' ),
					'title' => esc_html__( 'Sticky Add To Cart', 'merchant' ),
					'desc' => esc_html__( 'Display sticky add-to-cart bar when the visitors are scrolling down', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-sticky-add-to-cart/'
				),
				'wait-list' => array(
					'pro' => true,
					'section' => 'convert-more',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-wait-list' ),
					'title' => esc_html__( 'Waitlist', 'merchant' ),
					'desc' => esc_html__( 'Build a waiting list for sold-out items and auto-notify when stock\'s back', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-waitlist/'
				),
				'countdown-timer' => array(
					'pro' => true,
					'section' => 'convert-more',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-countdown-timer' ),
					'title' => esc_html__( 'Countdown Timer', 'merchant' ),
					'desc' => esc_html__( 'Create urgency using a countdown timer for all your discounted products', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-waitlist/'
				),
				'stock-scarcity' => array(
					'pro' => true,
					'section' => 'convert-more',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-stock-scarcity' ),
					'title' => esc_html__( 'Stock Scarcity', 'merchant' ),
					'desc' => esc_html__( 'Alert low inventory on the products to drive customer urgency when they\'re browsing', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-stock-scarcity/'
				),
				'checkout' => array(
					'pro' => true,
					'section' => 'convert-more',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-checkout' ),
					'title'        => esc_html__( 'Checkout', 'merchant' ),
					'desc'         => esc_html__( 'Streamlined checkout options: Shopify-style, Multi-step, and One-step', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-checkout/'
				),
				'recently-viewed-products' => array(
					'pro' => true,
					'section' => 'convert-more',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-recently-viewed-products' ),
					'title' => esc_html__( 'Recently Viewed Products', 'merchant' ),
					'desc' => esc_html__( 'Increase cross-sells by showing recently viewed items on product pages and in the cart', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-recently-viewed-products/'
				),

				// Boost Revenue.
				'pre-orders' => array(
					'pro' => false,
					'section' => 'boost-revenue',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-pre-orders' ),
					'title' => esc_html__( 'Pre-Orders', 'merchant' ),
					'desc' => esc_html__( 'Allow visitors to pre-order products that are either out of stock or not yet released', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/pre-orders/'
				),
				'frequently-bought-together' => array(
					'pro' => true,
					'section' => 'boost-revenue',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-frequently-bought-together' ),
					'title' => esc_html__( 'Frequently Bought Together', 'merchant' ),
					'desc' => esc_html__( 'Create product bundles with items that are purchased together as a group', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-frequently-bought-together/'
				),
				'buy-x-get-y' => array(
					'pro' => true,
					'section' => 'boost-revenue',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-buy-x-get-y' ),
					'title' => esc_html__( 'Buy X, Get Y', 'merchant' ),
					'desc' => esc_html__( 'Discount a product when another product is purchased and balance stock inventory', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-buy-x-get-y/'
				),
				'volume-discounts' => array(
					'pro' => true,
					'section' => 'boost-revenue',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-volume-discounts' ),
					'title' => esc_html__( 'Volume Discounts', 'merchant' ),
					'desc' => esc_html__( 'Increase store\'s average order value by offering discounts on larger quantity purchases', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-volume-discounts/'
				),
				'spending-goal' => array(
					'pro' => true,
					'section' => 'boost-revenue',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-spending-goal' ),
					'title' => esc_html__( 'Spending Discount Goal', 'merchant' ),
					'desc' => esc_html__( 'Incentivise customers with a discount when they reach the spending goal target', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-spending-goal/'
				),
				'free-gifts' => array(
					'pro' => true,
					'section' => 'boost-revenue',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-free-gifts' ),
					'title' => esc_html__( 'Gift', 'merchant' ),
					'desc' => esc_html__( 'Increase customer satisfaction with gifts that remind your customers about your store', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-gift-card/'
				),

				// Reduce Abandonment.
				'cart-count-favicon' => array(
					'pro' => false,
					'section' => 'reduce-abandonment',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-cart-count-favicon' ),
					'title' => esc_html__( 'Cart Count Favicon', 'merchant' ),
					'desc' => esc_html__( 'Stands out your store\'s browser tab by displaying the number of items in the cart on the favicon', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/cart-count-favicon/'
				),
				'inactive-tab-message' => array(
					'pro' => false,
					'section' => 'reduce-abandonment',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-inactive-tab-message' ),
					'title' => esc_html__( 'Inactive Tab Message', 'merchant' ),
					'desc' => esc_html__( 'Modify the browser tab\'s title when the visitor navigates away from your store', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-inactive-tab-message/'
				),
				'floating-mini-cart' => array(
					'pro' => true,
					'section' => 'reduce-abandonment',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-floating-mini-cart' ),
					'title'        => esc_html__( 'Floating Mini Cart', 'merchant' ),
					'desc'         => esc_html__( 'A cart icon with item count and a sliding cart to encourage customers for checkout', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-floating-mini-cart/'
				),
				'cart-reserved-timer' => array(
					'pro' => true,
					'section' => 'reduce-abandonment',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-cart-reserved-timer' ),
					'title'        => esc_html__( 'Cart Reserved Timer', 'merchant' ),
					'desc'         => esc_html__( 'Create urgency by letting visitors know that the products in cart are reserved for limited time', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-cart-reserved-timer/'
				),

				// Build Trust.
				'payment-logos' => array(
					'pro' => false,
					'section' => 'build-trust',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-payment-logos' ),
					'title' => esc_html__( 'Payment Logos', 'merchant' ),
					'desc' => esc_html__( 'Letting your visitors know that you are accepting a wide assortment of payment methods', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/payment-logos/'
				),
				'trust-badges' => array(
					'pro' => false,
					'section' => 'build-trust',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-trust-badges' ),
					'title' => esc_html__( 'Trust Badges', 'merchant' ),
					'desc' => esc_html__( 'Increase conversion rate and reassure customers with badge-shaped store benefits', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/trust-badges/'
				),
				'advanced-reviews' => array(
					'pro' => true,
					'section' => 'build-trust',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-advanced-reviews' ),
					'title' => esc_html__( 'Advanced Reviews', 'merchant' ),
					'desc' => esc_html__( 'Easily collect and display advanced reviews to boost trust and conversion rates', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-advanced-reviews/'
				),
				'reasons-to-buy' => array(
					'pro' => true,
					'section' => 'build-trust',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-reasons-to-buy' ),
					'title' => esc_html__( 'Reasons To Buy List', 'merchant' ),
					'desc' => esc_html__( 'Provide customer a persuasive summary of the key features and benefits of your products', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-reasons-to-buy/'
				),
				'product-brand-image' => array(
					'pro' => true,
					'section' => 'build-trust',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-product-brand-image' ),
					'title'        => esc_html__( 'Product Brand Image', 'merchant' ),
					'desc'         => esc_html__( 'Add brand image for each products to instil confidence in potential buyers', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-product-brand-image/'
				),				

				// Improve Experience.
				'auto-external-links' => array(
					'pro' => false,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-auto-external-links' ),
					'title' => esc_html__( 'Auto External Links', 'merchant' ),
					'desc' => esc_html__( 'Prevent users from navigating away from your online store by opening links in a new browser tab', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/auto-external-links/'
				),
				'real-time-search' => array(
					'pro' => false,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-real-time-search' ),
					'title' => esc_html__( 'Real-Time Search', 'merchant' ),
					'desc' => esc_html__( 'Help visitors instantly find the products they\'re looking for by using predictive search', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/real-time-search/'
				),
				'code-snippets' => array(
					'pro' => false,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-code-snippets' ),
					'title' => esc_html__( 'Code Snippets', 'merchant' ),
					'desc' => esc_html__( 'Add code snippets in WordPress without having to edit your theme\'s functions.php file', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/code-snippets/'
				),
				'scroll-to-top-button' => array(
					'pro' => false,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-scroll-to-top-button' ),
					'title' => esc_html__( 'Scroll to Top Button', 'merchant' ),
					'desc' => esc_html__( 'Help your customers get back easily to the top of the page with a single click', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/scroll-to-top-button/'
				),
				'size-chart' => array(
					'pro' => true,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-size-chart' ),
					'title'        => esc_html__( 'Size Chart', 'merchant' ),
					'desc'         => esc_html__( 'Reduce returns and increase sales by showing a size chart on product pages', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-size-chart/'
				),
				'wishlist' => array(
					'pro' => true,
					'section' => 'improve-experience',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-wishlist' ),
					'title' => esc_html__( 'Wishlist', 'merchant' ),
					'desc' => esc_html__( 'Prevent cart abandonment by allowing customer to save products for later purchases', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-wishlist/'
				),
				'product-video' => array(
					'pro' => true,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-product-video' ),
					'title'        => esc_html__( 'Product Video', 'merchant' ),
					'desc'         => esc_html__( 'Upload or embed video in the product gallery on product single page and archive page', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-product-video/'
				),
				'product-audio' => array(
					'pro' => true,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-product-audio' ),
					'title'        => esc_html__( 'Product Audio', 'merchant' ),
					'desc'         => esc_html__( 'Upload or embed audio in the product gallery on product single page and archive page', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-product-audio/'
				),
				'login-popup' => array(
					'pro' => true,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-login-popup' ),
					'title'        => esc_html__( 'Login Popup', 'merchant' ),
					'desc'         => esc_html__( 'Allow users to login/signup with the simple pop up without refreshing page', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-login-popup/'
				),
				
				// Protect Store.
				'agree-to-terms-checkbox' => array(
					'pro' => false,
					'section' => 'protect-your-store',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-agree-to-terms-checkbox' ),
					'title' => esc_html__( 'Agree to Terms Checkbox', 'merchant' ),
					'desc' => esc_html__( 'Get customers to agree to your Terms & Conditions as part of the checkout process.', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/agree-to-terms-checkbox/'
				),
				'cookie-banner' => array(
					'pro' => false,
					'section' => 'protect-your-store',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-cookie-banner' ),
					'title' => esc_html__( 'Cookie Banner', 'merchant' ),
					'desc' => esc_html__( 'Inform your visitors that the site uses cookies to improve the user experience', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/cookie-banner/'
				),
				'content-protection' => array(
					'pro' => true,
					'section' => 'protect-your-store',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-content-protection' ),
					'title'        => esc_html__( 'Content Protection', 'merchant' ),
					'desc'         => esc_html__( 'Prevent content theft by blocking text and image selection, copy, cut and save shortcuts', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/merchant-content-protection/'
				),
				

			);

		}

		/**
		 * Get modules.
		 */
		public static function get_modules() {

			$modules = array(

				'convert-more' => array(
					'title' => esc_html__( 'Convert More', 'merchant' ),
					'modules' => array()
				),

				'boost-revenue' => array(
					'title' => esc_html__( 'Boost Revenue', 'merchant' ),
					'modules' => array(),
				),

				'reduce-abandonment' => array(
					'title' => esc_html__( 'Reduce Abandonment', 'merchant' ),
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
					'modules' => array()
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
