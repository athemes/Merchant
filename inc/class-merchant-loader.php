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
			// Includes.
			$this->includes();

			// Register scripts.
			add_action( 'wp_enqueue_scripts', array( $this, 'register_global_js_and_css' ) );

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

			// Ajax callbacks.
			require_once MERCHANT_DIR . 'inc/classes/class-merchant-ajax-callbacks.php';

			// Core classes.
			require_once MERCHANT_DIR . 'inc/classes/class-merchant-option.php';
			require_once MERCHANT_DIR . 'inc/classes/class-merchant-modules.php';
			require_once MERCHANT_DIR . 'inc/classes/class-merchant-custom-css.php';
			require_once MERCHANT_DIR . 'inc/classes/class-merchant-svg-icons.php';

			// Metabox
			require_once MERCHANT_DIR . 'inc/classes/class-merchant-metabox.php';

			// The main class for adding modules.
			require_once MERCHANT_DIR . 'inc/modules/class-add-module.php';

			// Modules.
			require_once MERCHANT_DIR . 'inc/modules/global-settings/global-settings.php';
			require_once MERCHANT_DIR . 'inc/modules/buy-now/class-buy-now.php';
			require_once MERCHANT_DIR . 'inc/modules/animated-add-to-cart/class-animated-add-to-cart.php';
			require_once MERCHANT_DIR . 'inc/modules/quick-view/class-quick-view.php';
			require_once MERCHANT_DIR . 'inc/modules/product-labels/class-product-labels.php';
			require_once MERCHANT_DIR . 'inc/modules/add-to-cart-text/class-add-to-cart-text.php';
			require_once MERCHANT_DIR . 'inc/modules/pre-orders/class-pre-orders.php';
			require_once MERCHANT_DIR . 'inc/modules/cart-count-favicon/class-cart-count-favicon.php';
			require_once MERCHANT_DIR . 'inc/modules/inactive-tab-message/class-inactive-tab-message.php';
			require_once MERCHANT_DIR . 'inc/modules/payment-logos/class-payment-logos.php';
			require_once MERCHANT_DIR . 'inc/modules/trust-badges/class-trust-badges.php';
			require_once MERCHANT_DIR . 'inc/modules/auto-external-links/class-auto-external-links.php';
			require_once MERCHANT_DIR . 'inc/modules/real-time-search/class-real-time-search.php';
			require_once MERCHANT_DIR . 'inc/modules/code-snippets/class-code-snippets.php';
			require_once MERCHANT_DIR . 'inc/modules/scroll-to-top-button/class-scroll-to-top-button.php';
			require_once MERCHANT_DIR . 'inc/modules/agree-to-terms-checkbox/class-agree-to-terms-checkbox.php';
			require_once MERCHANT_DIR . 'inc/modules/cookie-banner/class-cookie-banner.php';

			// Pro modules;
			if ( ! defined( 'MERCHANT_PRO_VERSION' )
				|| defined( 'MERCHANT_PRO_VERSION' ) && (float) MERCHANT_PRO_VERSION > 1.2 ) {
				require_once MERCHANT_DIR . 'inc/modules/cart-reserved-timer/class-cart-reserved-timer.php';
				require_once MERCHANT_DIR . 'inc/modules/buy-x-get-y/class-buy-x-get-y.php';
				require_once MERCHANT_DIR . 'inc/modules/advanced-reviews/class-advanced-reviews.php';
				require_once MERCHANT_DIR . 'inc/modules/checkout/class-checkout.php';
				require_once MERCHANT_DIR . 'inc/modules/countdown-timer/class-countdown-timer.php';
				require_once MERCHANT_DIR . 'inc/modules/floating-mini-cart/class-floating-mini-cart.php';
				require_once MERCHANT_DIR . 'inc/modules/free-gifts/class-free-gifts.php';
				require_once MERCHANT_DIR . 'inc/modules/frequently-bought-together/class-frequently-bought-together.php';
				require_once MERCHANT_DIR . 'inc/modules/login-popup/class-login-popup.php';
				require_once MERCHANT_DIR . 'inc/modules/product-audio/class-product-audio.php';
				require_once MERCHANT_DIR . 'inc/modules/product-brand-image/class-product-brand-image.php';
				require_once MERCHANT_DIR . 'inc/modules/product-video/class-product-video.php';
				require_once MERCHANT_DIR . 'inc/modules/reasons-to-buy/class-reasons-to-buy.php';
				require_once MERCHANT_DIR . 'inc/modules/recently-viewed-products/class-recently-viewed-products.php';
				require_once MERCHANT_DIR . 'inc/modules/size-chart/class-size-chart.php';
				require_once MERCHANT_DIR . 'inc/modules/spending-goal/class-spending-goal.php';
				require_once MERCHANT_DIR . 'inc/modules/sticky-add-to-cart/class-sticky-add-to-cart.php';
				require_once MERCHANT_DIR . 'inc/modules/stock-scarcity/class-stock-scarcity.php';
				require_once MERCHANT_DIR . 'inc/modules/volume-discounts/class-volume-discounts.php';
				require_once MERCHANT_DIR . 'inc/modules/wait-list/class-wait-list.php';
				require_once MERCHANT_DIR . 'inc/modules/wishlist/class-wishlist.php';
				require_once MERCHANT_DIR . 'inc/modules/product-swatches/class-product-swatches.php';
				require_once MERCHANT_DIR . 'inc/modules/side-cart/class-side-cart.php';
				require_once MERCHANT_DIR . 'inc/modules/quick-social-links/class-quick-social-links.php';
				require_once MERCHANT_DIR . 'inc/modules/product-navigation-links/class-product-navigation-links.php';
			}

			// Compatibility Layer
			require_once MERCHANT_DIR . 'inc/compatibility/class-merchant-kadence-theme.php';
			require_once MERCHANT_DIR . 'inc/compatibility/class-merchant-oceanwp-theme.php';
			require_once MERCHANT_DIR . 'inc/compatibility/class-merchant-twenty-twenty-four-theme.php';

			/**
			 * Hook 'merchant_admin_after_include_modules_classes'.
			 *
			 * @since 1.0
			 */
			do_action( 'merchant_admin_after_include_modules_classes' );
		}

		/**
		 * Add to body class.
		 */
		public function add_body_class( $classes ) {
			$theme = wp_get_theme();
			$theme = ( get_template_directory() !== get_stylesheet_directory() && $theme->parent() ) ? $theme->parent() : $theme;

			$classes[] = 'merchant-theme-' . strtolower( esc_attr( $theme->name ) );

			return $classes;
		}

		/**
		 * Register scripts.
		 * Scripts that might be used by multiple modules should be registered here.
		 *
		 */
		public function register_global_js_and_css() {
			// Register styles.
			$styles = array(

				// Grid.
				array(
					'handle' => 'merchant-grid',
					'src'    => 'assets/css/grid.min.css',
					'dep'    => array(),
					'ver'    => MERCHANT_VERSION,
					'media'  => 'all'
				),

				// Utilities.
				array(
					'handle' => 'merchant-utilities',
					'src'    => 'assets/css/utilities.min.css',
					'dep'    => array(),
					'ver'    => MERCHANT_VERSION,
					'media'  => 'all'
				),

				// Carousel.
				array(
					'handle' => 'merchant-carousel',
					'src'    => 'assets/css/carousel.min.css',
					'dep'    => array(),
					'ver'    => MERCHANT_VERSION,
					'media'  => 'all'
				),

				// Pagination.
				array(
					'handle' => 'merchant-pagination',
					'src'    => 'assets/css/pagination.min.css',
					'dep'    => array(),
					'ver'    => MERCHANT_VERSION,
					'media'  => 'all'
				)

			);

			foreach ( $styles as $style ) {
				wp_register_style( $style['handle'], MERCHANT_URI . $style['src'], $style['dep'], $style['ver'], $style['media'] );
			}

			// Register scripts.
			$scripts = array(

				// Scroll Direction
				array(
					'handle'    => 'merchant-scroll-direction',
					'src'       => 'assets/js/scroll-direction.min.js',
					'dep'       => array(),
					'in_footer' => true
				),

				// Toggle Class
				array(
					'handle'    => 'merchant-toggle-class',
					'src'       => 'assets/js/toggle-class.min.js',
					'dep'       => array(),
					'in_footer' => true
				),

				// Custom Add To Cart Button
				array(
					'handle'    => 'merchant-custom-addtocart-button',
					'src'       => 'assets/js/custom-addtocart-button.min.js',
					'dep'       => array(),
					'in_footer' => true
				),

				// Carousel
				array(
					'handle'    => 'merchant-carousel',
					'src'       => 'assets/js/carousel.min.js',
					'dep'       => array(),
					'in_footer' => true
				),

				// Pagination
				array(
					'handle'    => 'merchant-pagination',
					'src'       => 'assets/js/pagination.min.js',
					'dep'       => array(),
					'in_footer' => true
				)
			);

			foreach ( $scripts as $script ) {
				wp_register_script( $script['handle'], MERCHANT_URI . $script['src'], $script['dep'], MERCHANT_VERSION, $script['in_footer'] );
			}
		}

		/**
		 * Enqueue styles and scripts.
		 */
		public function enqueue_styles_scripts() {

			/**
			 * Hook 'merchant_enqueue_before_main_css_js'
			 *
			 * @since 1.0
			 */
			do_action( 'merchant_enqueue_before_main_css_js' );

			wp_enqueue_style( 'merchant', MERCHANT_URI . 'assets/css/merchant.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_script( 'merchant', MERCHANT_URI . 'assets/js/merchant.min.js', array( 'jquery' ), MERCHANT_VERSION, true );

			$setting = array(
				'nonce'    => wp_create_nonce( 'merchant-nonce' ),
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			);

			// Scroll to Top Button
			// TODO: Move this to the respective module class.
			if ( Merchant_Modules::is_module_active( 'scroll-to-top-button' ) ) {
				$setting['scroll_to_top'] = true;
			}

			// Animated Add to Cart
			// TODO: Move this to the respective module class.
			if ( Merchant_Modules::is_module_active( 'animated-add-to-cart' ) ) {
				$trigger = Merchant_Admin_Options::get( 'animated-add-to-cart', 'trigger', 'on-mouse-hover' );

				if ( 'on-page-load' === $trigger ) {
					wp_enqueue_script( 'merchant-animated-add-to-cart', MERCHANT_URI . 'assets/js/modules/animated-add-to-cart.js', array( 'merchant' ), MERCHANT_VERSION, true );
				}
			}

			// Auto External Links
			// TODO: Move this to the respective module class.
			if ( Merchant_Modules::is_module_active( 'auto-external-links' ) ) {
				$setting['auto_external_links'] = true;
			}

			// Cookie Banner
			if ( Merchant_Modules::is_module_active( 'cookie-banner' ) ) {
				$setting['cookie_banner']          = true;
				$setting['cookie_banner_duration'] = Merchant_Admin_Options::get( 'cookie-banner', 'cookie_duration', 365 );
			}

			// Real Time Search
			// TODO: Move this to the respective module class.
			if ( Merchant_Modules::is_module_active( 'real-time-search' ) ) {
				$setting['ajax_search']                              = true;
				$setting['ajax_search_results_amounth_per_search']   = Merchant_Admin_Options::get( 'real-time-search', 'results_amounth_per_search', 15 );
				$setting['ajax_search_results_order_by']             = Merchant_Admin_Options::get( 'real-time-search', 'results_order_by', 'title' );
				$setting['ajax_search_results_order']                = Merchant_Admin_Options::get( 'real-time-search', 'results_order', 'asc' );
				$setting['ajax_search_results_display_categories']   = Merchant_Admin_Options::get( 'real-time-search', 'display_categories', 0 );
				$setting['ajax_search_results_enable_search_by_sku'] = Merchant_Admin_Options::get( 'real-time-search', 'enable_search_by_sku', 0 );
			}

			/**
			 * Hook 'merchant_enqueue_after_main_css_js'
			 *
			 * @since 1.0
			 */
			do_action( 'merchant_enqueue_after_main_css_js' );

			/**
			 * Hook 'merchant_localize_script'
			 *
			 * @since 1.0
			 */
			$setting = apply_filters( 'merchant_localize_script', $setting );

			wp_localize_script( 'merchant', 'merchant', array( 
				'general' => array(
					'wooCurrencySymbol' => html_entity_decode( get_woocommerce_currency_symbol() )
				),
				'setting' => $setting 
			) );
		}

	}

	Merchant_Loader::instance();
}
