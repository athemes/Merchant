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
		public static $upsell_modules = array();

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
			self::$upsell_modules = array(

				// Convert More.
				'sticky-add-to-cart' => array(
					'upsell' => true,
					'section' => 'convert-more',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-sticky-add-to-cart' ),
					'title' => esc_html__( 'Sticky Add To Cart', 'merchant' ),
					'desc' => esc_html__( 'Improve conversion rate by displaying a sticky add-to-cart bar when the visitors are scrolling down.', 'merchant' ),
					'tutorial_url' => 'https://docs.athemes.com/article/sticky-add-to-cart/'
				),
				'checkout' => array(
					'upsell' => true,
					'section' => 'convert-more',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-checkout' ),
					'title'        => esc_html__( 'Checkout', 'merchant-pro' ),
					'desc'         => esc_html__( 'Choose a layout that aligns with your audience\'s preferences, resulting in a frictionless checkout experience that drives more successful transactions.', 'merchant-pro' ),
					'tutorial_url' => 'https://docs.athemes.com/article/checkout/'
				),
				'countdown-timer' => array(
					'upsell' => true,
					'section' => 'convert-more',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-countdown-timer' ),
					'title'        => esc_html__( 'Countdown Timer', 'merchant-pro' ),
					'desc'         => esc_html__( 'Create urgency and increase conversion rate using a countdown timer for all your discounted products.', 'merchant-pro' ),
					'tutorial_url' => 'https://docs.athemes.com/article/countdown-timer/'
				),
				'recently-viewed-products' => array(
					'upsell' => true,
					'section' => 'convert-more',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-recently-viewed-products' ),
					'title' => esc_html__( 'Recently Viewed Products', 'merchant-pro' ),
					'desc' => esc_html__( 'Cross-sell efficiently by displaying on product pages and in the cart the products your visitors have recently viewed.', 'merchant-pro' ),
					'tutorial_url' => 'https://docs.athemes.com/article/recently-viewed-products/'
				),
				'waitlist' => array(
					'upsell' => true,
					'section' => 'convert-more',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-waitlist' ),
					'title'        => esc_html__( 'Wait List', 'merchant-pro' ),
					'desc'         => esc_html__( 'Wait-list for where user can opt-in for back-in-stock alert. A lead magnet and sales booster.', 'merchant-pro' ),
					'tutorial_url' => 'https://docs.athemes.com/article/waitlist/'
				),

				// Build Trust.
				'advanced-reviews' => array(
					'upsell' => true,
					'section' => 'build-trust',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-advanced-reviews' ),
					'title' => esc_html__( 'Advanced Reviews', 'merchant-pro' ),
					'desc' => esc_html__( 'Easily collect, import, and display reviews with photos and boost trust and conversion rates with social proof. Have voting for reviews (vote for helpful or unhelpful). Option to sort reviews by most helpful.', 'merchant-pro' ),
					'tutorial_url' => 'https://docs.athemes.com/article/advanced-reviews/'
				),
				'product-brand-image' => array(
					'upsell' => true,
					'section' => 'build-trust',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-product-brand-image' ),
					'title'        => esc_html__( 'Product Brand Image', 'merchant-pro' ),
					'desc'         => esc_html__( 'With a consistent brand image for each product, you present a unified and professional facade that instills confidence in potential buyers, making them more likely to convert.', 'merchant-pro' ),
					'tutorial_url' => 'https://docs.athemes.com/article/product-brand-image/'
				),
				'reasons-to-buy' => array(
					'upsell' => true,
					'section' => 'build-trust',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-reasons-to-buy' ),
					'title' => esc_html__( 'Reasons To Buy List', 'merchant-pro' ),
					'desc' => esc_html__( 'Provide prospective customers with a concise and persuasive summary of the key features, benefits, and selling points of your products.', 'merchant-pro' ),
					'tutorial_url' => 'https://docs.athemes.com/article/reasons-to-buy/'
				),

				// Reduce Abandonment.
				'cart-reserved-timer' => array(
					'upsell' => true,
					'section' => 'reduce-abandonment',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-cart-reserved-timer' ),
					'title'        => esc_html__( 'Cart Reserved Timer', 'merchant-pro' ),
					'desc'         => esc_html__( 'Create urgency by letting your visitors know that the products in cart are reserved only for a limited time.', 'merchant-pro' ),
					'tutorial_url' => 'https://docs.athemes.com/article/cart-reserved-timer/'
				),
				'floating-mini-cart' => array(
					'upsell' => true,
					'section' => 'reduce-abandonment',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-floating-mini-cart' ),
					'title'        => esc_html__( 'Floating Mini Cart', 'merchant-pro' ),
					'desc'         => esc_html__( 'Reduce the cart abandonment by providing a convenient and distraction-free way for customers to manage their cart contents and proceed to checkout.', 'merchant-pro' ),
					'tutorial_url' => 'https://docs.athemes.com/article/floating-mini-cart/'
				),

				// Improve Experience.
				'login-popup' => array(
					'upsell' => true,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-login-popup' ),
					'title'        => esc_html__( 'Login Popup', 'merchant-pro' ),
					'desc'         => esc_html__( 'Capture more leads and prevent cart abandonment by displaying automatic and exit-intent customizable pop-ups.', 'merchant-pro' ),
					'tutorial_url' => 'https://docs.athemes.com/article/login-popup/'
				),
				'product-audio' => array(
					'upsell' => true,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-product-audio' ),
					'title'        => esc_html__( 'Product Audio', 'merchant-pro' ),
					'desc'         => esc_html__( 'Empower customers to engage with your products using their sense of sound, enriching their shopping journey and fostering satisfaction.', 'merchant-pro' ),
					'tutorial_url' => 'https://docs.athemes.com/article/product-audio/'
				),
				'product-video' => array(
					'upsell' => true,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-product-video' ),
					'title'        => esc_html__( 'Product Video', 'merchant-pro' ),
					'desc'         => esc_html__( 'Help customers visualize your products potential. Whether it\'s demonstrating product features, usage scenarios, or showcasing intricate details.', 'merchant-pro' ),
					'tutorial_url' => 'https://docs.athemes.com/article/product-video/'
				),
				'size-chart' => array(
					'upsell' => true,
					'section' => 'improve-experience',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-size-chart' ),
					'title'        => esc_html__( 'Size Chart', 'merchant-pro' ),
					'desc'         => esc_html__( 'Seamlessly integrate sizing insights into your product pages. The feature offers customers immediate access to valuable sizing details, eliminating the need to navigate to external resources and creating a smoother shopping journey.', 'merchant-pro' ),
					'tutorial_url' => 'https://docs.athemes.com/article/size-chart/'
				),
				'wishlist' => array(
					'upsell' => true,
					'section' => 'improve-experience',
					'icon' => Merchant_SVG_Icons::get_svg_icon( 'module-wishlist' ),
					'title' => esc_html__( 'Wishlist', 'merchant-pro' ),
					'desc' => esc_html__( 'Prevent cart abandonment and increase your customer\'s engagement by allowing them to save products in Wishlists, for later purchases.', 'merchant-pro' ),
					'tutorial_url' => 'https://docs.athemes.com/article/wishlist/'
				),

				// Boost Revenue.
				'spending-goal' => array(
					'upsell' => true,
					'section' => 'boost-revenue',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-spending-goal' ),
					'title'        => esc_html__( 'Spending Goal', 'merchant-pro' ),
					'desc'         => esc_html__( 'Increase your store’s average order value by incentivizing customers with a discount when they reach the spending goal target.', 'merchant-pro' ),
					'tutorial_url' => 'https://docs.athemes.com/article/spending-goal/'
				),
				'volume-discounts' => array(
					'upsell' => true,
					'section' => 'boost-revenue',
					'icon'         => Merchant_SVG_Icons::get_svg_icon( 'module-volume-discounts' ),
					'title'        => esc_html__( 'Volume Discounts', 'merchant-pro' ),
					'desc'         => esc_html__( 'Increase your store’s average order value, by providing discounts when customers buy larger quantities.', 'merchant-pro' ),
					'tutorial_url' => 'https://docs.athemes.com/article/volume-discounts/'
				),

			);

		}

		/**
		 * Get modules.
		 */
		public static function get_modules() {

			// Set specific preview URLs
			$default_url  = site_url( '/' );
			$shop_url     = $default_url;
			$checkout_url = $default_url;
			$product_url  = $default_url;

			// Set shop url
			if ( function_exists( 'wc_get_page_id' ) ) {
				$shop_url = get_permalink( wc_get_page_id( 'shop' ) );
			} 

			// Set checkout url
			if ( function_exists( 'wc_get_page_id' ) ) {
				$checkout_url = get_permalink( wc_get_page_id( 'checkout' ) );
			}

			// Set single product url
			if ( function_exists( 'wc_get_products' ) ) {
				$products = wc_get_products( array( 'limit' => 1 ) );
				if ( ! empty( $products ) && ! empty( $products[0] ) ) {
					$product_url = get_permalink( $products[0]->get_id() );
				}
			}

			$modules = array(

				'convert-more' => array(
					'title' => esc_html__( 'Convert More', 'merchant' ),
					'modules' => array()
				),

				'build-trust' => array(
					'title' => esc_html__( 'Build Trust', 'merchant' ),
					'modules' => array(),
				),

				'boost-revenue' => array(
					'title' => esc_html__( 'Boost Revenue', 'merchant' ),
					'modules' => array(),
				),

				'reduce-abandonment' => array(
					'title' => esc_html__( 'Reduce Abandonment', 'merchant' ),
					'modules' => array(),
				),

				'improve-experience' => array(
					'title' => esc_html__( 'Improve Experience', 'merchant' ),
					'modules' => array(

						'auto-external-links' => array(
							'icon' => '<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18"><path d="M8 4a1 1 0 1 0 0 2h2.586L7.293 9.293a1 1 0 0 0 1.414 1.414L12 7.414V10a1 1 0 1 0 2 0V5c0-.025 0-.05-.003-.075A1 1 0 0 0 13 4H8ZM0 13.5A1.5 1.5 0 0 1 1.5 12h3A1.5 1.5 0 0 1 6 13.5v3A1.5 1.5 0 0 1 4.5 18h-3A1.5 1.5 0 0 1 0 16.5v-3Z"/><path d="M1.5 0A1.5 1.5 0 0 0 0 1.5V10h2V2h14v14H8v2h8.5a1.5 1.5 0 0 0 1.5-1.5v-15A1.5 1.5 0 0 0 16.5 0h-15Z"/></svg>',
							'title' => esc_html__( 'Auto External Links', 'merchant' ),
							'desc' => esc_html__( 'Keep customers on your store by automatically opening third-party links in a new browser tab.', 'merchant' ),
							'placeholder' => MERCHANT_URI . 'assets/images/modules/auto-external-links.png',
							'tutorial_url' => 'https://docs.athemes.com/article/auto-external-links/',
							'preview_url' => $product_url,
						),

						'real-time-search' => array(
							'icon' => '<svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9 5a1 1 0 1 0-2 0v3a1 1 0 0 0 .293.707l2 2a1 1 0 0 0 1.414-1.414L9 7.586V5Z"/><path fill-rule="evenodd" clip-rule="evenodd" d="m14.312 12.897 5.395 5.396a1 1 0 1 1-1.414 1.414l-5.396-5.395A7.954 7.954 0 0 1 8 16c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8a7.946 7.946 0 0 1-1.688 4.897ZM8 2C4.691 2 2 4.691 2 8s2.691 6 6 6 6-2.691 6-6-2.691-6-6-6Z"/></svg>',
							'title' => esc_html__( 'Real-Time Search', 'merchant' ),
							'desc' => esc_html__( 'Help visitors instantly find the products they’re looking for by using predictive search.', 'merchant' ),
							'placeholder' => MERCHANT_URI . 'assets/images/modules/real-time-search.png',
							'tutorial_url' => 'https://docs.athemes.com/article/real-time-search/',
							'preview_url' => $shop_url,
						),

						'code-snippets' => array(
							'icon' => '<svg width="20" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 16"><path d="M2.707 7.707A.996.996 0 0 0 3 7V3a1 1 0 0 1 1-1 1 1 0 0 0 0-2C2.346 0 1 1.346 1 3v3.586l-.707.707a.999.999 0 0 0 0 1.414L1 9.414V13c0 1.654 1.346 3 3 3a1 1 0 0 0 0-2 1 1 0 0 1-1-1V9a.996.996 0 0 0-.293-.707L2.414 8l.293-.293ZM19.924 7.617a1 1 0 0 0-.217-.324L19 6.586V3c0-1.654-1.346-3-3-3a1 1 0 1 0 0 2 1 1 0 0 1 1 1v4a.996.996 0 0 0 .293.707l.293.293-.293.293A.996.996 0 0 0 17 9v4a1 1 0 0 1-1 1 1 1 0 0 0 0 2c1.654 0 3-1.346 3-3V9.414l.707-.707a1 1 0 0 0 .217-1.09ZM12.697 3.284a1.002 1.002 0 0 0-1.63.346l-3.996 8a.999.999 0 0 0 .56 1.299 1.006 1.006 0 0 0 1.302-.557l3.996-8a1.001 1.001 0 0 0-.232-1.088Z"/></svg>',
							'title' => esc_html__( 'Code Snippets', 'merchant' ),
							'desc' => esc_html__( 'Add custom code to your global header, body, or footer without editing your theme’s functions.php file.', 'merchant' ),
							'placeholder' => MERCHANT_URI . 'assets/images/modules/code-snippets.png',
							'tutorial_url' => 'https://docs.athemes.com/article/code-snippets/',
							'preview_url' => $shop_url,
						)

					),
				),


				'protect-your-store' => array(
					'title' => esc_html__( 'Protect Store', 'merchant' ),
					'modules' => array(

						'cookie-banner' => array(
							'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="17" height="20" viewBox="0 0 17 20"><path d="M6 4h4v2H6V4ZM4 10v2h4v-2H4ZM4 14h6v2H4v-2Z"/><path fill-rule="evenodd" d="M0 18.5v-17C0 .7.7 0 1.5 0h13.1c.8 0 1.5.7 1.5 1.5v17c0 .8-.7 1.5-1.5 1.5H1.5C.7 20 0 19.3 0 18.5Zm2-.5h12V6h-2V4h2V2H2v2h2v2H2v12Z" clip-rule="evenodd"/></svg>',
							'title' => esc_html__( 'Cookie Banner', 'merchant' ),
							'desc' => esc_html__( 'Inform visitors about your cookie policy to comply with GDPR and other regulations.', 'merchant' ),
							'placeholder' => MERCHANT_URI . 'assets/images/modules/cookie-banner.png',
							'tutorial_url' => 'https://docs.athemes.com/article/cookie-banner/',
							'preview_url' => $shop_url,
						),

					),
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
			return self::$upsell_modules;
		}

		/**
		 * Add upsell to modules.
		 * 
		 */
		public static function add_upsell_modules( $modules ) {
			if ( defined( 'MERCHANT_PRO_VERSION' ) ) {
				return $modules;
			}

			foreach ( self::$upsell_modules as $module_id => $module_data ) {
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
