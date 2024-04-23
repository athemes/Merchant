<?php

/**
 * Product swatches.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Product swatches class.
 *
 */
class Merchant_Product_Swatches extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'product-swatches';

	/**
	 * Module default settings.
	 */
	const MODULE_DEFAULT_SETTINGS = array(

		/**
		 * Settings
		 */
		'on_shop_catalog'                            => 0,
		'mouseover'                                  => 0,
		'tooltip'                                    => 0,
		'display_variation_name_on_product_title'    => 0,

		/**
		 * Select swatch settings
		 */
		'select_text_color'                          => '#212121',
		'select_border_color'                        => '#212121',
		'select_background_color'                    => '#ffffff',
		'select_padding'                             => array(
			'desktop' => array( 'unit' => 'px', 'top' => '10', 'right' => '15', 'bottom' => '10', 'left' => '15' ),
			'tablet'  => array( 'unit' => 'px', 'top' => '10', 'right' => '15', 'bottom' => '10', 'left' => '15' ),
			'mobile'  => array( 'unit' => 'px', 'top' => '10', 'right' => '15', 'bottom' => '10', 'left' => '15' ),
		),
		'select_border_radius'                       => array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0' ),
		'select_custom_style_shop_archive'           => 1,
		'select_text_color_shop_archive'             => '#212121',
		'select_border_color_shop_archive'           => '#212121',
		'select_background_color_shop_archive'       => '#ffffff',
		'select_padding_shop_archive'                => array(
			'desktop' => array( 'unit' => 'px', 'top' => '10', 'right' => '15', 'bottom' => '10', 'left' => '15' ),
			'tablet'  => array( 'unit' => 'px', 'top' => '10', 'right' => '15', 'bottom' => '10', 'left' => '15' ),
			'mobile'  => array( 'unit' => 'px', 'top' => '10', 'right' => '15', 'bottom' => '10', 'left' => '15' ),
		),
		'select_border_radius_shop_archive'          => array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0' ),

		/**
		 * Color swatch settings
		 */
		'color_width'                                => 26,
		'color_height'                               => 26,
		'color_spacing'                              => 5,
		'color_border_color'                         => '#dddddd',
		'color_border_hover_color'                   => '#212121',
		'color_border_width'                         => array(
			'desktop' => array( 'unit' => 'px', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1' ),
			'tablet'  => array( 'unit' => 'px', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1' ),
			'mobile'  => array( 'unit' => 'px', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1' ),
		),
		'color_border_radius'                        => array( 'unit' => 'px', 'top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50' ),
		'color_custom_style_shop_archive'            => 1,
		'color_width_shop_archive'                   => 26,
		'color_height_shop_archive'                  => 26,
		'color_spacing_shop_archive'                 => 5,
		'color_border_color_shop_archive'            => '#dddddd',
		'color_border_hover_color_shop_archive'      => '#212121',
		'color_border_width_shop_archive'            => array(
			'desktop' => array( 'unit' => 'px', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1' ),
			'tablet'  => array( 'unit' => 'px', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1' ),
			'mobile'  => array( 'unit' => 'px', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1' ),
		),
		'color_border_radius_shop_archive'           => array( 'unit' => 'px', 'top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50' ),

		/**
		 * Button swatch settings
		 */
		'button_text_color'                          => '#666666',
		'button_text_hover_color'                    => '#212121',
		'button_border_color'                        => '#666666',
		'button_border_hover_color'                  => '#212121',
		'button_background_color'                    => '#ffffff',
		'button_background_hover_color'              => '#ffffff',
		'button_padding'                             => array(
			'desktop' => array( 'unit' => 'px', 'top' => '10', 'right' => '15', 'bottom' => '10', 'left' => '15' ),
			'tablet'  => array( 'unit' => 'px', 'top' => '10', 'right' => '15', 'bottom' => '10', 'left' => '15' ),
			'mobile'  => array( 'unit' => 'px', 'top' => '10', 'right' => '15', 'bottom' => '10', 'left' => '15' ),
		),
		'button_spacing'                             => 5,
		'button_border_width'                        => array(
			'desktop' => array( 'unit' => 'px', 'top' => '2', 'right' => '2', 'bottom' => '2', 'left' => '2' ),
			'tablet'  => array( 'unit' => 'px', 'top' => '2', 'right' => '2', 'bottom' => '2', 'left' => '2' ),
			'mobile'  => array( 'unit' => 'px', 'top' => '2', 'right' => '2', 'bottom' => '2', 'left' => '2' ),
		),
		'button_border_radius'                       => array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0' ),
		'button_custom_style_shop_archive'           => 1,
		'button_text_color_shop_archive'             => '#666666',
		'button_text_hover_color_shop_archive'       => '#212121',
		'button_border_color_shop_archive'           => '#666666',
		'button_border_hover_color_shop_archive'     => '#212121',
		'button_background_color_shop_archive'       => '#ffffff',
		'button_background_hover_color_shop_archive' => '#ffffff',
		'button_padding_shop_archive'                => array(
			'desktop' => array( 'unit' => 'px', 'top' => '10', 'right' => '15', 'bottom' => '10', 'left' => '15' ),
			'tablet'  => array( 'unit' => 'px', 'top' => '10', 'right' => '15', 'bottom' => '10', 'left' => '15' ),
			'mobile'  => array( 'unit' => 'px', 'top' => '10', 'right' => '15', 'bottom' => '10', 'left' => '15' ),
		),
		'button_spacing_shop_archive'                => 5,
		'button_border_width_shop_archive'           => array(
			'desktop' => array( 'unit' => 'px', 'top' => '2', 'right' => '2', 'bottom' => '2', 'left' => '2' ),
			'tablet'  => array( 'unit' => 'px', 'top' => '2', 'right' => '2', 'bottom' => '2', 'left' => '2' ),
			'mobile'  => array( 'unit' => 'px', 'top' => '2', 'right' => '2', 'bottom' => '2', 'left' => '2' ),
		),
		'button_border_radius_shop_archive'          => array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0' ),

		/**
		 * Image swatch settings
		 */
		'image_width'                                => 26,
		'image_height'                               => 26,
		'image_spacing'                              => 5,
		'image_border_color'                         => '#dddddd',
		'image_border_hover_color'                   => '#212121',
		'image_border_width'                         => array(
			'desktop' => array( 'unit' => 'px', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1' ),
			'tablet'  => array( 'unit' => 'px', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1' ),
			'mobile'  => array( 'unit' => 'px', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1' ),
		),
		'image_border_radius'                        => array( 'unit' => 'px', 'top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50' ),
		'image_custom_style_shop_archive'            => 1,
		'image_width_shop_archive'                   => 26,
		'image_height_shop_archive'                  => 26,
		'image_spacing_shop_archive'                 => 5,
		'image_border_color_shop_archive'            => '#dddddd',
		'image_border_hover_color_shop_archive'      => '#212121',
		'image_border_width_shop_archive'            => array(
			'desktop' => array( 'unit' => 'px', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1' ),
			'tablet'  => array( 'unit' => 'px', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1' ),
			'mobile'  => array( 'unit' => 'px', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1' ),
		),
		'image_border_radius_shop_archive'           => array( 'unit' => 'px', 'top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50' ),
	);

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

		// Parent construct.
		parent::__construct();

		// Module section.
		$this->module_section = 'improve-experience';

		// Module default settings.
		$this->module_default_settings = self::MODULE_DEFAULT_SETTINGS;

		// Module data.
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];

		// Module preview URL
		$this->module_data['preview_url'] = $this->set_module_preview_url( array(
			'type'  => 'product',
			'query' => array(
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				'tax_query' => array(
					array(
						'taxonomy' => 'product_type',
						'field'    => 'slug',
						'terms'    => 'variable',
					),
				),
			),
		) );

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
	}

	/**
	 * Admin enqueue CSS.
	 *
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( parent::is_module_settings_page() ) {
			wp_enqueue_style(
				'merchant-' . $this->module_id,
				MERCHANT_URI . "assets/css/modules/{$this->module_id}/admin/preview.min.css",
				array(),
				MERCHANT_VERSION
			);
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
                <div class="mrc-preview-text-placeholder mrc-mw-40"></div>
                <div class="single-product">
                    <table class="variations" cellspacing="0" role="presentation">
                        <tbody>
                        <tr>
                            <th class="label">
                                <label for="pa_color"><?php echo esc_html__( 'Color', 'merchant' ) ?></label>
                            </th>
                            <td class="value">
                                <div class="merchant-variations-wrapper merchant-variations-mouseover" data-type="color">
                                    <div class="merchant-variation-type-color">
                                        <a href="#" role="button" class="merchant-variation-item" value="blue" data-stock-status="instock">
											<span class="merchant-variation-text" style="background-color: #1e73be;">
												<?php if ( $settings['tooltip'] ) : ?>
                                                    <span class="merchant-variation-tooltip"><?php echo esc_html__( 'Blue', 'merchant' ) ?></span>
												<?php endif; ?>
											</span>
                                        </a>
                                        <a href="#" role="button" class="merchant-variation-item active" value="red" data-stock-status="instock">
											<span class="merchant-variation-text" style="background-color: #dd3333;">
												<?php if ( $settings['tooltip'] ) : ?>
                                                    <span class="merchant-variation-tooltip"><?php echo esc_html__( 'Red', 'merchant' ) ?></span>
												<?php endif; ?>
											</span>
                                        </a>
                                        <a href="#" role="button" class="merchant-variation-item" value="white" data-stock-status="instock">
											<span class="merchant-variation-text" style="background-color: #ffffff;">
												<?php if ( $settings['tooltip'] ) : ?>
                                                    <span class="merchant-variation-tooltip"><?php echo esc_html__( 'White', 'merchant' ) ?></span>
												<?php endif; ?>
											</span>
                                        </a>
                                    </div>
                                    <select id="pa_color" class="" name="attribute_pa_color" data-attribute_name="attribute_pa_color" data-show_option_none="yes">
                                        <option value=""><?php echo esc_html__( 'Select', 'merchant' ) ?>></option>
                                        <option value="blue"><?php echo esc_html__( 'Blue', 'merchant' ) ?></option>
                                        <option value="red"><?php echo esc_html__( 'Red', 'merchant' ) ?></option>
                                        <option value="white"><?php echo esc_html__( 'White', 'merchant' ) ?></option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="label">
                                <label for="pa_select"><?php echo esc_html__( 'Select', 'merchant' ) ?></label>
                            </th>
                            <td class="value">
                                <div class="merchant-variation-type-select">
                                    <select class="merchant-variation-item active" id="pa_select" name="attribute_pa_select" data-attribute_name="attribute_pa_select" data-show_option_none="yes">
                                        <option value=""><?php echo esc_html__( 'Select', 'merchant' ) ?></option>
                                        <option value="slim"><?php echo esc_html__( 'Slim', 'merchant' ) ?></option>
                                        <option value="wavy"><?php echo esc_html__( 'Wavy', 'merchant' ) ?></option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="label">
                                <label for="pa_image"><?php echo esc_html__( 'Image', 'merchant' ) ?></label>
                            </th>
                            <td class="value">
                                <div class="merchant-variations-wrapper merchant-variations-mouseover" data-type="image">
                                    <div class="merchant-variation-type-image">
                                        <a href="#" role="button" class="merchant-variation-item" data-stock-status="instock" value="image-option-1">
                            <span class="merchant-variation-text">
                                <img src="<?php echo esc_url( MERCHANT_URI . 'assets/images/dummy/Glamifiedpeach.jpeg' ); ?>" alt="<?php echo esc_html__( 'Image option 1', 'merchant' ) ?>"
                                    loading="lazy">
								<?php if ( $settings['tooltip'] ) : ?>
                                    <span class="merchant-variation-tooltip"><?php echo esc_html__( 'Image option 1', 'merchant' ) ?></span>
								<?php endif; ?>
                            </span>
                                        </a>
                                        <a href="#" role="button" class="merchant-variation-item active" data-stock-status="instock" value="image-option-2">
                            <span class="merchant-variation-text">
                                <img src="<?php echo esc_url( MERCHANT_URI . 'assets/images/dummy/Pearlville.jpeg' ); ?>" alt="<?php echo esc_html__( 'Image option 2', 'merchant' ) ?>" loading="lazy">
									<?php if ( $settings['tooltip'] ) : ?>
                                        <span class="merchant-variation-tooltip"><?php echo esc_html__( 'Image option 2', 'merchant' ) ?></span>
									<?php endif; ?>
                            </span>
                                        </a>
                                        <a href="#" role="button" class="merchant-variation-item" data-stock-status="instock" value="image-option-3">
                            <span class="merchant-variation-text">
                                <img src="<?php echo esc_url( MERCHANT_URI . 'assets/images/dummy/Glamifiedviola.jpeg' ); ?>" alt="<?php echo esc_html__( 'Image option 3', 'merchant' ) ?>"
                                    loading="lazy">
									<?php if ( $settings['tooltip'] ) : ?>
                                        <span class="merchant-variation-tooltip"><?php echo esc_html__( 'Image option 3', 'merchant' ) ?></span>
									<?php endif; ?>
                            </span>
                                        </a>
                                    </div>
                                    <select id="pa_image" class="" name="attribute_pa_image" data-attribute_name="attribute_pa_image" data-show_option_none="yes">
                                        <option value=""><?php echo esc_html__( 'Select', 'merchant' ) ?></option>
                                        <option value="image-option-1"><?php echo esc_html__( 'Image option 1', 'merchant' ) ?></option>
                                        <option value="image-option-2"><?php echo esc_html__( 'Image option 2', 'merchant' ) ?></option>
                                        <option value="image-option-3"><?php echo esc_html__( 'Image option 3', 'merchant' ) ?></option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="label">
                                <label for="pa_button">Button</label>
                            </th>
                            <td class="value">
                                <div class="merchant-variations-wrapper merchant-variations-mouseover" data-type="button">
                                    <div class="merchant-variation-type-button">
                                        <a href="#" role="button" class="merchant-variation-item" data-stock-status="instock" value="button-option-1">
											<?php echo esc_html__( 'Button option 1', 'merchant' ) ?>
                                        </a>
                                        <a href="#" role="button" class="merchant-variation-item active" data-stock-status="instock" value="button-option-2">
											<?php echo esc_html__( 'Button option 2', 'merchant' ) ?>
                                        </a>
                                    </div>
                                    <select id="pa_button" class="" name="attribute_pa_button" data-attribute_name="attribute_pa_button" data-show_option_none="yes">
                                        <option value=""><?php echo esc_html__( 'Select', 'merchant' ) ?></option>
                                        <option value="button-option-1"><?php echo esc_html__( 'Button option 1', 'merchant' ) ?></option>
                                        <option value="button-option-2"><?php echo esc_html__( 'Button option 2', 'merchant' ) ?></option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
                <div class="mrc-preview-addtocart-placeholder"></div>
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
		$settings = $this->get_module_settings();

		$css = '
		    .merchant-product-swatches .merchant-bogo,
		    .merchant-product-swatches .merchant-volume-discounts {
                display: none;
            }

            .wp-block-product-new .wc-block-grid__product .merchant-product-swatches .single_variation_wrap,
			.merchant-variations-wrapper select,
			 .merchant-variations-wrapper .theme-select {
			    display: none!important;
			}
			.merchant-variations-wrapper .merchant-variation-type-image,
			.merchant-variations-wrapper .merchant-variation-type-color {
			    display: -webkit-box;
			    display: -ms-flexbox;
			    display: flex;
			    grid-gap: 5px;
			    -ms-flex-wrap: wrap;
			    flex-wrap: wrap;

			}
	        .merchant-variations-wrapper .merchant-variation-type-color > a,
	        .merchant-variations-wrapper .merchant-variation-type-image > a {
			    position: relative;
			    cursor: pointer;
			    display: -webkit-box;
			    display: -ms-flexbox;
			    display: flex;
			    padding: 2px;
			    -webkit-transition: border-color .3s;
			    transition: border-color .3s;
			    -webkit-box-sizing: content-box;
			    box-sizing: content-box;
		        border-style: solid;
			}
			.merchant-variations-wrapper .merchant-variation-type-image > a > span,
			.merchant-variations-wrapper .merchant-variation-type-color > a > span {
			    width: 100%;
			    height: 100%;
			}
			.merchant-variation-type-color > a > span {
			    text-indent: -9999px;
			}
			.merchant-variations-wrapper .merchant-variation-type-button,
			.merchant-variations-wrapper .merchant-variation-type-select {
			    display: -webkit-box;
			    display: -ms-flexbox;
			    display: flex;
			    -ms-flex-wrap: wrap;
			    flex-wrap: wrap;
			}
			.merchant-variations-wrapper .merchant-variation-type-button > a,
			.merchant-variations-wrapper .merchant-variation-type-select > a {
			    display: -webkit-box;
			    display: -ms-flexbox;
			    display: flex;
			    -webkit-box-align: center;
			    -ms-flex-align: center;
			    align-items: center;
			    -webkit-box-pack: center;
			    -ms-flex-pack: center;
			    justify-content: center;
			    font-size: 0.9rem;
			    font-weight: 600;
			    line-height: 1;
			    min-width: 30px;
			    min-height: 30px;
			    text-align: center;
			    cursor: pointer;
			    overflow: hidden;
			    -webkit-transition: ease all 300ms;
			    transition: ease all 300ms;
		        border-style: solid;
			}
			.merchant-variations-wrapper .merchant-variation-type-image img {
		        vertical-align: inherit;
			}
			
			.merchant-variations-wrapper .merchant-variation-type-button > a,
			.merchant-variations-wrapper .merchant-variation-type-select > a,
			.merchant-product-swatches .merchant-add-to-cart-button {
			    text-decoration: none !important;
			}
		';

		// Variation name on title
		$display_variation_name_on_product_title = $settings['display_variation_name_on_product_title'];

		if ( $display_variation_name_on_product_title ) {
			$css .= '
                .merchant-ptitle-variation-name {
                    display: block;
                    font-size: 0.7em;
                }
            ';
		}

		$enable_tooltip = $settings['tooltip'];

		if ( $enable_tooltip ) {
			$css .= '
				.merchant-variations-wrapper .merchant-variation-tooltip {
				    position: absolute;
				    bottom: 100%;
				    left: 50%;
				    -webkit-transform: translateX(-50%);
				    transform: translateX(-50%);
				    margin-bottom: 5px;
				    white-space: nowrap;
				    color: #fff;
				    background-color: #212121;
				    font-size: 12px;
				    line-height: 1.5em;
				    text-align: center;
				    text-indent: 0;
				    padding: 4px 10px;
				    opacity: 0;
				    visibility: hidden;
				    pointer-events: none;
				    -webkit-transition: all .3s;
				    transition: all .3s;
				}
				.merchant-variations-wrapper .merchant-variation-type-color > a:hover .merchant-variation-tooltip,
				.merchant-variations-wrapper .merchant-variation-type-image > a:hover .merchant-variation-tooltip {
				    opacity: 1;
				    margin-bottom: 10px;
				    visibility: visible;
				}
		';
		}

		$on_shop_catalog = $settings['on_shop_catalog'];

		if ( $on_shop_catalog ) {
			$swatches_alignment = '-webkit-box-pack:start; -ms-flex-pack:start; justify-content:flex-start;';

			$css .= '
                ul.wc-block-grid__products li.wc-block-grid__product .merchant-variations-wrapper .merchant-variation-type-image,
                ul.wc-block-grid__products li.wc-block-grid__product .merchant-variations-wrapper .merchant-variation-type-color,
                ul.wc-block-grid__products li.product .merchant-variations-wrapper .merchant-variation-type-image,
                ul.wc-block-grid__products li.product .merchant-variations-wrapper .merchant-variation-type-color,
                ul.products li.wc-block-grid__product .merchant-variations-wrapper .merchant-variation-type-image,
                ul.products li.wc-block-grid__product .merchant-variations-wrapper .merchant-variation-type-color,
                ul.products li.product .merchant-variations-wrapper .merchant-variation-type-image,
                ul.products li.product .merchant-variations-wrapper .merchant-variation-type-color {'
					. esc_attr( $swatches_alignment ) .
					'}
            ';
		}

		// Swatches Wrapper Selectors
		$selectors = array(
			'.single-product'                                      => '',
			'ul.products li.product'                               => '_shop_archive',
			'ul.wc-block-grid__products li.wc-block-grid__product' => '_shop_archive',
		);

		// Swatches Styling (Select)

		$shop_archive_select_custom = $settings['select_custom_style_shop_archive'];

		foreach ( $selectors as $css_wrapper => $setting_slug ) {
			// If inherit is enabled, then set the mod slug to the first one (empty)
			if ( '_shop_archive' === $setting_slug && ! $shop_archive_select_custom ) {
				$setting_slug = '';
			}

			// Text Color
			$css .= Merchant_Custom_CSS::get_color_css(
				$this->module_id,
				'select_text_color' . $setting_slug,
				$this->module_default_settings[ 'select_text_color' . $setting_slug ],
				$css_wrapper . ' .variations select',
				false,
				true
			);

			// Border Color
			$css .= Merchant_Custom_CSS::get_border_color_css(
				$this->module_id,
				'select_border_color' . $setting_slug,
				$this->module_default_settings[ 'select_border_color' . $setting_slug ],
				$css_wrapper . ' .variations select',
				false,
				true
			);

			// Background Color
			$css .= Merchant_Custom_CSS::get_background_color_css(
				$this->module_id,
				'select_background_color' . $setting_slug,
				$this->module_default_settings[ 'select_background_color' . $setting_slug ],
				$css_wrapper . ' .variations select',
				false,
				true
			);

			if ( method_exists( 'Merchant_Custom_CSS', 'get_responsive_dimensions_css' ) ) {
				// Padding
				$css .= Merchant_Custom_CSS::get_responsive_dimensions_css(
					$this->module_id,
					'select_padding' . $setting_slug,
					$this->module_default_settings[ 'select_padding' . $setting_slug ],
					$css_wrapper . ' .variations select',
					'padding'
				);

				// Broder Radius
				$css .= Merchant_Custom_CSS::get_dimensions_css(
					$this->module_id,
					'select_border_radius' . $setting_slug,
					$this->module_default_settings[ 'select_border_radius' . $setting_slug ],
					$css_wrapper . ' .variations select',
					'border-radius'
				);
			}
		}

		// Swatches Styling (Color)

		$shop_archive_color_custom = $settings['color_custom_style_shop_archive'];

		foreach ( $selectors as $css_wrapper => $setting_slug ) {
			// If inherit is enabled, then set the mod slug to the first one (empty)
			if ( '_shop_archive' === $setting_slug && ! $shop_archive_color_custom ) {
				$setting_slug = '';
			}

			// Width
			$css .= Merchant_Custom_CSS::get_variable_css(
				$this->module_id,
				'color_width' . $setting_slug,
				$this->module_default_settings[ 'color_width' . $setting_slug ],
				$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-color>a',
				'width',
				'px'
			);

			// Height
			$css .= Merchant_Custom_CSS::get_variable_css(
				$this->module_id,
				'color_height' . $setting_slug,
				$this->module_default_settings[ 'color_height' . $setting_slug ],
				$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-color>a',
				'height',
				'px'
			);

			// Spacing
			$css .= Merchant_Custom_CSS::get_variable_css(
				$this->module_id,
				'color_spacing' . $setting_slug,
				$this->module_default_settings[ 'color_spacing' . $setting_slug ],
				$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-color',
				'grid-gap',
				'px'
			);

			if ( method_exists( 'Merchant_Custom_CSS', 'get_responsive_dimensions_css' ) ) {
				// Border Width
				$css .= Merchant_Custom_CSS::get_responsive_dimensions_css(
					$this->module_id,
					'color_border_width' . $setting_slug,
					$this->module_default_settings[ 'color_border_width' . $setting_slug ],
					$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-color > a',
					'border-width'
				);

				// Border Radius
				$css .= Merchant_Custom_CSS::get_dimensions_css(
					$this->module_id,
					'color_border_radius' . $setting_slug,
					$this->module_default_settings[ 'color_border_radius' . $setting_slug ],
					$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-color > a, ' . $css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-color > a > span',
					'border-radius'
				);
			}

			// Border Color
			$css .= Merchant_Custom_CSS::get_border_color_css(
				$this->module_id,
				'color_border_color' . $setting_slug,
				$this->module_default_settings[ 'color_border_color' . $setting_slug ],
				$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-color > a',
				false,
				true
			);

			// Border Color Hover
			$css .= Merchant_Custom_CSS::get_border_color_css(
				$this->module_id,
				'color_border_hover_color' . $setting_slug,
				$this->module_default_settings[ 'color_border_hover_color' . $setting_slug ],
				$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-color > a:hover, ' . $css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-color > a.active',
				false,
				true
			);
		}

		// Swatches Styling (Button)

		$shop_archive_button_custom = $settings['button_custom_style_shop_archive'];

		foreach ( $selectors as $css_wrapper => $setting_slug ) {
			// If inherit is enabled, then set the mod slug to the first one (empty)
			if ( '_shop_archive' === $setting_slug && ! $shop_archive_button_custom ) {
				$setting_slug = '';
			}

			// Spacing
			$css .= Merchant_Custom_CSS::get_variable_css(
				$this->module_id,
				'button_spacing' . $setting_slug,
				$this->module_default_settings[ 'button_spacing' . $setting_slug ],
				$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-button',
				'grid-gap',
				'px'
			);

			// Text Color
			$css .= Merchant_Custom_CSS::get_color_css(
				$this->module_id,
				'button_text_color' . $setting_slug,
				$this->module_default_settings[ 'button_text_color' . $setting_slug ],
				$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-button > a',
				false,
				true
			);

			// Text Hover Color
			$css .= Merchant_Custom_CSS::get_color_css(
				$this->module_id,
				'button_text_hover_color' . $setting_slug,
				$this->module_default_settings[ 'button_text_hover_color' . $setting_slug ],
				$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-button > a:hover',
				false,
				true
			);
			$css .= Merchant_Custom_CSS::get_color_css(
				$this->module_id,
				'button_text_hover_color' . $setting_slug,
				$this->module_default_settings[ 'button_text_hover_color' . $setting_slug ],
				$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-button > a.active',
				false,
				true
			);

			// Border Color
			$css .= Merchant_Custom_CSS::get_border_color_css(
				$this->module_id,
				'button_border_color' . $setting_slug,
				$this->module_default_settings[ 'button_border_color' . $setting_slug ],
				$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-button > a',
				false,
				true
			);

			// Border Hover Color
			$css .= Merchant_Custom_CSS::get_border_color_css(
				$this->module_id,
				'button_border_hover_color' . $setting_slug,
				$this->module_default_settings[ 'button_border_hover_color' . $setting_slug ],
				$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-button > a:hover',
				false,
				true
			);
			$css .= Merchant_Custom_CSS::get_border_color_css(
				$this->module_id,
				'button_border_hover_color' . $setting_slug,
				$this->module_default_settings[ 'button_border_hover_color' . $setting_slug ],
				$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-button > a.active',
				false,
				true
			);

			// Background Color
			$css .= Merchant_Custom_CSS::get_background_color_css(
				$this->module_id,
				'button_background_color' . $setting_slug,
				$this->module_default_settings[ 'button_background_color' . $setting_slug ],
				$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-button > a',
				false,
				true
			);

			// Background Hover Color
			$css .= Merchant_Custom_CSS::get_background_color_css(
				$this->module_id,
				'button_background_hover_color' . $setting_slug,
				$this->module_default_settings[ 'button_background_hover_color' . $setting_slug ],
				$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-button > a:hover, ' . $css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-button > a.active',
				false,
				true
			);


			if ( method_exists( 'Merchant_Custom_CSS', 'get_responsive_dimensions_css' ) ) {
				// Padding
				$css .= Merchant_Custom_CSS::get_responsive_dimensions_css(
					$this->module_id,
					'button_padding' . $setting_slug,
					$settings[ 'button_padding' . $setting_slug ],
					$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-button > a',
					'padding'
				);

				// Border Width
				$css .= Merchant_Custom_CSS::get_responsive_dimensions_css(
					$this->module_id,
					'button_border_width' . $setting_slug,
					$settings[ 'button_border_width' . $setting_slug ],
					$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-button > a',
					'border-width'
				);

				// Broder Radius
				$css .= Merchant_Custom_CSS::get_dimensions_css(
					$this->module_id,
					'button_border_radius' . $setting_slug,
					$settings[ 'button_border_radius' . $setting_slug ],
					$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-button > a',
					'border-radius'
				);
			}
		}

		// Swatches Styleling (Image)

		$shop_archive_image_custom = $settings['image_custom_style_shop_archive'];

		foreach ( $selectors as $css_wrapper => $setting_slug ) {
			// If inherit is enabled, then set the mod slug to the first one (empty)
			if ( '_shop_archive' === $setting_slug && ! $shop_archive_image_custom ) {
				$setting_slug = '';
			}

			// Width
			$css .= Merchant_Custom_CSS::get_variable_css(
				$this->module_id,
				'image_width' . $setting_slug,
				$this->module_default_settings[ 'image_width' . $setting_slug ],
				$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-image>a',
				'width',
				'px'
			);

			// Height
			$css .= Merchant_Custom_CSS::get_variable_css(
				$this->module_id,
				'image_height' . $setting_slug,
				$this->module_default_settings[ 'image_height' . $setting_slug ],
				$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-image>a',
				'height',
				'px'
			);

			// Spacing
			$css .= Merchant_Custom_CSS::get_variable_css(
				$this->module_id,
				'image_spacing' . $setting_slug,
				$this->module_default_settings[ 'image_spacing' . $setting_slug ],
				$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-image',
				'grid-gap',
				'px'
			);

			if ( method_exists( 'Merchant_Custom_CSS', 'get_responsive_dimensions_css' ) ) {
				// Border Width
				$css .= Merchant_Custom_CSS::get_responsive_dimensions_css(
					$this->module_id,
					'image_border_width' . $setting_slug,
					$settings[ 'image_border_width' . $setting_slug ],
					$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-image > a',
					'border-width'
				);

				// Border Radius
				$css .= Merchant_Custom_CSS::get_dimensions_css(
					$this->module_id,
					'image_border_radius' . $setting_slug,
					$settings[ 'image_border_radius' . $setting_slug ],
					$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-image > a, ' . $css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-image > a > span, .merchant-variations-wrapper .merchant-variation-type-image>a img',
					'border-radius'
				);
			}

			// Border Color
			$css .= Merchant_Custom_CSS::get_border_color_css(
				$this->module_id,
				'image_border_color' . $setting_slug,
				$this->module_default_settings[ 'image_border_color' . $setting_slug ],
				$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-image > a',
				false,
				true
			);

			// Border Color Hover
			$css .= Merchant_Custom_CSS::get_border_color_css(
				$this->module_id,
				'image_border_hover_color' . $setting_slug,
				$this->module_default_settings[ 'image_border_hover_color' . $setting_slug ],
				$css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-image > a:hover, ' . $css_wrapper . ' .merchant-variations-wrapper .merchant-variation-type-image > a.active',
				false,
				true
			);
		}


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
	Merchant_Modules::create_module( new Merchant_Product_Swatches() );
} );
