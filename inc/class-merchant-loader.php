<?php
/**
 * Merchant_Loader Class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Merchant_Loader' ) ) {

	class Merchant_Loader {

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

			$this->includes();

			// Register scripts.
			add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );

			// Enqueue scripts.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );

			// Add identifier to body class.
			add_filter( 'body_class', array( $this, 'add_body_class' ) );

		}

		/**
		 * Include required classes.
		 */
		public function includes() {

			// Essential functions.
			require_once MERCHANT_DIR . 'inc/functions.php';

			// Helpers.
			require_once MERCHANT_DIR . 'inc/helpers.php';

			// Core classes.
			require_once MERCHANT_DIR . 'inc/classes/class-merchant-option.php';
			require_once MERCHANT_DIR . 'inc/classes/class-merchant-modules.php';
			require_once MERCHANT_DIR . 'inc/classes/class-merchant-custom-css.php';

			// Metabox
			require_once MERCHANT_DIR . 'inc/classes/class-merchant-metabox.php';

			// Modules
			require_once MERCHANT_DIR . 'inc/modules/global-settings/global-settings.php';
			require_once MERCHANT_DIR . 'inc/modules/scroll-to-top-button/scroll-to-top-button.php';
			require_once MERCHANT_DIR . 'inc/modules/animated-add-to-cart/animated-add-to-cart.php';
			require_once MERCHANT_DIR . 'inc/modules/payment-logos/payment-logos.php';
			require_once MERCHANT_DIR . 'inc/modules/trust-badges/trust-badges.php';
			require_once MERCHANT_DIR . 'inc/modules/buy-now/buy-now.php';
			require_once MERCHANT_DIR . 'inc/modules/agree-to-terms-checkbox/agree-to-terms-checkbox.php';
			require_once MERCHANT_DIR . 'inc/modules/cookie-banner/cookie-banner.php';
			require_once MERCHANT_DIR . 'inc/modules/quick-view/quick-view.php';
			require_once MERCHANT_DIR . 'inc/modules/product-labels/product-labels.php';
			require_once MERCHANT_DIR . 'inc/modules/pre-orders/pre-orders.php';
			require_once MERCHANT_DIR . 'inc/modules/real-time-search/real-time-search.php';
			require_once MERCHANT_DIR . 'inc/modules/code-snippets/code-snippets.php';
			require_once MERCHANT_DIR . 'inc/modules/inactive-tab-message/inactive-tab-message.php';
			require_once MERCHANT_DIR . 'inc/modules/cart-count-favicon/cart-count-favicon.php';

		}

		/**
		 * Add to body class.
		 */
		public function add_body_class( $classes ) {

			$theme = wp_get_theme();
			$theme = ( get_template_directory() !== get_stylesheet_directory() && $theme->parent() ) ? $theme->parent() : $theme;

			$classes[] = 'merchant-theme-' . strtolower( sanitize_html_class( $theme->name ) );

			return $classes;

		}

		/**
		 * Register scripts.
		 * Scripts that might be used by multiple modules should be registered here.
		 * 
		 */
		public function register_scripts() {
			$scripts = array(

				// Scroll Direction
				array(
					'handle'	=> 'merchant-scroll-direction',
					'src'		=> 'assets/js/scroll-direction.min.js',
					'dep'		=> array(),
					'in_footer' => true
				),

				// Toggle Class
				array(
					'handle'	=> 'merchant-toggle-class',
					'src'		=> 'assets/js/toggle-class.min.js',
					'dep'		=> array(),
					'in_footer' => true
				)
			);

			foreach( $scripts as $script ) {
				wp_register_script( $script[ 'handle' ], MERCHANT_URI . $script[ 'src' ], $script[ 'dep' ], MERCHANT_VERSION, $script[ 'in_footer' ] );
			}
		}

		/**
		 * Enqueue styles and scripts.
		 */
		public function enqueue_styles_scripts() {

			do_action( 'merchant_enqueue_before_main_css_js' );

			wp_enqueue_style( 'merchant', MERCHANT_URI . 'assets/css/merchant.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_script( 'merchant', MERCHANT_URI . 'assets/js/merchant.min.js', array( 'jquery' ), MERCHANT_VERSION, true );

			$setting = array(
				'nonce'    => wp_create_nonce( 'merchant-nonce' ),
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			);

			// Scroll to Top Button
			if ( Merchant_Modules::is_module_active( 'scroll-to-top-button' ) ) {

				$setting['scroll_to_top'] = true;

				wp_enqueue_script( 'merchant-scroll-to-top', MERCHANT_URI . 'assets/js/modules/scroll-to-top.js', array( 'merchant' ), MERCHANT_VERSION, true );

			}

			// Animated Add to Cart
			if ( Merchant_Modules::is_module_active( 'animated-add-to-cart' ) ) {

				$trigger = Merchant_Admin_Options::get( 'animated-add-to-cart', 'trigger', 'on-mouse-hover' );

				if ( 'on-page-load' === $trigger ) {

					wp_enqueue_script( 'merchant-animated-add-to-cart', MERCHANT_URI . 'assets/js/modules/animated-add-to-cart.js', array( 'merchant' ), MERCHANT_VERSION, true );

				}

			}

			// Auto External Links
			if ( Merchant_Modules::is_module_active( 'auto-external-links' ) ) {

				$setting['auto_external_links'] = true;

				wp_enqueue_script( 'merchant-auto-external-links', MERCHANT_URI . 'assets/js/modules/auto-external-links.js', array( 'merchant' ), MERCHANT_VERSION, true );

			}

			// Inactive Tab Message
			if ( Merchant_Modules::is_module_active( 'inactive-tab-messsage' ) ) {

				$setting['inactive_tab_messsage']          = Merchant_Admin_Options::get( 'inactive-tab-messsage', 'message', esc_html__( '✋ Don\'t forget this', 'merchant' ) );
				$setting['inactive_tab_abandoned_message'] = Merchant_Admin_Options::get( 'inactive-tab-messsage', 'abandoned_message', esc_html__( '✋ You left something in the cart', 'merchant' ) );
				$setting['inactive_tab_cart_count']        = '0';
				
				if ( function_exists( 'WC' ) ) {
					$setting['inactive_tab_cart_count'] = WC()->cart->get_cart_contents_count();
				}

				wp_enqueue_script( 'merchant-inactive-tab-messsage', MERCHANT_URI . 'assets/js/modules/inactive-tab-messsage.js', array( 'merchant' ), MERCHANT_VERSION, true );

			}

			// Cart Count Favicon
			if ( Merchant_Modules::is_module_active( 'cart-count-favicon' ) ) {

				$setting['cart_count_favicon']                  = true;
				$setting['cart_count_favicon_shape']            = Merchant_Admin_Options::get( 'cart-count-favicon', 'shape', 'circle' );
				$setting['cart_count_favicon_position']         = Merchant_Admin_Options::get( 'cart-count-favicon', 'position', 'down-left' );
				$setting['cart_count_favicon_background_color'] = Merchant_Admin_Options::get( 'cart-count-favicon', 'background_color', '#ff0101' );
				$setting['cart_count_favicon_text_color']       = Merchant_Admin_Options::get( 'cart-count-favicon', 'text_color', '#ffffff' );
				$setting['cart_count_favicon_delay']            = Merchant_Admin_Options::get( 'cart-count-favicon', 'delay', '0' );
				$setting['cart_count_favicon_count']            = '0';
				
				if ( function_exists( 'WC' ) ) {
					$setting['cart_count_favicon_count'] = WC()->cart->get_cart_contents_count();
				}

				wp_enqueue_script( 'merchant-favico', MERCHANT_URI . 'assets/js/vendor/favico.js', array( 'merchant' ), MERCHANT_VERSION, true );
				wp_enqueue_script( 'merchant-cart-count-favicon', MERCHANT_URI . 'assets/js/modules/cart-count-favicon.js', array( 'merchant' ), MERCHANT_VERSION, true );

			}

			// Cookie Banner
			if ( Merchant_Modules::is_module_active( 'cookie-banner' ) ) {

				$setting['cookie_banner']          = true;
				$setting['cookie_banner_duration'] = Merchant_Admin_Options::get( 'cookie-banner', 'cookie_duration', 365 );
				
				wp_enqueue_script( 'merchant-cookie-banner', MERCHANT_URI . 'assets/js/modules/cookie-banner.js', array( 'merchant' ), MERCHANT_VERSION, true );

			}

			// Quick View
			if ( Merchant_Modules::is_module_active( 'quick-view' ) ) {

				$setting['quick_view']      = true;
				$setting['quick_view_zoom'] = Merchant_Admin_Options::get( 'quick-view', 'zoom_effect', 1 );

				wp_enqueue_script( 'zoom' );
				wp_enqueue_script( 'flexslider' );
				wp_enqueue_script( 'wc-single-product' );
				wp_enqueue_script( 'wc-add-to-cart-variation' );
				
				wp_enqueue_script( 'merchant-quick-view', MERCHANT_URI . 'assets/js/modules/quick-view.js', array( 'merchant' ), MERCHANT_VERSION, true );
				
			}

			// Real Time Search
			if ( Merchant_Modules::is_module_active( 'real-time-search' ) ) {

				$setting['ajax_search']                              = true;
				$setting['ajax_search_results_amounth_per_search']   = Merchant_Admin_Options::get( 'real-time-search', 'results_amounth_per_search', 15 );
				$setting['ajax_search_results_order_by']             = Merchant_Admin_Options::get( 'real-time-search', 'results_order_by', 'title' );
				$setting['ajax_search_results_order']                = Merchant_Admin_Options::get( 'real-time-search', 'results_order', 'asc' );
				$setting['ajax_search_results_display_categories']   = Merchant_Admin_Options::get( 'real-time-search', 'display_categories', 0 );
				$setting['ajax_search_results_enable_search_by_sku'] = Merchant_Admin_Options::get( 'real-time-search', 'enable_search_by_sku', 0 );

				wp_enqueue_script( 'merchant-real-time-search', MERCHANT_URI . 'assets/js/modules/real-time-search.js', array( 'merchant' ), MERCHANT_VERSION, true );

			}

			// Pre Orders
			if ( Merchant_Modules::is_module_active( 'pre-orders' ) ) {
				
				$setting['pre_orders']                  = true;
				$setting['pre_orders_add_button_title'] = Merchant_Admin_Options::get( 'pre-orders', 'add_button_title', esc_html__( 'Pre Order Now!', 'merchant' ) );

				wp_enqueue_script( 'merchant-pre-orders', MERCHANT_URI . 'assets/js/modules/pre-orders.js', array( 'merchant' ), MERCHANT_VERSION, true );

			}

			wp_localize_script( 'merchant', 'merchant', array( 'setting' =>  $setting ) );

			do_action( 'merchant_enqueue_after_main_css_js' );

		}

	}

	Merchant_Loader::instance();

}
