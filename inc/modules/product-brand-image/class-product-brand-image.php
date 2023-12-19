<?php

/**
 * Product Brand Image
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Product brand image class.
 * 
 */
class Merchant_Product_Brand_Image extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'product-brand-image';

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
			'global-brand-image' => '',
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
		$this->module_options_path = MERCHANT_DIR . 'inc/modules/product-brand-image/admin/options.php';

		// Is module preview page.
		if ( is_admin() && parent::is_module_settings_page() ) {
			self::$is_module_preview = true;

			// Enqueue admin styles.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_css' ) );

			// Render module essencial instructions before the module page body content.
			add_action( 'merchant_admin_after_module_page_page_header', array( $this, 'admin_module_essencial_instructions' ) );

			// Admin preview box.
			add_filter( 'merchant_module_preview', array( $this, 'render_admin_preview' ), 10, 2 );

			// Custom CSS.
			// The custom CSS should be added here as well due to ensure preview box works properly.
			add_filter( 'merchant_custom_css', array( $this, 'admin_custom_css' ) );

		}
	}

	/**
	 * Admin enqueue CSS.
	 * 
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( parent::is_module_settings_page() ) {
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/product-brand-image.min.css', array(), MERCHANT_VERSION );
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
								<p><?php echo esc_html__( 'The product brand image can be either enabled globally to be displayed in all products or in specific products. If you want to display a different brand image for each product, that’s possible from the admin product edit page.', 'merchant' ); ?> <?php printf( '<a href="%s" target="_blank">%s</a>', esc_url( admin_url( 'edit.php?post_type=product' ) ), esc_html__( 'View All Products', 'merchant' ) ); ?></p>
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
	public function admin_preview_content() {
		$settings = $this->get_module_settings();
		$is_placeholder = empty( $settings[ 'global-brand-image' ] ) ? true : false;

		$image = $is_placeholder 
			? '<img src="' . esc_url( MERCHANT_URI . 'inc/modules/product-brand-image/admin/images/brand-images.png' ) . '" />'
			: wp_get_attachment_image( $settings[ 'global-brand-image' ], 'full' );

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
				<div class="mrc-preview-module-content">
					<div class="merchant-product-brand-image">
						<?php echo wp_kses_post( $image ); ?>
					</div>
				</div>
				<div class="mrc-preview-addtocart-placeholder"></div>
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

		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'margin-top', 15, '.merchant-product-brand-image', '--mrc-pbi-margin-top', 'px' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'margin-bottom', 15, '.merchant-product-brand-image', '--mrc-pbi-margin-bottom', 'px' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'image-max-width', 250, '.merchant-product-brand-image', '--mrc-pbi-image-max-width', 'px' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'image-max-height', 250, '.merchant-product-brand-image', '--mrc-pbi-image-max-height', 'px' );

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
}

// Initialize the module.
add_action( 'init', function() {
	Merchant_Modules::create_module(new Merchant_Product_Brand_Image());
} );
