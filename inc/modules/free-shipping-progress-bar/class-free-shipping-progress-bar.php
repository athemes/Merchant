<?php

/**
 * Free Shipping Progress Bar.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Free Shipping Progress Bar.
 *
 */
class Merchant_Free_Shipping_Progress_Bar extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'free-shipping-progress-bar';

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
		parent::__construct();

		// Module section.
		$this->module_section = 'boost-revenue';

		// Module id.
		$this->module_id = self::MODULE_ID;

		// Module default settings.
		$this->module_default_settings = array();

		// Mount preview url.
		$preview_url = site_url( '/' );

		if ( function_exists( 'wc_get_page_id' ) ) {
			$preview_url = get_permalink( wc_get_page_id( 'shop' ) );
		}

		// Module data.
		$this->module_data                = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];
		$this->module_data['preview_url'] = $preview_url;

		// Module options path.
		$this->module_options_path = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID . '/admin/options.php';

		// Is module preview page.
		if ( is_admin() && parent::is_module_settings_page() ) {
			self::$is_module_preview = true;

			// Enqueue admin assets.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ) );

			// Admin preview box.
			add_filter( 'merchant_module_preview', array( $this, 'render_admin_preview' ), 10, 2 );
		}

		if ( ! Merchant_Modules::is_module_active( self::MODULE_ID ) ) {
			return;
		}
	}

	/**
	 * Admin enqueue Assets.
	 *
	 * @return void
	 */
	public function admin_enqueue_assets() {
		$page   = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$module = ( ! empty( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( 'merchant' === $page && self::MODULE_ID === $module ) {
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_script( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/admin/preview.min.js', array(), MERCHANT_VERSION,
				true );
			wp_localize_script(
				'merchant-admin-' . self::MODULE_ID,
				'merchantFreeShippingProgressBar',
				array(
					'amount' => wc_price( 15 ),
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
	public static function admin_preview_content() {
		?>
        <div class="merchant-freespb-wrapper">
            <p class="merchant-freespb-text">
				<?php
				echo wp_kses(
					sprintf(
					/* translators: %s: Amount. */
						__( 'You are %s away from free shipping.', 'merchant' ),
						wc_price( 15 )
					),
					merchant_kses_allowed_tags( array( 'bdi' ) )
				);
				?>
            </p>
            <div class="merchant-freespb-progress-bar-wrapper">
                <div class="merchant-freespb-progress-bar">
                    <div class="merchant-freespb-progress-bar-inner" style="width: 70%"></div>
                </div>
            </div>
        </div>
		<?php
	}
}

// Initialize the module.
add_action( 'init', static function () {
	Merchant_Modules::create_module( new Merchant_Free_Shipping_Progress_Bar() );
} );
