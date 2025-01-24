<?php

/**
 * Scroll To Top Button.
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Scroll To Top Button Class.
 * 
 */
class Merchant_Scroll_To_Top_Button extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'scroll-to-top-button';

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
		$this->module_section = 'improve-experience';

		// Module id.
		$this->module_id = self::MODULE_ID;

		// Module default settings.
		$this->module_default_settings = array(
			'style' => 'merchant-style-filled',
			'type' => 'icon',
			'icon' => 'arrow-1',
			'text' => esc_html__( 'Back to top', 'merchant' ),
			'position' => 'merchant-position-right',
			'visibility' => 'all',
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

		if ( ! Merchant_Modules::is_module_active( self::MODULE_ID ) ) {
			return;
		}

		// Return early if it's on admin but not in the respective module settings page.
		if ( is_admin() && ! parent::is_module_settings_page() ) {
			return; 
		}

		// Enqueue styles.
		add_action( 'merchant_enqueue_before_main_css_js', array( $this, 'enqueue_css' ) );

		// Enqueue scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Render the scroll to top button on footer.
		add_action( 'wp_footer', array( $this, 'get_scroll_to_top_button' ) );

		// Custom CSS.
		add_filter( 'merchant_custom_css', array( $this, 'frontend_custom_css' ) );
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
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/scroll-to-top-button.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
		}
	}

	/**
	 * Enqueue CSS.
	 * 
	 * @return void
	 */
	public function enqueue_css() {
		wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/scroll-to-top-button.min.css', array(), MERCHANT_VERSION );
	}

	/**
	 * Enqueue scripts.
	 * 
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/scroll-to-top-button.min.js', array(), MERCHANT_VERSION, true );
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

			$preview->set_class( 'position', '.merchant-scroll-to-top-button', array( 'merchant-position-right', 'merchant-position-left' ) );
			$preview->set_class( 'style', '.merchant-scroll-to-top-button', array( 'merchant-style-filled', 'merchant-style-outline' ) );

			$preview->set_css( 'side-offset', '.merchant-scroll-to-top-button', '--merchant-side-offset', 'px' );
			$preview->set_css( 'bottom-offset', '.merchant-scroll-to-top-button', '--merchant-bottom-offset', 'px' );

			$preview->set_css( 'icon-size', '.merchant-scroll-to-top-button', '--merchant-icon-size', 'px' );
			$preview->set_css( 'padding', '.merchant-scroll-to-top-button', '--merchant-padding' , 'px');
			$preview->set_css( 'border-radius', '.merchant-scroll-to-top-button', '--merchant-border-radius', 'px' );

			$preview->set_css( 'icon-color', '.merchant-scroll-to-top-button svg', '--merchant-icon-color' );
			$preview->set_css( 'icon-hover-color', '.merchant-scroll-to-top-button svg', '--merchant-icon-hover-color' );
			$preview->set_css( 'background-color', '.merchant-scroll-to-top-button', '--merchant-background-color' );
			$preview->set_css( 'background-hover-color', '.merchant-scroll-to-top-button', '--merchant-background-hover-color' );

		}

		return $preview;
	}

	/**
	 * Admin preview content.
	 * 
	 * @return void
	 */
	public function admin_preview_content() {
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
				<div class="mrc-preview-text-placeholder mrc-mw-30"></div>
				<div class="mrc-preview-addtocart-placeholder"></div>
			</div>
		</div>

		<?php $this->get_scroll_to_top_button(); ?>

		<?php
	}

	/**
	 * Get scroll to top button.
	 * 
	 * @return void
	 */
	public function get_scroll_to_top_button() {
		$settings = $this->get_module_settings();
	
		$html = '<div class="merchant-scroll-to-top-button ' . esc_attr( $settings[ 'position' ] ) . ' ' . esc_attr( $settings[ 'style' ] ) . ' merchant-visibility-' . esc_attr( $settings[ 'visibility' ] ) . '">';
	
		if ( 'text-icon' === $settings[ 'type' ] ) {
			$html .= '<span>' . esc_html( $settings[ 'text' ] ) . '</span>';
		}

		switch ( $settings[ 'icon' ] ) {
			case 'arrow-1':
				$html .= '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 15L12 8L19 15" stroke-width="1.5" stroke-linejoin="round"></path></svg>';
				break;
	
			case 'arrow-2':
				$html .= '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 15l7-7 7 7" stroke-width="3" stroke-linejoin="round"></path></svg>';
				break;
	
			case 'arrow-3':
				$html .= '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 12l5.5-5.5m0 0L18 12m-5.5-5.5V19" stroke-width="1.5" stroke-linejoin="round"></path></svg>';
				break;
	
			case 'arrow-4':
				$html .= '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 12l5.5-5.5m0 0L18 12m-5.5-5.5V19" stroke-width="3" stroke-linejoin="round"></path></svg>';
				break;
		}
	
		$html .= '</div>';
	
		echo wp_kses( $html, merchant_kses_allowed_tags() );
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
add_action( 'init', function() {
	Merchant_Modules::create_module( new Merchant_Scroll_To_Top_Button() );
} );
