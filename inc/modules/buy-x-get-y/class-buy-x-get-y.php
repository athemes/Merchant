<?php

/**
 * Buy X Get Y
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Buy X Get Y Class.
 *
 */
class Merchant_Buy_X_Get_Y extends Merchant_Add_Module {

	/**
	 * Module ID.
	 */
	const MODULE_ID = 'buy-x-get-y';

	/**
	 * Module path.
	 */
	const MODULE_DIR = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID;

	/**
	 * Module template path.
	 */
	const MODULE_TEMPLATES = 'modules/' . self::MODULE_ID;

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
		$this->module_section = 'boost-revenue';

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
		$this->module_options_path = self::MODULE_DIR . "/admin/options.php";

		// Enqueue admin styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );

		// Add preview box
		add_filter( 'merchant_module_preview', array( $this, 'render_admin_preview' ), 10, 2 );

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
		if ( ! empty( $settings['rules'] ) ) {
			foreach ( $settings['rules'] as $rule ) {
				if ( ! empty( $rule['offer-title'] ) ) {
					Merchant_Translator::register_string( $rule['offer-title'], esc_html__( 'Campaign title', 'merchant' ) );
				}
				if ( ! empty( $rule['title'] ) ) {
					Merchant_Translator::register_string( $rule['title'], esc_html__( 'Buy X, Get Y title', 'merchant' ) );
				}
				if ( ! empty( $rule['buy_label'] ) ) {
					Merchant_Translator::register_string( $rule['buy_label'], esc_html__( 'Buy X, Get Y buy label', 'merchant' ) );
				}
				if ( ! empty( $rule['get_label'] ) ) {
					Merchant_Translator::register_string( $rule['get_label'], esc_html__( 'Buy X, Get Y get label', 'merchant' ) );
				}
				if ( ! empty( $rule['button_text'] ) ) {
					Merchant_Translator::register_string( $rule['button_text'], esc_html__( 'Buy X, Get Y button text', 'merchant' ) );
				}
			}
		}
	}

	/**
	 * Enqueue admin page content scripts.
	 *
	 * @return void
	 */
	public
	function enqueue_admin_styles() {
		if ( $this->is_module_settings_page() ) {
			// Module styling.
			wp_enqueue_style(
				'merchant-' . self::MODULE_ID,
				MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/' . self::MODULE_ID . '.min.css',
				array(),
				MERCHANT_VERSION
			);

			// Preview-specific styling.
			wp_enqueue_style(
				'merchant-preview-' . self::MODULE_ID,
				MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css',
				array(),
				MERCHANT_VERSION
			);

			wp_enqueue_script(
				'merchant-preview-' . self::MODULE_ID,
				MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/admin/preview.min.js',
				array(),
				MERCHANT_VERSION,
				true
			);
		}
	}

	/**
	 * Render admin preview
	 *
	 * @param Merchant_Admin_Preview $preview
	 * @param string                 $module
	 *
	 * @return Merchant_Admin_Preview
	 */
	public
	function render_admin_preview(
		$preview,
		$module
	) {
		if ( $module === self::MODULE_ID ) {
			// get 2 simple wc products ids
			$product_ids = wc_get_products(
				array(
					'limit'   => 2,
					'type'    => 'simple',
					'orderby' => 'rand',
					'return'  => 'ids',
				)
			);
			// HTML.
			if ( ! empty( $product_ids ) && 1 < count( $product_ids ) ) {
				$preview->set_html( merchant_get_template_part(
					self::MODULE_TEMPLATES,
					'single-product',
					array(
						'offers'   => array(
							array(
								'rules_to_display'         => 'products',
								'min_quantity'             => 2,
								'product_ids'              => $product_ids[0],
								'quantity'                 => 1,
								'discount'                 => 10,
								'discount_type'            => 'percentage',
								'customer_get_product_ids' => $product_ids[1],
								'total_discount'           => 2.8,
							),
						),
						'nonce'    => '',
						'settings' => Merchant_Admin_Options::get_all( self::MODULE_ID ),
						'product'  => $product_ids[0],
					),
					true
				) );
			} else {
				$preview->set_html( '<p>' . esc_html__( 'No products found, please add some products to render the module preview', 'merchant' ) . '</p>' );
			}
			// Title Text.
			$preview->set_text( 'title', '.merchant-bogo-title' );

			// Buy Label Text ({quantity} gets replaced with a dummy "2" text)
			$preview->set_text( 'buy_label', '.merchant-bogo-product-buy-label', array(
				array(
					'{quantity}',
				),
				array(
					'2',
				),
			) );

			// Get Label Text ({quantity} gets replaced with a dummy "2" text and {discount} gets replaced with dummy "10%" text)
			$preview->set_text( 'get_label', '.merchant-bogo-product-get-label', array(
				array(
					'{quantity}',
					'{discount}',
				),
				array(
					'2',
					'10%',
				),
			) );

			// Button Text
			$preview->set_text( 'button_text', '.merchant-bogo-add-to-cart' );
		}

		return $preview;
	}
}

// Initialize the module.
add_action( 'init', function () {
	Merchant_Modules::create_module( new Merchant_Buy_X_Get_Y() );
} );
