<?php
/**
 * Merchant_Admin_Modules Class.
 */
if ( ! class_exists( 'Merchant_Admin_Modules' ) ) {

	class Merchant_Admin_Modules {

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
		public function __construct() {}

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
					'modules' => array(

						'quick-view' => array(
							'icon' => '',
							'title' => esc_html__( 'Quick View', 'merchant' ),
							'desc' => esc_html__( 'Show a quick-view button to view product details and add to cart via lightbox popup.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => 'https://docs.athemes.com/article/quick-view/',
							'preview_url' => $shop_url,
						),

						'animated-add-to-cart' => array(
							'icon' => '',
							'title' => esc_html__( 'Animated Add to Cart', 'merchant' ),
							'desc' => esc_html__( 'Make sure your Add To Cart button stands out by adding a subtle animation on the mouse over or every few seconds.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => 'https://docs.athemes.com/article/animated-add-to-cart/',
							'preview_url' => $shop_url,
						),

						'accelerated-checkout' => array(
							'icon' => '',
							'title' => esc_html__( 'Accelerated Checkout', 'merchant' ),
							'desc' => esc_html__( 'If increasing average order value is not important for your store, send your customers directly to checkout instead of cart.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => 'https://docs.athemes.com/article/accelerated-checkout/',
							'preview_url' => $shop_url,
						),

					),
				),

				'boost-revenue' => array(
					'title' => esc_html__( 'Boost Revenue', 'merchant' ),
					'modules' => array(

						'sale-tags' => array(
							'icon'  => '',
							'title' => esc_html__( 'Sale Tags', 'merchant' ),
							'desc'  => esc_html__( 'Increase conversion rate with discount badges on top of your product image.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => 'https://docs.athemes.com/article/sale-tags/',
							'preview_url' => $shop_url,
						),

						'pre-orders' => array(
							'icon' => '',
							'title' => esc_html__( 'Pre-Orders', 'merchant' ),
							'desc' => esc_html__( 'Increase revenue by allowing visitors to pre-order products that are either out of stock or not yet released.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => 'https://docs.athemes.com/article/pre-orders/',
							'preview_url' => $shop_url,
						),

					),
				),

				'improve-experience' => array(
					'title' => esc_html__( 'Improve Experience', 'merchant' ),
					'modules' => array(

						'ajax-real-time-search' => array(
							'icon' => '',
							'title' => esc_html__( 'Ajax Real-Time Search', 'merchant' ),
							'desc' => esc_html__( 'Help visitors instantly find the products they\'re looking for by using predictive search and displaying frequent searches.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => 'https://docs.athemes.com/article/ajax-real-time-search/',
							'preview_url' => $shop_url,
						),

						'auto-external-links' => array(
							'icon' => '',
							'title' => esc_html__( 'Auto External Links', 'merchant' ),
							'desc' => esc_html__( 'All the external links on your store will be opened in a new browser tab by enabling this app. It prevents visitors from navigating away from your online store.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => 'https://docs.athemes.com/article/auto-external-links/',
							'preview_url' => $shop_url,
						),

						'scroll-to-top-button' => array(
							'icon' => '',
							'title' => esc_html__( 'Scroll to Top Button', 'merchant' ),
							'desc' => esc_html__( 'Help your customers get back easily to the top of the page, where they can see the product photos and purchase options.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => 'https://docs.athemes.com/article/scroll-to-top-button/',
							'preview_url' => $shop_url,
						),

						'code-snippets' => array(
							'icon' => '',
							'title' => esc_html__( 'Code Snippets', 'merchant' ),
							'desc' => esc_html__( 'Add code snippets in WordPress without having to edit your theme’s functions.php file.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => 'https://docs.athemes.com/article/code-snippets/',
							'preview_url' => $shop_url,
						),

					),
				),

				'build-trust' => array(
					'title' => esc_html__( 'Build Trust', 'merchant' ),
					'modules' => array(

						'payment-logos' => array(
							'icon' => '',
							'title' => esc_html__( 'Payment Logos', 'merchant' ),
							'desc' => esc_html__( 'Build trust by letting your visitors know that you are accepting a wide assortment of payment methods.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => 'https://docs.athemes.com/article/payment-logos/',
							'preview_url' => $product_url,
						),

						'product-trust-badge' => array(
							'icon' => '',
							'title' => esc_html__( 'Product Trust Badge', 'merchant' ),
							'desc' => esc_html__( 'Increase trust and conversion rate with premium, professionally-designed badges that match your store\'s look and feel.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => 'https://docs.athemes.com/article/product-trust-badge/',
							'preview_url' => $product_url,
						),

					),
				),

				'reduce-abandonment' => array(
					'title' => esc_html__( 'Reduce Abandonment', 'merchant' ),
					'modules' => array(

						'cart-count-favicon' => array(
							'icon' => '',
							'title' => esc_html__( 'Cart Count Favicon', 'merchant' ),
							'desc' => esc_html__( 'Make sure your store\'s browser tab stands out by displaying the number of items in the cart on the favicon.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => 'https://docs.athemes.com/article/cart-count-favicon/',
							'preview_url' => $shop_url,
						),

						'inactive-tab-messsage' => array(
							'icon' => '',
							'title' => esc_html__( 'Inactive Tab Message', 'merchant' ),
							'desc' => esc_html__( 'Reduce cart abandonment by dynamically modifying the browser tab\'s title when the visitor navigates away from your store.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => 'https://docs.athemes.com/article/inactive-tab-message/',
							'preview_url' => $shop_url,
						),

					),
				),

				'product-your-store' => array(
					'title' => esc_html__( 'Protect Your Store', 'merchant' ),
					'modules' => array(

						'agree-to-terms-checkbox' => array(
							'icon' => '',
							'title' => esc_html__( 'Agree to Terms Checkbox', 'merchant' ),
							'desc' => esc_html__( 'Follow EU\'s regulations on processing personal data by obtaining consent before visitors start the checkout process.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => 'https://docs.athemes.com/article/agree-to-terms-checkbox/',
							'preview_url' => $checkout_url,
						),

						'cookie-banner' => array(
							'icon' => '',
							'title' => esc_html__( 'Cookie Banner', 'merchant' ),
							'desc' => esc_html__( 'Inform your visitors that the site uses cookies to improve the user experience and track the visitors activity.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => 'https://docs.athemes.com/article/cookie-banner/',
							'preview_url' => $shop_url,
						),

					),
				),

			);

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
