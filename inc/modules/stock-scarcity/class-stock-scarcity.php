<?php

/**
 * Stock Scarcity
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Stock Scarcity Class.
 *
 */
class Merchant_Stock_Scarcity extends Merchant_Add_Module {

	/**
	 * Module ID.
	 */
	const MODULE_ID = 'stock-scarcity';

	/**
	 * Module path.
	 */
	const MODULE_DIR = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID;


	/**
	 * Module template path.
	 */
	const MODULE_TEMPLATES_PATH = 'modules/' . self::MODULE_ID;

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
			'min_inventory'      => 50,
			'display-pages'      => array( 'product' ),          
			'low_inventory_text' => esc_html__( 'Hurry! Only {stock} units left in stock!', 'merchant' ),
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

			// Admin custom CSS.
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
		if ( ! empty( $settings['low_inventory_text'] ) ) {
			Merchant_Translator::register_string( $settings['low_inventory_text'], esc_html__( 'Stock Scarcity: Text when inventory is low (when only 1 item left in stock)', 'merchant' ) );
		}
		if ( ! empty( $settings['low_inventory_text_plural'] ) ) {
			Merchant_Translator::register_string( $settings['low_inventory_text_plural'], esc_html__( 'Stock Scarcity: Text when inventory is low (when more than 1 item is left in stock)', 'merchant' ) );
		}
		if ( ! empty( $settings['low_inventory_text_simple'] ) ) {
			Merchant_Translator::register_string( $settings['low_inventory_text_simple'], esc_html__( 'Stock Scarcity: Text when inventory is low (variable - used for product variation)', 'merchant' ) );
		}
	}

	/**
	 * Admin enqueue CSS.
	 *
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( parent::is_module_settings_page() ) {
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/stock-scarcity.min.css', array(), MERCHANT_VERSION );
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
		if ( $module === self::MODULE_ID ) {
			ob_start();
			self::admin_preview_content();
			$content = ob_get_clean();

			$preview->set_html( $content );

			$preview->set_text( 'low_inventory_text', '.merchant-stock-scarcity-message', array(
				array(
					'{stock}',
				),
				array(
					'20',
				),
			) );
			$preview->set_css( 'text_font_size', '.merchant-stock-scarcity-message', '--merchant-font-size', 'px' );
			$preview->set_css( 'text_font_weight', '.merchant-stock-scarcity-message', '--merchant-font-weight' );
			$preview->set_css( 'text_text_color', '.merchant-stock-scarcity-message', '--merchant-text-color' );
			$preview->set_css( 'gradient_start', '.merchant-stock-scarcity-progress-bar ', '--merchant-gradient-start' );
			$preview->set_css( 'gradient_end', '.merchant-stock-scarcity-progress-bar ', '--merchant-gradient-end' );
			$preview->set_css( 'progress_bar_bg', '.merchant-stock-scarcity-content', '--merchant-bg-color' );
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
            <div class="mrc-preview-content">
				<?php
				merchant_get_template_part(
					self::MODULE_TEMPLATES_PATH,
					'single-product',
					array(
						'settings'   => $settings,
						'percentage' => 30,
						'stock'      => 20,
					)
				); ?>
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

		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'gradient_start', '#ffc108', '.merchant-stock-scarcity-progress-bar ', '--merchant-gradient-start' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'gradient_end', '#d61313', '.merchant-stock-scarcity-progress-bar ', '--merchant-gradient-end' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'progress_bar_bg', '#e1e1e1', '.merchant-stock-scarcity-content', '--merchant-bg-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'text_font_size', 16, '.merchant-stock-scarcity-message', '--merchant-font-size', 'px' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'text_font_weight', 'normal', '.merchant-stock-scarcity-message', '--merchant-font-weight' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'text_text_color', '#212121', '.merchant-stock-scarcity-message', '--merchant-text-color' );

		return $css;
	}

	/**
	 * Admin custom CSS.
	 *
	 * @param string $css The custom CSS.
	 *
	 * @return string $css The custom CSS.
	 */
	public function admin_custom_css( $css ) {
		$css .= $this->get_module_custom_css();

		return $css;
	}
}

// Initialize the module.
add_action( 'init', function () {
	Merchant_Modules::create_module( new Merchant_Stock_Scarcity() );
} );
