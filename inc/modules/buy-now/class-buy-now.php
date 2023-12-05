<?php

/**
 * Buy Now.
 *
 * @package Merchant
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

		// Module data.
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];
		$this->module_data['preview_url'] = $this->set_module_preview_url( array( 'type' => 'product' ) );

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
			// HTML.
			$preview->set_html( array( $this, 'admin_preview_content' ), $this->get_module_settings() );

			// Button Text.
			$preview->set_text( 'button-text', '.merchant-buy-now-button' );

		}

		return $preview;
	}

	/**
	 * Admin preview content.
	 *
	 * @param array $settings The module settings
	 *
	 * @return void
	 */
	public function admin_preview_content( $settings ) {
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
				<div class="mrc-preview-call-to-action">
					<a href="#" class="merchant-buy-now-button"><?php echo esc_html( $settings[ 'button-text' ] ); ?></a>
				</div>
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
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'text-color', '#ffffff', '.merchant-buy-now-button', '--mrc-buy-now-text-color' );

		// Text Color (hover).
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'text-hover-color', '#ffffff', '.merchant-buy-now-button', '--mrc-buy-now-text-hover-color' );

		// Border Color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'border-color', '#212121', '.merchant-buy-now-button', '--mrc-buy-now-border-color' );

		// Border Color (hover).
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'border-hover-color', '#414141', '.merchant-buy-now-button', '--mrc-buy-now-border-hover-color' );

		// Background Color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'background-color', '#212121', '.merchant-buy-now-button', '--mrc-buy-now-background-color' );

		// Background Color (hover).
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'background-hover-color', '#414141', '.merchant-buy-now-button', '--mrc-buy-now-background-hover-color' );

		// Font Size.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'font-size', 15, '.merchant-buy-now-button', '--mrc-buy-now-font-size', 'px' );

		// Align.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'align', 'center', '.merchant-buy-now-button', '--mrc-buy-now-align' );

		// Padding.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'padding', 20, '.merchant-buy-now-button', '--mrc-buy-now-padding', 'px' );

		// Border radius.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'border-radius', 15, '.merchant-buy-now-button', '--mrc-buy-now-border-radius', 'px' );

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
	new Merchant_Buy_Now();
} );
