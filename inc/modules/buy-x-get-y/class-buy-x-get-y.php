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
		$strings  = array(
			'offer-title' => 'Buy X Get Y: Campaign title',
			'title'       => 'Buy X Get Y: title',
			'buy_label'   => 'Buy X Get Y: buy label',
			'get_label'   => 'Buy X Get Y: get label',
			'button_text' => 'Buy X Get Y: button text',
		);
		if ( ! empty( $settings['rules'] ) ) {
			foreach ( $settings['rules'] as $rule ) {
				foreach ( $strings as $key => $string ) {
					if ( ! empty( $rule['product_single_page'][ $key ] ) ) {
						Merchant_Translator::register_string( $rule['product_single_page'][ $key ], $string . ' - product single page' );
					}
					if ( ! empty( $rule['cart_page'][ $key ] ) ) {
						Merchant_Translator::register_string( $rule['cart_page'][ $key ], $string . ' - cart page' );
					}
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
	public function render_admin_preview( $preview, $module ) {
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
				$preview_html = '';
				$preview_html .= '<div class="merchant-single-product-preview">';
				$preview_html .= merchant_get_template_part(
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
				);
				$preview_html .= '</div>';
                $preview_html .= $this->cart_preview();
                $preview_html .= $this->checkout_page_preview();
                $preview_html .= $this->thank_you_page_preview();
				$preview->set_html( $preview_html );
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

	/**
     * Cart item admin preview.
     *
	 * @return string
	 */
	public function cart_preview() {
		ob_start();
		?>
		<div class="merchant-cart-preview">
            <div class="my-cart">
                <div class="cart-title"><?php esc_html_e( 'My Cart', 'merchant' ); ?></div>
                <table class="cart-table">
                    <tr>
                        <th class="product-col"><?php esc_html_e( 'PRODUCT', 'merchant' ); ?></th>
                        <th class="price-col"><?php esc_html_e( 'PRICE', 'merchant' ); ?></th>
                        <th class="quantity-col"><?php esc_html_e( 'QUANTITY', 'merchant' ); ?></th>
                        <th class="total-col"><?php esc_html_e( 'TOTAL', 'merchant' ); ?></th>
                    </tr>
                    <tr class="cart-item">
                        <td class="product-column">
                            <div class="product">
                                <div class="product-image"></div>
                                <div class="product-info">
                                    <div class="product-name"><?php esc_html_e( 'Your Product Name', 'merchant' ); ?></div>
                                    <p class="upsell-offer"><?php esc_html_e( 'You are eligible to get {offer_quantity}', 'merchant' ); ?></p>
                                    <div class="upsell-product">
                                        <div class="upsell-image"></div>
                                        <div class="upsell-info">
                                            <div class="upsell-name"><?php esc_html_e( 'Product Name', 'merchant' ); ?></div>
                                            <p><?php esc_html_e( 'with {discount} off', 'merchant' ); ?></p>
                                            <button class="add-to-cart"><?php esc_html_e( 'Add To Cart', 'merchant' ); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="price-col">
                            <span class="original-price"><?php echo wp_kses(wc_price(16), merchant_kses_allowed_tags(array( 'bdi' )))?></span>
                            <span class="discounted-price"><?php echo wp_kses(wc_price(12), merchant_kses_allowed_tags(array( 'bdi' )))?></span>
                        </td>
                        <td class="quantity-col">
                            <div class="quantity-control">
                                <button class="decrease">-</button>
                                <input type="text" value="1" min="1">
                                <button class="increase">+</button>
                            </div>
                        </td>
                        <td class="total-col"><?php echo wp_kses(wc_price(300), merchant_kses_allowed_tags(array( 'bdi' )))?></td>
                    </tr>
                </table>
            </div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Checkout page preview.
	 *
	 * @return string
	 */
	public function checkout_page_preview() {
		ob_start();
		?>
        <div class="merchant-checkout-preview">
            <div class="order-received">
                <div class="page-title"><?php esc_html_e('Checkout','merchant'); ?></div>
                <br>
                <div class="upsell-offer">
                    <div class="offer-title"><?php esc_html_e('Last chance to get {offer_quantity} x','merchant'); ?></div>
                    <div class="product-details">
                        <div class="product-image"></div>
                        <div class="product-info">
                            <div class="product-name"><?php esc_html_e('Your Product Name','merchant'); ?></div>
                            <p><?php esc_html_e('with {discount} off','merchant'); ?></p>
                            <button class="add-to-order"><?php esc_html_e('Add To My Order','merchant'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}

    /**
     * Thank you page preview.
     *
     * @return string
     */
	public function thank_you_page_preview() {
		ob_start();
		?>
        <div class="merchant-thank-you-preview">
            <div class="order-received">
                <div class="page-title"><?php esc_html_e('Order Received','merchant'); ?></div>
                <p><?php esc_html_e('Thank you. Your order has been received.','merchant'); ?></p>
                <div class="order-details">
                    <div class="order-info">
                        <div class="item-title"><?php esc_html_e('ORDER NUMBER:','merchant'); ?></div>
                        <p>550</p>
                    </div>
                    <div class="order-info">
                        <div class="item-title"><?php esc_html_e('PAYMENT METHOD:','merchant'); ?></div>
                        <p><?php echo esc_html( merchant_get_first_active_payment_gateway_label() ?? 'Apple Pay' ) ?></p>
                    </div>
                </div>
                <div class="upsell-offer">
                    <div class="offer-title"><?php esc_html_e('Last chance to get {offer_quantity} x','merchant'); ?></div>
                    <div class="product-details">
                        <div class="product-image"></div>
                        <div class="product-info">
                            <div class="product-name"><?php esc_html_e('Your Product Name','merchant'); ?></div>
                            <p><?php esc_html_e('with {discount} off','merchant'); ?></p>
                            <button class="add-to-order"><?php esc_html_e('Add To My Order','merchant'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}
}

// Initialize the module.
add_action( 'init', function () {
	Merchant_Modules::create_module( new Merchant_Buy_X_Get_Y() );
} );
