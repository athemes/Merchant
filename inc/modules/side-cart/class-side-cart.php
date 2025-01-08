<?php

/**
 * Side Cart
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Side Cart Class.
 *
 */
class Merchant_Side_Cart extends Merchant_Add_Module {

	/**
	 * Module ID.
	 */
	const MODULE_ID = 'side-cart';

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

		$this->migrate_floating_mini_cart_to_side_cart(); // Run on both BE & FE

		// Module default settings.
		$this->module_default_settings = array(
			'show_after_add_to_cart'                => 1,
			'show_after_add_to_cart_single_product' => 0,
			'show_on_cart_url_click'                => 1,
			'enable-floating-cart'                  => false,
			'show_view_cart_btn'                    => true,
			'show_checkout_btn'                     => true,
		);

		// Module data.
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];

		// Module section.
		$this->module_section = $this->module_data['section'];

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

			// Enqueue admin scripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

			// Admin preview box.
			add_filter( 'merchant_module_preview', array( $this, 'render_admin_preview' ), 10, 2 );

			// Custom CSS.
			add_filter( 'merchant_custom_css', array( $this, 'admin_custom_css' ) );

            add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );
		}
	}

	public function init_translations() {
		$settings = $this->get_module_settings();
		if ( ! empty( $settings['checkout_btn_text'] ) ) {
			Merchant_Translator::register_string( $settings['checkout_btn_text'], esc_html__( 'Side cart checkout button text', 'merchant' ) );
		}
		if ( ! empty( $settings['view_cart_btn_text'] ) ) {
			Merchant_Translator::register_string( $settings['view_cart_btn_text'], esc_html__( 'Side cart view cart button text', 'merchant' ) );
		}
		if ( ! empty( $settings['upsells_title'] ) ) {
			Merchant_Translator::register_string( $settings['upsells_title'], esc_html__( 'Side cart upsells title', 'merchant' ) );
		}
		if ( ! empty( $settings['upsells_add_to_cart_text'] ) ) {
			Merchant_Translator::register_string( $settings['upsells_add_to_cart_text'], esc_html__( 'Side cart upsells add to cart text', 'merchant' ) );
		}
	}

	/**
	 * Admin enqueue CSS.
	 *
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( $this->is_module_settings_page() ) {
			wp_enqueue_style(
                'merchant-' . self::MODULE_ID,
                MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/side-cart.min.css',
                array(),
                MERCHANT_VERSION
            );

			wp_enqueue_style(
                'merchant-admin-preview-' . self::MODULE_ID,
                MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css',
                array(),
                MERCHANT_VERSION
			);
		}
	}

	/**
	 * Admin Enqueue scripts.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_script(
            'merchant-' . self::MODULE_ID,
            MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/side-cart.min.js',
            array(),
            MERCHANT_VERSION,
            true
        );

		wp_enqueue_script(
            'merchant-preview-' . self::MODULE_ID,
            MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/admin/preview.min.js',
            array(),
            MERCHANT_VERSION,
            true
        );

		wp_localize_script( 'merchant-preview-' . self::MODULE_ID, 'merchant_side_cart_params', array(
			'keywords' => array(
				'multi_categories'     => esc_html__( 'Multi Categories', 'merchant' ),
				'category_trigger'     => esc_html__( 'Category Trigger:', 'merchant' ),
				'no_cats_selected'     => esc_html__( 'No Categories Selected', 'merchant' ),
				'no_products_selected' => esc_html__( 'No Products Selected', 'merchant' ),
				'multi_products'       => esc_html__( 'Multi Products', 'merchant' ),
				'all_products'         => esc_html__( 'All Products', 'merchant' ),
			),
		) );
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

			// HTML.
			$preview->set_html( $content );

            // View Cart button
            $preview->set_text( 'view_cart_btn_text', '.merchant-side-cart-view-cart-btn' );

            // Checkout button
            $preview->set_text( 'checkout_btn_text', '.merchant-side-cart-checkout-btn' );

            // Icon visibility
			$preview->set_class( 'enable-floating-cart', '.merchant-side-cart-floating-cart', array(), 'merchant-show' );

            // Icon.
			$preview->set_svg_icon( 'icon', '.merchant-side-cart-floating-cart-icon' );

			// Position.
			$preview->set_class( 'icon-position', '.merchant-side-cart-floating-cart', array( 'merchant-side-cart-floating-cart-position-left', 'merchant-side-cart-floating-cart-position-right' ) );

            $preview->set_class( 'show_view_cart_btn', '.merchant-side-cart-view-cart-btn', array(), 'show-btn' );
            $preview->set_class( 'show_checkout_btn', '.merchant-side-cart-checkout-btn', array(), 'show-btn' );
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

        <?php
		$icon_classes  = 'merchant-side-cart-floating-cart merchant-side-cart-floating-cart-position-' . ( $settings['icon-position'] ?? 'right' ) ;
		$icon_classes .= ! empty( $settings['enable-floating-cart'] ) ? ' merchant-show' :  '';
		$icon_classes .= ' js-merchant-side-cart-toggle-handler';
        ?>
        <a href="#" class="<?php echo esc_attr( $icon_classes ); ?>">
            <span class="merchant-side-cart-floating-cart-counter"><?php echo esc_attr( 2 ); ?></span>
            <i class="merchant-side-cart-floating-cart-icon"><?php echo wp_kses( Merchant_SVG_Icons::get_svg_icon( $settings['icon'] ?? 'cart-icon-1' ), merchant_kses_allowed_tags( array(), false ) ); ?></i>
        </a>

        <div class="merchant-side-cart-wrapper">
            <div class="merchant-side-cart-overlay"></div>
            <div class="merchant-side-cart <?php echo esc_attr( 'slide-' . ( $settings['slide_direction'] ?? 'right' ) ); ?>">
                <div class="merchant-side-cart-body">
                    <a href="#" class="merchant-side-cart-close-button js-merchant-side-cart-toggle-handler"
                        title="<?php
						echo esc_attr__( 'Close the side mini cart', 'merchant' ); ?>">
						<?php
						echo wp_kses( Merchant_SVG_Icons::get_svg_icon( 'icon-cancel' ), merchant_kses_allowed_tags( array(), false ) ); ?>
                    </a>

                    <div class="merchant-side-cart-widget">
                        <div class="merchant-side-cart-widget-title"><?php
							echo esc_html__( 'Your Cart', 'merchant' ); ?></div>
                        <div class="widget_shopping_cart_content">
                            <ul class="woocommerce-mini-cart cart_list product_list_widget">
                                <li class="woocommerce-mini-cart-item mini_cart_item">
                                    <a href="#" class="remove remove_from_cart_button">×</a>
                                    <a href="#">
                                        <span class="mrc-product-image"></span>
										<?php
										echo esc_html__( 'Product Sample Title', 'merchant' ); ?>
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
										<?php
										echo esc_html__( 'Product Sample Title', 'merchant' ); ?>
                                    </a>
                                    <span class="quantity">1 ×
										<span class="woocommerce-Price-amount amount">
											<bdi><span class="woocommerce-Price-currencySymbol">$</span>12.00 </bdi>
										</span>
									</span>
                                </li>
                            </ul>

                            <p class="woocommerce-mini-cart__total total">
                                <strong><?php
									echo esc_html__( 'Subtotal:', 'merchant' ); ?></strong>
                                <span class="woocommerce-Price-amount amount">
									<bdi><span class="woocommerce-Price-currencySymbol">$</span>12.00 </bdi>
								</span>
                            </p>
                            <p class="woocommerce-mini-cart__buttons buttons">
                                <a href="#" class="button wc-forward merchant-side-cart-view-cart-btn<?php echo esc_attr( ! empty( $settings['show_view_cart_btn'] ) ) ? ' show-btn' : ''; ?>"><?php echo esc_html( $settings['view_cart_btn_text'] ?? 'View Cart' ); ?></a>
                                <a href="#" class="button checkout wc-forward merchant-side-cart-checkout-btn<?php echo esc_attr( ! empty( $settings['show_checkout_btn'] ) ) ? ' show-btn' : ''; ?>"><?php echo esc_html( $settings['checkout_btn_text'] ?? 'Checkout' ); ?></a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}

	/**
     * Add class to body in admin.
     *
	 * @param $classes
	 *
	 * @return string
	 */
	public function admin_body_class( $classes ) {
		$classes .= ' merchant-side-cart-show';

		return $classes;
	}

	/**
	 * Admin custom CSS.
	 *
	 * @param string $css The custom CSS.
	 *
	 * @return string $css The custom CSS.
	 */
	public function admin_custom_css( $css ) {
		$css .= self::get_module_custom_css();

		return $css;
	}

	/**
	 * Custom CSS.
	 *
	 * @return string
	 */
	public static function get_module_custom_css() {
		$css = '';

		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'icon-size', 25, '.merchant-side-cart-floating-cart', '--mrc-fmci-icon-size', 'px' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'icon-corner-offset', 30, '.merchant-side-cart-floating-cart', '--mrc-fmci-corner-offset', 'px' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'icon-position', 'right', '.merchant-side-cart-floating-cart', '--mrc-fmci-icon-position' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'icon-border-radius', 35, '.merchant-side-cart-floating-cart-icon', '--mrc-fmci-border-radius', 'px' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'icon-color', '#ffffff', '.merchant-side-cart-floating-cart-icon', '--mrc-fmci-icon-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'icon-background-color', '#212121', '.merchant-side-cart-floating-cart-icon', '--mrc-fmci-background-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'icon-counter-color', '#ffffff', '.merchant-side-cart-floating-cart-counter', '--mrc-fmci-counter-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'icon-counter-background-color', '#757575', '.merchant-side-cart-floating-cart-counter', '--mrc-fmci-counter-background-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-width', 380, '.merchant-side-cart', '--mrc-fmci-side-cart-width', 'px' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-title-color', '#212121', '.merchant-side-cart', '--mrc-fmci-side-cart-title-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-title-icon-color', '#212121', '.merchant-side-cart', '--mrc-fmci-side-cart-title-icon-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-title-background-color', '#cccccc', '.merchant-side-cart', '--mrc-fmci-side-cart-title-background-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-content-text-color', '#212121', '.merchant-side-cart', '--mrc-fmci-side-cart-content-text-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-content-background-color', '#ffffff', '.merchant-side-cart', '--mrc-fmci-side-cart-content-background-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-content-remove-color', '#ffffff', '.merchant-side-cart', '--mrc-fmci-side-cart-content-remove-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-content-remove-background-color', '#212121', '.merchant-side-cart', '--mrc-fmci-side-cart-content-remove-background-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-total-text-color', '#212121', '.merchant-side-cart', '--mrc-fmci-side-cart-total-text-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-total-background-color', '#f5f5f5', '.merchant-side-cart', '--mrc-fmci-side-cart-total-background-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-button-color', '#ffffff', '.merchant-side-cart', '--mrc-fmci-side-cart-button-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-button-color-hover', '#ffffff', '.merchant-side-cart', '--mrc-fmci-side-cart-button-color-hover' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-button-border-color', '#212121', '.merchant-side-cart', '--mrc-fmci-side-cart-button-border-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-button-border-color-hover', '#313131', '.merchant-side-cart', '--mrc-fmci-side-cart-button-border-color-hover' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-button-background-color', '#212121', '.merchant-side-cart', '--mrc-fmci-side-cart-button-background-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'side-cart-button-background-color-hover', '#313131', '.merchant-side-cart', '--mrc-fmci-side-cart-button-background-color-hover' );

		return $css;
	}

	/**
	 * Merge Some Floating Mini Cart Settings to Side Cart
	 *
	 * @return void
	 */
	private function migrate_floating_mini_cart_to_side_cart() {
        if ( get_option( 'merchant_floating_mini_cart_merged_with_side_cart', false ) || ! method_exists( 'Merchant_Admin_Options', 'set' ) ) {
			return;
		}

        // Floating Mini Cart
		$fmc_module_id  = 'floating-mini-cart';
		$is_fmc_enabled = Merchant_Modules::is_module_active( $fmc_module_id );
		if ( $is_fmc_enabled ) {
		    $fmc_settings = Merchant_Admin_Options::get_all( $fmc_module_id );

            // Map Side cart options data by Floating Mini Cart options.
			$side_cart_options = array(
				'enable-floating-cart'          => true,
				'icon-display'                  => sanitize_text_field( $fmc_settings['display'] ?? 'always' ),
				'icon'                          => sanitize_text_field( $fmc_settings['icon'] ?? 'cart-icon-1' ),
				'icon-position'                 => sanitize_text_field( $fmc_settings['icon-position'] ?? 'right' ),
				'icon-size'                     => absint( $fmc_settings['icon-size'] ?? 25 ),
				'icon-corner-offset'            => absint( $fmc_settings['corner-offset'] ?? 30 ),
				'icon-border-radius'            => absint( $fmc_settings['border-radius'] ?? 35 ),
				'icon-color'                    => sanitize_hex_color( $fmc_settings['icon-color'] ?? '#ffffff' ),
				'icon-background-color'         => sanitize_hex_color( $fmc_settings['background-color'] ?? '#212121' ),
				'icon-counter-color'            => sanitize_hex_color( $fmc_settings['counter-color'] ?? '#ffffff' ),
				'icon-counter-background-color' => sanitize_hex_color( $fmc_settings['counter-background-color'] ?? '#757575' ),
			);

            // Update the Side Cart options.
			foreach ( $side_cart_options as $key => $value ) {
				Merchant_Admin_Options::set( self::MODULE_ID, $key, $value );
			}
        }

        // Free Shipping Bar - Field options ids were changed. So map old ids to the new ids.
		$fsb_module_id  = 'free-shipping-progress-bar';
		$is_fsb_enabled = Merchant_Modules::is_module_active( $fsb_module_id );
        if ( $is_fsb_enabled ) {
	        $side_cart_placement = Merchant_Admin_Options::get( $fsb_module_id, 'side_cart_placement', 'merchant_widget_shopping_cart_before_buttons' );

	        // Define the old-to-new placement mappings
	        $placement_mapping = array(
		        'woocommerce_before_mini_cart_contents'           => 'merchant_before_mini_cart_contents',
		        'woocommerce_widget_shopping_cart_before_buttons' => 'merchant_widget_shopping_cart_before_buttons',
		        'woocommerce_widget_shopping_cart_after_buttons'  => 'merchant_widget_shopping_cart_after_buttons',
	        );

	        // Check and update side cart placement if the current setting has a mapped new value
	        if ( isset( $placement_mapping[ $side_cart_placement ] ) ) {
		        Merchant_Admin_Options::set( $fsb_module_id, 'side_cart_placement', sanitize_text_field( $placement_mapping[ $side_cart_placement ] ) );
	        }
        }

        // Delete FMC Module's Enabled/Disabled data from DB
		$modules = get_option( Merchant_Modules::$option, array() );
		if ( isset( $modules[ $fmc_module_id ] ) ) {
			unset( $modules[ $fmc_module_id ] );
			update_option( Merchant_Modules::$option, $modules );
		}

		// Delete FMC Module's settings information from DB
		$options = get_option( 'merchant', array() );
		if ( isset( $options[ $fmc_module_id ] ) ) {
			unset( $options[ $fmc_module_id ] );
			update_option( 'merchant', $options );
		}

		// Track Migration to do the action once only.
		update_option( 'merchant_floating_mini_cart_merged_with_side_cart', true );
    }
}

// Initialize the module.
add_action( 'init', function () {
	Merchant_Modules::create_module( new Merchant_Side_Cart() );
} );
