<?php

/**
 * Inactive Tab Message.
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Inactive Tab Message Class.
 * 
 */
class Merchant_Inactive_Tab_Message extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'inactive-tab-message';

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
		$this->module_section = 'reduce-abandonment';

		// Module default settings.
		$this->module_default_settings = array(
			'message' => __( '✋ Don\'t forget this', 'merchant' ),
			'abandoned_message' => __( '✋ You left something in the cart', 'merchant' ),
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

		}

		if ( Merchant_Modules::is_module_active( self::MODULE_ID ) && is_admin() ) {
			// Init translations.
			$this->init_translations();
		}

		if ( ! Merchant_Modules::is_module_active( self::MODULE_ID ) ) {
			return;
		}

		// Return early if it's on admin but not in the respective module settings page.
		if ( is_admin() && ! parent::is_module_settings_page() ) {
			return; 
		}

		// Enqueue scripts.
		add_action( 'merchant_enqueue_after_main_css_js', array( $this, 'enqueue_scripts' ) );

		// Localize script.
		add_filter( 'merchant_localize_script', array( $this, 'localize_script' ) );

		// Add merchant selector and content to cart fragments.
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_count_fragment' ) );
	}

	/**
	 * Init translations.
	 *
	 * @return void
	 */
	public function init_translations() {
		$settings = $this->get_module_settings();
		if ( ! empty( $settings['message'] ) ) {
			Merchant_Translator::register_string( $settings['message'], esc_html__( 'Inactive tab messages: message text', 'merchant' ) );
		}
		if ( ! empty( $settings['abandoned_message'] ) ) {
			Merchant_Translator::register_string( $settings['abandoned_message'], esc_html__( 'Inactive tab messages abandoned message', 'merchant' ) );
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
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
		}
	}

	/**
	 * Enqueue scripts.
	 * 
	 * @return void
	 */
	public function enqueue_scripts() {

		// Register and enqueue the main module script.
		wp_enqueue_script( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/inactive-tab-message.min.js', array(), MERCHANT_VERSION, true );
	}

	/**
	 * Localize script with module settings.
	 * 
	 * @param array $setting The merchant global object setting parameter.
	 * @return array $setting The merchant global object setting parameter.
	 */
	public function localize_script( $setting ) {
		$module_settings = $this->get_module_settings();

		$setting['inactive_tab_message']           = Merchant_Translator::translate( $module_settings[ 'message' ] );
		$setting['inactive_tab_abandoned_message'] = Merchant_Translator::translate( $module_settings[ 'abandoned_message' ] );
		$setting['inactive_tab_cart_count']        = '0';

		if ( function_exists( 'WC' ) ) {
			$setting['inactive_tab_cart_count'] = WC()->cart->get_cart_contents_count();
		}

		return $setting;
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

			// Message.
			$preview->set_text( 'message', '.mrc-inactive-tab-message-text' );

		}

		return $preview;
	}

	/**
	 * Admin preview content.
	 * 
	 * @return void
	 */
	public function admin_preview_content() {
		$settings    = $this->get_module_settings();
		$favicon_url = get_site_icon_url() ? get_site_icon_url( 512 ) : MERCHANT_URI . 'inc/modules/' . self::MODULE_ID . '/admin/images/wplogo.svg';

		?>

		<div class="mrc-preview-inactive-tab-message">
			<div class="mrc-inactive-tab-message-icon-wrapper">
				<div class="mrc-inactive-tab-message__icon">
					<img src="<?php echo esc_url( $favicon_url ); ?>" />
				</div>
			</div>
			<div class="mrc-inactive-tab-message-text">
				<?php echo esc_html( Merchant_Translator::translate( $settings[ 'message' ] ) ); ?>
			</div>
		</div>

		<?php
	}

	/**
	 * Cart count fragments.
	 * 
	 * @param array $fragments The cart fragments.
	 * @return array $fragments The cart fragments.
	 */
	public function cart_count_fragment( $fragments ) {
		if ( Merchant_Modules::is_module_active( 'inactive-tab-message' ) ) {
			$fragments['.merchant_cart_count'] = WC()->cart->get_cart_contents_count();
		}
		
		return $fragments;
	}
}

// Initialize the module.
add_action( 'init', function() {
	Merchant_Modules::create_module( new Merchant_Inactive_Tab_Message() );
} );
