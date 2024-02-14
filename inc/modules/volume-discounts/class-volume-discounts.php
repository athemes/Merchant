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
 * Volume discounts class.
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
			'buy_text'                 => esc_html__( 'Buy {amount}, get {discount} off each', 'merchant' ),
			'item_text'                => esc_html__( 'Per item:', 'merchant' ),
			'total_text'               => esc_html__( 'Total price:', 'merchant' ),
			'cart_title_text'          => esc_html__( 'Discount', 'merchant' ),
			'cart_description_text'    => esc_html__( 'A discount of {amount} has been applied.', 'merchant' ),
		);

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
						'key'     => '_merchant_volume_discounts',
						'value'   => '',
						'compare' => '!=',
					),
					array(
						'key'     => '_merchant_volume_discounts',
						'value'   => 'a:0:{}',
						'compare' => '!=',
					),
				),
			),
		) );

		// Module options path.
		$this->module_options_path = self::MODULE_DIR . '/admin/options.php';

		// Is module preview page.
		if ( is_admin() && parent::is_module_settings_page() ) {
			self::$is_module_preview = true;

			// Enqueue admin scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_css' ) );

			// Add preview box
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
		if ( isset( $settings['offers'] ) && ! empty( $settings['offers'] ) ) {
			foreach ( $settings['offers'] as $offer ) {
				if ( ! empty( $offer['table_title'] ) ) {
					Merchant_Translator::register_string( $offer['table_title'], esc_html__( 'Bulk discount: title', 'merchant' ) );
				}
				if ( ! empty( $offer['save_label'] ) ) {
					Merchant_Translator::register_string( $offer['save_label'], esc_html__( 'Bulk discount: save label', 'merchant' ) );
				}
				if ( ! empty( $offer['buy_text'] ) ) {
					Merchant_Translator::register_string( $offer['buy_text'], esc_html__( 'Bulk discount: buy text', 'merchant' ) );
				}
				if ( ! empty( $offer['item_text'] ) ) {
					Merchant_Translator::register_string( $offer['item_text'], esc_html__( 'Bulk discount: item text', 'merchant' ) );
				}
				if ( ! empty( $offer['total_text'] ) ) {
					Merchant_Translator::register_string( $offer['total_text'], esc_html__( 'Bulk discount: total text', 'merchant' ) );
				}
				if ( ! empty( $offer['cart_title_text'] ) ) {
					Merchant_Translator::register_string( $offer['cart_title_text'], esc_html__( 'Bulk discount: cart item discount title', 'merchant' ) );
				}
				if ( ! empty( $offer['cart_description_text'] ) ) {
					Merchant_Translator::register_string( $offer['cart_description_text'], esc_html__( 'Bulk discount: Cart item discount description', 'merchant' ) );
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

			// Item Text
			$preview->set_text( 'item_text', '.merchant-volume-discounts-item-text' );

			// Total Text
			$preview->set_text( 'total_text', '.merchant-volume-discounts-total-text' );

			// Title Font Size
			$preview->set_css( 'title_font_size', '.merchant-volume-discounts-title', '--merchant-font-size', 'px' );

			// Title Font Weight
			$preview->set_css( 'title_font_weight', '.merchant-volume-discounts-title', '--merchant-font-weight' );

			// Title Text Color
			$preview->set_css( 'title_text_color', '.merchant-volume-discounts-title', '--merchant-text-color' );

			// Table Item Background Color
			$preview->set_css( 'table_item_bg_color', '.merchant-volume-discounts-item', '--merchant-table-item-bg-color' );

			// Table Item Text Color
			$preview->set_css( 'table_item_text_color', '.merchant-volume-discounts-item', '--merchant-table-item-text-color' );

			// Table Item Border Color
			$preview->set_css( 'table_item_border_color', '.merchant-volume-discounts-item', '--merchant-table-item-border-color' );

			// Table Label Background Color
			$preview->set_css( 'table_label_bg_color', '.merchant-volume-discounts-item-label > span', '--merchant-table-label-bg-color' );

			// Table label Text Color
			$preview->set_css( 'table_label_text_color', '.merchant-volume-discounts-item-label > span', '--merchant-table-label-text-color' );
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
				<?php
				merchant_get_template_part(
					Merchant_Volume_Discounts::MODULE_TEMPLATES_PATH,
					'single-product',
					array(
						'settings'       => $settings,
						'discount_tiers' => array(
							array(
								'quantity'      => 10,
								'discount'      => 5,
								'discount_type' => 'percentage_discount',
								'save_label'    => esc_html__( 'Save {amount}', 'merchant' ),
								'item_text'     => esc_html__( 'Per item:', 'merchant' ),
								'total_text'    => esc_html__( 'Total price:', 'merchant' ),
								'buy_text'      => esc_html__( 'Buy {amount}, get {discount} off each', 'merchant' ),
								'table_title'   => esc_html__( 'Buy more, save more!', 'merchant' ),
							),
						),
						'product_price'  => 20,
					)
				);
				?>
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

		// Title Font Size
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'title_font_size', 16, '.merchant-volume-discounts-title', '--merchant-font-size', 'px' );

		// Title Font Weight
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'title_font_weight', 'normal', '.merchant-volume-discounts-title', '--merchant-font-weight' );

		// Title Text Color
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'title_text_color', '#212121', '.merchant-volume-discounts-title', '--merchant-text-color' );

		// Table Item Background Color
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'table_item_bg_color', '#fcf0f1', '.merchant-volume-discounts-item', '--merchant-table-item-bg-color' );

		// Table Item Border Color
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'table_item_border_color', '#d83b3b', '.merchant-volume-discounts-item', '--merchant-table-item-border-color' );

		// Table Item Text Color
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'table_item_text_color', 'inherit', '.merchant-volume-discounts-item', '--merchant-table-item-text-color' );

		// Table Label Background Color
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'table_label_bg_color', '#d83b3b', '.merchant-volume-discounts-item-label > span', '--merchant-table-label-bg-color' );

		// Table Label Text Color
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'table_label_text_color', '#ffffff', '.merchant-volume-discounts-item-label > span', '--merchant-table-label-text-color' );

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
	Merchant_Modules::create_module( new Merchant_Volume_Discounts() );
} );
