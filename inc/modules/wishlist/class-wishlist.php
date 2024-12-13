<?php

/**
 * Wishlist.
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Wishlist class.
 * 
 */
class Merchant_Wishlist extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'wishlist';

	/**
	 * Is module preview.
	 * 
	 */
	public static $is_module_preview = false;

	/**
	 * Module settings.
	 * 
	 */
	public static $module_settings = array();

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

		// Module default settings.
		$this->module_default_settings = array(
			'display_on_shop_archive'   => 1,
			'display_on_single_product' => 1,
			'display_on_cart_page'      => false,
			'posts_per_page'            => 6,
			'cart_page_title'           => esc_html__( 'Your wishlist items', 'merchant' ),
			'cart_page_title_tag'       => 'h2',
			'button_icon'               => 'heart1',
			'button_position_top'       => 8,
			'button_position_left'      => 85,
			'tooltip'                   => 1,
			'tooltip_text'              => esc_html__( 'Add To Wishlist', 'merchant' ),
			'tooltip_text_after'        => esc_html__( 'Added To Wishlist', 'merchant' ),
			'tooltip_border_radius'     => 4,
			'hide_page_title'           => 0,
			'enable_sharing'            => 1,
			'sharing_links'             => array(
				array(
					'layout'         => 'social',
					'social_network' => 'facebook',
				),
				array(
					'layout'         => 'social',
					'social_network' => 'twitter',
				),
				array(
					'layout'         => 'social',
					'social_network' => 'linkedin',
				),
			),
			'display_copy_to_clipboard' => 1,
		);

		// Mount preview url.
		$preview_url = site_url( '/' );

		if ( function_exists( 'wc_get_page_id' ) ) {
			$preview_url = get_permalink( wc_get_page_id( 'shop' ) );
		}

		// Module data.
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];
		$this->module_data[ 'preview_url' ] = $preview_url;

		// Module options path.
		$this->module_options_path = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID . '/admin/options.php';

		// Store module handled (with defaults) module settings into a static variable.
		self::$module_settings = $this->get_module_settings();

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
	}
	/**
	 * Admin enqueue CSS.
	 * 
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( parent::is_module_settings_page() ) {
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/wishlist-button.min.css', array(), MERCHANT_VERSION );
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
			$this->admin_preview_content();
			$content = ob_get_clean();

			// HTML.
			$preview->set_html( $content );

			// Select Icon.
			$preview->set_svg_icon( 'button_icon', '.merchant-wishlist-button' );

			// Dislplay Tooltip.
			$preview->set_class( 'tooltip', '.merchant-wishlist-button', array(), 'merchant-wishlist-button-tooltip' );     

			// Tooltip Text.
			$preview->set_attribute( 'tooltip_text', '.merchant-wishlist-button', 'data-merchant-wishlist-tooltip' );

		}

		return $preview;
	}

	/**
	 * Admin preview content.
	 * 
	 * @return void
	 */
	public function admin_preview_content() { 
		$settings = self::$module_settings;

		?>

		<div class="merchant-wishlist-product-preview">
			<div class="image-wrapper">
				<a href="#" class="merchant-wishlist-button<?php echo ( $settings[ 'tooltip' ] ) ? ' merchant-wishlist-button-tooltip' : ''; ?>" data-type="add" data-wishlist-link="#" data-merchant-wishlist-tooltip="<?php echo esc_attr( $settings[ 'tooltip_text' ] ); ?>">
					<?php echo wp_kses( Merchant_SVG_Icons::get_svg_icon( $settings[ 'button_icon' ] ), merchant_kses_allowed_tags( array(), false ) ); ?>
				</a>
			</div>
			<h3><?php echo esc_html__( 'Product Title', 'merchant' ); ?></h3>
			<p><?php echo esc_html__( 'Product description normally goes here.', 'merchant' ); ?></p>
		</div>

		<?php
	}

	/**
	 * Custom CSS.
	 * 
	 * @return string $css The custom CSS.
	 */
	public function get_module_custom_css() {
		$css = '';

		/**
		 * Add To Wishlist Button Settings
		 * 
		 */
		
		// Button position top.
		$css .= Merchant_Custom_CSS::get_variable_css( 'wishlist', 'button_position_top', 20, '.merchant-wishlist-button', '--mrc-wl-button-position-top', 'px' );

		// Button position left.
		$css .= Merchant_Custom_CSS::get_variable_css( 'wishlist', 'button_position_left', 20, '.merchant-wishlist-button', '--mrc-wl-button-position-left', 'px' );

		// Icon stroke color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'wishlist', 'icon_stroke_color', '#212121', '.merchant-wishlist-button', '--mrc-wl-button-icon-stroke-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( 'wishlist', 'icon_stroke_color_hover', '#212121', '.merchant-wishlist-button', '--mrc-wl-button-icon-stroke-color-hover' );
		
		// Icon fill color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'wishlist', 'icon_fill_color', 'transparent', '.merchant-wishlist-button', '--mrc-wl-button-icon-fill-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( 'wishlist', 'icon_fill_color_hover', '#f04c4c', '.merchant-wishlist-button', '--mrc-wl-button-icon-fill-color-hover' );

		// Tooltip text color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'wishlist', 'tooltip_text_color', '#FFF', '.merchant-wishlist-button', '--mrc-wl-button-tooltip-text-color' );

		// Tooltip background color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'wishlist', 'tooltip_background_color', '#212121', '.merchant-wishlist-button', '--mrc-wl-button-tooltip-background-color' );

		// Tooltip border radius.
		$css .= Merchant_Custom_CSS::get_variable_css( 'wishlist', 'tooltip_border_radius', 4, '.merchant-wishlist-button', '--mrc-wl-button-tooltip-border-radius', 'px' );

		/**
		 * Wishlist Page Template Settings
		 * 
		 */

		// Table heading background color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'wishlist', 'table_heading_background_color', '#f8f8f8', '.is-merchant-wishlist-page', '--mrc-wl-table-heading-background-color' );

		// Table body background color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'wishlist', 'table_body_background_color', '#fdfdfd', '.is-merchant-wishlist-page', '--mrc-wl-table-body-background-color' );

		// Table text color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'wishlist', 'table_text_color', '#777', '.is-merchant-wishlist-page', '--mrc-wl-table-text-color' );

		// Table links color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'wishlist', 'table_links_color', '#212121', '.is-merchant-wishlist-page', '--mrc-wl-table-links-color' );

		// Table links color (hover).
		$css .= Merchant_Custom_CSS::get_variable_css( 'wishlist', 'table_links_color_hover', '#757575', '.is-merchant-wishlist-page', '--mrc-wl-table-links-color-hover' );

		// Buttons color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'wishlist', 'buttons_color', '#FFF', '.is-merchant-wishlist-page', '--mrc-wl-buttons-color' );
		
		// Buttons color (hover).
		$css .= Merchant_Custom_CSS::get_variable_css( 'wishlist', 'buttons_color_hover', '#FFF', '.is-merchant-wishlist-page', '--mrc-wl-buttons-color-hover' );

		// Buttons background color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'wishlist', 'buttons_background_color', '#212121', '.is-merchant-wishlist-page', '--mrc-wl-buttons-bg-color' );
		
		// Buttons color (hover).
		$css .= Merchant_Custom_CSS::get_variable_css( 'wishlist', 'buttons_background_color_hover', '#757575', '.is-merchant-wishlist-page', '--mrc-wl-buttons-bg-color-hover' );

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
	Merchant_Modules::create_module(new Merchant_Wishlist());
} );