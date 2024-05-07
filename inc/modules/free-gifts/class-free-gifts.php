<?php

/**
 * Free Gifts
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Free Gifts Class.
 *
 */
class Merchant_Free_Gifts extends Merchant_Add_Module {

	/**
	 * Module ID.
	 */
	const MODULE_ID = 'free-gifts';

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
			'total_type'            => 'subtotal',
			'display_homepage'      => 1,
			'display_shop'          => 1,
			'display_product'       => 1,
			'display_cart'          => 1,
			'position'              => 'top_right',
			'distance'              => 250,
			'free_text'             => esc_html__( 'Free', 'merchant' ),
			'cart_title_text'       => esc_html__( 'Free Gift', 'merchant' ),
			'cart_description_text' => esc_html__( 'This item was added as a free gift', 'merchant' ),
		);

		// Mount preview url.
		$preview_url = site_url( '/' );

		if ( function_exists( 'wc_get_page_id' ) ) {
			$preview_url = get_permalink( wc_get_page_id( 'shop' ) );
		}

		// Module data.
		$this->module_data                = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];
		$this->module_data['preview_url'] = $preview_url;

		// Module options path.
		$this->module_options_path = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID . '/admin/options.php';

		// Is module preview page.
		if ( is_admin() && parent::is_module_settings_page() ) {
			self::$is_module_preview = true;

			// Enqueue admin styles.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_css' ) );

			// Enqueue admin scripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_js' ) );

			// Localize Script.
			add_filter( 'merchant_admin_localize_script', array( $this, 'localize_script' ) );

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
		if ( ! empty( $settings['spending_text'] ) ) {
			Merchant_Translator::register_string( $settings['spending_text'], esc_html__( 'Free Gifts: Spending text', 'merchant' ) );
		}
		if ( ! empty( $settings['free_text'] ) ) {
			Merchant_Translator::register_string( $settings['free_text'], esc_html__( 'Free Gifts: Free text', 'merchant' ) );
		}
		if ( ! empty( $settings['cart_title_text'] ) ) {
			Merchant_Translator::register_string( $settings['cart_title_text'], esc_html__( 'Free Gifts: Cart item title text', 'merchant' ) );
		}
		if ( ! empty( $settings['cart_description_text'] ) ) {
			Merchant_Translator::register_string( $settings['cart_description_text'], esc_html__( 'Free Gifts: Cart item description text', 'merchant' ) );
		}
	}

	/**
	 * Admin enqueue CSS.
	 *
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( parent::is_module_settings_page() ) {
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/free-gifts.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
		}
	}

	/**
	 * Admin enqueue scripts.
	 *
	 * @return void
	 */
	public function admin_enqueue_js() {
		if ( parent::is_module_settings_page() ) {
			wp_enqueue_script( "merchant-{$this->module_id}", MERCHANT_URI . "assets/js/modules/{$this->module_id}/admin/preview.min.js", array( 'jquery' ), MERCHANT_VERSION, true );
		}
	}

	/**
	 * Localize Script.
	 */
	public function localize_script( $data ) {
        $data['spending_texts'] = array(
	        'all'        => array(
		        'spending_text_0'       => esc_html__( 'Spend {goalAmount} on any product to receive this gift!', 'merchant' ),
		        'spending_text_1_to_99' => esc_html__( 'Spend {amountMore} on any product to receive this gift!', 'merchant' ),
		        'spending_text_100'     => esc_html__( 'Congratulations! You are eligible to receive a free gift.', 'merchant' ),
	        ),
	        'product'    => array(
		        'spending_text_0'       => esc_html__( 'Spend {goalAmount} on {productName} to receive this free gift!', 'merchant' ),
		        'spending_text_1_to_99' => esc_html__( 'Spend {amountMore} more on {productName} to receive this free gift!', 'merchant' ),
		        'spending_text_100'     => esc_html__( 'Congratulations! You are eligible to receive a free gift.', 'merchant' ),
	        ),
	        'categories' => array(
		        'spending_text_0'       => esc_html__( 'Spend {goalAmount} in the {categories} to receive this free gift!', 'merchant' ),
		        'spending_text_1_to_99' => esc_html__( 'Spend {amountMore} more in the {categories} to receive this free gift!', 'merchant' ),
		        'spending_text_100'     => esc_html__( 'Congratulations! You are eligible to receive a free gift.', 'merchant' ),
	        ),
        );

		$data['gifts_icons'] = array(
            'gifts-icon-1' => Merchant_SVG_Icons::get_svg_icon( 'gifts-icon-1' ),
            'gifts-icon-2' => Merchant_SVG_Icons::get_svg_icon( 'gifts-icon-2' ),
            'gifts-icon-3' => Merchant_SVG_Icons::get_svg_icon( 'gifts-icon-3' ),
            'gifts-icon-4' => Merchant_SVG_Icons::get_svg_icon( 'gifts-icon-4' ),
            'gifts-icon-5' => Merchant_SVG_Icons::get_svg_icon( 'gifts-icon-5' ),
        );

		return $data;
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

			$preview->set_css( 'content_width', '.merchant-free-gifts-widget-offers', '--merchant-content-width', 'px' );
			$preview->set_css( 'content_width', '.merchant-free-gifts-widget', '--merchant-content-width', 'px' );
			$preview->set_css( 'content_bg_color', '.merchant-free-gifts-widget-offers', '--merchant-bg-color' );
			$preview->set_css( 'count_bg_color', '.merchant-free-gifts-widget-count', '--merchant-bg-color' );
			$preview->set_css( 'count_text_color', '.merchant-free-gifts-widget-count', '--merchant-text-color' );
			$preview->set_css( 'button_bg_color', '.merchant-free-gifts-widget-button', '--merchant-bg-color' );
			$preview->set_css( 'button_hover_bg_color', '.merchant-free-gifts-widget-button', '--merchant-hover-bg-color' );
			$preview->set_css( 'button_text_color', '.merchant-free-gifts-widget-button', '--merchant-text-color' );
			$preview->set_css( 'label_bg_color', '.merchant-free-gifts-widget-offer-label', '--merchant-bg-color' );
			$preview->set_css( 'label_text_color', '.merchant-free-gifts-widget-offer-label', '--merchant-text-color' );
			$preview->set_css( 'product_text_color', '.merchant-free-gifts-widget-offer-product-title', '--merchant-text-color' );
			$preview->set_css( 'product_text_hover_color', '.merchant-free-gifts-widget-offer-product-title', '--merchant-text-hover-color' );
			$preview->set_css( 'product_price_text_color', '.merchant-free-gifts-widget-offer-product-price del', '--merchant-text-color' );
			$preview->set_css( 'free_text_color', '.merchant-free-gifts-widget-offer-product-free', '--merchant-text-color' );
			$preview->set_text( 'free_text', '.merchant-free-gifts-widget-offer-product-free' );
			$preview->set_text( 'spending_text', '.merchant-free-gifts-widget-offer-label', array(
				array(
					'{amount}',
				),
				array(
					wc_price( 50 ),
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

        <div class="mrc-preview-single-product-elements">
            <div class="mrc-preview-left-column">
                <div class="mrc-preview-product-image-wrapper">
                    <div class="mrc-preview-product-image"></div>
                </div>
            </div>
            <div class="mrc-preview-right-column">
                <h3 style="margin-top: 0;"><?php echo esc_html__( 'Your Product Name', 'merchant' ); ?></h3>
                <div class="mrc-preview-rating">
                    <div class="star-rating merchant-star-rating-style2" role="img" aria-label="Rated 3.00 out of 5">
                        <span style="width: 80%"></span>
                    </div>
                    <span style="color: #969696;"><?php echo esc_html__( 'reviews', 'merchant' ); ?></span>
                </div>
                <h3><?php echo esc_html__( '$49', 'merchant' ); ?></h3>
                <p><?php echo esc_html__( "An amazing product people can't refuse. What’s the next moment of value-realization when using your product? Tell the biggest use case. Briefly expand your product benefits on how this will help customers.", 'merchant' ); ?></p>

                <div class="merchant-preview-add-to-cart-inner">
                    <div class="merchant-preview-qty">
                        <button><?php echo esc_html( '+' ); ?></button>
                        <input type="text" value="<?php echo esc_attr( '1' ); ?>">
                        <button><?php echo esc_html( '-' ); ?></button>
                    </div>
                    <div class="merchant-preview-add-to-cart"><?php echo esc_html__( 'Add to cart', 'merchant' ); ?></div>
                </div>
                <?php
				echo wp_kses( merchant_get_template_part(
					Merchant_Free_Gifts::MODULE_TEMPLATES_PATH,
					'widget',
					array(
						'settings'   => $settings,
						'offers'     => array(
							0 => array(
								'product' => array(
									'id'         => 97,
									'image'      => '<img src="' . MERCHANT_URI . 'assets/images/dummy/Glamifiedpeach.jpeg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="">',
									'title'      => 'Eternal Sunset Collection Lip and Cheek',
									'price_html' => wc_price( 12 ),
									'price'      => 12,
									'permalink'  => '#',
								),
								'amount'  => 50,
							),
							1 => array(
								'product' => array(
									'id'         => 94,
									'image'      => '<img src="' . MERCHANT_URI . 'assets/images/dummy/Pearlville.jpeg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="">',
									'title'      => 'Vinopure Pore Purifying Gel Cleanser',
									'price_html' => wc_price( 14 ),
									'price'      => 14,
									'permalink'  => '#',
								),
								'amount'  => 40,
							),
						),
						'count'      => 2,
						'cart_total' => 0,
					),
					true
				),
				merchant_kses_allowed_tags( array( 'bdi' ) ) );
				wp_add_inline_script(
                        'merchant-admin-preview',
                        "jQuery('.merchant-free-gifts-widget-button').on('click', function(){ jQuery('#merchant-free-gifts-widget').toggleClass('active') })"
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

		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'count_bg_color', '#000', '.merchant-free-gifts-widget-count', '--merchant-bg-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'count_text_color', '#fff', '.merchant-free-gifts-widget-count', '--merchant-text-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'button_bg_color', '#362e94', '.merchant-free-gifts-widget-button', '--merchant-bg-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'button_hover_bg_color', '#7167e1', '.merchant-free-gifts-widget-button', '--merchant-hover-bg-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'button_text_color', '#fff', '.merchant-free-gifts-widget-button', '--merchant-text-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'content_width', 300, '.merchant-free-gifts-widget-offers', '--merchant-content-width', 'px' );
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'content_width', 300, '.merchant-free-gifts-widget', '--merchant-content-width', 'px' );
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'content_bg_color', '#fff', '.merchant-free-gifts-widget-offers', '--merchant-bg-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'label_bg_color', '#f5f5f5', '.merchant-free-gifts-widget-offer-label', '--merchant-bg-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'label_text_color', '#212121', '.merchant-free-gifts-widget-offer-label', '--merchant-text-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'product_text_color', '#212121', '.merchant-free-gifts-widget-offer-product-title', '--merchant-text-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'product_text_hover_color', '#757575', '.merchant-free-gifts-widget-offer-product-title', '--merchant-text-hover-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'product_price_text_color', '#999999', '.merchant-free-gifts-widget-offer-product-price del', '--merchant-text-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'free_text_color', '#212121', '.merchant-free-gifts-widget-offer-product-free', '--merchant-text-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( $this->module_id, 'distance', 250, '.merchant-free-gifts-widget', '--merchant-free-gifts-distance', 'px' );

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
	Merchant_Modules::create_module( new Merchant_Free_Gifts() );
} );
