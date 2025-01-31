<?php

/**
 * Checkout
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Checkout class.
 * 
 */
class Merchant_Checkout extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'checkout';

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
		$this->module_section = 'convert-more';

		// Module default settings.
		$this->module_default_settings = array(
			'layout' => 'layout-shopify',
			'sticky_totals_box' => 0,
		);

		// Module data.
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];

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
			$this->checkout_admin_preview_output();
			$content = ob_get_clean();

			// HTML.
			$preview->set_html( $content );

			// Toggle layout class identifier.
			$preview->set_class( 'layout', '.mrc-checkout-preview', array( 'layout-shopify', 'layout-one-step', 'layout-multi-step' ) );

		}

		return $preview;
	}

	public function checkout_admin_preview_output() {
		$settings = $this->get_module_settings();

		?>

		<div class="mrc-checkout-preview <?php echo esc_attr( $settings[ 'layout' ] ); ?>">
			<div class="mrc-checkout-preview-one-step">
				<img src="<?php echo esc_url( MERCHANT_URI . 'inc/modules/checkout/admin/images/preview-layout-one-step.png' ); ?>" />
			</div>
			<div class="mrc-checkout-preview-shopify">
				<img src="<?php echo esc_url( MERCHANT_URI . 'inc/modules/checkout/admin/images/preview-layout-shopify.png' ); ?>" />
			</div>
			<div class="mrc-checkout-preview-multi-step">
				<img src="<?php echo esc_url( MERCHANT_URI . 'inc/modules/checkout/admin/images/preview-layout-multi-step.png' ); ?>" />
			</div>
		</div>
		
		<?php
	}
}

// Initialize the module.
add_action( 'init', function () {
	Merchant_Modules::create_module( new Merchant_Checkout() );
} );
