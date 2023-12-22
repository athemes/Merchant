<?php

/**
 * Pre Orders.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Pre Orders Class.
 *
 */
class Merchant_Pre_Orders extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'pre-orders';

	/**
	 * Is module preview.
	 *
	 */
	public static $is_module_preview = false;

	/**
	 * Main functionality dependency.
	 *
	 */
	public $main_func;

	/**
	 * Constructor.
	 *
	 */
	public function __construct( Merchant_Pre_Orders_Main_Functionality $main_func ) {

		// Module id.
		$this->module_id = self::MODULE_ID;

		// WooCommerce only.
		$this->wc_only = true;

		// Parent construct.
		parent::__construct();

		$this->main_func = $main_func;

		// Module section.
		$this->module_section = 'boost-revenue';

		// Module default settings.
		$this->module_default_settings = array(
			'button_text' => __( 'Pre Order Now!', 'merchant' ),
			'additional_text' => __( 'Ships on {date}.', 'merchant' ),
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

		if ( Merchant_Modules::is_module_active( self::MODULE_ID ) && is_admin() ) {
			// Init translations.
			$this->init_translations();
		}

		if ( ! Merchant_Modules::is_module_active( self::MODULE_ID ) ) {
			return;
		}

		// TODO: Refactor the 'Merchant_Pre_Orders_Main_Functionality' class to load admin things separated from frontend things.
		$main_func->init();

		// Return early if it's on admin but not in the respective module settings page.
		if ( is_admin() && ! parent::is_module_settings_page() ) {
			return;
		}

		// Enqueue styles.
		add_action( 'merchant_enqueue_before_main_css_js', array( $this, 'enqueue_css' ) );

		// Enqueue scripts.
		add_action( 'merchant_enqueue_after_main_css_js', array( $this, 'enqueue_scripts' ) );

		// Localize script.
		add_filter( 'merchant_localize_script', array( $this, 'localize_script' ) );

		// Custom CSS.
		add_filter( 'merchant_custom_css', array( $this, 'frontend_custom_css' ) );
	}

	/**
	 * Init translations.
	 *
	 * @return void
	 */
	public function init_translations() {
		$settings = $this->get_module_settings();
		if ( ! empty( $settings['button_text'] ) ) {
			Merchant_Translator::register_string( $settings['button_text'], esc_html__( 'Pre orders button text', 'merchant' ) );
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
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/pre-orders.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
		}
	}

	/**
	 * Enqueue CSS.
	 *
	 * @return void
	 */
	public function enqueue_css() {

		// Specific module styles.
		wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/pre-orders.min.css', array(), MERCHANT_VERSION );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {

		// Register and enqueue the main module script.
		wp_enqueue_script( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/pre-orders.min.js', array(), MERCHANT_VERSION, true );
	}

	/**
	 * Localize script with module settings.
	 *
	 * @param array $setting The merchant global object setting parameter.
	 * @return array $setting The merchant global object setting parameter.
	 */
	public function localize_script( $setting ) {
		$module_settings = $this->get_module_settings();

		$setting[ 'pre_orders' ]                  = true;
		$setting[ 'pre_orders_add_button_title' ] = Merchant_Translator::translate( $module_settings[ 'button_text' ] );

		return $setting;
	}

	/**
	 * Render module essencial instructions.
	 *
	 * @return void
	 */
	public function admin_module_essencial_instructions() { ?>
		<div class="merchant-module-page-settings">
			<div class="merchant-module-page-setting-box merchant-module-page-setting-box-style-2">
				<div class="merchant-module-page-setting-title"><?php echo esc_html__( 'Tag Pre-Orders', 'merchant' ); ?></div>
				<div class="merchant-module-page-setting-fields">
					<div class="merchant-module-page-setting-field merchant-module-page-setting-field-content">
						<div class="merchant-module-page-setting-field-inner">
							<div class="merchant-tag-pre-orders">
								<i class="dashicons dashicons-info"></i>
								<p><?php echo esc_html__( 'Pre-orders captured by Merchant are tagged with "MerchantPreOrder" and can be found in your WooCommerce Order Section.', 'merchant' ); ?> <?php printf( '<a href="%s" target="_blank">%s</a>', esc_url( admin_url( 'edit.php?post_type=shop_order' ) ), esc_html__( 'View Pre-Orders', 'merchant' ) ); ?></p>
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
			$settings = $this->get_module_settings();

			// Additional text.
			$additional_text = $settings[ 'additional_text' ];
			$time_format     = date_i18n( get_option( 'date_format' ), strtotime( gmdate( 'Y-m-d', strtotime('+2 days') ) ) );
			$text            = $this->main_func->replaceDateTxt( $additional_text, $time_format );

			ob_start();
			self::admin_preview_content( $settings, $text );
			$content = ob_get_clean();

			// HTML.
			$preview->set_html( $content );

			// Button Text.
			$preview->set_text( 'button_text', '.add_to_cart_button' );

			// Additional Text.
			$preview->set_text( 'additional_text', '.merchant-pre-orders-date', array(
				array(
					'{date}',
				),
				array(
					$time_format,
				),
			) );

		}

		return $preview;
	}

	/**
	 * Admin preview content.
	 *
	 * @return void
	 */
	public function admin_preview_content( $settings, $text ) {
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
				<div class="mrc-preview-text-placeholder mrc-mw-40 mrc-hide-on-smaller-screens"></div>
				<div class="mrc-preview-text-placeholder mrc-mw-30 mrc-hide-on-smaller-screens"></div>
				<div class="merchant-pre-ordered-product">
					<div class="merchant-pre-orders-date"><?php printf( '<div class="merchant-pre-orders-date">%s</div>', esc_html( $text ) ); ?></div>
					<a href="#" class="add_to_cart_button"><?php echo esc_html( $settings[ 'button_text' ] ); ?></a>
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
		$css = '';

		// Text Color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'pre-orders', 'text-color', '#FFF', '.merchant-pre-ordered-product', '--mrc-po-text-color' );

		// Text Color (hover).
		$css .= Merchant_Custom_CSS::get_variable_css( 'pre-orders', 'text-hover-color', '#FFF', '.merchant-pre-ordered-product', '--mrc-po-text-hover-color' );

		// Border Color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'pre-orders', 'border-color', '#212121', '.merchant-pre-ordered-product', '--mrc-po-border-color' );

		// Border Color (hover).
		$css .= Merchant_Custom_CSS::get_variable_css( 'pre-orders', 'border-hover-color', '#414141', '.merchant-pre-ordered-product', '--mrc-po-border-hover-color' );

		// Background Color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'pre-orders', 'background-color', '#212121', '.merchant-pre-ordered-product', '--mrc-po-background-color' );

		// Background Color (hover).
		$css .= Merchant_Custom_CSS::get_variable_css( 'pre-orders', 'background-hover-color', '#414141', '.merchant-pre-ordered-product', '--mrc-po-background-hover-color' );

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

	/**
	 * Frontend custom CSS.
	 *
	 * @param string $css The custom CSS.
	 * @return string $css The custom CSS.
	 */
	public function frontend_custom_css( $css ) {
		$css .= $this->get_module_custom_css();

		return $css;
	}
}

// Main functionality.
require MERCHANT_DIR . 'inc/modules/pre-orders/class-pre-orders-main-functionality.php';

// Initialize the module.
add_action( 'init', function() {
	new Merchant_Pre_Orders( new Merchant_Pre_Orders_Main_Functionality() );
} );
