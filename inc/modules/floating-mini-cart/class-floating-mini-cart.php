<?php

/**
 * Floating Mini Cart
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Floating mini cart class.
 *
 */
class Merchant_Floating_Mini_Cart extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'floating-mini-cart';

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
			'display' => 'always',
			'icon' => 'cart-icon-1',
			'icon-position' => 'right',
		);

		// Module data.
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];
		$this->module_data['preview_url'] = $this->set_module_preview_url( array( 'type' => 'shop' ) );

		// Module options path.
		$this->module_options_path = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID . '/admin/options.php';

		// Is module preview page.
		if ( is_admin() && parent::is_module_settings_page() ) {
			self::$is_module_preview = true;

			// Enqueue admin styles.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_css' ) );

			// Enqueue admin scripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

			// Admin preview box.
			add_filter( 'merchant_module_preview', array( $this, 'render_admin_preview' ), 10, 2 );

			// Custom CSS.
			// The custom CSS should be added here as well due to ensure preview box works properly.
			add_filter( 'merchant_custom_css', array( $this, 'admin_custom_css' ) );

		}
	}

	/**
	 * Admin enqueue CSS.
	 *
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( $this->is_module_settings_page()) {
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/floating-mini-cart.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
		}
	}

	/**
	 * Admin Enqueue scripts.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {

		// Register and enqueue the main module script.
		wp_enqueue_script( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/floating-mini-cart.min.js', array(), MERCHANT_VERSION, true );
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

			// Icon.
			$preview->set_svg_icon( 'icon', '.merchant-floating-mini-cart-icon-icon' );

			// Position.
			$preview->set_class( 'icon-position', '.merchant-floating-mini-cart-icon', array( 'left', 'right' ) );


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
				<div class="mrc-preview-text-placeholder"></div>
				<div class="mrc-preview-text-placeholder mrc-mw-70"></div>
				<div class="mrc-preview-text-placeholder mrc-mw-30"></div>
				<div class="mrc-preview-text-placeholder"></div>
				<div class="mrc-preview-text-placeholder mrc-mw-70"></div>
				<div class="mrc-preview-text-placeholder mrc-mw-30"></div>
				<div class="mrc-preview-text-placeholder"></div>
				<div class="mrc-preview-text-placeholder mrc-mw-70"></div>
				<div class="mrc-preview-addtocart-placeholder"></div>
			</div>
		</div>

		<?php self::floating_mini_cart_icon_output(); ?>

		<div class="merchant-floating-side-mini-cart-overlay merchant-floating-side-mini-cart-toggle"></div>
			<div class="merchant-floating-side-mini-cart">
				<div class="merchant-floating-side-mini-cart-body">
					<a href="#" class="merchant-floating-side-mini-cart-close-button merchant-floating-side-mini-cart-toggle" title="<?php echo esc_attr__( 'Close the side mini cart', 'merchant' ); ?>">
						<?php echo wp_kses( Merchant_SVG_Icons::get_svg_icon( 'icon-cancel' ), merchant_kses_allowed_tags( array(), false ) ); ?>
					</a>

					<div class="merchant-floating-side-mini-cart-widget">
						<div class="merchant-floating-side-mini-cart-widget-title"><?php echo esc_html__( 'Your Cart', 'merchant' ); ?></div>
						<div class="widget_shopping_cart_content">
							<ul class="woocommerce-mini-cart cart_list product_list_widget">
								<li class="woocommerce-mini-cart-item mini_cart_item">
									<a href="#" class="remove remove_from_cart_button">×</a>
									<a href="#">
										<span class="mrc-product-image"></span>
										<?php echo esc_html__( 'Product Sample Title', 'merchant' ); ?>
									</a>
									<span class="quantity">1 ×
										<span class="woocommerce-Price-amount amount">
											<bdi><span class="woocommerce-Price-currencySymbol">$</span>12.00 </bdi>
										</span>
									</span>
								</li>
								<li class="woocommerce-mini-cart-item mini_cart_item">
									<a href="#" class="remove remove_from_cart_button">×</a>
									<a href="#">
										<span class="mrc-product-image"></span>
										<?php echo esc_html__( 'Product Sample Title', 'merchant' ); ?>
									</a>
									<span class="quantity">1 ×
										<span class="woocommerce-Price-amount amount">
											<bdi><span class="woocommerce-Price-currencySymbol">$</span>12.00 </bdi>
										</span>
									</span>
								</li>
							</ul>

							<p class="woocommerce-mini-cart__total total">
								<strong><?php echo esc_html__( 'Subtotal:', 'merchant' ); ?></strong>
								<span class="woocommerce-Price-amount amount">
									<bdi><span class="woocommerce-Price-currencySymbol">$</span>12.00 </bdi>
								</span>
							</p>
							<p class="woocommerce-mini-cart__buttons buttons">
							<a href="#" class="button wc-forward"><?php echo esc_html__( 'View cart', 'merchant' ); ?></a>
							<a href="#" class="button checkout wc-forward"><?php echo esc_html__( 'Checkout', 'merchant' ); ?></a>
							</p>
						</div>
					</div>
				</div>
			</div>

		<?php
	}

	/**
	 * Floating mini cart icon output.
	 * TODO: Render the output through template files.
	 *
	 * @return void
	 */
	public function floating_mini_cart_icon_output() {
		if ( is_cart() || is_checkout() ) {
			return;
		}

		// Get module settings.
		$settings = $this->get_module_settings();

		?>

		<a href="#" class="merchant-floating-mini-cart-icon merchant-floating-mini-cart-icon-position-<?php echo esc_attr( $settings[ 'icon-position' ] ); ?> merchant-floating-side-mini-cart-toggle" data-display="<?php echo esc_attr( $settings[ 'display' ] ); ?>">
			<span class="merchant-floating-mini-cart-icon-counter">0</span>
			<i class="merchant-floating-mini-cart-icon-icon">
				<?php echo wp_kses( Merchant_SVG_Icons::get_svg_icon( $settings[ 'icon' ] ), merchant_kses_allowed_tags( array(), false ) ); ?>
			</i>
		</a>

		<?php
	}

	/**
	 * Custom CSS.
	 *
	 * @return string
	 */
	public static function get_module_custom_css() {
		$css = '';

		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'icon-size', 25, '.merchant-floating-mini-cart-icon', '--mrc-fmci-icon-size', 'px' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'corner-offset', 30, '.merchant-floating-mini-cart-icon', '--mrc-fmci-corner-offset', 'px' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'icon-position', 'left', '.merchant-floating-mini-cart-icon', '--mrc-fmci-icon-position' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'border-radius', 35, '.merchant-floating-mini-cart-icon-icon', '--mrc-fmci-border-radius', 'px' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'icon-color', '#ffffff', '.merchant-floating-mini-cart-icon-icon', '--mrc-fmci-icon-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'background-color', '#212121', '.merchant-floating-mini-cart-icon-icon', '--mrc-fmci-background-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'counter-color', '#ffffff', '.merchant-floating-mini-cart-icon-counter', '--mrc-fmci-counter-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'counter-background-color', '#757575', '.merchant-floating-mini-cart-icon-counter', '--mrc-fmci-counter-background-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-width', 380, '.merchant-floating-side-mini-cart', '--mrc-fmci-side-cart-width', 'px' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-title-color', '#212121', '.merchant-floating-side-mini-cart', '--mrc-fmci-side-cart-title-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-title-icon-color', '#212121', '.merchant-floating-side-mini-cart', '--mrc-fmci-side-cart-title-icon-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-title-background-color', '#cccccc', '.merchant-floating-side-mini-cart', '--mrc-fmci-side-cart-title-background-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-content-text-color', '#212121', '.merchant-floating-side-mini-cart', '--mrc-fmci-side-cart-content-text-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-content-background-color', '#ffffff', '.merchant-floating-side-mini-cart', '--mrc-fmci-side-cart-content-background-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-content-remove-color', '#ffffff', '.merchant-floating-side-mini-cart', '--mrc-fmci-side-cart-content-remove-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-content-remove-background-color', '#212121', '.merchant-floating-side-mini-cart', '--mrc-fmci-side-cart-content-remove-background-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-total-text-color', '#212121', '.merchant-floating-side-mini-cart', '--mrc-fmci-side-cart-total-text-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-total-background-color', '#f5f5f5', '.merchant-floating-side-mini-cart', '--mrc-fmci-side-cart-total-background-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-button-color', '#ffffff', '.merchant-floating-side-mini-cart', '--mrc-fmci-side-cart-button-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-button-color-hover', '#ffffff', '.merchant-floating-side-mini-cart', '--mrc-fmci-side-cart-button-color-hover' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-button-border-color', '#212121', '.merchant-floating-side-mini-cart', '--mrc-fmci-side-cart-button-border-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-button-border-color-hover', '#313131', '.merchant-floating-side-mini-cart', '--mrc-fmci-side-cart-button-border-color-hover' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-button-background-color', '#212121', '.merchant-floating-side-mini-cart', '--mrc-fmci-side-cart-button-background-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-button-background-color-hover', '#313131', '.merchant-floating-side-mini-cart', '--mrc-fmci-side-cart-button-background-color-hover' );

		return $css;
	}

	/**
	 * Admin custom CSS.
	 *
	 * @param string $css The custom CSS.
	 * @return string $css The custom CSS.
	 */
	public function admin_custom_css( $css ) {
		$css .= static::get_module_custom_css();

		return $css;
	}
}

// Initialize the module.
add_action( 'init', function () {
	Merchant_Modules::create_module( new Merchant_Floating_Mini_Cart() );
} );
