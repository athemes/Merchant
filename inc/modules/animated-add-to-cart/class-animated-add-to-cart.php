<?php

/**
 * Animated Add To Cart.
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Animated Add To Cart Class.
 * 
 */
class Merchant_Animated_Add_To_Cart extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'animated-add-to-cart';

	/**
	 * Is module preview.
	 * 
	 */
	public static $is_module_preview = false;

	/**
	 * Constructor.
	 * 
	 */
	public function __construct() {

		// Module id.
		$this->module_id = self::MODULE_ID;

		// WooCommerce only.
		$this->wc_only = true;

		// Parent construct.
		parent::__construct();

		// Module section.
		$this->module_section = 'improve-experience';

		// Module default settings.
		$this->module_default_settings = array(
			'trigger' => 'on-mouse-hover',
			'animation' => 'swing',
		);

		// Mount preview url.
		$preview_url = site_url( '/' );

		if ( function_exists( 'wc_get_products' ) ) {
			$products = wc_get_products( array( 'limit' => 1 ) );

			if ( ! empty( $products ) && ! empty( $products[0] ) ) {
				$preview_url = get_permalink( $products[0]->get_id() );
			}
		}

		// Module data.
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];
		$this->module_data[ 'preview_url' ] = $preview_url;

		// Module options path.
		$this->module_options_path = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID . '/admin/options.php';

		// Is module preview page.
		if ( is_admin() && parent::is_module_settings_page() ) {
			self::$is_module_preview = true;

			// Enqueue admin styles.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_css' ) );

			// Admin preview box.
			add_filter( 'merchant_module_preview', array( $this, 'render_admin_preview' ), 10, 2 );

			// Custom CSS.
			// The custom CSS should be added here as well due to ensure preview box works properly.
			add_filter( 'merchant_custom_css', array( $this, 'admin_custom_css' ) );

		}

		if ( ! Merchant_Modules::is_module_active( self::MODULE_ID ) ) {
			return;
		}

		// Return early if it's on admin but not in the respective module settings page.
		if ( is_admin() && ! parent::is_module_settings_page() ) {
			return; 
		}

		// Enqueue styles.
		add_action( 'merchant_enqueue_before_main_css_js', array( $this, 'enqueue_css' ) );

		// Handle body class.
		add_action( 'body_class', array( $this, 'body_class' ) );

		// Custom CSS.
		add_filter( 'merchant_custom_css', array( $this, 'frontend_custom_css' ) );
	}

	/**
	 * Admin enqueue CSS.
	 * 
	 * @return void
	 */
	public function admin_enqueue_css() {
		$page   = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$module = ( ! empty( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( 'merchant' === $page && self::MODULE_ID === $module ) {
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/animated-add-to-cart.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
		}
	}

	/**
	 * Enqueue CSS.
	 * 
	 * @return void
	 */
	public function enqueue_css() {

		// Specific module styles.
		wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/animated-add-to-cart.min.css', array(), MERCHANT_VERSION );
	}

	/**
	 * Render admin preview
	 *
	 * @param Merchant_Admin_Preview $preview
	 * @param string $module
	 *
	 * @return Merchant_Admin_Preview
	 */
	public function render_admin_preview( $preview, $module ) {
		if ( self::MODULE_ID === $module ) {
			ob_start();
			self::admin_preview_content();
			$content = ob_get_clean();

			// HTML.
			$preview->set_html( $content );

			// Trigger.
			$preview->set_class( 'trigger', '.add_to_cart_button', array( 'on-mouse-hover', 'on-page-load' ) );

			// Animation.
			$preview->set_class( 'animation', '.add_to_cart_button', array( 
				'flash', 
				'bounce',
				'zoom-in',
				'shake',
				'pulse',
				'jello-shake',
				'wobble',
				'vibrate',
				'swing',
				'tada', 
			) );

		}

		return $preview;
	}

	/**
	 * Admin preview content.
	 * 
	 * @return void
	 */
	public function admin_preview_content() {
		?>

		<div class="mrc-preview-single-product-elements">
			<div class="mrc-preview-left-column">
				<div class="mrc-preview-product-image-wrapper">
					<div class="mrc-preview-product-image"></div>
					<div class="mrc-preview-product-image-thumbs">
						<div class="mrc-preview-product-image-thumb"></div>
						<div class="mrc-preview-product-image-thumb"></div>
						<div class="mrc-preview-product-image-thumb"></div>
					</div>
				</div>
			</div>
			<div class="mrc-preview-right-column">
				<div class="mrc-preview-text-placeholder"></div>
				<div class="mrc-preview-text-placeholder mrc-mw-70"></div>
				<div class="mrc-preview-text-placeholder mrc-mw-30"></div>
				<div class="mrc-preview-text-placeholder mrc-mw-40"></div>
				<a href="#" class="add_to_cart_button"><?php echo esc_html__( 'Add To Cart', 'merchant' ); ?></a>
			</div>
		</div>

		<?php
	}

	/**
	 * Add body class.
	 * 
	 * @param array $classes The body classes.
	 * @return array $classes The body classes.
	 */
	public function body_class( $classes ) {
		$settings = $this->get_module_settings();

		$classes[] = 'merchant-animated-add-to-cart merchant-animated-add-to-cart-' . esc_attr( $settings[ 'animation' ] );
	
		return $classes;
	}

	/**
	 * Custom CSS.
	 * 
	 * @return string
	 */
	public function get_module_custom_css() {
		$settings = $this->get_module_settings();
		
		$css = '';

		$css .= '.add_to_cart_button:not(.merchant_buy_now_button),';
		$css .= '.product_type_grouped:not(.merchant_buy_now_button) {';
		$css .= '	transition: all .3s ease-in;';
		$css .= '}';

		if ( 'on-mouse-hover' === $settings[ 'trigger' ] ) {
			$css .= '.add_to_cart_button:not(.merchant_buy_now_button):hover,';
			$css .= '.product_type_grouped:not(.merchant_buy_now_button):hover,';
			$css .= '.single_add_to_cart_button:not(.merchant_buy_now_button):hover,';
		}

		$css .= '.add_to_cart_button:not(.merchant_buy_now_button).merchant-active,';
		$css .= '.product_type_grouped:not(.merchant_buy_now_button).merchant-active,';
		$css .= '.single_add_to_cart_button:not(.merchant_buy_now_button).merchant-active {';

		switch ( $settings[ 'animation' ] ) {

			case 'flash':
				$css .= 'animation: merchant-flash 1s infinite both;';
				$css .= 'animation-iteration-count: 1;';
				break;

			case 'bounce':
				$css .= 'animation: merchant-bounce .3s alternate;';
				$css .= 'animation-iteration-count: 4;';
				break;

			case 'zoom-in':
				$css .= 'transform: scale(1.2);';
				break;

			case 'shake':
				$css .= 'animation: merchant-shake .3s;';
				$css .= 'animation-iteration-count: 2;';
				break;

			case 'pulse':
				$css .= 'animation: merchant-pulse 1.5s ease-in-out infinite both;';
				break;

			case 'jello-shake':
				$css .= 'animation: merchant-jello-shake 1.5s infinite both;';
				break;

			case 'wobble':
				$css .= 'animation: merchant-wobble 1.5s ease-in-out infinite both;';
				break;

			case 'vibrate':
				$css .= 'animation: merchant-vibrate .3s linear 4 both;';
				break;

			case 'swing':
				$css .= 'animation: merchant-swing 2s ease-in-out infinite alternate;';
				break;

			case 'tada':
				$css .= 'animation: merchant-tada 1s infinite both;';
				break;

		}

		$css .= '}';

		return $css;
	}

	/**
	 * Admin custom CSS.
	 * 
	 * @param string $css The custom CSS.
	 * @return string $css The custom CSS.
	 */
	public function admin_custom_css( $css ) {
		$css .= $this->get_module_custom_css(); 

		return $css;
	}

	/**
	 * Frontend custom CSS.
	 * 
	 * @param string $css The custom CSS.
	 * @return string $css The custom CSS.
	 */
	public function frontend_custom_css( $css ) {
		$css .= $this->get_module_custom_css();

		return $css;
	}
}

// Initialize the module.
add_action( 'init', function() {
	new Merchant_Animated_Add_To_Cart();
} );
