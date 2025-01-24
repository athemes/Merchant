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
	 * Module template path.
	 */
	const MODULE_TEMPLATES = 'modules/' . self::MODULE_ID;

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

		// Module data.
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];

		// Module options path.
		$this->module_options_path = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID . '/admin/options.php';

		// Is module preview page.
		if ( is_admin() && parent::is_module_settings_page() ) {
			self::$is_module_preview = true;

			// Enqueue admin assets.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ) );

			// Admin preview box.
			add_filter( 'merchant_module_preview', array( $this, 'render_admin_preview' ), 10, 2 );

			add_action( 'merchant_admin_before_include_modules_options', array( $this, 'help_banner' ) );
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
					'amount'  => wc_price( 15 ),
					'presets' => array(
						'custom'        => array(
							'card_background_color' => '#FFFFFF',
							'card_border_radius'    => 8,
							'card_border_width'     => 0,
							'card_border_color'     => '#c5c8d1',
							'card_padding_top'      => 16,
							'card_padding_bottom'   => 16,
							'bar_background_color'  => '#E4E5E7',
							'bar_foreground_color'  => '#000000',
							'bar_border_radius'     => 4,
							'card_text_color'       => '#202223',
							'variable_text_color'   => '#202223',
							'card_font_size'        => '18',
							'x_icon_color'          => '#000000',
							'bar_width'             => '90',
						),
						'solar_night'   => array(
							'card_background_color' => '#000000',
							'card_border_radius'    => 8,
							'card_border_width'     => 0,
							'card_border_color'     => '#c5c8d1',
							'card_padding_top'      => 16,
							'card_padding_bottom'   => 16,
							'bar_background_color'  => '#5c5c5c',
							'bar_foreground_color'  => '#faf16d',
							'bar_border_radius'     => 4,
							'card_text_color'       => '#ffffff',
							'variable_text_color'   => '#ffffff',
							'card_font_size'        => '18',
							'x_icon_color'          => '#ffffff',
							'bar_width'             => '90',
						),
						'lively_breeze' => array(
							'card_background_color' => '#ffffff',
							'card_border_radius'    => 8,
							'card_border_width'     => 2,
							'card_border_color'     => '#f15bb5',
							'card_padding_top'      => 16,
							'card_padding_bottom'   => 16,
							'bar_background_color'  => '#e9d6ff',
							'bar_foreground_color'  => '#f15bb5',
							'bar_border_radius'     => 4,
							'card_text_color'       => '#9b5de5',
							'variable_text_color'   => '#9b5de5',
							'card_font_size'        => '18',
							'x_icon_color'          => '#9B5DE5',
							'bar_width'             => '90',
						),
						'midnight_tide' => array(
							'card_background_color' => '#222831',
							'card_border_radius'    => 8,
							'card_border_width'     => 0,
							'card_border_color'     => '#000000',
							'card_padding_top'      => 16,
							'card_padding_bottom'   => 16,
							'bar_background_color'  => '#f8f8f8',
							'bar_foreground_color'  => '#13cad2',
							'bar_border_radius'     => 4,
							'card_text_color'       => '#c0deed',
							'variable_text_color'   => '#c0deed',
							'card_font_size'        => '18',
							'x_icon_color'          => '#c0deed',
							'bar_width'             => '90',
						),
						'clean_slate'   => array(
							'card_background_color' => '#FFFFFF',
							'card_border_radius'    => 8,
							'card_border_width'     => 1,
							'card_border_color'     => '#000000',
							'card_padding_top'      => 16,
							'card_padding_bottom'   => 16,
							'bar_background_color'  => '#c5d2de',
							'bar_foreground_color'  => '#202223',
							'bar_border_radius'     => 4,
							'card_text_color'       => '#202223',
							'variable_text_color'   => '#202223',
							'card_font_size'        => '18',
							'x_icon_color'          => '#202223',
							'bar_width'             => '90',
						),
						'fresh_frost'   => array(
							'card_background_color' => '#b0ead7',
							'card_border_radius'    => 8,
							'card_border_width'     => 0,
							'card_border_color'     => '#c5c8d1',
							'card_padding_top'      => 16,
							'card_padding_bottom'   => 16,
							'bar_background_color'  => '#f8ffff',
							'bar_foreground_color'  => '#009094',
							'bar_border_radius'     => 4,
							'card_text_color'       => '#006d71',
							'variable_text_color'   => '#006d71',
							'card_font_size'        => '18',
							'x_icon_color'          => '#006d71',
							'bar_width'             => '90',
						),

                        // added by ai
						'sky_harmony'    => array(
							'card_background_color' => '#e3f2fd',
							'card_text_color'       => '#0277bd',
							'variable_text_color'   => '#0277bd',
							'card_font_size'        => '18',
							'card_padding_top'      => '16',
							'card_padding_bottom'   => '16',
							'card_padding_left'     => '50',
							'card_padding_right'    => '50',
							'card_border_radius'    => '8',
							'card_border_width'     => '2',
							'card_border_color'     => '#0288d1',
							'bar_background_color'  => '#b3e5fc',
							'bar_foreground_color'  => '#0288d1',
							'bar_border_radius'     => '4',
							'bar_height'            => '17',
							'bar_width'             => '90',
						),
						'ocean_breeze'        => array(
							'card_background_color' => '#e8eaf6',
							'card_text_color'       => '#303f9f',
							'variable_text_color'   => '#303f9f',
							'card_font_size'        => '16',
							'card_padding_top'      => '16',
							'card_padding_bottom'   => '18',
							'card_padding_left'     => '50',
							'card_padding_right'    => '50',
							'card_border_radius'    => '8',
							'card_border_width'     => '2',
							'card_border_color'     => '#3f51b5',
							'bar_background_color'  => '#c5cae9',
							'bar_foreground_color'  => '#3f51b5',
							'bar_border_radius'     => '4',
							'bar_height'            => '17',
							'bar_width'             => '90',
						),
					),
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
	 * Help banner.
	 *
	 * @return void
	 */
	public function help_banner( $module_id ) {
		if ( $module_id === self::MODULE_ID ) {
			?>
            <div class="merchant-module-page-setting-fields">
                <div class="merchant-module-page-setting-field merchant-module-page-setting-field-content">
                    <div class="merchant-module-page-setting-field-inner">
                        <div class="merchant-tag-pre-orders">
                            <i class="dashicons dashicons-info"></i>
                            <p>
								<?php
								echo esc_html__(
									'You need to setup free shipping rules with a minimum order value, to do that go to WooCommerce > Settings > Shipping menu in the left sidebar of your WordPress admin area and configure your free shipping method.',
									'merchant'
								);
								printf(
									'<a href="%1s" target="_blank">%2s</a>',
									esc_url( admin_url( 'admin.php?page=wc-settings&tab=shipping' ) ),
									esc_html__( 'Add Shipping', 'merchant' )
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
