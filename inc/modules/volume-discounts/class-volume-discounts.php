<?php

/**
 * Volume discounts
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Bulk discounts module class.
 *
 */
class Merchant_Volume_Discounts extends Merchant_Add_Module {

	/**
	 * Module ID.
	 */
	const MODULE_ID = 'volume-discounts';

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
	 * Set the module as having analytics.
	 *
	 * @var bool
	 */
	protected $has_analytics = true;

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

		// Module default settings.
		$this->module_default_settings = array(
			'title'                    => __( 'Discount', 'merchant' ),
			'single_product_placement' => 'before-cart-form',
			'table_title'              => __( 'Buy more, save more!', 'merchant' ),
			'save_label'               => esc_html__( 'Save {amount}', 'merchant' ),
			'buy_text'                 => esc_html__( 'Buy {quantity}, get {discount} off each', 'merchant' ),
			'item_text'                => esc_html__( 'Per item:', 'merchant' ),
			'total_text'               => esc_html__( 'Total price:', 'merchant' ),
			'cart_title_text'          => esc_html__( 'Discount', 'merchant' ),
			'cart_description_text'    => esc_html__( 'A discount of {amount} has been applied.', 'merchant' ),
		);

		// Module data.
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];

		// Module options path.
		$this->module_options_path = self::MODULE_DIR . '/admin/options.php';

		// Is module preview page.
		if ( is_admin() && parent::is_module_settings_page() ) {
			self::$is_module_preview = true;

			// Enqueue admin scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_css' ) );

			// Add preview box
			add_filter( 'merchant_module_preview', array( $this, 'render_admin_preview' ), 10, 2 );
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
		$strings  = array(
			'table_title'           => 'Bulk discount: title',
			'save_label'            => 'Bulk discount: save label',
			'buy_text'              => 'Bulk discount: buy text',
			'item_text'             => 'Bulk discount: item text',
			'total_text'            => 'Bulk discount: total text',
			'cart_title_text'       => 'Bulk discount: cart item discount title',
			'cart_description_text' => 'Bulk discount: Cart item discount description',
		);
		if ( isset( $settings['offers'] ) && ! empty( $settings['offers'] ) ) {
			foreach ( $settings['offers'] as $offer ) {
				foreach ( $strings as $key => $string ) {
					if ( ! empty( $offer['product_single_page'][ $key ] ) ) {
						Merchant_Translator::register_string( $offer['product_single_page'][ $key ], $string . ' - product single page' );
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
	public function admin_enqueue_css() {
		if ( $this->is_module_settings_page() ) {
			wp_enqueue_style( "merchant-admin-{$this->module_id}", MERCHANT_URI . "assets/css/modules/volume-discounts/admin/preview.min.css", array(), MERCHANT_VERSION );
			wp_enqueue_style( "merchant-{$this->module_id}", MERCHANT_URI . "assets/css/modules/{$this->module_id}/{$this->module_id}.min.css", array(), MERCHANT_VERSION );
			wp_enqueue_script( "merchant-{$this->module_id}", MERCHANT_URI . "assets/js/modules/{$this->module_id}/admin/preview.min.js", array( 'jquery' ), MERCHANT_VERSION,
				true );
            wp_localize_script( "merchant-{$this->module_id}", 'merchant_volume_discounts', array(
                'mock_item_price' => wc_format_sale_price( 20, 16 ),
            ) );
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
			ob_start();
			self::admin_preview_content();
			$content = ob_get_clean();

			// HTML
			$preview->set_html( $content );

			// Table Title
			$preview->set_text( 'table_title', '.merchant-volume-discounts-title' );

			// Save Label
			$preview->set_text( 'save_label', '.merchant-volume-discounts-save-label', array(
				array(
					'{amount}',
				),
				array(
					wc_price( 10 ),
				),
			) );

			// Buy Text
			$preview->set_text( 'buy_text', '.merchant-volume-discounts-buy-label', array(
				array(
					'{amount}',
					'{discount}',
				),
				array(
					'<strong>10</strong>',
					'<strong>' . wc_price( 2 ) . '</strong>',
				),
			) );
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
        <div class="merchant-single-product-preview">
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
                <div class="mrc-preview-right-column" data-currency="<?php
				echo esc_attr( get_woocommerce_currency_symbol() ); ?>">
					<?php
					merchant_get_template_part(
						Merchant_Volume_Discounts::MODULE_TEMPLATES_PATH,
						'admin-preview',
						array(
							'settings'       => $settings,
							'discount_tiers' => array(
								array(
									'quantity'            => 10,
									'discount'            => 5,
									'discount_type'       => 'percentage_discount',
									'product_single_page' => array(
										'save_label'  => esc_html__( 'Save {amount}', 'merchant' ),
										'item_text'   => esc_html__( 'Per item:', 'merchant' ),
										'total_text'  => esc_html__( 'Total price:', 'merchant' ),
										'buy_text'    => esc_html__( 'Buy {quantity}, get {discount} off each', 'merchant' ),
										'table_title' => esc_html__( 'Buy more, save more!', 'merchant' ),
									),
								),
							),
							'product_price'  => 20,
						)
					);

					merchant_get_template_part(
						Merchant_Volume_Discounts::MODULE_TEMPLATES_PATH,
						'admin-preview-tiered-radio',
						array(
							'settings'       => $settings,
							'discount_tiers' => array(
								array(
									'quantity'            => 10,
									'discount'            => 5,
									'discount_type'       => 'percentage_discount',
									'product_single_page' => array(
										'save_label'  => esc_html__( 'Save {amount}', 'merchant' ),
										'item_text'   => esc_html__( 'Per item:', 'merchant' ),
										'total_text'  => esc_html__( 'Total price:', 'merchant' ),
										'buy_text'    => esc_html__( 'Buy {quantity}, get {discount} off each', 'merchant' ),
										'table_title' => esc_html__( 'Buy more, save more!', 'merchant' ),
									),
								),
                                array(
									'quantity'            => 10,
									'discount'            => 5,
									'discount_type'       => 'percentage_discount',
									'product_single_page' => array(
										'save_label'  => esc_html__( 'Save {amount}', 'merchant' ),
										'item_text'   => esc_html__( 'Per item:', 'merchant' ),
										'total_text'  => esc_html__( 'Total price:', 'merchant' ),
										'buy_text'    => esc_html__( 'Buy {quantity}, get {discount} off each', 'merchant' ),
										'table_title' => null,
									),
								),
							),
							'product_price'  => 20,
						)
					);
					?>
                </div>
            </div>
        </div>
		<?php
		$this->cart_item_preview();
        $this->checkout_page_preview();
        $this->thank_you_page_preview();
	}

	/**
	 * Checkout page preview.
	 *
	 * @return string
	 */
	public function checkout_page_preview() {
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
	}

	/**
	 * Thank you page preview.
	 *
	 * @return string
	 */
	public function thank_you_page_preview() {
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
	}

	/**
	 * Cart item admin preview.
	 *
	 * @return void
	 */
	public function cart_item_preview() {
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
                                    <p class="upsell-offer"><?php esc_html_e( 'Buy 3 Get 3 with 20% off', 'merchant' ); ?></p>
                                    <div class="upsell-product">
                                        <div class="upsell-info">
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
	}

	/**
	 * Add this function for backward compatibility purpose.
	 *
	 * @return void
	 */
	public function get_module_custom_css() {
		// No implementation needed.
		return;
	}
}


// Initialize the module.
add_action( 'init', function () {
	Merchant_Modules::create_module( new Merchant_Volume_Discounts() );
} );
