<?php

/**
 * Clear Cart.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Clear Cart Class.
 *
 */
class Merchant_Clear_Cart extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'clear-cart';

	/**
	 * Is module preview.
	 *
	 */
	public static $is_module_preview = false;

	/**
	 * Whether the module has a shortcode or not.
	 *
	 * @var bool
	 */
	public $has_shortcode = true;

	/**
	 * Constructor.
	 *
	 */
	public function __construct() {
		if ( ! class_exists( 'Woocommerce' ) ) {
			return;
		}

		// Module id.
		$this->module_id = self::MODULE_ID;

		// WooCommerce only.
		$this->wc_only = true;

		// Module section.
		$this->module_section = 'improve-experience';

		// Parent construct.
		parent::__construct();

		// Module default settings.
		$this->module_default_settings = array(
			'cart_threshold'              => 1,
			'enable_auto_clear'           => false,
			'auto_clear_expiration_hours' => 24,
			'popup_message'               => __( 'Are you sure you want to empty your shopping cart?', 'merchant' ),
			'popup_message_inactive'      => __( 'It looks like you haven’t been active for a while. Would you like to empty your shopping cart?', 'merchant' ),
			'redirect_link'               => '',
			'redirect_link_custom'        => '',
			'enable_cart_page'            => true,
			'cart_page_position'          => 'woocommerce_cart_coupon',
			'enable_mini_cart'            => false,
			'mini_cart_position'          => 'after_checkout',
			'enable_side_cart'            => false,
			'side_cart_position'          => 'after_view_cart',
			'label'                       => __( 'Clear Cart', 'merchant' ),
			'style'                       => 'solid',
        );

		// Mount preview url.
		$preview_url = site_url( '/' );

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

			// Admin preview box.
			add_filter( 'merchant_module_preview', array( $this, 'render_admin_preview' ), 10, 2 );

			// Custom CSS.
			// The custom CSS should be added here as well due to ensure preview box works properly.
			add_filter( 'merchant_custom_css', array( $this, 'admin_custom_css' ) );
		}

		if ( ! Merchant_Modules::is_module_active( self::MODULE_ID ) ) {
			return;
		}

		if ( Merchant_Modules::is_module_active( self::MODULE_ID ) && is_admin() ) {
			$this->init_translations();
		}

		// Return early if it's on admin but not in the respective module settings page.
		if ( is_admin() && ! wp_doing_ajax() && ! parent::is_module_settings_page() ) {
			return;
		}

		$settings = $this->get_module_settings();

        // Cart Page
        if ( ! empty( $settings['enable_cart_page'] ) ) {
            $hook_name = ! empty( $settings['cart_page_position'] ) ? $settings['cart_page_position'] : 'woocommerce_cart_coupon';

	        add_action( $hook_name, array( $this, 'button_cart_page' ) );
        }

        // Mini Cart
        if ( ! empty( $settings['enable_mini_cart'] ) ) {
            $position      = $settings['mini_cart_position'] ?? 'after_checkout';
	        $hook_priority = $position === 'before_view_cart' ? 9 : ( $position === 'after_view_cart' ? 15 : 30 );

	        add_action( 'woocommerce_widget_shopping_cart_buttons', array( $this, 'button_mini_cart' ), $hook_priority );
        }

        // Side Cart
		if ( ! empty( $settings['enable_side_cart'] ) && merchant_is_pro_active() && ( Merchant_Modules::is_module_active( Merchant_Side_Cart::MODULE_ID ) || Merchant_Modules::is_module_active( Merchant_Floating_Mini_Cart::MODULE_ID ) ) ) {
            $position      = $settings['side_cart_position'] ?? 'after_view_cart';
            $hook_priority = $position === 'before_view_cart' ? 15 : ( $position === 'before_checkout' ? 30 : 9 );

            add_action( 'woocommerce_widget_shopping_cart_buttons', array( $this, 'button_side_cart' ), $hook_priority );
		}

		// Enqueue CSS.
		add_action( 'merchant_enqueue_before_main_css_js', array( $this, 'enqueue_css' ) );

		// Enqueue scripts.
		add_action( 'merchant_enqueue_after_main_css_js', array( $this, 'enqueue_scripts' ) );

		// Localize script.
		add_filter( 'merchant_localize_script', array( $this, 'localize_script' ) );

		// Custom CSS.
		add_filter( 'merchant_custom_css', array( $this, 'frontend_custom_css' ) );

		add_action( 'wp_ajax_clear_cart', array( $this, 'clear_cart_ajax_handler' ) );
		add_action( 'wp_ajax_nopriv_clear_cart', array( $this, 'clear_cart_ajax_handler' ) );

		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_count_fragment' ) );
	}

	/**
	 * Init translations.
	 *
	 * @return void
	 */
	public function init_translations() {
		$settings = $this->get_module_settings();
		if ( ! empty( $settings['label'] ) ) {
			Merchant_Translator::register_string( $settings['label'], esc_html__( 'Clear Cart', 'merchant' ) );
		}
	}

	/**
	 * Admin enqueue CSS.
	 *
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( $this->is_module_settings_page() ) {
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
        }
	}

	/**
	 * Admin enqueue scripts.
	 *
	 * @return void
	 */
	public function admin_enqueue_js() {
		if ( $this->is_module_settings_page() ) {
			wp_enqueue_script( "merchant-{$this->module_id}", MERCHANT_URI . "assets/js/modules/{$this->module_id}/admin/preview.min.js", array( 'jquery' ), MERCHANT_VERSION, true );
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
		if ( self::MODULE_ID === $module ) {
			$preview->set_html( array( $this, 'admin_preview_content' ), $this->get_module_settings() );

			// Button Label.
			$preview->set_text( 'label', '.merchant-clear-cart-button' );
		}

		return $preview;
	}

	/**
	 * Admin preview content.
	 *
	 * @return void
	 */
	public static function admin_preview_content( $settings ) {
        $products = array(
            array(
                'title'    => __( 'Product title', 'merchant' ),
                'price'    => '$30',
                'subtotal' => '$30',
            ),
	        array(
		        'title'    => __( 'Product title', 'merchant' ),
		        'price'    => '$55',
		        'subtotal' => '$55',
	        ),
        );

		?>
        <table class="shop_table" cellspacing="0">
            <thead>
                <tr>
                    <th class="product-remove"><span class="screen-reader-text"><?php esc_html_e( 'Remove item', 'merchant' ); ?></span></th>
                    <th class="product-thumbnail"><span class="screen-reader-text"><?php esc_html_e( 'Thumbnail image', 'merchant' ); ?></span></th>
                    <th class="product-name"><?php esc_html_e( 'Product', 'merchant' ); ?></th>
                    <th class="product-price"><?php esc_html_e( 'Price', 'merchant' ); ?></th>
                    <th class="product-quantity"><?php esc_html_e( 'Quantity', 'merchant' ); ?></th>
                    <th class="product-subtotal"><?php esc_html_e( 'Total', 'merchant' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $products as $p ) : ?>
                    <tr class="woocommerce-cart-form__cart-item cart_item">
                        <td class="product-remove">
                            <svg width="10" height="10" viewBox="0 0 5 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.715086 0.0388184L0.400391 0.353514L2.32277 2.27589L0.400391 4.19827L0.715086 4.51297L2.63747 2.59059L4.55985 4.51297L4.87454 4.19827L2.95216 2.27589L4.87454 0.353514L4.55985 0.0388184L2.63747 1.9612L0.715086 0.0388184Z" fill="#424242"/>
                            </svg>
                        </td>
                        <td class="product-thumbnail"><span></span></td>
                        <td class="product-name"><?php echo esc_html( $p['title'] ); ?></td>
                        <td class="product-price"><?php echo esc_html( $p['price'] ); ?></td>
                        <td class="product-quantity">
                            <div class="merchant-preview-qty">
                                <button>+</button>
                                <input type="text" value="1">
                                <button>-</button>
                            </div>
                        </td>
                        <td class="product-subtotal"><?php echo esc_html( $p['subtotal'] ); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="shop_table-bottom">
            <div class="shop_table-bottom__start">
                <div class="shop_table__coupon">
                    <input type="text" placeholder="<?php echo esc_attr__( 'Coupon Code', 'merchant' ); ?>">
                    <button class="shop_table__button shop_table__button__apply-coupon"><?php echo esc_html__( 'Apply Coupon', 'merchant' ); ?></button>
                </div>
                <div class="shop_table__clear-cart">
                    <button class="hide shop_table__button merchant-clear-cart-button woocommerce_cart_coupon"><?php echo esc_html( $settings['label'] ); ?></button>
                </div>
            </div>
            <div class="shop_table-bottom__end">
                <button class="shop_table__button shop_table__button__update-cart"><?php echo esc_html__( 'Update Cart', 'merchant' ); ?></button>
                <button class="hide shop_table__button merchant-clear-cart-button woocommerce_cart_actions"><?php echo esc_html( $settings['label'] ); ?></button>
            </div>
        </div>
        <button class="hide shop_table__button merchant-clear-cart-button woocommerce_after_cart_table"><?php echo esc_html( $settings['label'] ); ?></button>
		<?php
	}

	/**
	 * Print shortcode content.
	 *
	 * @return string
	 */
	public function shortcode_handler() {
		// Check if module is active.
		if ( ! Merchant_Modules::is_module_active( self::MODULE_ID ) ) {
			return '';
		}

		// Check if shortcode is enabled.
		if ( ! $this->is_shortcode_enabled() ) {
			return '';
		}

        ob_start();
        $this->print_button();
		$shortcode_content = ob_get_clean();

		/**
		 * Filter the shortcode html content.
		 *
		 * @param string $shortcode_content shortcode html content
		 * @param string $module_id         module id
		 * @param int    $post_id           product id
		 *
		 * @since 1.8
		 */
		return apply_filters( 'merchant_module_shortcode_content_html', $shortcode_content, self::MODULE_ID, get_the_ID() );
	}

	/**
	 * Display Button on Cart Page
	 *
	 * @return void
	 */
	public function button_cart_page() {
		if ( $this->is_shortcode_enabled() ) {
			return;
		}

		$this->print_button( 'cart-page' );
	}

	/**
	 * Display Button on Side Cart
	 *
	 * @return void
	 */
	public function button_side_cart() {
		if ( $this->is_shortcode_enabled() ) {
			return;
		}

		$this->print_button( 'side-cart' );
	}

	/**
	 * Display Button on Mini Cart
	 *
	 * @return void
	 */
	public function button_mini_cart() {
		if ( $this->is_shortcode_enabled() ) {
			return;
		}

		$this->print_button( 'mini-cart' );
	}

	/**
	 * Display Button
	 *
	 * @param $context
	 *
	 * @return void
	 */
	public function print_button( $context = '' ) {
		$settings  = $this->get_module_settings();
		$threshold = $settings['cart_threshold'] ?? 1;

        // Early return if the cart item count is less than the threshold
		if ( WC()->cart->get_cart_contents_count() < $threshold ) {
			return;
		}

		$theme      = wp_get_theme();
		$theme_name = $theme->get( 'Name' );

		$is_enabled_cart_page = $settings['enable_cart_page'] ?? true;
		$cart_page_position   = $settings['cart_page_position'] ?? 'woocommerce_cart_coupon';

		$label = $settings['label'] ?? esc_html__( 'Clear Cart', 'merchant' );
		$style = $settings['style'] ?? 'solid';

		$classes  = 'merchant-clear-cart-button';
		$classes .= ' merchant-clear-cart-button--' . $style;
		$classes .= $context ? ' merchant-clear-cart-button--' . $context : '';
		$classes .= $context === 'cart-page' ? ' ' . ( $settings['cart_page_position'] ?? 'woocommerce_cart_coupon' ) : '';
        ?>
        <button type="button" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> <?php echo esc_attr( $classes ); ?>">
			<?php echo esc_html( $label ); ?>
        </button>
		<?php
        // Because of a CSS issue, adding extra Update cart button on Botiga. Default one hidden by CSS.
		if ( $context === 'cart-page' && $theme_name === 'Botiga' && ! $this->is_shortcode_enabled() && $is_enabled_cart_page && $cart_page_position === 'woocommerce_cart_actions' ) {
			?>
            <button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> merchant-clear-cart-button__update-cart" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'merchant' ); ?>"><?php esc_html_e( 'Update cart', 'merchant' ); ?></button>
			<?php
		}
	}

	/**
     * Cart count fragments.
     *
	 * @param $fragments
	 *
	 * @return mixed
	 */
	public function cart_count_fragment( $fragments ) {
		$fragments['.merchant_clear_cart_cart_count'] = WC()->cart->get_cart_contents_count();

		return $fragments;
	}

	/**
     * Clear Cart AJAX
     *
	 * @return void
	 */
	public function clear_cart_ajax_handler() {
		check_ajax_referer( 'merchant-nonce', 'nonce' );

		if ( WC()->cart && ! WC()->cart->is_empty() ) {
			$settings = $this->get_module_settings();

			$redirect_url  = '';
			$redirect_link = $settings['redirect_link'] ?? '';

			switch ( $redirect_link ) {
				case 'home':
					$redirect_url = home_url();
					break;

				case 'shop':
					$redirect_url = wc_get_page_permalink( 'shop' );
					break;

				case 'custom':
					$custom_link  = $settings['redirect_link_custom'] ?? '';
					$redirect_url = ! empty( $custom_link ) ? esc_url( $custom_link ) : '';
					break;
			}

			WC()->cart->empty_cart();
			wp_send_json_success( array( 'url' => $redirect_url ) );
		}

		wp_send_json_error( array( 'message' => esc_html__( 'Cart is Empty.', 'merchant' ) ) );
	}

	/**
     * Determine if assets should be enqueued.
     *
	 * @return bool
	 */
    private function should_load_assets() {
	    $settings = $this->get_module_settings();

	    // Check if auto-clear is enabled or if the shortcode is being used
	    $load_assets = ( $settings['enable_auto_clear'] ?? false ) || $this->is_shortcode_enabled();

	    // Check additional conditions: cart page, mini cart, or side cart enabled
	    $load_assets = $load_assets || ( $settings['enable_cart_page'] && is_cart() ) || $settings['enable_mini_cart'] || $settings['enable_side_cart'];

	    return $load_assets;
    }

	/**
	 * Enqueue CSS.
	 *
	 * @return void
	 */
	public function enqueue_css() {
        if ( ! $this->should_load_assets() ) {
            return;
        }

		// Specific module styles.
		wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/clear-cart.min.css', array(), MERCHANT_VERSION );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		if ( ! $this->should_load_assets() ) {
			return;
		}

		wp_enqueue_script( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/clear-cart.min.js', array( 'jquery' ), MERCHANT_VERSION, true );
	}

	/**
	 * Localize script with module settings.
	 *
	 * @param array $setting The merchant global object setting parameter.
	 * @return array $setting The merchant global object setting parameter.
	 */
	public function localize_script( $setting ) {
		if ( ! $this->should_load_assets() ) {
			return $setting;
		}

		$module_settings = $this->get_module_settings();

        $expiration_in_hours   = (int) ( $module_settings['auto_clear_expiration_hours'] ?? 24 );
		$expiration_in_seconds = $expiration_in_hours * 60 * 60;

		$setting['clear_cart'] = array(
			'threshold'                  => (int) ( $module_settings['cart_threshold'] ?? 1 ),
			'auto_clear'                 => (bool) ( $module_settings['enable_auto_clear'] ?? false ),
			'expiration_time'            => $expiration_in_seconds,
			'wc_session_expiration_time' => DAY_IN_SECONDS * 2, // Default 48h,
			'popup_message'              => $module_settings['popup_message'] ?? '',
			'popup_message_inactive'     => $module_settings['popup_message_inactive'] ?? '',
			'is_cart_page'               => is_cart(),
			'is_product_single'          => is_product(),
			'added_to_cart_no_ajax'      => ! empty( $_REQUEST['add-to-cart'] ) || ! empty( $_REQUEST['botiga-adtc-added-to-cart'] ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			'total_items'                => WC()->cart->get_cart_contents_count(),
        );

		return $setting;
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

	/**
	 * Frontend custom CSS.
	 *
	 * @param string $css The custom CSS.
	 * @return string $css The custom CSS.
	 */
	public function frontend_custom_css( $css ) {
		if ( ! $this->should_load_assets() ) {
			return $css;
		}

		$settings = $this->get_module_settings();

        $is_enabled_cart_page = $settings['enable_cart_page'] ?? true;
		$cart_page_position   = $settings['cart_page_position'] ?? 'woocommerce_cart_coupon';
		$is_enabled_mini_cart = $settings['enable_mini_cart'] ?? false;

		$theme      = wp_get_theme();
		$theme_name = $theme->get( 'Name' );

		$css .= $this->get_module_custom_css();

		if ( $theme_name === 'Botiga' && ! $this->is_shortcode_enabled() ) {
			if ( $is_enabled_mini_cart ) {
				$css .= '
                    #site-header-cart button.merchant-clear-cart-button {
                        color: var(--mrc-clear-cart-text-color, #ffffff);
                        font-size: var(--mrc-clear-cart-font-size, 16px) !important;
                        padding-block: var(--mrc-clear-cart-padding-vertical, 13px) !important;
                        padding-inline: var(--mrc-clear-cart-padding-horizontal, 25px) !important;
                        margin-top: 5px;
                        text-decoration: none;
                    }
                    
                    #site-header-cart button.merchant-clear-cart-button:hover {
                        background: none;
                        color: var(--mrc-clear-cart-text-color-hover, #ffffff);
                    }
                    
                    #site-header-cart  button.merchant-clear-cart-button--solid,
                    #site-header-cart button.merchant-clear-cart-button--outline {
                        margin-top: 15px;
                    }
                    
                    #site-header-cart  button.merchant-clear-cart-button--solid {
                        background-color: var(--mrc-clear-cart-bg-color, #212121) !important;
                    }
                    
                    #site-header-cart  button.merchant-clear-cart-button--solid:hover {
                        background-color: var(--mrc-clear-cart-bg-color-hover, #414141) !important;
                    }
                    
                    #site-header-cart button.merchant-clear-cart-button--outline {
                        border: 2px solid var(--mrc-clear-cart-border-color, #212121);
                    }
                    
                    #site-header-cart  button.merchant-clear-cart-button--outline:hover {
                        border-color: var(--mrc-clear-cart-border-color-hover, #414141);
                    }
				';
            }

			// After Coupon
            if ( $is_enabled_cart_page && $cart_page_position === 'woocommerce_cart_coupon' ) {
				$css .= '@media (max-width: 767px) {';
				$css .= '
                    .woocommerce-cart .woocommerce-cart-form .actions .coupon {
                        flex-wrap: wrap;
                    }
                    
                ';
				$css .= '}';

				$css .= '@media (max-width: 575px) {';
				$css .= '
				    .button.merchant-clear-cart-button {
				        min-width: 100%;
                        margin-top: 10px;
                    }
				';
				$css .= '}';
            }

            // After Update Cart Button
			if ( $is_enabled_cart_page && $cart_page_position === 'woocommerce_cart_actions' ) {
				$css .= '
				    .shop_table .button[name="update_cart"]:not(.merchant-clear-cart-button__update-cart) {
				        display: none;
				    }
				';

				$css .= '@media (max-width: 767px) {';
				$css .= '
                    .button.merchant-clear-cart-button {
                        margin-top: 15px;
                        margin-left: 10px;
                    }
                ';
				$css .= '}';

				$css .= '@media (max-width: 575px) {';
				$css .= '
                    .button.merchant-clear-cart-button {
                        width: 100%;
                        margin-top: 15px;
                        margin-left: 0px;
                    }
                ';
				$css .= '}';
            }
		}

		return $css;
	}

	/**
	 * Custom CSS.
	 *
	 * @return string
	 */
	public function get_module_custom_css() {
		if ( ! $this->should_load_assets() && ! is_admin() ) {
			return '';
		}

		$css = '';

		// Background Color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'background_color', '#212121', '.merchant-clear-cart-button', '--mrc-clear-cart-bg-color' );

		// Background Color(Hover).
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'background_color_hover', '#414141', '.merchant-clear-cart-button', '--mrc-clear-cart-bg-color-hover' );

		// Border Width
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'border_width', 2, '.merchant-clear-cart-button', '--mrc-clear-cart-border-width', 'px' );

		// Border Color
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'border_color', '#212121', '.merchant-clear-cart-button', '--mrc-clear-cart-border-color' );

		// Border Color(Hover).
        $css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'border_color_hover', '#414141', '.merchant-clear-cart-button', '--mrc-clear-cart-border-color-hover' );

		// Border radius.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'border_radius', 0, '.merchant-clear-cart-button', '--mrc-clear-cart-border-radius', 'px' );

		// Text Color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'text_color', '#ffffff', '.merchant-clear-cart-button', '--mrc-clear-cart-text-color' );

		// Text Color(Hover).
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'text_color_hover', '#ffffff', '.merchant-clear-cart-button', '--mrc-clear-cart-text-color-hover' );

		// Font Size.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'font_size', 16, '.merchant-clear-cart-button', '--mrc-clear-cart-font-size', 'px' );

		// Padding Top/Bottom.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'padding_vertical', 15, '.merchant-clear-cart-button', '--mrc-clear-cart-padding-vertical', 'px' );

		// Padding Left/Right.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'padding_horizontal', 25, '.merchant-clear-cart-button', '--mrc-clear-cart-padding-horizontal', 'px' );

		return $css;
	}
}

// Initialize the module.
add_action( 'init', static function () {
	Merchant_Modules::create_module( new Merchant_Clear_Cart() );
} );
