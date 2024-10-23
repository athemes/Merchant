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

			// Multi Language.
			require_once MERCHANT_DIR . 'inc/MultiLang/interface-language-strategy.php';
			require_once MERCHANT_DIR . 'inc/MultiLang/class-merchant-no-plugin-support.php';
			require_once MERCHANT_DIR . 'inc/MultiLang/class-merchant-polylang-support.php';
			require_once MERCHANT_DIR . 'inc/MultiLang/class-merchant-wpml-support.php';
			require_once MERCHANT_DIR . 'inc/MultiLang/class-merchant-translator.php';

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

			// Modules global settings.
			require_once MERCHANT_DIR . 'inc/modules/global-settings/global-settings.php';

			// Modules (free and pro).
			foreach ( Merchant_Admin_Modules::$modules_data as $module_id => $module_data ) {
				if (
					defined( 'MERCHANT_PRO_VERSION' )
					&& version_compare( MERCHANT_PRO_VERSION, '1.3', '<' )
					&& isset( $module_data['pro'] ) && $module_data['pro']
				) {
					continue;
				}

				require_once MERCHANT_DIR . 'inc/modules/' . $module_id . '/class-' . $module_id . '.php';
			}

			// Compatibility Layer
			require_once MERCHANT_DIR . 'inc/compatibility/class-merchant-botiga-theme.php';
			require_once MERCHANT_DIR . 'inc/compatibility/class-merchant-divi-theme.php';
			require_once MERCHANT_DIR . 'inc/compatibility/class-merchant-avada-theme.php';
			require_once MERCHANT_DIR . 'inc/compatibility/class-merchant-astra-theme.php';
			require_once MERCHANT_DIR . 'inc/compatibility/class-merchant-kadence-theme.php';
			require_once MERCHANT_DIR . 'inc/compatibility/class-merchant-oceanwp-theme.php';
			require_once MERCHANT_DIR . 'inc/compatibility/class-merchant-twenty-twenty-four-theme.php';
			require_once MERCHANT_DIR . 'inc/compatibility/class-merchant-blocksy-theme.php';
			require_once MERCHANT_DIR . 'inc/compatibility/class-merchant-flatsome-theme.php';
			require_once MERCHANT_DIR . 'inc/compatibility/class-merchant-breakdance-builder.php';
			require_once MERCHANT_DIR . 'inc/compatibility/class-merchant-elementor-builder.php';
			require_once MERCHANT_DIR . 'inc/compatibility/class-merchant-bricks-builder.php';

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
			$theme_name = str_replace( ' ', '-', $theme->name );

			$classes[] = 'merchant-theme-' . strtolower( esc_attr( $theme_name ) );

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
					'media'  => 'all',
				),

				// Utilities.
				array(
					'handle' => 'merchant-utilities',
					'src'    => 'assets/css/utilities.min.css',
					'dep'    => array(),
					'ver'    => MERCHANT_VERSION,
					'media'  => 'all',
				),

				// Carousel.
				array(
					'handle' => 'merchant-carousel',
					'src'    => 'assets/css/carousel.min.css',
					'dep'    => array(),
					'ver'    => MERCHANT_VERSION,
					'media'  => 'all',
				),

				// Modal.
				array(
					'handle' => 'merchant-modal',
					'src'    => 'assets/css/modal.min.css',
					'dep'    => array(),
					'ver'    => MERCHANT_VERSION,
					'media'  => 'all',
				),

				// Tooltip.
				array(
					'handle' => 'merchant-tooltip',
					'src'    => 'assets/css/tooltip.min.css',
					'dep'    => array(),
					'ver'    => MERCHANT_VERSION,
					'media'  => 'all',
				),

				// Pagination.
				array(
					'handle' => 'merchant-pagination',
					'src'    => 'assets/css/pagination.min.css',
					'dep'    => array(),
					'ver'    => MERCHANT_VERSION,
					'media'  => 'all',
				),

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
					'in_footer' => true,
				),

				// Toggle Class
				array(
					'handle'    => 'merchant-toggle-class',
					'src'       => 'assets/js/toggle-class.min.js',
					'dep'       => array(),
					'in_footer' => true,
				),

				// Scroll To
				array(
					'handle'    => 'merchant-scroll-to',
					'src'       => 'assets/js/scroll-to.min.js',
					'dep'       => array(),
					'in_footer' => true,
				),

				// Custom Add To Cart Button
				array(
					'handle'    => 'merchant-custom-addtocart-button',
					'src'       => 'assets/js/custom-addtocart-button.min.js',
					'dep'       => array(),
					'in_footer' => true,
				),

				// Carousel
				array(
					'handle'    => 'merchant-carousel',
					'src'       => 'assets/js/carousel.min.js',
					'dep'       => array(),
					'in_footer' => true,
				),

				// Modal
				array(
					'handle'    => 'merchant-modal',
					'src'       => 'assets/js/modal.min.js',
					'dep'       => array(),
					'in_footer' => true,
				),

				// Copy To Clipboard
				array(
					'handle'          => 'merchant-copy-to-clipboard',
					'src'             => 'assets/js/copy-to-clipboard.min.js',
					'dep'             => array(),
					'in_footer'       => true,
					'localize_script' => array(
						'object' => 'merchantCopyToClipboard',
						'data'   => array(
							'i18n' => array(
								'copied' => esc_html__( 'Copied!', 'merchant' ),
							),
						),
					),
				),

				// Pagination
				array(
					'handle'    => 'merchant-pagination',
					'src'       => 'assets/js/pagination.min.js',
					'dep'       => array(),
					'in_footer' => true,
				),
			);

			foreach ( $scripts as $script ) {
				wp_register_script( $script['handle'], MERCHANT_URI . $script['src'], $script['dep'], MERCHANT_VERSION, $script['in_footer'] );

				if ( isset( $script['localize_script'] ) ) {
					wp_localize_script( $script['handle'], $script['localize_script']['object'], $script['localize_script']['data'] );
				}
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
					'wooCurrencySymbol' => class_exists( 'Woocommerce' ) ? html_entity_decode( get_woocommerce_currency_symbol() ) : '',
				),
				'setting' => $setting,
			) );
		}
	}

	Merchant_Loader::instance();
}
