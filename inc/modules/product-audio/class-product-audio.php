<?php

/**
 * Product Audio
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Product audio class.
 * 
 */
class Merchant_Product_Audio extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'product-audio';

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

		// Module data.
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];

		// Module options path.
		$this->module_options_path = MERCHANT_DIR . 'inc/modules/product-audio/admin/options.php';

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
		if ( parent::is_module_settings_page()) {
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/product-audio.min.css', array(), MERCHANT_VERSION );
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
								<p><?php echo esc_html__( 'Once this module is enabled new options to upload audio files will appear under the admin product edit page. This means you have to edit and upload the audio file for each product in your store.', 'merchant' ); ?> <?php printf( '<a href="%s" target="_blank">%s</a>', esc_url( admin_url( 'edit.php?post_type=product' ) ), esc_html__( 'View All Products', 'merchant' ) ); ?></p>
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

		<div class="mrc-product-audio-preview">
			<img alt="product audio" src="<?php echo esc_url( MERCHANT_URI . 'inc/modules/product-audio/admin/images/preview-product-audio.png' ); ?>" />
		</div>

		<?php
	}
}

// Initialize the module.
add_action( 'init', function() {
	Merchant_Modules::create_module(new Merchant_Product_Audio());
} );
