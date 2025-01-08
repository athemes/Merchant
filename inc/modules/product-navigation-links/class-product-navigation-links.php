<?php

/**
 * Product Navigation Links
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Product Navigation Links Class.
 *
 */
class Merchant_Product_Navigation_Links extends Merchant_Add_Module {

	/**
	 * Module ID.
	 */
	const MODULE_ID = 'product-navigation-links';

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

		// Module default settings.
		$this->module_default_settings = array(
				'placement'               => 'bottom',
				'text'                    => 'titles',
				'text_color'              => '#212121',
				'product_navigation_mode' => 'default',
		);

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

			// Custom CSS.
			add_filter( 'merchant_custom_css', array( $this, 'admin_custom_css' ) );
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
		if ( $module === self::MODULE_ID ) {
			// HTML.
			$preview->set_html( array( $this, 'admin_preview_content' ), $this->get_module_settings() );

			// Sets the class whether to show product titles or navigational cues.
			$preview->set_class( 'text', '.merchant-product-navigation', array( 'titles', 'navigational' ) );

			// Sets the class dat determines the placement of the buttons.
			$preview->set_class( 'placement', '.merchant-preview-dynamic-layout', array( 'bottom', 'top', 'after-cart-form', 'bottom-product-summary' ) );
		}

		return $preview;
	}

	/**
	 * Admin preview content.
	 *
	 * @param array $settings The module settings
	 *
	 * @return void
	 */
	public function admin_preview_content( $settings ) {
		?>
		<div class="merchant-preview-dynamic-layout <?php echo esc_attr( $settings['placement'] ) ?>">

			<?php self::get_preview_navigation_buttons( $settings, 'top' ) ?>

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

					<?php self::get_preview_navigation_buttons( $settings, 'after-product-summary' ) ?>

				</div>
			</div>

			<?php self::get_preview_navigation_buttons( $settings, 'bottom' ) ?>

		</div>
		<?php
	}

	/**
	 * The navigational buttons, this is being rendered multiple times, so we can preview it in different locations.
	 *
	 * @param array $settings The module settings
	 * @param string $position_class The position class.
	 *
	 * @return void
	 */
	public static function get_preview_navigation_buttons( $settings, $position_class ) {
		?>
		<div class="merchant-product-navigation <?php echo esc_attr( $position_class ) ?> <?php echo esc_attr( $settings['text'] ) ?>">
			<a href="#" class="merchant-previous-product">
				<span><?php echo esc_html__( '← Rare Earth Deep Pore Minimizing Cleansing', 'merchant' ) ?></span>
				<span><?php echo esc_html__( '← Previous', 'merchant' ) ?></span>
			</a>
			<a href="#" class="merchant-next-product">
				<span><?php echo esc_html__( 'Mini Radiant Creamy Concealer and Blush →', 'merchant' ) ?></span>
				<span><?php echo esc_html__( 'Next →', 'merchant' ) ?></span>
			</a>
		</div>
		<?php
	}


	/**
	 * Custom CSS.
	 *
	 * @return string
	 */
	public static function get_module_custom_css() {
		// Navigation buttons base CSS.
		$css = '
		    .merchant-product-navigation { display: flex; gap: 15px;}
		    .merchant-product-navigation a { max-width: 50%; }
		    .merchant-product-navigation a.merchant-next-product:only-child { min-width: 100%; }
		    .merchant-product-navigation a.merchant-next-product { text-align: right; }
		';

		// Text hover color.
		$css .= Merchant_Custom_CSS::get_color_css( self::MODULE_ID, 'text_hover_color', '#757575', '.merchant-product-navigation a:hover', false, true );

		// Text color.
		$css .= Merchant_Custom_CSS::get_color_css( self::MODULE_ID, 'text_color', '#212121', '.merchant-product-navigation a', false, true );

		// Text decoration
		$css .= Merchant_Custom_CSS::get_css( self::MODULE_ID, 'text_decoration', 'none', '.merchant-product-navigation a', 'text-decoration', '', false, true );

		// Margin top.
		$css .= Merchant_Custom_CSS::get_css( self::MODULE_ID, 'margin_top', 20, '.merchant-product-navigation', 'margin-top', 'px', false, true );

		// Margin bottom.
		$css .= Merchant_Custom_CSS::get_css( self::MODULE_ID, 'margin_bottom', 20, '.merchant-product-navigation', 'margin-bottom', 'px', false, true );

		// Justify content.
		$css .= Merchant_Custom_CSS::get_css( self::MODULE_ID, 'justify_content', 'space-between', '.merchant-product-navigation', 'justify-content', '', false, true );


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
		$css .= static::get_module_custom_css();

		return $css;
	}
}

// Initialize the module.
add_action( 'init', function () {
	Merchant_Modules::create_module( new Merchant_Product_Navigation_Links() );
} );
