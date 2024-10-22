<?php

/**
 * Quick View.
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Quick View Class.
 * 
 */
class Merchant_Quick_View extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'quick-view';

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

	private static $instance = null;

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
			'button_type'                  => 'text',
			'button_text'                  => __( 'Quick View', 'merchant' ),
			'button_icon'                  => 'eye',
			'button_position'              => 'overlay',
			'button-position-top'          => 50,
			'button-position-left'         => 50,
			'mobile_position'              => false,
			'button-position-top-mobile'   => 50,
			'button-position-left-mobile'  => 50,
			'zoom_effect'                  => 1,
			'show_quantity'                => 1,
			'place_product_description'    => 'top',
			'description_style'            => 'short',
			'place_product_image'          => 'thumbs-at-left',
            'show_buy_now_button'          => false,
            'show_suggested_products'      => true,
            'suggested_products_module'    => 'bulk_discounts',
            'suggested_products_placement' => 'after_add_to_cart',
		);

		// Mount preview url.
		$preview_url = site_url( '/' );

		if ( function_exists( 'wc_get_page_id' ) ) {
			$preview_url = get_permalink( wc_get_page_id( 'shop' ) );
		}

		// Module data.
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];
		$this->module_data[ 'preview_url' ] = $preview_url;

		// Module options path.
		$this->module_options_path = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID . '/admin/options.php';

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

		if ( ! Merchant_Modules::is_module_active( self::MODULE_ID ) ) {
			return;
		}

		// Return early if it's on admin but not in the respective module settings page.
		if ( is_admin() && ! wp_doing_ajax() && ! parent::is_module_settings_page() ) {
			return;
		}

		// Enqueue styles.
		add_action( 'merchant_enqueue_before_main_css_js', array( $this, 'enqueue_css' ) );

		// Enqueue scripts.
		add_action( 'merchant_enqueue_after_main_css_js', array( $this, 'enqueue_scripts' ) );

		// Localize script.
		add_filter( 'merchant_localize_script', array( $this, 'localize_script' ) );

		// Handle Botiga theme scripts for compatibility.
		if ( defined( 'BOTIGA_PRO_URI' ) && defined( 'BOTIGA_PRO_VERSION' ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'modal_compatibility_with_botiga_theme' ) );
		}

		// Button Position.
        if ( ! $this->is_shortcode_enabled() ) {
	        $button_position = Merchant_Admin_Options::get( self::MODULE_ID, 'button_position', 'overlay' );

	        if ( 'before' === $button_position ) {
		        add_action( 'woocommerce_after_shop_loop_item', array( $this, 'quick_view_button' ), 5 );
	        } elseif ( 'after' === $button_position ) {
		        add_action( 'woocommerce_after_shop_loop_item', array( $this, 'quick_view_button' ), 15 );
	        } elseif ( 'overlay' === $button_position ) {
		        if ( merchant_is_kadence_active() ) {
                    add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'quick_view_button' ), 35 );
                    //add_filter( 'kadence_archive_content_wrap_start', array( $this, 'add_quick_view_button' ) );
		        } elseif ( merchant_is_blocksy_active() ) {
                    add_action( 'blocksy:woocommerce:product-card:thumbnail:end', array( $this, 'quick_view_button' ) );
                } elseif ( merchant_is_botiga_active() ) {
                    add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'quick_view_button' ) );
		        } elseif ( merchant_is_oceanwp_active() ) {
                    add_action( 'ocean_after_archive_product_image', array( $this, 'quick_view_button' ) );
		        } elseif ( merchant_is_flatsome_active() ) {
                    add_action( 'flatsome_woocommerce_shop_loop_images', array( $this, 'quick_view_button' ) );
		        } elseif ( merchant_is_storefront_active() ) {
			        remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
			        add_action( 'woocommerce_before_shop_loop_item_title', function () {
				        echo '<div class="merchant-storefront-thumbnail-wrapper">';
				        woocommerce_template_loop_product_thumbnail();
                        $this->quick_view_button();
				        echo '</div>';
			        } );
		        } elseif ( merchant_is_astra_active() ) {
			        add_action( 'woocommerce_after_shop_loop_item', array( $this, 'quick_view_button' ), 7 );
		        } else {
			        add_action( 'woocommerce_after_shop_loop_item', array( $this, 'quick_view_button' ) );
		        }
	        }
        }

		// Inject quick view modal output on footer.
		add_action( 'wp_footer', array( $this, 'modal_output' ) );

        // Show Suggested Module
        $suggested_placement = Merchant_Admin_Options::get( self::MODULE_ID, 'suggested_products_placement', 'after_add_to_cart' );
        add_action( 'merchant_quick_view_' . $suggested_placement, array( $this, 'render_suggested_module_content' ), 10, 2 );

        // Show Buy Now Module
        add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'render_buy_now_button' ) );

		// Custom CSS.
		add_filter( 'merchant_custom_css', array( $this, 'frontend_custom_css' ) );

		// Modal content ajax callback.
		add_action( 'wp_ajax_merchant_quick_view_content', array( $this, 'modal_content_ajax_callback' ) );
		add_action( 'wp_ajax_nopriv_merchant_quick_view_content', array( $this, 'modal_content_ajax_callback' ) );
	}

	/**
     * Singleton
     *
	 * @return self|null
	 */
	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Init translations.
	 *
	 * @return void
	 */
	public function init_translations() {
		$settings = $this->get_module_settings();
		if ( ! empty( $settings['button_text'] ) ) {
			Merchant_Translator::register_string( $settings['button_text'], esc_html__( 'Quick view button text', 'merchant' ) );
		}
	}

	/**
	 * Print shortcode content.
	 *
	 * @return string
	 */
	public function shortcode_handler() {
		// Check if module is active.
		if ( ! Merchant_Modules::is_module_active( $this->module_id ) ) {
			return '';
		}

		// Check if shortcode is enabled.
		if ( ! $this->is_shortcode_enabled() ) {
			return '';
		}

        global $product;

        $product_id = is_object( $product ) ? $product->get_id() : get_the_ID();

        ob_start();
		$this->quick_view_button( 'shortcode' );
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
		return apply_filters( 'merchant_module_shortcode_content_html', $shortcode_content, $this->module_id, $product_id );
	}

	/**
     * Concatenate the quick view button with the content start wrap.
     *
	 * @return string
	 */
	public function add_quick_view_button() {
		return $this->quick_view_button() . '<div class="product-details content-bg entry-content-wrap">';
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
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/quick-view.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
		}
	}

	/**
	 * Enqueue CSS.
	 * 
	 * @return void
	 */
	public function enqueue_css() {

		// Specific module styles.
		wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/quick-view.min.css', array(), MERCHANT_VERSION );
	}

	/**
	 * Enqueue scripts.
	 * 
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'zoom' );
		wp_enqueue_script( 'flexslider' );
		wp_enqueue_script( 'wc-single-product' );
		wp_enqueue_script( 'wc-add-to-cart-variation' );

		// Register and enqueue the main module script.
		wp_enqueue_script( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/quick-view.min.js', array(), MERCHANT_VERSION, true );
	}

	/**
	 * Localize script with module settings.
	 * 
	 * @param array $setting The merchant global object setting parameter.
	 * @return array $setting The merchant global object setting parameter.
	 */
	public function localize_script( $setting ) {
		$module_settings = $this->get_module_settings();

		$setting[ 'quick_view' ]      = true;
		$setting[ 'quick_view_zoom' ] = $module_settings[ 'zoom_effect' ];

		return $setting;
	}

	/**
	 * Enqueue botiga theme scripts.
	 * 
	 * @return void
	 */
	public function modal_compatibility_with_botiga_theme() {
		if ( ! wp_script_is( 'botiga-product-swatch' ) ) {
			wp_enqueue_script( 'botiga-product-swatch', BOTIGA_PRO_URI . 'assets/js/botiga-product-swatch.min.js', array(), BOTIGA_PRO_VERSION, true );
		}
	
		if ( ! wp_script_is( 'botiga-checkout-quantity-input' ) ) {
			wp_enqueue_script( 'botiga-checkout-quantity-input', BOTIGA_PRO_URI . 'assets/js/botiga-checkout-quantity-input.min.js', array( 'jquery' ), BOTIGA_PRO_VERSION, true );
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
			self::admin_preview_content();
			$content = ob_get_clean();

			// HTML.
			$preview->set_html( $content );

			// Button Type.
			$preview->set_class( 'button_type', '.merchant-quick-view-button', array( 'icon', 'icon-text', 'text' ) );

			// Button Text.
			$preview->set_text( 'button_text', '.button-text' );

			// Button Icon.
			$preview->set_svg_icon( 'button_icon', '.quick-view-icon' );

			// Button Position.
			$preview->set_class( 'button_position', '.merchant-quick-view-preview', array( 'before', 'after', 'overlay' ) );

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

		<div class="merchant-quick-view-preview <?php echo esc_attr( $settings[ 'button_position' ] ); ?>">
			<div class="image-wrapper">
				<div class="button-position button-position-overlay">
					<?php $this->admin_preview_quick_view_button(); ?>
				</div>
			</div>
			<h3><?php echo esc_html__( 'Product Title', 'merchant' ); ?></h3>
			<p><?php echo esc_html__( 'The product description normally is displayed here.', 'merchant' ); ?></p>
			<div class="button-position button-position-before">
				<?php $this->admin_preview_quick_view_button(); ?>
			</div>
			<div>
				<a href="#" class="add_to_cart_button"><?php echo esc_html__( 'Add To Cart', 'merchant' ); ?></a>
			</div>
			<div class="button-position button-position-after">
				<?php $this->admin_preview_quick_view_button(); ?>
			</div>

		</div>

		<?php
	}

	/**
	 * Admin preview quick view button.
	 * 
	 * @return void
	 */
	public function admin_preview_quick_view_button() {
		$settings = $this->get_module_settings();

		?>

		<a href="#" class="merchant-quick-view-button <?php echo esc_attr( $settings[ 'button_type' ] ); ?>">
			<span class="button-type button-type-icon">
				<span class="quick-view-icon">
					<?php echo wp_kses( Merchant_SVG_Icons::get_svg_icon( $settings[ 'button_icon' ] ), merchant_kses_allowed_tags( array(), false ) ); ?>
				</span>
			</span>
			<span class="button-type button-type-icon-text">
				<span class="quick-view-icon">
					<?php echo wp_kses( Merchant_SVG_Icons::get_svg_icon( $settings[ 'button_icon' ] ), merchant_kses_allowed_tags( array(), false ) ); ?>
				</span>
				<span class="button-text"><?php echo esc_html( $settings[ 'button_text' ] ); ?></span>
			</span>
			<span class="button-type button-type-text">
				<span class="button-text"><?php echo esc_html( $settings[ 'button_text' ] ); ?></span>
			</span>
		</a>

		<?php
	}

	/**
	 * Modal content ajax callback.
	 * TODO: Render the output through templates files.
	 * 
	 * @return void 
	 */
	public function modal_content_ajax_callback() {
		check_ajax_referer( 'merchant-nonce', 'nonce' );

		if ( ! isset( $_POST['product_id'] ) || ! function_exists( 'wc_get_product' ) ) {
			wp_send_json_error();
		}

		$args = array(
			'product_id' => absint( $_POST['product_id'] ),
		);

		global $product;

		$settings = $this->get_module_settings();
		$product  = wc_get_product( $args['product_id'] );

		if ( is_wp_error( $product ) || empty( $product ) ) {
			wp_send_json_error();
		}

		$product_id    = $product->get_id(); 
		$hide_quantity = ( empty( $settings[ 'show_quantity' ] ) ) ? 'merchant-hide-quantity' : '';

		ob_start();
		?>
			<div id="product-<?php echo absint( $product_id ); ?>" <?php wc_product_class( '', $product ); ?>>
				<div class="merchant-quick-view-row">
					<div class="merchant-quick-view-column">
						<div class="merchant-quick-view-product-gallery">
							<?php woocommerce_show_product_images(); ?>
						</div>
					</div>

					<div class="merchant-quick-view-column">
						<div class="merchant-quick-view-summary product-gallery-summary">
							<div class="merchant-quick-view-product-title">
								<h2 class="product_title entry-title"><?php echo esc_html( $product->get_title() ); ?></h2>
							</div>

							<?php if ( 0 !== $product->get_average_rating() ) : ?>
								<div class="merchant-quick-view-product-rating">
									<div class="woocommerce-product-rating">
										<?php echo wp_kses( wc_get_rating_html( $product->get_average_rating() ), merchant_kses_allowed_tags() ); ?>
									</div>
								</div>
							<?php endif; ?>

							<div class="merchant-quick-view-product-price">
								<div class="price"><?php echo wp_kses( $product->get_price_html(), merchant_kses_allowed_tags() ); ?></div>
							</div>

							<?php if ( 'top' === $settings[ 'place_product_description' ] ) : ?>
								<?php
								/**
								 * Hook 'merchant_quick_view_before_product_description'
								 *
								 * @since 1.9.14
								 */
								do_action( 'merchant_quick_view_before_product_description' );

                                $description = $settings[ 'description_style' ] === 'full' ? $product->get_description() : $product->get_short_description();

								/**
								 * `merchant_quick_view_description`
                                 *
                                 * @since 1.9.16
								 */
								$description = apply_filters( 'merchant_quick_view_description', $description );
								?>
                                <div class="merchant-quick-view-product-excerpt">
									<?php echo wp_kses_post( $description ); ?>
                                </div>

								<?php
								/**
								 * Hook 'merchant_quick_view_before_product_description'
								 *
								 * @since 1.9.14
								 */
								do_action( 'merchant_quick_view_after_product_description' );
								?>
							<?php endif; ?>

							<?php
							/**
							 * Hook 'merchant_quick_view_before_add_to_cart'
							 *
							 * @since 1.9.14
							 */
							do_action( 'merchant_quick_view_before_add_to_cart', $product, $settings );
							?>
							<div class="merchant-quick-view-product-add-to-cart <?php echo esc_attr( $hide_quantity ); ?>">
								<?php woocommerce_template_single_add_to_cart(); ?>
							</div>

							<?php
							/**
							 * Hook 'merchant_quick_view_after_add_to_cart'
							 *
							 * @since 1.9.14
							 */
							do_action( 'merchant_quick_view_after_add_to_cart', $product, $settings );
							?>

							<?php if ( 'bottom' === $settings[ 'place_product_description' ] ) : ?>
								<?php
								/**
								 * Hook 'merchant_quick_view_before_product_description'
								 *
								 * @since 1.9.14
								 */
								do_action( 'merchant_quick_view_before_product_description' );

								$description = $settings[ 'description_style' ] === 'full' ? $product->get_description() : $product->get_short_description();

								/**
								 * `merchant_quick_view_description`
								 *
								 * @since 1.9.16
								 */
								$description = apply_filters( 'merchant_quick_view_description', $description );
								?>
                                <div class="merchant-quick-view-product-excerpt">
									<?php echo wp_kses_post( $description ); ?>
                                </div>

								<?php
								/**
								 * Hook 'merchant_quick_view_after_product_description'
								 *
								 * @since 1.9.14
								 */
								do_action( 'merchant_quick_view_after_product_description' );
								?>
							<?php endif; ?>
							<div class="merchant-quick-view-product-meta">
								<?php woocommerce_template_single_meta(); ?>
							</div>

							<?php 
							/**
							 * Hook 'merchant_quick_view_after_product_excerpt'
							 * 
							 * @since 1.0.0
							 */
							do_action( 'merchant_quick_view_after_product_meta' ); 
							?>
						</div>
					</div>
				</div>
			</div>
		<?php
		$content = ob_get_contents();

		ob_get_clean();

		wp_send_json_success( $content );
	}

	/**
	 * Modal output.
	 * TODO: Render the output through templates files.
	 * 
	 * @return void
	 */
	public function modal_output() {
		$settings = $this->get_module_settings();
		?>
			<div class="single-product merchant-quick-view-modal merchant-quick-view-<?php echo esc_attr( $settings[ 'place_product_image' ] ); ?>">
				<div class="merchant-quick-view-overlay"></div>
				<div class="merchant-quick-view-loader">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
						<path opacity="0.4" d="M478.71 364.58zm-22 6.11l-27.83-15.9a15.92 15.92 0 0 1-6.94-19.2A184 184 0 1 1 256 72c5.89 0 11.71.29 17.46.83-.74-.07-1.48-.15-2.23-.21-8.49-.69-15.23-7.31-15.23-15.83v-32a16 16 0 0 1 15.34-16C266.24 8.46 261.18 8 256 8 119 8 8 119 8 256s111 248 248 248c98 0 182.42-56.95 222.71-139.42-4.13 7.86-14.23 10.55-22 6.11z" />
						<path d="M271.23 72.62c-8.49-.69-15.23-7.31-15.23-15.83V24.73c0-9.11 7.67-16.78 16.77-16.17C401.92 17.18 504 124.67 504 256a246 246 0 0 1-25 108.24c-4 8.17-14.37 11-22.26 6.45l-27.84-15.9c-7.41-4.23-9.83-13.35-6.2-21.07A182.53 182.53 0 0 0 440 256c0-96.49-74.27-175.63-168.77-183.38z" />
					</svg>
				</div>
				<div class="merchant-quick-view-inner">
					<a href="#" class="merchant-quick-view-close-button" title="<?php echo esc_attr__( 'Close quick view modal', 'merchant' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
							<path d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z"/>
						</svg>
					</a>
					<div class="merchant-quick-view-content"></div>
				</div>
			</div>
		<?php
	}

	/**
	 * Quick view button.
	 * 
	 * @return void
	 */
	public function quick_view_button( $context = '' ) {
		global $product;

        if ( ! is_object( $product ) ) {
            return;
        }

		$settings   = $this->get_module_settings();
		$product_id = $product->get_id();

		$button_text_html = '';
		$button_icon_html = '';

		if ( 'icon' === $settings[ 'button_type' ] || 'icon-text' === $settings[ 'button_type' ] ) {
			$button_icon_html = Merchant_SVG_Icons::get_svg_icon( $settings[ 'button_icon' ] );
		}

		if ( 'text' === $settings[ 'button_type' ] || 'icon-text' === $settings[ 'button_type' ] ) {
			$button_text_html = '<span>' . Merchant_Translator::translate( $settings[ 'button_text' ] ) . '</span>';
		}

        $classes  = 'button wp-element-button merchant-quick-view-button';
        $classes .= $context !== 'shortcode' ? ' merchant-quick-view-position-' . ( $settings[ 'button_position' ] ?? '' ) : '';
        $classes .= ( $context !== 'shortcode' && ! empty( $settings['mobile_position'] ) ) ? ' merchant-quick-view-position-has-mobile-position' : '';
		?>
        <button class="<?php echo esc_attr( $classes ); ?>" data-product-id="<?php echo absint( $product_id ); ?>" type="button">
            <?php echo wp_kses( $button_icon_html, merchant_kses_allowed_tags( array(), false ) ) . wp_kses_post( $button_text_html ); ?>
        </button>
		<?php
	}

	/**
     * Renders the Buy Now button.
     *
	 * @return void
	 */
	public function render_buy_now_button() {
		if ( ! Merchant_Modules::is_module_active( Merchant_Buy_Now::MODULE_ID ) ) {
			return;
		}

		// Don't include on Single Product
		if ( ! did_action( 'merchant_quick_view_before_add_to_cart' ) ) {
			return;
		}

		$show_buy_now = (bool) Merchant_Admin_Options::get( $this->module_id, 'show_buy_now_button', false );
        if ( ! $show_buy_now ) {
            return;
        }

		global $product;

		$text = Merchant_Admin_Options::get( Merchant_Buy_Now::MODULE_ID, 'button-text', esc_html__( 'Buy Now', 'merchant' ) );

		$_wrapper_classes   = array();
		$_wrapper_classes[] = $product->get_type() === 'variable' ? 'disabled' : '';

		/**
		 * Hook 'merchant_module_buy_now_wrapper_class'
		 *
		 * @since 1.8
		 */
		$wrapper_classes = apply_filters( 'merchant_module_buy_now_wrapper_class', $_wrapper_classes );
		?>
        <!-- Don't define type="submit" because it creates issue with block themes. The button is inside the form, so by default the type is already "submit". -->
        <button name="merchant-buy-now" value="<?php echo absint( $product->get_ID() ); ?>" class="button alt wp-element-button merchant-buy-now-button <?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"><?php echo esc_html( Merchant_Translator::translate( $text ) ); ?></button>
		<?php
    }

	/**
     * Renders the suggested module content based on the provided settings.
     *
	 * @param $product
	 * @param $settings
	 *
	 * @return void
	 */
	public function render_suggested_module_content( $product, $settings ) {
		$show_suggested = (bool) Merchant_Admin_Options::get( $this->module_id, 'show_suggested_products', true );

		if ( ! $show_suggested || empty( $product ) ) {
			return;
		}

		$suggested_module = $settings['suggested_products_module'] ?? 'bulk_discounts';

		switch ( $suggested_module ) {
			case 'bulk_discounts':
				$this->print_bulk_discounts( $product );
				break;

			case 'buy_x_get_y':
				$this->print_buy_x_get_y( $product );
				break;

			case 'frequently_bought_together':
				$this->print_frequently_bought_together( $product );
				break;
		}
	}

	/**
     * Prints the bulk discounts for the specified product.
     *
	 * @param $product
	 *
	 * @return void
	 */
	public function print_bulk_discounts( $product ) {
		if ( ! merchant_is_pro_active() || ! Merchant_Modules::is_module_active( Merchant_Volume_Discounts::MODULE_ID ) ) {
			return;
		}

		$product_id     = $product->get_id();
		$discount_tiers = Merchant_Pro_Volume_Discounts::availabe_offers( $product_id );

        if ( ! empty( $discount_tiers ) ) {
	        merchant_get_template_part(
		        Merchant_Volume_Discounts::MODULE_TEMPLATES_PATH,
		        'single-product',
		        array(
			        'settings'              => Merchant_Admin_Options::get_all( Merchant_Volume_Discounts::MODULE_ID ),
			        'product'               => $product,
			        'discount_tiers'        => $discount_tiers,
			        'product_price'         => $product->get_price(),
			        'in_cart'               => Merchant_Pro_Volume_Discounts::is_in_cart( $product_id ),
			        'product_cart_quantity' => Merchant_Pro_Volume_Discounts::get_product_cart_quantity( $product_id ),
			        'product_id'            => $product_id,
		        )
	        );
        }
	}

	/**
     * Prints the Buy X Get Y offers for the specified product.
     *
	 * @param $product
	 *
	 * @return void
	 */
	public function print_buy_x_get_y( $product ) {
		if ( ! merchant_is_pro_active() || ! Merchant_Modules::is_module_active( Merchant_Buy_X_Get_Y::MODULE_ID ) ) {
			return;
		}

		$product_id = $product->get_id();
		$offers     = Merchant_Pro_Buy_X_Get_Y::availabe_offers( $product_id );

		if ( ! empty( $offers ) ) {
			merchant_get_template_part(
				Merchant_Buy_X_Get_Y::MODULE_TEMPLATES,
				'single-product',
				array(
					'offers'   => $offers,
					'nonce'    => wp_create_nonce( 'merchant_bogo_add_to_cart' ),
					'settings' => Merchant_Admin_Options::get_all( Merchant_Buy_X_Get_Y::MODULE_ID ),
					'product'  => $product_id,
				)
			);
		}
	}

	/**
     * Prints the frequently bought together bundles for the specified product.
     *
	 * @param $product
	 *
	 * @return void
	 */
	public function print_frequently_bought_together( $product ) {
		if ( ! merchant_is_pro_active() || ! Merchant_Modules::is_module_active( Merchant_Frequently_Bought_Together::MODULE_ID ) ) {
			return;
		}

		$post_id     = $product->get_id();
		$bundle_data = Merchant_Pro_Frequently_Bought_Together::availabe_offers( $post_id, Merchant_Pro_Frequently_Bought_Together::bundles() );

		if ( ! empty( $bundle_data ) ) {
			$bundles[ $post_id ] = Merchant_Pro_Frequently_Bought_Together::map_bundles( $bundle_data );
		}

		if ( ! empty( $bundles ) ) {
			merchant_get_template_part(
				Merchant_Frequently_Bought_Together::MODULE_TEMPLATES_PATH,
				'single-product',
				array(
					'bundles'  => $bundles,
					'nonce'    => wp_create_nonce( Merchant_Pro_Frequently_Bought_Together::AJAX_NONCE_ACTION ),
					'settings' => Merchant_Admin_Options::get_all( Merchant_Frequently_Bought_Together::MODULE_ID ),
				)
			);
		}
	}
	
	/**
	 * Custom CSS.
	 * 
	 * @return string
	 */
	public function get_module_custom_css() {
		$css = '';

		// Button Icon Color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'quick-view', 'icon-color', '#ffffff', '.merchant-quick-view-button', '--mrc-qv-button-icon-color' );

		// Button Icon Color (hover).
		$css .= Merchant_Custom_CSS::get_variable_css( 'quick-view', 'icon-hover-color', '#ffffff', '.merchant-quick-view-button', '--mrc-qv-button-icon-color-hover' );

		// Button Text Color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'quick-view', 'text-color', '#ffffff', '.merchant-quick-view-button', '--mrc-qv-button-text-color' );

		// Button Text Color (hover).
		$css .= Merchant_Custom_CSS::get_variable_css( 'quick-view', 'text-hover-color', '#ffffff', '.merchant-quick-view-button', '--mrc-qv-button-text-color-hover' );

		// Button Border Color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'quick-view', 'border-color', '#212121', '.merchant-quick-view-button', '--mrc-qv-button-border-color' );

		// Button Border Color (hover).
		$css .= Merchant_Custom_CSS::get_variable_css( 'quick-view', 'border-hover-color', '#414141', '.merchant-quick-view-button', '--mrc-qv-button-border-color-hover' );

		// Button Background Color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'quick-view', 'background-color', '#212121', '.merchant-quick-view-button', '--mrc-qv-button-bg-color' );

		// Button Background Color (hover).
		$css .= Merchant_Custom_CSS::get_variable_css( 'quick-view', 'background-hover-color', '#414141', '.merchant-quick-view-button', '--mrc-qv-button-bg-color-hover' );

		// Button Position Top.
		$css .= Merchant_Custom_CSS::get_variable_css( 'quick-view', 'button-position-top', '50', '.merchant-quick-view-button', '--mrc-qv-button-position-top', '%' );

		// Button Position Top (Mobile).
		$css .= Merchant_Custom_CSS::get_variable_css( 'quick-view', 'button-position-top-mobile', '50', '.merchant-quick-view-button', '--mrc-qv-button-position-top-mobile', '%' );

		// Button Position Left.
		$css .= Merchant_Custom_CSS::get_variable_css( 'quick-view', 'button-position-left', '50', '.merchant-quick-view-button', '--mrc-qv-button-position-left', '%' );

		// Button Position Left (Mobile).
		$css .= Merchant_Custom_CSS::get_variable_css( 'quick-view', 'button-position-left-mobile', '50', '.merchant-quick-view-button', '--mrc-qv-button-position-left-mobile', '%' );

		// Modal Width.
		$css .= Merchant_Custom_CSS::get_variable_css( 'quick-view', 'modal_width', 1000, '.merchant-quick-view-modal', '--mrc-qv-modal-width', 'px' );

		// Modal Height.
		$css .= Merchant_Custom_CSS::get_variable_css( 'quick-view', 'modal_height', 500, '.merchant-quick-view-modal', '--mrc-qv-modal-height', 'px' );

		// Sale Price Color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'quick-view', 'sale-price-color', '#212121', '.merchant-quick-view-modal', '--mrc-qv-modal-sale-price-color' );

		// Regular Price Color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'quick-view', 'regular-price-color', '#414141', '.merchant-quick-view-modal', '--mrc-qv-modal-regular-price-color' );

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

	/**
	 * Frontend custom CSS.
	 * 
	 * @param string $css The custom CSS.
	 * @return string $css The custom CSS.
	 */
	public function frontend_custom_css( $css ) {
		$css .= $this->get_module_custom_css();

		$theme      = wp_get_theme();
		$theme_name = $theme->get( 'Name' );

		if ( 'Astra' === $theme_name ) {
			$css .= '
                .astra-shop-thumbnail-wrap {
                    position: relative;
                }
                .astra-shop-thumbnail-wrap img {
                    width: 100%;
                }
            ';
        }

		if ( 'Storefront' === $theme_name ) {
			$css .= '
                .merchant-storefront-thumbnail-wrapper {
                    position: relative;
                }
            ';
        }

		return $css;
	}
}

// Initialize the module.
add_action( 'init', function() {
	Merchant_Quick_View::get_instance();
} );
