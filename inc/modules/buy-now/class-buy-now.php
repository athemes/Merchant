<?php

/**
 * Buy Now.
 * 
 * @package Merchant_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Buy Now Class.
 * 
 */
class Merchant_Buy_Now extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'buy-now';

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
		$this->module_section = 'convert-more';

		// Module id.
		$this->module_id = self::MODULE_ID;

		// Module default settings.
		$this->module_default_settings = array(
			'button-text' => __( 'Buy Now', 'merchant' )
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
		$this->module_data = array(
			'icon' => '<svg width="18" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 20"><path d="M8.707 1.293a1 1 0 0 0-1.414 1.414L8.586 4 7.293 5.293a1 1 0 0 0 1.414 1.414L10 5.414l1.293 1.293a1 1 0 1 0 1.414-1.414L11.414 4l1.293-1.293a1 1 0 0 0-1.414-1.414L10 2.586 8.707 1.293Z"/><path fill-rule="evenodd" clip-rule="evenodd" d="M0 1a1 1 0 0 1 1-1h1.5A1.5 1.5 0 0 1 4 1.5V10h11.133l.877-6.141a1 1 0 1 1 1.98.282l-.939 6.571A1.5 1.5 0 0 1 15.566 12H4v2h10a3 3 0 1 1-2.83 2H5.83A3 3 0 1 1 2 14.17V2H1a1 1 0 0 1-1-1Zm13 16a1 1 0 1 1 2 0 1 1 0 0 1-2 0ZM2 17a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z"/></svg>',
			'title' => esc_html__( 'Buy Now', 'merchant' ),
			'desc' => esc_html__( 'Add Buy Now buttons to your product pages that take customers directly to the checkout.', 'merchant' ),
			'placeholder' => MERCHANT_URI . 'assets/images/modules/buy-now.png',
			'tutorial_url' => 'https://docs.athemes.com/article/buy-now/',
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

		// Buy now listener.
		add_action( 'wp', array( $this, 'buy_now_listener' ) );

		// Render buy now button on single product page.
		add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'single_product_buy_now_button' ) );

		// Render buy now button on shop archive products.
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'shop_archive_product_buy_now_button' ), 20 );

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
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/buy-now.min.css', [], MERCHANT_VERSION );
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
		wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/buy-now.min.css', array(), MERCHANT_VERSION );
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

			// Button Text.
			$preview->set_text( 'button-text', '.merchant-buy-now-button' );

		}

		return $preview;
	}

	/**
	 * Admin preview content.
	 * 
	 * @return void
	 */
	public function admin_preview_content() {

		// Get all settings.
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
				<div class="mrc-preview-text-placeholder mrc-mw-40"></div>
				<a href="#" class="merchant-buy-now-button"><?php echo esc_html( $settings[ 'button-text' ] ); ?></a>
			</div>
		</div>

		<?php
	}

	/**
	 * Buy now listener.
	 * 
	 * @return void
	 */
	public function buy_now_listener() {
		$product_id = ( isset( $_REQUEST['merchant-buy-now'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['merchant-buy-now'] ) ) : '';

		if ( $product_id ) {
	
			$variation_id = ( isset( $_REQUEST['variation_id'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['variation_id'] ) ) : '';
			if ( $variation_id ) {
				WC()->cart->add_to_cart( $product_id, 1, $variation_id );
			} else {
				WC()->cart->add_to_cart( $product_id, 1 );
			}
	
			wp_safe_redirect( wc_get_checkout_url() );
	
			exit;
		}
	}

	/**
	 * Single product buy now button.
	 * TODO: Render the output trough template files.
	 * 
	 * @return void
	 */
	public function single_product_buy_now_button() {	
		global $post, $product;
	
		if ( ! empty( $product ) ) {
			if ( 'yes' == get_post_meta( $post->ID, '_is_pre_order', true ) && strtotime( get_post_meta( $post->ID, '_pre_order_date', true ) ) > time() ) {
				return;
			}
		}
	
		$text = Merchant_Admin_Options::get( 'buy-now', 'button-text', esc_html__( 'Buy Now', 'merchant' ) );
	
		?>
	
		<button type="submit" name="merchant-buy-now" value="<?php echo esc_attr( $product->get_ID() ); ?>" class="single_add_to_cart_button button alt wp-element-button merchant-buy-now-button"><?php echo esc_html( $text ); ?></button>
	
		<?php 
	}

	/**
	 * Shop archive product buy now button.
	 * TODO: Render the output trough template files.
	 * 
	 * @return void
	 */
	public function shop_archive_product_buy_now_button() {
		global $post, $product;
	
		if ( ! $product->is_type( 'simple' ) ) {
		  return;
		}
	
		if ( ! empty( $product ) ) {
			if ( 'yes' == get_post_meta( $post->ID, '_is_pre_order', true ) && strtotime( get_post_meta( $post->ID, '_pre_order_date', true ) ) > time() ) {
				return;
			}
		}
	
		$text = Merchant_Admin_Options::get( 'buy-now', 'button-text', esc_html__( 'Buy Now', 'merchant' ) );
	
		?>
		
		<a href="<?php echo esc_url( add_query_arg( array( 'merchant-buy-now' => $product->get_ID() ), wc_get_checkout_url() ) ); ?>" class="button alt wp-element-button product_type_simple add_to_cart_button merchant-buy-now-button"><?php echo esc_html( $text ); ?></a>
	
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
		$css .= Merchant_Custom_CSS::get_variable_css( 'buy-now', 'text-color', '#ffffff', '.merchant-buy-now-button', '--mrc-buy-now-text-color' );

		// Text Color (hover).
		$css .= Merchant_Custom_CSS::get_variable_css( 'buy-now', 'text-hover-color', '#ffffff', '.merchant-buy-now-button', '--mrc-buy-now-text-hover-color' );

		// Border Color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'buy-now', 'border-color', '#212121', '.merchant-buy-now-button', '--mrc-buy-now-border-color' );

		// Border Color (hover).
		$css .= Merchant_Custom_CSS::get_variable_css( 'buy-now', 'border-hover-color', '#414141', '.merchant-buy-now-button', '--mrc-buy-now-border-hover-color' );

		// Background Color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'buy-now', 'background-color', '#212121', '.merchant-buy-now-button', '--mrc-buy-now-background-color' );

		// Background Color (hover).
		$css .= Merchant_Custom_CSS::get_variable_css( 'buy-now', 'background-hover-color', '#414141', '.merchant-buy-now-button', '--mrc-buy-now-background-hover-color' );

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
new Merchant_Buy_Now();
