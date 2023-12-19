<?php

/**
 * Product Video
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Product video class.
 *
 */
class Merchant_Product_Video extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'product-video';

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
		$this->module_data['preview_url'] = $preview_url;

		// Module options path.
		$this->module_options_path = MERCHANT_DIR . 'inc/modules/product-video/admin/options.php';

		// Is module preview page.
		if ( is_admin() && parent::is_module_settings_page() ) {
			self::$is_module_preview = true;

			// Enqueue admin styles.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_css' ) );

			// Render module essencial instructions before the module page body content.
			add_action( 'merchant_admin_after_module_page_page_header', array( $this, 'admin_module_essencial_instructions' ) );

			// Admin preview box.
			add_filter( 'merchant_module_preview', array( $this, 'render_admin_preview' ), 10, 2 );
		}
	}

	/**
	 * Admin enqueue CSS.
	 *
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( parent::is_module_settings_page() ) {
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/product-video.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
		}
	}

	/**
	 * Render module essencial instructions.
	 *
	 * @return void
	 */
	public function admin_module_essencial_instructions() { ?>
        <div class="merchant-module-page-settings">
            <div class="merchant-module-page-setting-box merchant-module-page-setting-box-style-2">
                <div class="merchant-module-page-setting-fields">
                    <div class="merchant-module-page-setting-field merchant-module-page-setting-field-content">
                        <div class="merchant-module-page-setting-field-inner">
                            <div class="merchant-tag-pre-orders">
                                <i class="dashicons dashicons-info"></i>
                                <p><?php echo esc_html__( 'Once this module is enabled new options to upload video files will appear under admin product edit page. This means you have to edit and upload the video file for each product in your store.',
										'merchant' ); ?><?php printf( '<a href="%s" target="_blank">%s</a>',
										esc_url( admin_url( 'edit.php?post_type=product' ) ),
										esc_html__( 'View All Products', 'merchant' ) ); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

		<?php
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
		}

		return $preview;
	}

	/**
	 * Admin preview content.
	 *
	 * @return void
	 */
	public function admin_preview_content() { ?>

        <div class="mrc-product-video-preview">
            <img src="<?php echo esc_url( MERCHANT_URI . 'inc/modules/' . self::MODULE_ID . '/admin/images/preview-product-video.png' ); ?>"/>
        </div>

		<?php
	}
}

// Initialize the module.
add_action( 'init', function () {
	Merchant_Modules::create_module( new Merchant_Product_Video() );
} );
