<?php

/**
 * Wait list
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Wait list class.
 *
 */
class Merchant_Wait_List extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'wait-list';

	/**
	 * Module path.
	 */
	const MODULE_DIR = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID;

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
			'form_title'               => __( 'New stock is coming! Email me when this item is back in stock', 'merchant' ),
			'form_email_label'         => __( 'Your Email Address', 'merchant' ),
			'form_button_text'         => __( 'Notify Me', 'merchant' ),
			'form_success_message'     => __( 'You have been successfully added to our stock waitlist. As soon as new stock becomes available, we will notify you via email.',
				'merchant' ),
			'email_new_subscriber'     => __( 'Hello, thank you for subscribing to the stock waitlist for {product}. We will email you once the product back in stock.',
				'merchant' ),
			'form_unsubscribe_message' => __( 'You have been successfully unsubscribed from our stock waitlist.', 'merchant' ),
			'email_update'             => __( 'Hello, thanks for your patience and finally the wait is over! Your {product} is now back in stock! We only have a limited amount of stock, and this email is not a guarantee you’ll get one. Add this {product} directly to your cart.',
				'merchant' ),
			'form_nonce_field'         => wp_nonce_field( 'merchant_wait_list_action', 'merchant_wait_list_action', true, false ),
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
		$this->module_data                = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];
		$this->module_data['preview_url'] = $preview_url;

		// Module options path.
		$this->module_options_path = self::MODULE_DIR . '/admin/options.php';

		// Is module preview page.
		if ( is_admin() && parent::is_module_settings_page() ) {
			self::$is_module_preview = true;

			// Enqueue admin styles.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_css' ) );

			// Admin preview box.
			add_filter( 'merchant_module_preview', array( $this, 'render_admin_preview' ), 10, 2 );
		}

		if ( Merchant_Modules::is_module_active( self::MODULE_ID ) && is_admin() ) {
			// Init translations.
			$this->init_translations();
		}
	}

	/**
	 * Init translations.
	 *
	 * @return void
	 */
	public function init_translations() {
		$settings = $this->get_module_settings();
		if ( ! empty( $settings['form_title'] ) ) {
			Merchant_Translator::register_string( $settings['form_title'], esc_html__( 'Wait list: Form title', 'merchant' ) );
		}
		if ( ! empty( $settings['form_email_label'] ) ) {
			Merchant_Translator::register_string( $settings['form_email_label'], esc_html__( 'Wait list: Form email label', 'merchant' ) );
		}
		if ( ! empty( $settings['form_button_text'] ) ) {
			Merchant_Translator::register_string( $settings['form_button_text'], esc_html__( 'Wait list: Form button text', 'merchant' ) );
		}
		if ( ! empty( $settings['form_success_message'] ) ) {
			Merchant_Translator::register_string( $settings['form_success_message'], esc_html__( 'Wait list: Form success message', 'merchant' ) );
		}
		if ( ! empty( $settings['email_new_subscriber'] ) ) {
			Merchant_Translator::register_string( $settings['email_new_subscriber'], esc_html__( 'Wait list: Email new subscriber', 'merchant' ) );
		}
		if ( ! empty( $settings['email_update'] ) ) {
			Merchant_Translator::register_string( $settings['email_update'], esc_html__( 'Wait list: Email update', 'merchant' ) );
		}
	}

	/**
	 * Admin enqueue CSS.
	 *
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( parent::is_module_settings_page() ) {
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/wait-list.min.css', array(), MERCHANT_VERSION );
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
			self::admin_preview_content();
			$content = ob_get_clean();

			$preview->set_html( $content );

			$preview->set_text( 'form_title', '.merchant-wait-list-title' );
			$preview->set_text( 'form_button_text', '.merchant-wait-list-submit' );
			$preview->set_attribute( 'form_email_label', '#merchant-wait-list-email', 'placeholder' );
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
                <div class="preview-merchant-wait-list">
					<?php 
					$preview_html = str_replace( 'required', '', $this->get_html( $settings ) );
					echo wp_kses( $preview_html, merchant_kses_allowed_tags( array( 'forms', 'nonce' ) ) ); ?>
                </div>
            </div>
        </div>

		<?php
	}

	/**
	 * Get the module HTML.
	 *
	 * @param array $settings
	 *
	 * @return string
	 */
	public function get_html( $settings ) {
		$html = '<form id="merchant-wait-list" class="merchant-wait-list" method="post">';
		$html .= '<p class="merchant-wait-list-title">' . Merchant_Translator::translate( $settings['form_title'] ) . '</p>';
		$html .= '<div class="merchant-wait-list-email">';
		$html .= '<label for="merchant-wait-list-email">' . Merchant_Translator::translate( $settings['form_email_label'] ) . ' <abbr class="required" title="required">*</abbr></label>';
		$html .= '<input type="email" name="merchant-wait-list-email" id="merchant-wait-list-email" value="" autocomplete="email" placeholder="' . Merchant_Translator::translate( $settings['form_email_label'] ) . '" required="">';
		$html .= '</div>';
		$html .= '<button class="merchant-wait-list-submit" type="submit" name="subscribe">' . Merchant_Translator::translate( $settings['form_button_text'] ) . '</button>';
		$html .= $settings['form_nonce_field'];
		$html .= '</form>';

		return $html;
	}
}

// Initialize the module.
add_action( 'init', function () {
	Merchant_Modules::create_module( new Merchant_Wait_List() );
} );
