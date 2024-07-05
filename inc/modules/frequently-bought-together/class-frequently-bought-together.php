<?php

/**
 * Frequently Bought Together
 *
 * Module's entry class.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Frequently Bought Together Class.
 */
class Merchant_Frequently_Bought_Together extends Merchant_Add_Module {

	/**
	 * Module ID..
	 */
	const MODULE_ID = 'frequently-bought-together';

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
	 */
	public function __construct() {
		// Module id.
		$this->module_id = self::MODULE_ID;

		// WooCommerce only.
		$this->wc_only = true;

		// Module default settings.
		$this->module_default_settings = array(
			'no_variation_selected_text_has_no_discount' => __( 'Please select an option to see the total price.', 'merchant' ),
			'no_variation_selected_text' => __( 'Please select an option to see your savings.', 'merchant' ),
		);

		// Parent construct.
		parent::__construct();

		// Module section.
		$this->module_section = 'boost-revenue';

		// Module data.
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];

		// Module preview URL
		$this->module_data['preview_url'] = $this->set_module_preview_url( array(
			'type'  => 'product',
			'query' => array(
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key'     => '_merchant_frequently_bought_together_bundles',
						'value'   => '',
						'compare' => '!=',
					),
					array(
						'key'     => '_merchant_frequently_bought_together_bundles',
						'value'   => 'a:0:{}',
						'compare' => '!=',
					),
				),
			),
		) );

		// Module options path.
		$this->module_options_path = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID . '/admin/options.php';

		if ( Merchant_Modules::is_module_active( self::MODULE_ID ) && is_admin() ) {
			// Init translations.
			$this->init_translations();
		}

		// Is module preview page.
		if ( is_admin() && parent::is_module_settings_page() ) {
			self::$is_module_preview = true;

			// Enqueue admin styles.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_css' ) );

			// Admin preview box.
			add_filter( 'merchant_module_preview', array( $this, 'render_admin_preview' ), 10, 2 );
		}
	}

	/**
	 * Init translations.
	 *
	 * @return void
	 */
	public function init_translations() {
		$settings = $this->get_module_settings();
		$strings  = array(
			'title'                                      => 'Frequently bought together: title',
			'price_label'                                => 'Frequently bought together: price label',
			'save_label'                                 => 'Frequently bought together: save label',
			'no_variation_selected_text'                 => 'Frequently bought together: no variation selected text',
			'no_variation_selected_text_has_no_discount' => 'Frequently bought together: no variation selected text (no discount)',
			'button_text'                                => 'Frequently bought together: button text',
		);
		if ( ! empty( $settings['offers'] ) ) {
			foreach ( $settings['offers'] as $offer ) {
				// Register strings.
				foreach ( $strings as $key => $string ) {
					if ( ! empty( $offer['product_single_page'][ $key ] ) ) {
						Merchant_Translator::register_string( $offer['product_single_page'][ $key ], $string . ' - product single page' );
					}
					if ( ! empty( $offer['cart_page'][ $key ] ) ) {
						Merchant_Translator::register_string( $offer['cart_page'][ $key ], $string . ' - cart page' );
					}
				}
			}
		}
	}

	/**
	 * Admin enqueue CSS.
	 *
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( parent::is_module_settings_page() ) {
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/frequently-bought-together.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_script( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/admin/preview.min.js', array( 'jquery' ), MERCHANT_VERSION, true );
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

			$preview->set_html( array( $this, 'admin_preview_content' ), $this->get_module_settings() );
		}

		return $preview;
	}

	/**
	 * Cart item admin preview.
	 *
	 * @return string
	 */
	public function cart_item_preview() {
		?>
		<div class="merchant-cart-offers-container">
			<div class="cart-item-offers">
				<div class="cart-item-offer__container fbt">
					<div class="cart-item-offer">
						<div class="offer-title">Add</div>
						<div class="item-row product-row">
							<div class="column_1">
								<div class="product_image">
									<a href="#" title="Product Name" class="link-do-nothing">
                                        <span class="product-image-placeholder"></span>
									</a>
								</div>
							</div>
							<div class="column_3">
								<div class="product-details">
									<div class="product-name"><a href="#" class="link-do-nothing" title="Product Name">Product Name</a></div>
                                    <div class="offer-discount">
                                        <div class="discount-savings"><span class="label">and save:</span> <strong>£15.50</strong></div>
                                    </div>
								</div>
							</div>
						</div>
					</div>

					<div class="item-footer">
						<div class="add-to-cart">
							<button class="add-to-cart-button" type="button">Claim offer</button>
						</div>
					</div>
				</div>
			</div></div>
		<?php
	}

	/**
	 * Admin preview content.
	 *
	 * @param array $settings
	 *
	 * @return void
	 */
	public function admin_preview_content( $settings ) {
        ?>
		<div class="merchant-single-product-preview">
        <?php
		echo wp_kses( merchant_get_template_part(
			self::MODULE_TEMPLATES_PATH,
			'single-product',
			array(
				'bundles'  => array(
					10 => array(
						array(
							'discount_value'         => 20,
							'product_to_display'     => 97,
							'products'               => array(
								array(
									'id'         => 97,
									'image'      => '<img src="' . MERCHANT_URI
									                . 'assets/images/dummy/Glamifiedpeach.jpeg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="">',
									'title'      => 'Eternal Sunset Collection Lip and Cheek',
									'price_html' => wc_price( 12 ),
									'price'      => 12,
									'permalink'  => '#',
								),
								array(
									'id'         => 96,
									'image'      => '<img src="' . MERCHANT_URI
									                . 'assets/images/dummy/Pearlville.jpeg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="">',
									'title'      => 'Vinopure Pore Purifying Gel Cleanser',
									'price_html' => wc_price( 14 ),
									'price'      => 14,
									'permalink'  => '#',
								),
							),
							'discount_type'          => 'percentage_discount',
							'total_products'         => 3,
							'total_price'            => 47,
							'total_discount'         => 12,
							'total_product_discount' => 4,
							'total_discounted_price' => 35,
						),
					),
				),
				'nonce'    => '',
				'settings' => $settings,
			),
			true
		),
			merchant_kses_allowed_tags() );
		?>
        </div>
        <div class="merchant-cart-preview">
		<?php
		    $this->cart_item_preview();
		?>
        </div>
		<?php
	}

	/**
	 * Custom CSS.
	 *
	 * @return string
	 */
	public function get_module_custom_css() {
		// For backward compatibility, no implementation is needed.

		return '';
	}
}

// Initialize the module.
add_action( 'init', function () {
	Merchant_Modules::create_module( new Merchant_Frequently_Bought_Together() );
} );
