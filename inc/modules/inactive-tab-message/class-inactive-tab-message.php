<?php

/**
 * Inactive Tab Message.
 * 
 * @package Merchant_Pro
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
		parent::__construct();

		// Module section.
		$this->module_section = 'reduce-abandonment';

		// Module id.
		$this->module_id = self::MODULE_ID;

		// Module default settings.
		$this->module_default_settings = array(
			'message' => __( '✋ Don\'t forget this', 'merchant' ),
			'abandoned_message' => __( '✋ You left something in the cart', 'merchant' )
		);

		// Mount preview url.
		$preview_url = site_url( '/' );

		if ( function_exists( 'wc_get_page_id' ) ) {
			$preview_url = get_permalink( wc_get_page_id( 'shop' ) );
		}

		// Module data.
		$this->module_data = array(
			'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path d="M0 1.5A1.5 1.5 0 0 1 1.5 0h17A1.5 1.5 0 0 1 20 1.5v6A1.5 1.5 0 0 1 18.5 9h-5.889a1.5 1.5 0 0 1-1.5-1.5V5.111a1.111 1.111 0 1 0-2.222 0V7.5a1.5 1.5 0 0 1-1.5 1.5H1.5A1.5 1.5 0 0 1 0 7.5v-6Z"/><path fill-rule="evenodd" d="M7 5a3 3 0 0 1 6 0v4.384a.5.5 0 0 0 .356.479l2.695.808a2.5 2.5 0 0 1 1.756 2.748l-.633 4.435A2.5 2.5 0 0 1 14.699 20H6.96a2.5 2.5 0 0 1-2.27-1.452l-2.06-4.464a2.417 2.417 0 0 1-.106-1.777c.21-.607.719-1.16 1.516-1.273 1.035-.148 2.016.191 2.961.82V5Zm3-1a1 1 0 0 0-1 1v7.793c0 1.39-1.609 1.921-2.527 1.16-.947-.784-1.59-.987-2.069-.948a.486.486 0 0 0 .042.241l2.06 4.463A.5.5 0 0 0 6.96 18h7.74a.5.5 0 0 0 .494-.43l.633-4.434a.5.5 0 0 0-.35-.55l-2.695-.808A2.5 2.5 0 0 1 11 9.384V5a1 1 0 0 0-1-1Z" clip-rule="evenodd"/></svg>',
			'title' => esc_html__( 'Inactive Tab Message', 'merchant' ),
			'desc' => esc_html__( 'Don’t let customers forget about their order – change the title of the browser tab when visitors navigate away from your store.', 'merchant' ),
			'placeholder' => MERCHANT_URI . 'assets/images/modules/inactive-tab-messsage.png',
			'tutorial_url' => 'https://docs.athemes.com/article/inactive-tab-message/',
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
	 * Admin enqueue CSS.
	 * 
	 * @return void
	 */
	public function admin_enqueue_css() {
		$page   = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
		$module = ( ! empty( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : '';

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

		$setting['inactive_tab_messsage']          = $module_settings[ 'message' ];
		$setting['inactive_tab_abandoned_message'] = $module_settings[ 'abandoned_message' ];
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
		$settings 	 = $this->get_module_settings();
		$favicon_url = get_site_icon_url() ? get_site_icon_url( 512 ) : MERCHANT_URI . 'inc/modules/' . self::MODULE_ID . '/admin/images/wplogo.svg';

		?>

		<div class="mrc-preview-inactive-tab-message">
			<div class="mrc-inactive-tab-message-icon-wrapper">
				<div class="mrc-inactive-tab-message__icon">
					<img src="<?php echo esc_url( $favicon_url ); ?>" />
				</div>
			</div>
			<div class="mrc-inactive-tab-message-text">
				<?php echo esc_html( $settings[ 'message' ] ); ?>
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
new Merchant_Inactive_Tab_Message();
