<?php

/**
 * Reasons To Buy.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Reasons to buy class.
 *
 */
class Merchant_Reasons_To_Buy extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'reasons-to-buy';

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
		$this->module_section = 'build-trust';

		// Module default settings.
		$this->module_default_settings = array(
			'placement'    => 'after-short-description',
			'title'        => __( 'Reasons to buy list', 'merchant' ),
			'reasons'      => array(
				esc_html__( '100% Polyester.', 'merchant' ),
				esc_html__( 'Recycled Polyamid.', 'merchant' ),
				esc_html__( 'GOTS-certified organic cotton.', 'merchant' ),
			),
			'display_icon' => 1,
			'icon'         => 'check2',
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
		$this->module_data                = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];
		$this->module_data['preview_url'] = $preview_url;

		// Module options path.
		$this->module_options_path = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID . '/admin/options.php';

		// Module wrapper class.
		add_filter( 'merchant_reasons_to_buy_wrapper_class', array( $this, 'html_wrapper_class' ) );

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

		if ( Merchant_Modules::is_module_active( self::MODULE_ID ) && is_admin() ) {
			// Init translations.
			$this->init_translations();
		}
	}

	/**
	 * Init translations.
	 *
	 * @return void
	 */
	public function init_translations() {
		$settings = $this->get_module_settings();
		if ( ! empty( $settings['reasons'] ) ) {
			foreach ( $settings['reasons'] as $reason ) {
				Merchant_Translator::register_string( $reason, esc_html__( 'Reasons to buy: reason text', 'merchant' ) );
			}
		}
	}

	/**
	 * Admin enqueue CSS.
	 *
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( parent::is_module_settings_page() ) {
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/reasons-to-buy.min.css', array(), MERCHANT_VERSION );
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

			// TItle.
			$preview->set_text( 'title', '.merchant-reasons-list-title' );

			// Reasons.
			$preview->set_repeater_content( 'reasons', '.merchant-reasons-list-item-text' );

			// Display Icon.
			$preview->set_class( 'display_icon', '.merchant-reasons-list', array(), 'show-icon' );

			// Select an Icon.
			$preview->set_svg_icon( 'icon', '.merchant-reasons-list-item-icon' );
		}

		return $preview;
	}

	/**
	 * HTML wrapper class.
	 *
	 * @return array $classes The wrapper classes.
	 */
	public function html_wrapper_class( $classes ) {
		$settings = $this->get_module_settings();

		if ( ! empty( $settings['display_icon'] ) ) {
			$classes[] = 'show-icon';
		}

		return $classes;
	}

	/**
	 * Admin preview content.
	 *
	 * @return void
	 */
	public function admin_preview_content() {
		// Get all settings.
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
                <div class="mrc-preview-text-placeholder mrc-mw-40 mrc-hide-on-smaller-screens"></div>
                <div class="mrc-preview-module-content">
                    <div class="merchant-product-brand-image">
						<?php merchant_get_template_part( 'modules/reasons-to-buy', 'content', $settings ); ?>
                    </div>
                </div>
            </div>
        </div>

		<?php
	}

	/**
	 * Custom CSS.
	 *
	 * @return string
	 */
	public function get_module_custom_css() {
		$css = '';

		// List Items Spacing.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'list_items_spacing', 5, '.merchant-reasons-list', '--mrc-rtb-items-spacing', 'px' );

		// Title Color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'title_color', '#212121', '.merchant-reasons-list', '--mrc-rtb-title-color' );

		// List Items Color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'list_items_color', '#777', '.merchant-reasons-list', '--mrc-rtb-items-text-color' );

		// List Items Icon Color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'list_items_icon_color', '#212121', '.merchant-reasons-list', '--mrc-rtb-items-icon-color' );

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
	Merchant_Modules::create_module( new Merchant_Reasons_To_Buy() );
} );
