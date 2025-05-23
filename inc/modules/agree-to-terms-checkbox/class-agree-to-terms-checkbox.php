<?php

/**
 * Agree To Terms Checkbox.
 *
 * @package Merchant
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
		// Module id.
		$this->module_id = self::MODULE_ID;

		// WooCommerce only.
		$this->wc_only = true;

		// Parent construct.
		parent::__construct();

		if ( ! is_admin() && ! Merchant_Modules::is_module_active( self::MODULE_ID ) ) {
			return;
		}

		// Module section.
		$this->module_section = 'protect-your-store';

		// Module default settings.
		$this->module_default_settings = array(
			'label'        => __( 'I agree with the', 'merchant' ),
			'text'         => __( 'Terms & Conditions', 'merchant' ),
			'link'         => get_privacy_policy_url(),
			'warning_text' => __( 'Obtain consent before customers start the checkout process', 'merchant' ),
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

			// Custom CSS.
			// The custom CSS should be added here as well due to ensure preview box works properly.
			add_filter( 'merchant_custom_css', array( $this, 'admin_custom_css' ) );
		}

		if ( Merchant_Modules::is_module_active( self::MODULE_ID ) && is_admin() ) {
			// Init translations.
			$this->init_translations();
		}

		// Return early if it's on admin but not in the respective module settings page.
		if ( is_admin() && ! parent::is_module_settings_page() ) {
			return;
		}

		// Show the agree to terms when the module is active.
		// This is needed to ensure the checkbox will be displayed.
		add_filter( 'woocommerce_checkout_show_terms', '__return_true' );

		// Force the enable of the terms checkbox.
		// This is needed to ensure the checkbox will be displayed.
		// The logic here is to get the checkout page ID if the terms page ID is not set. In this condition, the ID actually doesn't matter, we just need to force the checkbox to be displayed.
		add_filter( 'woocommerce_terms_and_conditions_page_id', function($id){
			$terms_page_id = get_option( 'woocommerce_terms_page_id' );

			return $terms_page_id ? $terms_page_id : get_option( 'woocommerce_checkout_page_id' );
		} );

		// Control the text from the module settings.
		add_filter( 'woocommerce_get_terms_and_conditions_checkbox_text', array( $this, 'agree_to_terms_form_field' ) );

		// Fix: Germanized for WooCommerce
		add_filter( 'woocommerce_gzd_legal_checkbox_terms_label', array( $this, 'alter_terms_text' ), 999, 2 );

		// Enqueue styles.
		add_action( 'merchant_enqueue_before_main_css_js', array( $this, 'enqueue_css' ) );

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
		if ( ! empty( $settings['text'] ) ) {
			Merchant_Translator::register_string( $settings['text'], esc_html__( 'Text in agree to terms module', 'merchant' ) );
		}
		if ( ! empty( $settings['label'] ) ) {
			Merchant_Translator::register_string( $settings['label'], esc_html__( 'Link label in agree to terms module', 'merchant' ) );
		}
		if ( ! empty( $settings['link'] ) ) {
			Merchant_Translator::register_string( $settings['link'], esc_html__( 'Link in agree to terms module (you can use different link for each language)', 'merchant' ) );
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
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/agree-to-terms-checkbox.min.css', array(), MERCHANT_VERSION );
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
	 * @param string                 $module
	 *
	 * @return Merchant_Admin_Preview
	 */
	public function render_admin_preview( $preview, $module ) {
		if ( self::MODULE_ID === $module ) {
			ob_start();
			$this->admin_preview();
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
	 * Agree to terms checkout form field for admin preview.
	 *
	 * @return void
	 */
	public function admin_preview() {
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
	 * Agree to terms checkout form field.
	 *
	 * @return string
	 */
	public function agree_to_terms_form_field( $text = '' ) {
		$settings = $this->get_module_settings();

		if ( empty( $settings['text'] ) ) {
			return $text;
		}

		return sprintf( '%s <a href="%s" class=woocommerce-terms-and-conditions-link" target="_blank">%s</a>',
			esc_html( Merchant_Translator::translate( $settings['label'] ) ),
			esc_url( Merchant_Translator::translate( $settings['link'] ) ),
			esc_html( Merchant_Translator::translate( $settings['text'] ) )
		);
	}

	/**
	 * Show our terms text instead of Germanized plugin's.
	 *
	 * @param $label
	 * @param $instance
	 *
	 * @return string
	 */
	public function alter_terms_text( $label, $instance ) {
		$label = $this->agree_to_terms_form_field();

		return $label;
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
	 *
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
	 *
	 * @return string $css The custom CSS.
	 */
	public function frontend_custom_css( $css ) {
		$css .= $this->get_module_custom_css();

		return $css;
	}
}

// Initialize the module.
add_action( 'init', function () {
	Merchant_Modules::create_module( new Merchant_Agree_To_Terms_Checkbox() );
} );