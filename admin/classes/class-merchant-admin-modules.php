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
							'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20.1002 11.2L13.4002 4.49997C13.3002 4.39997 13.1002 4.29997 12.9002 4.29997H5.0002C4.6002 4.19997 4.2002 4.59997 4.2002 4.99997V12.8C4.2002 13 4.3002 13.2 4.4002 13.3L11.1002 20C11.3002 20.2 11.6002 20.4 11.8002 20.5C12.0002 20.6 12.4002 20.7 12.7002 20.7C13.0002 20.7 13.3002 20.6 13.6002 20.5C13.9002 20.4 14.1002 20.2 14.4002 20L20.0002 14.4C20.4002 14 20.7002 13.4 20.7002 12.8C20.8002 12.2 20.5002 11.6 20.1002 11.2ZM19.0002 13.4L13.4002 19C13.3002 19.1 13.2002 19.1 13.1002 19.2C12.9002 19.3 12.7002 19.3 12.5002 19.2C12.4002 19.2 12.3002 19.1 12.2002 19L5.7002 12.5V5.79997H12.5002L19.0002 12.3C19.2002 12.5 19.2002 12.7 19.2002 12.9C19.2002 13 19.2002 13.2 19.0002 13.4ZM9.0002 7.99997C8.4002 7.99997 8.0002 8.39997 8.0002 8.99997C8.0002 9.59997 8.4002 9.99997 9.0002 9.99997C9.6002 9.99997 10.0002 9.59997 10.0002 8.99997C10.0002 8.39997 9.6002 7.99997 9.0002 7.99997Z" fill="#787C82"/></svg>',
							'title' => esc_html__( 'Quick View', 'merchant' ),
							'desc' => esc_html__( 'Define plugin general settings and default settings for your services.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => '#',
							'preview_url' => $shop_url,
						),

						'animated-add-to-cart' => array(
							'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.75 18C9.75 18.825 9.075 19.5 8.25 19.5C7.425 19.5 6.75 18.825 6.75 18C6.75 17.175 7.425 16.5 8.25 16.5C9.075 16.5 9.75 17.175 9.75 18ZM15.75 16.5C14.925 16.5 14.25 17.175 14.25 18C14.25 18.825 14.925 19.5 15.75 19.5C16.575 19.5 17.25 18.825 17.25 18C17.25 17.175 16.575 16.5 15.75 16.5ZM8.4 14.1V14.025L9.075 12.75H14.625C15.15 12.75 15.675 12.45 15.9 12L18.825 6.74999L17.55 5.99999L14.625 11.25H9.375L6.225 4.49999H3.75V5.99999H5.25L7.95 11.7L6.9 13.5C6.825 13.725 6.75 13.95 6.75 14.25C6.75 15.075 7.425 15.75 8.25 15.75H17.25V14.25H8.55C8.475 14.25 8.4 14.175 8.4 14.1ZM16.5 5.09999L15.45 4.04999L11.85 7.64999L9.9 5.69999L8.85 6.74999L11.85 9.74999L16.5 5.09999Z" fill="#787C82"/></svg>',
							'title' => esc_html__( 'Animated Add to Cart', 'merchant' ),
							'desc' => esc_html__( 'Make sure your Add To Cart button stands out by adding a subtle animation on the mouse over or every few seconds.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => '#',
							'preview_url' => $shop_url,
						),

						'accelerated-checkout' => array(
							'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.25 16.5C7.425 16.5 6.7575 17.175 6.7575 18C6.7575 18.825 7.425 19.5 8.25 19.5C9.075 19.5 9.75 18.825 9.75 18C9.75 17.175 9.075 16.5 8.25 16.5ZM15.75 16.5C14.925 16.5 14.2575 17.175 14.2575 18C14.2575 18.825 14.925 19.5 15.75 19.5C16.575 19.5 17.25 18.825 17.25 18C17.25 17.175 16.575 16.5 15.75 16.5ZM9.075 12.75H15.57L18.75 6.72L17.4375 6L14.6625 11.25H9.3975L6.2025 4.5H3.75V6H5.25L7.95 11.6925L5.7075 15.75H17.25V14.25H8.25L9.075 12.75ZM12 4.5L15 7.5L12 10.5L10.9425 9.4425L12.1275 8.25H9V6.75H12.1275L10.935 5.5575L12 4.5Z" fill="#787C82"/></svg>',
							'title' => esc_html__( 'Accelerated Checkout', 'merchant' ),
							'desc' => esc_html__( 'If increasing average order value is not important for your store, send your customers directly to checkout instead of cart.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => '#',
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
							'tutorial_url' => '#',
							'preview_url' => $shop_url,
						),

						'pre-orders' => array(
							'icon' => '',
							'title' => esc_html__( 'Pre-Orders', 'merchant' ),
							'desc' => esc_html__( 'Increase revenue by allowing visitors to pre-order products that are either out of stock or not yet released.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => '#',
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
							'tutorial_url' => '#',
							'preview_url' => $shop_url,
						),

						'auto-external-links' => array(
							'icon' => '',
							'title' => esc_html__( 'Auto External Links', 'merchant' ),
							'desc' => esc_html__( 'All the external links on your store will be opened in a new browser tab by enabling this app. It prevents visitors from navigating away from your online store.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => '#',
							'preview_url' => $shop_url,
						),

						'scroll-to-top-button' => array(
							'icon' => '',
							'title' => esc_html__( 'Scroll to Top Button', 'merchant' ),
							'desc' => esc_html__( 'Help your customers get back easily to the top of the page, where they can see the product photos and purchase options.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => '#',
							'preview_url' => $shop_url,
						),

						'code-snippets' => array(
							'icon' => '',
							'title' => esc_html__( 'Code Snippets', 'merchant' ),
							'desc' => esc_html__( 'Add code snippets in WordPress without having to edit your theme’s functions.php file.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => '#',
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
							'tutorial_url' => '#',
							'preview_url' => $product_url,
						),

						'product-trust-badge' => array(
							'icon' => '',
							'title' => esc_html__( 'Product Trust Badge', 'merchant' ),
							'desc' => esc_html__( 'Increase trust and conversion rate with premium, professionally-designed badges that match your store\'s look and feel.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => '#',
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
							'tutorial_url' => '#',
							'preview_url' => $shop_url,
						),

						'inactive-tab-messsage' => array(
							'icon' => '',
							'title' => esc_html__( 'Inactive Tab Message', 'merchant' ),
							'desc' => esc_html__( 'Reduce cart abandonment by dynamically modifying the browser tab\'s title when the visitor navigates away from your store.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => '#',
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
							'tutorial_url' => '#',
							'preview_url' => $checkout_url,
						),

						'cookie-banner' => array(
							'icon' => '',
							'title' => esc_html__( 'Cookie Banner', 'merchant' ),
							'desc' => esc_html__( 'Inform your visitors that the site uses cookies to improve the user experience and track the visitors activity.', 'merchant' ),
							'help_url' => '#',
							'tutorial_url' => '#',
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
