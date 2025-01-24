<?php

/**
 * Sticky Add To Cart
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Sticky add to cart class.
 *
 */
class Merchant_Sticky_Add_To_Cart extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'sticky-add-to-cart';

	/**
	 * Is module preview.
	 *
	 */
	public static $is_module_preview = false;

	/**
	 * Module settings.
	 *
	 */
	public static $module_settings = array();

	/**
	 * Set the module as having analytics.
	 *
	 * @var bool
	 */
	protected $has_analytics = true;

	/**
	 * Constructor.
	 *
	 */
	public function __construct() {
		// Module id.
		$this->module_id = self::MODULE_ID;

		// WooCommerce only.
		$this->wc_only = true;

		// Parent constructor.
		parent::__construct();

		// Module section.
		$this->module_section = 'convert-more';

		// Module default settings.
		$this->module_default_settings = array(
			'position'                  => 'position-bottom',
			'display_after_amount'      => 100,
			'hide_product_image'        => false,
			'hide_product_title'        => false,
			'hide_product_price'        => false,
			'elements_spacing'          => 35,
			'scroll_hide'               => 0,
			'visibility'                => 'all',
			'allow_third_party_plugins' => 0,
		);

		// Module data.
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];

		// Module options path.
		$this->module_options_path = MERCHANT_DIR . 'inc/modules/sticky-add-to-cart/admin/options.php';

		// Store module handled (with defaults) module settings into a static variable.
		self::$module_settings = $this->get_module_settings();

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
	}

	/**
	 * Get all analytics metrics and allow modules to filter them.
	 *
	 * @return array List of available metrics.
	 */
	public function analytics_metrics() {
		$metrics              = $this->default_analytics_metrics();
		$metrics['campaigns'] = false;

		/**
		 * Hook: merchant_analytics_module_metrics
		 *
		 * @param array  $metrics   List of available metrics.
		 * @param string $module_id Module ID.
		 *
		 * @since 2.0
		 */
		return apply_filters( 'merchant_analytics_module_metrics', $metrics, $this->module_id, $this );
	}

	/**
	 * Admin enqueue CSS.
	 *
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( parent::is_module_settings_page() ) {
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/sticky-add-to-cart.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
		}
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

			// Position.
			$preview->set_class( 'position', '.merchant-sticky-add-to-cart-wrapper', array( 'position-top', 'position-bottom' ) );

			// Hide Product Image.
			$preview->set_class( 'hide_product_image', '.merchant-sticky-add-to-cart-wrapper', array( 'hide-product-image' ), 'hide-product-image' );

			// Hide Product Title.
			$preview->set_class( 'hide_product_title', '.merchant-sticky-add-to-cart-wrapper', array( 'hide-product-title' ), 'hide-product-title' );

			// Hide Product Price.
			$preview->set_class( 'hide_product_price', '.merchant-sticky-add-to-cart-wrapper', array( 'hide-product-price' ), 'hide-product-price' );
		}

		return $preview;
	}

	/**
	 * Admin preview content.
	 *
	 * @return void
	 */
	public function admin_preview_content() {
		$settings = $this->get_module_settings();

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
                <div class="mrc-preview-addtocart-placeholder"></div>
            </div>
        </div>

		<?php self::content(); ?>

		<?php
	}

	/**
	 * Content output.
	 *
	 * @return void
	 */
	public static function content() {

		/**
		 * Hook: merchant_sticky_add_to_cart_template_args
		 *
		 * @since 1.4
		 */
		$args = apply_filters( 'merchant_sticky_add_to_cart_template_args', array(
			'settings' => self::$module_settings,
			'elements' => array(
				'product_image' => '',
				'product_title' => '<h5>' . esc_html__( 'Product Title', 'merchant' ) . '</h5>',
				'product_price' => wc_price( 199 ),
				'add_to_cart'   => __( '<a href="#" class="single_add_to_cart_button button">Add to cart</a>', 'merchant' ),
			),
		) );

		merchant_get_template_part( 'modules/sticky-add-to-cart', 'content', $args );
	}

	/**
	 * Custom CSS.
	 *
	 * @param string $css
	 *
	 * @return string
	 */
	public function get_module_custom_css() {
		$css = '';

		// Elements spacing.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'elements_spacing', 35, '.merchant-sticky-add-to-cart-wrapper', '--mrc-sticky-adtc-elements-spacing', 'px' );

		// Border color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'border_color', '#E2E2E2', '.merchant-sticky-add-to-cart-wrapper', '--mrc-sticky-adtc-border-color' );

		// Background color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'background_color', '#FFF', '.merchant-sticky-add-to-cart-wrapper', '--mrc-sticky-adtc-bg-color' );

		// Content color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'content_color', '#212121', '.merchant-sticky-add-to-cart-wrapper', '--mrc-sticky-adtc-content-color' );

		// Title color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'title_color', '#212121', '.merchant-sticky-add-to-cart-wrapper', '--mrc-sticky-adtc-title-color' );

		// Button Background Color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'button_bg_color', '#212121', '.merchant-sticky-add-to-cart-wrapper', '--mrc-sticky-adtc-button-bg-color' );

		// Button Background Color (hover).
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'button_bg_color_hover', '#757575', '.merchant-sticky-add-to-cart-wrapper', '--mrc-sticky-adtc-button-bg-color-hover' );

		// Button Color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'button_color', '#FFF', '.merchant-sticky-add-to-cart-wrapper', '--mrc-sticky-adtc-button-color' );

		// Button Color (hover).
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'button_color_hover', '#FFF', '.merchant-sticky-add-to-cart-wrapper', '--mrc-sticky-adtc-button-color-hover' );

		return $css;
	}

	/**
	 * Admin custom CSS.
	 *
	 * @param string $css The custom CSS.
	 *
	 * @return string $css The custom CSS.
	 */
	public function admin_custom_css( $css ) {
		$css .= $this->get_module_custom_css();

		return $css;
	}
}

// Initialize the module.
add_action( 'init', function () {
	Merchant_Modules::create_module( new Merchant_Sticky_Add_To_Cart() );
} );
