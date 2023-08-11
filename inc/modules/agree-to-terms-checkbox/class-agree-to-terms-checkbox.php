<?php

/**
 * Agree To Terms Checkbox.
 * 
 * @package Merchant_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Agree To Terms Checkbox Class.
 * 
 */
class Merchant_Agree_To_Terms_Checkbox extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'agree-to-terms-checkbox';

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
		$this->module_section = 'protect-your-store';

		// Module id.
		$this->module_id = self::MODULE_ID;

		// Module default settings.
		$this->module_default_settings = array(
			'label' => __( 'I agree with the', 'merchant' ),
			'text' => __( 'Terms & Conditions', 'merchant' ),
			'link' => get_privacy_policy_url(),
			'warning_text' => __( 'You must read and accept the terms and conditions to complete checkout.', 'merchant' )
		);

		// Mount preview url.
		$preview_url = site_url( '/' );

		if ( function_exists( 'wc_get_page_id' ) ) {
			$checkout_url = get_permalink( wc_get_page_id( 'checkout' ) );
		}

		// Module data.
		$this->module_data = array(
			'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><path d="M.623 1.253.014 4.836C-.09 5.446.39 6 1.021 6h.91c.58 0 1.11-.321 1.37-.83L3.897 4l.597 1.17c.26.509.79.83 1.37.83h1.169c.58 0 1.11-.321 1.369-.83L9 4l.597 1.17c.26.509.79.83 1.37.83h1.169c.58 0 1.11-.321 1.369-.83L14.102 4l.598 1.17c.259.509.789.83 1.369.83h.91c.63 0 1.11-.555 1.007-1.164l-.61-3.583A1.522 1.522 0 0 0 15.867 0H2.134C1.385 0 .746.53.623 1.253ZM12.707 8.293a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 1 1 1.414-1.414L8 11.586l3.293-3.293a1 1 0 0 1 1.414 0Z"/><path d="M3 8H1v8.5A1.5 1.5 0 0 0 2.5 18h13a1.5 1.5 0 0 0 1.5-1.5V8h-2v8H3V8Z"/></svg>',
			'title' => esc_html__( 'Agree to Terms Checkbox', 'merchant' ),
			'desc' => esc_html__( 'Get customers to agree to your Terms & Conditions as part of the checkout process.', 'merchant' ),
			'placeholder' => MERCHANT_URI . 'assets/images/modules/agree-to-terms-checkbox.png',
			'tutorial_url' => 'https://docs.athemes.com/article/agree-to-terms-checkbox/',
			'preview_url' => $preview_url
		);

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
			// The custom CSS should be added here as well due to ensure preview box works properly.
			add_filter( 'merchant_custom_css', array( $this, 'admin_custom_css' ) );

		}

		if ( ! Merchant_Modules::is_module_active( self::MODULE_ID ) ) {
			return;
		}

		// Return early if it's on admin but not in the respective module settings page.
		if ( is_admin() && ! parent::is_module_settings_page() ) {
			return;	
		}

		// Enqueue styles.
		add_action( 'merchant_enqueue_before_main_css_js', array( $this, 'enqueue_css' ) );

		// Inject the module content into the checkout terms and conditions hook.
		add_action( 'woocommerce_checkout_terms_and_conditions', array( $this, 'agree_to_terms_form_field' ), 99 );

		// Hook into the checkout validation and include agree to terms rules.
		add_action( 'woocommerce_after_checkout_validation', array( $this, 'agree_to_terms_field_validation' ), 10, 2 );

		// Custom CSS.
		add_filter( 'merchant_custom_css', array( $this, 'frontend_custom_css' ) );

	}

	/**
	 * Admin enqueue CSS.
	 * 
	 * @return void
	 */
	public function admin_enqueue_css() {
		$page   = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
		$module = ( ! empty( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : '';

		if ( 'merchant' === $page && self::MODULE_ID === $module ) {
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/agree-to-terms-checkbox.min.css', [], MERCHANT_VERSION );
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
		wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/agree-to-terms-checkbox.min.css', array(), MERCHANT_VERSION );
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
			$this->agree_to_terms_form_field();
			$content = ob_get_clean();

			// HTML.
			$preview->set_html( $content );

			// Label.
			$preview->set_text( 'label', '.mrc-attc-label-text' );

			// Terms & Conditions Text.
			$preview->set_text( 'text', '.mrc-attc-terms-text' );

		}

		return $preview;
	}

	/**
	 * Agree to terms checkout form field.
	 * 
	 * @return void
	 */
	public function agree_to_terms_form_field() {
		$settings = $this->get_module_settings();

		echo '<div class="merchant-agree-to-terms-checkbox">';
			woocommerce_form_field( 'merchant_agree_to_terms', array(
				'type'     => 'checkbox',
				'label'    => sprintf( '<span class="mrc-attc-label-text">%s</span> <a href="%s" class="mrc-attc-terms-text" target="_blank">%s</a>', esc_html( $settings[ 'label' ] ), esc_url( $settings[ 'link' ] ), esc_html( $settings[ 'text' ] ) ),
				'required' => true,
			) );
		echo '</div>';
	}
	
	/**
	 * Validation.
	 * 
	 * @param array $fields The fields.
	 * @param object $errors The errors.
	 * @return void
	 */
	public function agree_to_terms_field_validation( $fields, $errors ) {
		if ( empty( $_REQUEST['merchant_agree_to_terms'] ) ) {
			$settings = $this->get_module_settings();
	
			$errors->add( 'validation', $settings[ 'warning_text' ] );
		}
	}

	/**
	 * Custom CSS.
	 * 
	 * @return string
	 */
	public function get_module_custom_css() {
		$css = '';

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

// Initialize the module.
new Merchant_Agree_To_Terms_Checkbox();
