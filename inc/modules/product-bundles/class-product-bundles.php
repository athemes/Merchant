<?php

/**
 * Add To Cart Text
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Add To Cart Text Class.
 *
 */
class Merchant_Product_Bundles extends Merchant_Add_Module {

	/**
	 * Module ID.
	 */
	const MODULE_ID = 'product-bundles';

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
	 *
	 */
	public function __construct() {
		// Module id.
		$this->module_id = self::MODULE_ID;

		// WooCommerce only.
		$this->wc_only = true;

		// Parent construct.
		parent::__construct();

		// Module data.
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];

		// Module section.
		$this->module_section = $this->module_data['section'];

		// Module options path.
		$this->module_options_path = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID . '/admin/options.php';

		// Is module preview page.
		if ( is_admin() && parent::is_module_settings_page() ) {
			self::$is_module_preview = true;

			// Enqueue admin styles.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_css' ) );

			// Admin preview box.
			add_filter( 'merchant_module_preview', array( $this, 'render_admin_preview' ), 10, 2 );
		}

		add_action( 'merchant_admin_before_include_modules_options', array( $this, 'help_banner' ) );
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
     * Help banner.
     *
     * @return void
     */
	public function help_banner( $module_id ) {
		if ( $module_id === 'product-bundles' ) {
			?>
            <div class="merchant-module-page-setting-fields">
                <div class="merchant-module-page-setting-field merchant-module-page-setting-field-content">
                    <div class="merchant-module-page-setting-field-inner">
                        <div class="merchant-tag-pre-orders">
                            <i class="dashicons dashicons-info"></i>
                            <p>
								<?php
								echo esc_html__(
									'To create a new product bundle, go to Products > Add New menu in the left sidebar of your WordPress admin area. Then, from the Product data dropdown, select Product Bundle.',
									'merchant'
								);
								printf(
									'<a href="%1s" target="_blank">%2s</a>',
									esc_url( admin_url( 'post-new.php?post_type=product' ) ),
									esc_html__( 'Add New Bundle', 'merchant' )
								);
								?></p>
                        </div>
                    </div>
                </div>
            </div>
			<?php
		}
	}

	/**
	 * Admin enqueue CSS.
	 *
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( $this->is_module_settings_page() ) {
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_script(
				'merchant-admin-' . self::MODULE_ID,
				MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/admin/preview.min.js',
				array( 'jquery' ),
				MERCHANT_VERSION,
				true
			);
			wp_localize_script(
				'merchant-admin-' . self::MODULE_ID,
				'merchant_admin_address_autocomplete',
				array(
					'field_placeholder' => esc_attr__( 'Start typing an address...', 'merchant' ),
				)
			);
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
			// HTML.
			$preview->set_html( array( $this, 'admin_preview_content' ), $this->get_module_settings() );
		}

		return $preview;
	}

	/**
	 * Admin preview content.
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
                <div class="mrc-preview-text-placeholder mrc-mw-30"></div>
                <div class="mrc-preview-text-placeholder mrc-mw-40"></div>
                <div class="mrc-preview-bundle-wrapper mrc-mw-60">
                    <div class="mrc-preview-bundle-product">
                        <div class="mrc-preview-bundle-product-image"></div>
                        <div class="mrc-preview-bundle-product-info">
                            <div class="mrc-preview-bundle-product-title"></div>
                            <div class="mrc-preview-bundle-product-description"></div>
                            <div class="mrc-preview-bundle-product-price"></div>
                        </div>
                    </div>
                    <div class="mrc-preview-bundle-product">
                        <div class="mrc-preview-bundle-product-image"></div>
                        <div class="mrc-preview-bundle-product-info">
                            <div class="mrc-preview-bundle-product-title"></div>
                            <div class="mrc-preview-bundle-product-description"></div>
                            <div class="mrc-preview-bundle-product-price"></div>
                        </div>
                    </div>
                    <div class="mrc-preview-bundle-product">
                        <div class="mrc-preview-bundle-product-image"></div>
                        <div class="mrc-preview-bundle-product-info">
                            <div class="mrc-preview-bundle-product-title"></div>
                            <div class="mrc-preview-bundle-product-description"></div>
                            <div class="mrc-preview-bundle-product-price"></div>
                        </div>
                    </div>
                </div>
                <div class="mrc-preview-addtocart-placeholder"></div>
            </div>
        </div>
		<?php
	}
}

// Initialize the module.
add_action( 'init', function () {
	Merchant_Modules::create_module( new Merchant_Product_Bundles() );
} );
