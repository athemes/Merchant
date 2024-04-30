<?php

/**
 * Recently Viewed Products
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Recently viewed products class.
 * 
 */
class Merchant_Recently_Viewed_Products extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'recently-viewed-products';

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
			'title'          => __( 'Recently Viewed', 'merchant' ),
			'title_tag'      => 'h2',
			'hide_title'     => 0,
			'slider'         => 0,
			'slider_nav'     => 'on-hover',
			'posts_per_page' => 10,
			'columns'        => 3,
			'columns_gap'    => 15,
			'orderby'        => 'none',
			'order'          => 'desc',
			'hook_order'     => 20,
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
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];
		$this->module_data[ 'preview_url' ] = $preview_url;

		// Module options path.
		$this->module_options_path = MERCHANT_DIR . 'inc/modules/recently-viewed-products/admin/options.php';

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
	}

	/**
	 * Init translations.
	 *
	 * @return void
	 */
	public function init_translations() {
		$settings = $this->get_module_settings();
		if ( ! empty( $settings['title'] ) ) {
			Merchant_Translator::register_string( $settings['title'], esc_html__( 'Recently viewed products: title', 'merchant' ) );
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
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/recently-viewed-products.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );

			wp_enqueue_style( 'merchant-grid', MERCHANT_URI . 'assets/css/grid.min.css', array(), MERCHANT_VERSION, 'all' );
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
			$this->admin_preview_content();
			$content = ob_get_clean();

			// HTML.
			$preview->set_html( $content );

			// Title.
			$preview->set_text( 'title', '.merchant-recently-viewed-products-section .section-title' );

			// Hide Title.
			$preview->set_class( 'hide_title', '.merchant-recently-viewed-products-section', array(), 'hide-title' );           

			// Slider Style.
			$preview->set_class( 'slider', '.merchant-recently-viewed-products-section', array(), 'slider-style-preview' ); 

			// Slider Navigation.
			$preview->set_class( 'slider_nav', '.merchant-recently-viewed-products-section', array( 'always-show', 'on-hover' ) );  

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

		<section class="merchant-recently-viewed-products-section<?php echo ( $settings[ 'slider' ] ) ? ' slider-style-preview' : ''; ?><?php echo ( 'always-show' === $settings[ 'slider_nav' ] ) ? ' always-show' : ' on-hover'; ?>">
			<h3 class="section-title"><?php echo esc_html( $settings[ 'title' ] ); ?></h3>
			<div class="merchant-recently-viewed-products merchant-carousel">
				<ul class="products columns-3">
					<li class="product">
						<div class="image-wrapper"></div>
						<div class="product-summary">
							<h3><?php echo esc_html__( 'Product Title', 'merchant' ); ?></h3>
							<p><?php echo esc_html__( 'Product description normally goes here.', 'merchant' ); ?></p>
						</div>
					</li>
					<li class="product">
						<div class="image-wrapper"></div>
						<div class="product-summary">
							<h3><?php echo esc_html__( 'Product Title', 'merchant' ); ?></h3>
							<p><?php echo esc_html__( 'Product description normally goes here.', 'merchant' ); ?></p>
						</div>
					</li>
					<li class="product">
						<div class="image-wrapper"></div>
						<div class="product-summary">
							<h3><?php echo esc_html__( 'Product Title', 'merchant' ); ?></h3>
							<p><?php echo esc_html__( 'Product description normally goes here.', 'merchant' ); ?></p>
						</div>
					</li>
				</ul>

				<div class="slider-navigation">
					<div class="merchant-carousel-nav-prev nav-prev">
						<svg width="18" height="18" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="stroke-based"><path d="M8.5 1.33301L1.83333 7.99967L8.5 14.6663" stroke="#242021" stroke-width="1.5"></path></svg>
					</div>
					<div class="merchant-carousel-nav-next nav-next">
						<svg width="18" height="18" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="stroke-based"><path d="M1.5 14.667L8.16667 8.00033L1.5 1.33366" stroke="#242021" stroke-width="1.5"></path></svg>
					</div>
				</div>
			</div>
		</section>

		<?php
	}

	/**
	 * Get module custom CSS.
	 * 
	 * @return string The module custom CSS.
	 */
	public function get_module_custom_css() {
		$css = '';

		// Columns gap.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'columns_gap', 15, '.merchant-recently-viewed-products', '--mrc-rvp-columns-gap', 'px' );

		/**
		 * Colors
		 * 
		 */

		// Title color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'title_color', '#212121', '.merchant-recently-viewed-products-section .section-title', '--mrc-rvp-section-title-color' );

		// Navigation icon color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'navigation_icon_color', '#FFF', '.merchant-recently-viewed-products-section .merchant-carousel', '--mrc-carousel-nav-icon-color' );

		// Navigation color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'navigation_color', '#212121', '.merchant-recently-viewed-products-section .merchant-carousel', '--mrc-carousel-nav-color' );

		// Navigation color (hover).
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'navigation_color_hover', '#757575', '.merchant-recently-viewed-products-section .merchant-carousel', '--mrc-carousel-nav-color-hover' );

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
	Merchant_Modules::create_module(new Merchant_Recently_Viewed_Products());
} );
