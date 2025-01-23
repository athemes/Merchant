<?php

/**
 * Storewide Sale
 *
 * Module's entry class.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Storewide Sale Class.
 */
class Merchant_Storewide_Sale extends Merchant_Add_Module {

	/**
	 * Module ID..
	 */
	const MODULE_ID = 'storewide-sale';

	/**
	 * Module template path.
	 */
	const MODULE_TEMPLATES_PATH = 'modules/' . self::MODULE_ID;

	/**
	 * Is module preview.
	 *
	 */
	public static $is_module_preview = false;

	/**
	 * Set the module as having analytics.
	 *
	 * @var bool
	 */
	protected $has_analytics = true;

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Module id.
		$this->module_id = self::MODULE_ID;

		// WooCommerce only.
		$this->wc_only = true;

		// Module default settings.
		$this->module_default_settings = array(
			'no_variation_selected_text_has_no_discount' => __( 'Please select an option to see the total price.', 'merchant' ),
			'no_variation_selected_text'                 => __( 'Please select an option to see your savings.', 'merchant' ),
		);

		// Parent construct.
		parent::__construct();

		// Module section.
		$this->module_section = 'boost-revenue';

		// Module data.
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];

		// Module options path.
		$this->module_options_path = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID . '/admin/options.php';

		if ( Merchant_Modules::is_module_active( self::MODULE_ID ) && is_admin() ) {
			// Init translations.
			$this->init_translations();
		}

		// Is module preview page.
		if ( is_admin() && parent::is_module_settings_page() ) {
			self::$is_module_preview = true;

			// Enqueue admin styles.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_css' ) );

			// Admin preview box.
			add_filter( 'merchant_module_preview', array( $this, 'render_admin_preview' ), 10, 2 );
		}
	}

	/**
	 * Get all analytics metrics and allow modules to filter them.
	 *
	 * @return array List of available metrics.
	 */
	public function analytics_metrics() {
		$metrics                = $this->default_analytics_metrics();
		$metrics['impressions'] = false;

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
	 * Init translations.
	 *
	 * @return void
	 */
	public function init_translations() {
		//todo to be implemented.
	}

	/**
	 * Admin enqueue CSS.
	 *
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( parent::is_module_settings_page() ) {
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
		}
	}

	/**
	 * Render admin preview
	 *
	 * @param Merchant_Admin_Preview $preview
	 * @param string                 $module
	 *
	 * @return Merchant_Admin_Preview
	 */
	public function render_admin_preview( $preview, $module ) {
		if ( $module === self::MODULE_ID ) {
			$preview->set_html( array( $this, 'admin_preview_content' ), $this->get_module_settings() );
		}

		return $preview;
	}

	/**
	 * Admin preview content.
	 *
	 * @param array $settings
	 *
	 * @return void
	 */
	public function admin_preview_content( $settings ) {
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
                <p class="price">
                    <del aria-hidden="true">
                        <span class="woocommerce-Price-amount amount">
                            <bdi>
                                <span class="woocommerce-Price-currencySymbol"><?php
                                    echo esc_html( get_woocommerce_currency_symbol() )
                                    ?></span>240.00
                            </bdi>
                        </span>
                    </del>
                    <ins>
                        <span class="woocommerce-Price-amount amount">
                            <bdi>
                                <span class="woocommerce-Price-currencySymbol"><?php
				                    echo esc_html( get_woocommerce_currency_symbol() )
				                    ?></span>200.00
                            </bdi>
                        </span>
                    </ins>
                </p>
                <div class="mrc-preview-text-placeholder smaller mrc-mw-70"></div>
                <div class="mrc-preview-text-placeholder smaller mrc-mw-40"></div>
                <br>
                <br>
                <div class="mrc-preview-addtocart-placeholder">
                    <input type="number" disabled readonly value="1">
                    <button disabled type="button" class="single_add_to_cart_button"></button>
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
		// For backward compatibility, no implementation is needed.

		return '';
	}
}

// Initialize the module.
add_action( 'init', function () {
	Merchant_Modules::create_module( new Merchant_Storewide_Sale() );
} );
