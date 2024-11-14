<?php

/**
 * Login Popup
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Login popup class.
 *
 */
class Merchant_Login_Popup extends Merchant_Add_Module {

	/**
	 * Module ID.
	 * 
	 */
	const MODULE_ID = 'login-popup';

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
		$this->module_section = 'improve-experience';

		// Module default settings.
		$this->module_default_settings = array(
			'login_link_text'      => esc_html__( 'Login', 'merchant' ),
			'show_welcome_message' => true,
			/* Translators: 1. Display name */
			'welcome_message_text' => sprintf( esc_html__( 'Welcome %s', 'merchant' ), '{display_name}' ),
		);

		// Mount preview url.
		$preview_url = site_url( '/' );

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

			// Enqueue admin scripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

			// Localize Script.
			add_filter( 'merchant_admin_localize_script', array( $this, 'localize_script' ) );

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
	}

	/**
	 * Init translations.
	 *
	 * @return void
	 */
	public function init_translations() {
		$settings = $this->get_module_settings();
		if ( ! empty( $settings['login_link_text'] ) ) {
			Merchant_Translator::register_string( $settings['login_link_text'], esc_html__( 'Login popup: link text', 'merchant' ) );
		}
		if ( ! empty( $settings['welcome_message_text'] ) ) {
			Merchant_Translator::register_string( $settings['welcome_message_text'], esc_html__( 'Login popup: welcome message text', 'merchant' ) );
		}
	}

	/**
	 * Admin enqueue CSS.
	 * 
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( parent::is_module_settings_page() ) {
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/login-popup.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
		}
	}

	/**
	 * Admin Enqueue scripts.
	 * 
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_script( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/login-popup.min.js', array(), MERCHANT_VERSION, true );
	}

	/**
	 * Localize Script.
	 */
	public function localize_script( $script ) {
		$script['is_admin'] = is_admin();

		return $script;
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
		if ( $module === self::MODULE_ID  ) {
			ob_start();
			self::admin_preview_content();
			$content = ob_get_clean();

			$preview->set_html( $content );

			$preview->set_text( 'welcome_message_text', '.merchant-login-popup-button', array(
					array(
						'{display_name}',
					),
					array(
						'Bob Jansen',
					),
				)
			);
			$preview->set_text( 'login_link_text', '.merchant-login-popup-button' );

			$preview->set_css( 'login-text-color', '.merchant-login-popup-dropdown', '--merchant-login-text-color' );
			$preview->set_css( 'login-text-color-hover', '.merchant-login-popup-dropdown', '--merchant-login-text-color-hover' );
			$preview->set_css( 'dropdown-background-color', '.merchant-login-popup-dropdown', '--merchant-dropdown-background-color' );
			$preview->set_css( 'dropdown-link-color', '.merchant-login-popup-dropdown', '--merchant-dropdown-link-color' );
			$preview->set_css( 'dropdown-link-color-hover', '.merchant-login-popup-dropdown', '--merchant-dropdown-link-color-hover' );

			$preview->set_css( 'popup-width', '.merchant-login-popup', '--merchant-popup-width', 'px' );
			$preview->set_css( 'popup-title-color', '.merchant-login-popup', '--merchant-popup-title-color' );
			$preview->set_css( 'popup-text-color', '.merchant-login-popup', '--merchant-popup-text-color' );
			$preview->set_css( 'popup-icon-color', '.merchant-login-popup', '--merchant-popup-icon-color' );
			$preview->set_css( 'popup-link-color', '.merchant-login-popup', '--merchant-popup-link-color' );
			$preview->set_css( 'popup-button-color', '.merchant-login-popup', '--merchant-popup-button-color' );
			$preview->set_css( 'popup-button-color-hover', '.merchant-login-popup', '--merchant-popup-button-color-hover' );
			$preview->set_css( 'popup-button-border-color', '.merchant-login-popup', '--merchant-popup-button-border-color' );
			$preview->set_css( 'popup-button-border-color-hover', '.merchant-login-popup', '--merchant-popup-button-border-color-hover' );
			$preview->set_css( 'popup-button-background-color', '.merchant-login-popup', '--merchant-popup-button-background-color' );
			$preview->set_css( 'popup-button-background-color-hover', '.merchant-login-popup', '--merchant-popup-button-background-color-hover' );
			$preview->set_css( 'popup-link-color-hover', '.merchant-login-popup', '--merchant-popup-link-color-hover' );
			$preview->set_css( 'popup-background-color', '.merchant-login-popup', '--merchant-popup-background-color' );

			$preview->set_css( 'popup-footer-text-color', '.merchant-login-popup', '--merchant-popup-footer-text-color' );
			$preview->set_css( 'popup-footer-link-color', '.merchant-login-popup', '--merchant-popup-footer-link-color' );
			$preview->set_css( 'popup-footer-link-color-hover', '.merchant-login-popup', '--merchant-popup-footer-link-color-hover' );
			$preview->set_css( 'popup-footer-background-color', '.merchant-login-popup', '--merchant-popup-footer-background-color' );
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

		$welcome_text = $settings[ 'welcome_message_text' ];
		$welcome_text = str_replace(
			array( '{user_firstname}', '{user_lastname}', '{user_email}', '{user_login}', '{display_name}' ),
			array( 'Bob', 'Jansen', 'bob@gmail.com', 'Bob Jansen', 'Bob Jansen' ),
			$welcome_text
		);

		?>

		<a href="#" class="merchant-login-popup-button merchant-login-popup-toggle"><?php echo esc_html( $settings[ 'login_link_text' ] ); ?></a>

		<div class="merchant-login-popup">
			<div class="merchant-login-popup-overlay merchant-login-popup-toggle"></div>
			<div class="merchant-login-popup-body">
				<a href="#" class="merchant-login-popup-close merchant-login-popup-toggle">
					<?php echo wp_kses( Merchant_SVG_Icons::get_svg_icon( 'icon-cancel' ), merchant_kses_allowed_tags( array(), false ) ); ?>
				</a>
				<div class="merchant-login-popup-content">
					<?php if ( function_exists( 'wc_get_template' ) ) : ?>
						<?php wc_get_template( 'myaccount/form-login.php' ); ?>
					<?php endif; ?>
				</div>

				<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
					<div class="merchant-login-popup-footer">
						<div class="merchant-show"><?php esc_html_e( 'Not a member?', 'merchant' ); ?> <a href="#"><?php esc_html_e( 'Register', 'merchant' ); ?></a></div>
						<div><?php esc_html_e( 'Already a member?', 'merchant' ); ?> <a href="#"><?php esc_html_e( 'Login', 'merchant' ); ?></a></div>
					</div>
				<?php endif; ?>
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

		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'login-text-color', '#212121', '.merchant-login-popup-button', '--merchant-login-text-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'login-text-color-hover', '#212121', '.merchant-login-popup-button', '--merchant-login-text-color-hover' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'dropdown-background-color', '#ffffff', '.merchant-login-popup-dropdown', '--merchant-dropdown-background-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'dropdown-link-color', '#212121', '.merchant-login-popup-dropdown', '--merchant-dropdown-link-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'dropdown-link-color-hover', '#515151', '.merchant-login-popup-dropdown', '--merchant-dropdown-link-color-hover' );

		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'popup-width', 400, '.merchant-login-popup', '--merchant-popup-width', 'px' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'popup-title-color', '#212121', '.merchant-login-popup', '--merchant-popup-title-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'popup-text-color', '#212121', '.merchant-login-popup', '--merchant-popup-text-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'popup-icon-color', '#212121', '.merchant-login-popup', '--merchant-popup-icon-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'popup-link-color', '#212121', '.merchant-login-popup', '--merchant-popup-link-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'popup-button-color', '#ffffff', '.merchant-login-popup', '--merchant-popup-button-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'popup-button-color-hover', '#ffffff', '.merchant-login-popup', '--merchant-popup-button-color-hover' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'popup-button-border-color', '#212121', '.merchant-login-popup', '--merchant-popup-button-border-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'popup-button-border-color-hover', '#757575', '.merchant-login-popup', '--merchant-popup-button-border-color-hover' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'popup-button-background-color', '#212121', '.merchant-login-popup', '--merchant-popup-button-background-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'popup-button-background-color-hover', '#757575', '.merchant-login-popup', '--merchant-popup-button-background-color-hover' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'popup-link-color-hover', '#515151', '.merchant-login-popup', '--merchant-popup-link-color-hover' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'popup-background-color', '#ffffff', '.merchant-login-popup', '--merchant-popup-background-color' );

		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'popup-footer-text-color', '#212121', '.merchant-login-popup', '--merchant-popup-footer-text-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'popup-footer-link-color', '#212121', '.merchant-login-popup', '--merchant-popup-footer-link-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'popup-footer-link-color-hover', '#515151', '.merchant-login-popup', '--merchant-popup-footer-link-color-hover' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'popup-footer-background-color', '#f5f5f5', '.merchant-login-popup', '--merchant-popup-footer-background-color' );

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
	Merchant_Modules::create_module(new Merchant_Login_Popup());
} );
