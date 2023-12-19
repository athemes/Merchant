<?php

/**
 * Cart Count Favicon.
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Cart Count Favicon Class.
 * 
 */
class Merchant_Cart_Count_Favicon extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'cart-count-favicon';

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
			'shape' => 'circle',
			'position' => 'up-right',
			'background_color' => '#ff0101',
			'text_color' => '#FFF',
			'animation' => 'slide',
			'delay' => '0s',
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

		// Enqueue vendor favico.js.
		wp_enqueue_script( 'favico', MERCHANT_URI . 'assets/js/vendor/favico.js', array(), MERCHANT_VERSION, true );

		// Register and enqueue the main module script.
		wp_enqueue_script( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/cart-count-favicon.min.js', array(), MERCHANT_VERSION, true );
	}

	/**
	 * Localize script with module settings.
	 * 
	 * @param array $setting The merchant global object setting parameter.
	 * @return array $setting The merchant global object setting parameter.
	 */
	public function localize_script( $setting ) {
		$module_settings = $this->get_module_settings();

		$setting['cart_count_favicon']                  = true;
		$setting['cart_count_favicon_shape']            = $module_settings[ 'shape' ];
		$setting['cart_count_favicon_position']         = $module_settings[ 'position' ];
		$setting['cart_count_favicon_background_color'] = $module_settings[ 'background_color' ];
		$setting['cart_count_favicon_text_color']       = $module_settings[ 'text_color' ];
		$setting['cart_count_favicon_delay']            = $module_settings[ 'delay' ];
		$setting['cart_count_favicon_animation']        = $module_settings[ 'animation' ];
		$setting['cart_count_favicon_count']            = '0';
		
		if ( function_exists( 'WC' ) ) {
			$setting['cart_count_favicon_count'] = WC()->cart->get_cart_contents_count();
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

			// Shape.
			$preview->set_class( 'shape', '.mrc-cart-count-favicon__count', array( 'circle', 'rectangle' ) );

			// Location of the bullet.
			$preview->set_class( 'position', '.mrc-cart-count-favicon__count', array( 'up-left', 'up-right', 'down-left', 'down-right' ) );

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

		<div class="mrc-preview-cart-count-favicon">
			<div class="mrc-cart-count-favicon">
				<div class="mrc-cart-count-favicon__icon">
					<img src="<?php echo esc_url( $favicon_url ); ?>" />
				</div>
				<div class="mrc-cart-count-favicon__count <?php echo esc_attr( $settings[ 'shape' ] ); ?> <?php echo esc_attr( $settings[ 'position' ] ); ?>">
					2
				</div>
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
		if ( Merchant_Modules::is_module_active( 'cart-count-favicon' ) ) {
			$fragments[ '.merchant_cart_count' ] = WC()->cart->get_cart_contents_count();
		}
		
		return $fragments;
	}

	/**
	 * Custom CSS.
	 * 
	 * @return string
	 */
	public function get_module_custom_css() {
		$css = '';

		// Background Color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'background_color', '#ff0101', '.mrc-preview-cart-count-favicon', '--mrc-ccf-bg-color' );

		// Text Color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'text_color', '#FFF', '.mrc-preview-cart-count-favicon', '--mrc-ccf-text-color' );

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
	new Merchant_Cart_Count_Favicon();
} );
