<?php

/**
 * Product Labels.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Product Labels Class.
 *
 */
class Merchant_Product_Labels extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'product-labels';

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
		$this->module_section = 'convert-more';

		// Module default settings.
		$this->module_default_settings = array(
			'layout' => 'single-label',
            'labels' => array(
	            array(
		            'label_type'       => 'text',
		            'label'            => esc_html__( 'SALE', 'merchant' ),
                    'label_text_shape' => 'text-shape-1',
                    'show_pages'       => array( 'homepage', 'single', 'archive' ),
                    'show_devices'     => array( 'desktop', 'mobile' ),
	            ),
            ),
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
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_js' ) );

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
		if ( is_admin() && ! parent::is_module_settings_page() ) {
			return;
		}

		// Enqueue styles.
		add_action( 'merchant_enqueue_before_main_css_js', array( $this, 'enqueue_css' ) );

        // Enqueue scripts
		add_action( 'merchant_enqueue_before_main_css_js', array( $this, 'enqueue_js' ) );

		// Inject module content in the products.
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'loop_product_output' ) );
		add_action( 'woocommerce_product_thumbnails', array( $this, 'single_product_output' ) );
		add_action( 'woostify_product_images_box_end', array( $this, 'single_product_output' ) );
		add_action( 'woocommerce_single_product_image_gallery_classes', array( $this, 'single_product_image_gallery_classes' ) );
		add_filter( 'woocommerce_blocks_product_grid_item_html', array( $this, 'products_block' ), 10, 3 );

		// Custom CSS.
		add_filter( 'merchant_custom_css', array( $this, 'frontend_custom_css' ) );
	}

	/**
	 * Init translations.
	 *
	 * @return void
	 */
	public function init_translations() {
		$settings = $this->get_module_settings();
		if ( isset( $settings['labels'] ) ) {
			foreach ( $settings['labels'] as $label ) {
				if ( isset( $label['label'] ) ) {
					Merchant_Translator::register_string( $label['label'], esc_html__( 'Product Labels', 'merchant' ) );
				}
				if ( isset( $label['percentage_text'] ) ) {
					Merchant_Translator::register_string( $label['percentage_text'], esc_html__( 'Percentage text in product labels', 'merchant' ) );
				}
			}
		}
	}

	/**
	 * Function for `woocommerce_blocks_product_grid_item_html` filter-hook.
	 *
	 * @param string      $html    Product grid item HTML.
	 * @param array       $data    Product data passed to the template.
	 * @param \WC_Product $product Product object.
	 *
	 * @return string
	 */
	public function products_block( $html, $data, $product ) {
		return str_replace( '</li>', $this->get_labels( $product, 'archive' ) . '</li>', $html );
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
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/product-labels.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
		}
	}

	/**
	 * Admin enqueue CSS.
	 *
	 * @return void
	 */
	public function admin_enqueue_js() {
		$page   = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$module = ( ! empty( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( 'merchant' === $page && self::MODULE_ID === $module ) {
			wp_enqueue_script( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/product-labels/admin/preview.min.js', array( 'jquery', 'merchant-admin' ), '4.0.13',
				true );
		}
	}

	/**
	 * Enqueue CSS.
	 *
	 * @return void
	 */
	public function enqueue_css() {
		// Specific module styles.
		wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/product-labels.min.css', array(), MERCHANT_VERSION );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @return void
	 */
	public function enqueue_js() {
		wp_enqueue_script( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/product-labels.min.js', array(), MERCHANT_VERSION, true );
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
			ob_start();
			self::admin_preview_content();
			$content = ob_get_clean();

			// HTML.
			$preview->set_html( $content );

			// Label Text.
			$preview->set_text( 'label_text', '.merchant-onsale' );

			// Position.
			$preview->set_class( 'label_position', '.merchant-onsale', array( 'top-left', 'top-right' ) );
		}

		return $preview;
	}

	/**
	 * Admin preview content.
	 *
	 * @return void
	 */
	public function admin_preview_content() {
		?>
        <div class="merchant-product-labels-preview">
            <div class="image-wrapper">
                <div class="merchant-product-labels" data-currency="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>">
                    <span class="merchant-label merchant-label-top-left"></span>
                </div>
            </div>
            <h3><?php
				echo esc_html__( 'Product Title', 'merchant' ); ?></h3>
            <p><?php
				echo esc_html__( 'The product description normally goes here.', 'merchant' ); ?></p>
        </div>

		<?php
	}

	/**
	 * Get sale percentage & amount
	 *
	 * @return string label
	 */
	public function get_product_sale_data( $product, $label ) {
		$sale_data = array();

        if ( $product->is_type( 'variable' ) ) {
	        $amounts     = array();
            $percentages = array();
            $prices      = $product->get_variation_prices();

            foreach ( $prices['price'] as $key => $price ) {
                if ( $prices['regular_price'][ $key ] !== $price ) {
                    $regular_price = (float) $prices['regular_price'][ $key ];
                    $sale_price    = (float) $prices['sale_price'][ $key ];

	                $amounts[]     = $regular_price - $sale_price;
	                $percentages[] = round( 100 - ( $sale_price / $regular_price * 100 ) );
                }
            }

	        $sale_data['amount']     = ! empty( $amounts ) ? max( $amounts ) : '';;
	        $sale_data['percentage'] = ! empty( $percentages ) ? max( $percentages ) : '';
        } elseif ( $product->is_type( 'grouped' ) ) {
	        $amounts      = array();
            $percentages  = array();
            $children_ids = $product->get_children();

            foreach ( $children_ids as $child_id ) {
                $child_product = wc_get_product( $child_id );
                $regular_price = (float) $child_product->get_regular_price();
                $sale_price    = (float) $child_product->get_sale_price();

                if ( 0 !== $sale_price || ! empty( $sale_price ) ) {
	                $amounts[]     = $regular_price - $sale_price;
                    $percentages[] = round( 100 - ( ( $sale_price / $regular_price ) * 100 ) );
                }
            }

	        $sale_data['amount']     = ! empty( $amounts ) ? max( $amounts ) : '';
	        $sale_data['percentage'] = ! empty( $percentages ) ? max( $percentages ) : '';
        } else {
            $regular_price = (float) $product->get_regular_price();
            $sale_price    = (float) $product->get_sale_price();

            if ( 0 !== $sale_price || ! empty( $sale_price ) ) {
	            $sale_data['amount']     = $regular_price - $sale_price;
	            $sale_data['percentage'] = round( 100 - ( ( $sale_price / $regular_price ) * 100 ) );
            }
        }

        /**
         * Filter the product sale dat .
         *
         * @param int        $percentage The product discount percentage.
         * @param WC_Product $product    The product object.
         * @param array      $label      The label data.
         *
         * @since 1.9.7
         */
        return apply_filters( 'merchant_product_labels_product_sale', $sale_data, $product, $label );
	}

	/**
	 * Custom CSS.
	 *
	 * @return string
	 */
	public function get_module_custom_css() {
		$css = '';

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

	/**
	 * Frontend custom CSS.
	 *
	 * @param string $css The custom CSS.
	 *
	 * @return string $css The custom CSS.
	 */
	public function frontend_custom_css( $css ) {
		// Append module custom CSS.
		$css .= $this->get_module_custom_css();

		// Append module custom CSS based on themes (ensure themes compatibility).
		// Detect the current theme.
		$theme      = wp_get_theme();
		$theme_name = $theme->get( 'Name' );

		// All themes.
		$css .= '
			.woocommerce .onsale,
			.wc-block-grid__product .onsale { 
				display: none !important; 
			}
		';

		// Astra.
		if ( 'Astra' === $theme_name ) {
			$css .= '
				.woocommerce .ast-onsale-card {
					display: none !important;
				}
			';
		}

		return $css;
	}

	/**
	 * Single product output.
	 *
	 * @return void
	 */
	public function single_product_output() {
		global $product;

		echo wp_kses( $this->get_labels( $product, 'single' ), array(
			'div'    => array(
				'class' => array(),
				'style' => array(),
			),
			'strong' => array(),
			'span'   => array(
				'class' => array(),
				'style' => array(),
			),
            'img'   => array(
				'src'    => true,
				'width'  => true,
				'height' => true,
				'class'  => array(),
				'style'  => array(),
			),
		) );
	}

	/**
     * Add class to image wrapper in Product page.
     *
	 * @param $classes
	 *
	 * @return mixed
	 */
	public function single_product_image_gallery_classes( $classes ) {
        $classes[] = 'merchant-product-labels-image-wrap';

        return $classes;
    }

	/**
	 * Loop product output.
	 *
	 * @return void
	 */
	public function loop_product_output() {
		global $product;

		echo wp_kses( $this->get_labels( $product, 'archive' ), array(
			'div'    => array(
				'class' => array(),
				'style' => array(),
			),
			'strong' => array(),
			'span'   => array(
				'class' => array(),
				'style' => array(),
			),
			'img'   => array(
				'src'    => true,
				'width'  => true,
				'height' => true,
				'class'  => array(),
				'style'  => array(),
			),
		) );
	}

	/**
	 * Get labels group.
	 *
	 * @param $product WC_Product product object.
	 * @param $context string context archive or single.
	 *
	 * @return string product labels html.
	 */
	public function get_labels( $product, $context = 'both' ) {
		$settings            = $this->get_module_settings();
		$product_labels_html = '';

		if ( isset( $settings['labels'] ) ) {
			$labels = $settings['labels'];
			foreach ( $labels as $label ) {
				if ( ! isset( $label['show_pages'] ) || ! $this->show_label( $label ) ) {
					continue;
				}

				$rule = $label['display_rules'] ?? 'products_on_sale';

				if ( in_array( $rule, array( 'all_products', 'featured_products', 'products_on_sale', 'by_category', 'out_of_stock' ), true ) ) {
					$excluded_product_ids = $label['excluded_products'] ?? array();
					$excluded_product_ids = merchant_parse_product_ids( $excluded_product_ids );

					if ( in_array( (int) $product->get_id(), $excluded_product_ids, true ) ) {
						continue;
					}
				}

				switch ( $rule ) {
					case 'featured_products':
						if ( $this->is_featured( $product ) ) {
							$product_labels_html .= $this->label( $label );
						}
						break;

					case 'products_on_sale':
						if ( $this->is_on_sale( $product ) ) {
							$product_labels_html .= $this->label( $label );
						}
						break;

					case 'by_category':
						$categories   = $label['product_cats'] ?? array();
						$categories   = is_array( $categories ) ? $categories : (array) $categories;

                        if ( ! empty( $categories ) && $this->is_in_category( $product, $categories ) ) {
							$product_labels_html .= $this->label( $label );
						}
						break;

					case 'out_of_stock':
						if ( $this->is_out_of_stock( $product ) ) {
							$product_labels_html .= $this->label( $label );
						}
						break;

					case 'new_products':
						if ( isset( $label['new_products_days'] ) && $this->is_new( $product, $label['new_products_days'] ) ) {
							$product_labels_html .= $this->label( $label );
						}
						break;

					case 'specific_products':
						$product_ids = $label['product_ids'] ?? array();
						$product_ids = ! is_array( $product_ids ) ? explode( ',', $product_ids ) : $product_ids;
						$product_ids = array_map( 'intval', $product_ids );

						if ( in_array( $product->get_id(), $product_ids, true ) ) {
							$product_labels_html .= $this->label( $label );
						}
						break;

					case 'all_products':
                        $product_labels_html .= $this->label( $label );
						break;
				}

				if ( $product_labels_html ) {
					$sale_data       = $this->is_on_sale( $product ) ? $this->get_product_sale_data( $product, $label ) : array();
					$sale_amount     = wc_price( $sale_data['amount'] ?? '' );
					$sale_percentage = ( $sale_data['percentage'] ?? '' ) . '%';

                    $inventory     = $product->is_in_stock() ? esc_html__( 'In stock', 'merchant' ) : esc_html__( 'Sold out', 'merchant' );
					$inventory_qty = $product->get_stock_quantity();

                    // Replace {shortcodes} by real values.
					$product_labels_html = str_replace(
                        array(
                            '{sale}',
                            '{sale_amount}',
                            '{inventory}',
                            '{inventory_quantity}',
                        ),
						array(
							$sale_percentage,
							$sale_amount,
							$inventory,
							$inventory_qty,
                        ),
                        $product_labels_html
                    );

					$label_type     = $label['label_type'] ?? 'text';
					$label_position = $label['label_position'] ?? 'top-left';

					$styles = $this->get_shape_based_styles( $label );

					$device_visibility_classes = '';

					if ( isset( $label['show_devices'] ) ) {
						foreach ( $label['show_devices'] as $device ) {
							$device_visibility_classes .= ' show-on-' . $device;
						}
					}

					$classes  = 'merchant-product-labels';
					$classes .= ' position-' . $label_position;
					$classes .= ' merchant-product-labels__' . $label_type;
					$classes .= $label_type === 'text' ? ' merchant-product-labels__' . $label['label_text_shape'] ?? 'text-shape-1' : '';
					$classes .= $device_visibility_classes;

					return '<div class="' . esc_attr( $classes ) . '" style="' . merchant_array_to_css( $styles ) . '">' . $product_labels_html . '</div>';
				}
			}
		} else {
			// legacy mode support.
			return $this->legacy_product_label( $product );
		}

		return '';
	}

	/**
     * Get all styles for the current label
     *
	 * @param $label
	 *
	 * @return array
	 */
	public function get_shape_based_styles( $label ) {
		$styles           = array();

        if ( ! is_array( $label ) || empty( $label ) ) {
            return $styles;
        }

		$label_position = $label['label_position'] ?? 'top-left';
		$label_type     = $label['label_type'] ?? 'text';
		$label_shape    = $label['label_text_shape'] ?? 'text-shape-1';

		if ( $label_type === 'text' ) {
			$styles['width']     = ( $label['label_width'] ?? 100 ) . 'px';
			$styles['height']    = ( $label['label_height'] ?? 32 ) . 'px';
			$styles['font-size'] = ( $label['font_size'] ?? 14 ) . 'px';

			$font_style = $label['font_style'] ?? 'normal';

            if ( strpos( $font_style, 'bold' ) !== false ) {
				$styles['font-weight'] = 'bold';
			}

			if ( strpos( $font_style, 'italic' ) !== false ) {
				$styles['font-style'] = 'italic';
			}

			$styles['background-color'] = $label['background_color'] ?? '#212121';
			$styles['color']            = $label['text_color'] ?? '#ffffff';
			$styles['border-radius']    = ( $label['shape_radius'] ?? 5 ) . 'px';
		}

		$styles['top']= ( $label['margin_y'] ?? 10 ) . 'px';
		$styles[ ( $label_position === 'top-left' ) ? 'left' : 'right' ] = ( $label['margin_x'] ?? 10 ) . 'px';

        return $styles;
    }

	/**
     * Should show the label for current page.
     *
	 * @param $label
	 *
	 * @return bool
	 */
	public function show_label( $label ) {
		$show       = false;
        $show_pages = $label ['show_pages' ] ?? array();

        if ( empty( $show_pages ) ) {
            return $show;
        }

		if ( is_product() && in_array( 'single', $show_pages, true ) ) {
			$show = true;
		}

		if ( in_array( 'archive', $show_pages, true ) && ( is_product_taxonomy() || is_shop() ) ) {
			$show = true;
		}

		if ( in_array( 'homepage', $show_pages, true ) && is_front_page() ) {
			$show = true;
		}

		return $show;
	}

	/**
	 * Product label output.
	 *
	 * @return string legacy product label html.
	 */
	private function legacy_product_label( $product ) {
		global $product;
		$settings = $this->get_module_settings();
		$styles   = array();

		if ( ! empty( $product ) && $this->is_on_sale( $product ) ) {
			$label_text = $settings['label_text'];
			if ( ! empty( $settings['display_percentage'] ) ) {
				if ( $product->is_type( 'variable' ) ) {
					$percentages = array();
					$prices      = $product->get_variation_prices();

					foreach ( $prices['price'] as $key => $price ) {
						if ( $prices['regular_price'][ $key ] !== $price ) {
							$percentages[] = round( 100 - ( floatval( $prices['sale_price'][ $key ] ) / floatval( $prices['regular_price'][ $key ] ) * 100 ) );
						}
					}

					$percentage = max( $percentages );
				} elseif ( $product->is_type( 'grouped' ) ) {
					$percentages  = array();
					$children_ids = $product->get_children();

					foreach ( $children_ids as $child_id ) {
						$child_product = wc_get_product( $child_id );
						$regular_price = (float) $child_product->get_regular_price();
						$sale_price    = (float) $child_product->get_sale_price();

						if ( 0 !== $sale_price || ! empty( $sale_price ) ) {
							$percentages[] = round( 100 - ( ( $sale_price / $regular_price ) * 100 ) );
						}
					}
					$percentage = max( $percentages );
				} else {
					$regular_price = (float) $product->get_regular_price();
					$sale_price    = (float) $product->get_sale_price();

					if ( 0 !== $sale_price || ! empty( $sale_price ) ) {
						$percentage = round( 100 - ( ( $sale_price / $regular_price ) * 100 ) );
					}
				}

				$label_text = str_replace( '{value}', $percentage, Merchant_Translator::translate( $settings['percentage_text'] ) );
			}

			$styles['background-color'] = isset( $settings['background_color'] ) ? $settings['background_color'] : '#212121';
			$styles['color']            = isset( $settings['text_color'] ) ? $settings['text_color'] : '#ffffff';
			$styles['border-radius']    = isset( $settings['label_shape'] ) ? $settings['label_shape'] . 'px' : 8 . 'px';

			return '<div class="merchant-product-labels position-' . esc_attr( $settings['label_position'] ) . '"><span class="merchant-label merchant-label-'
					. esc_attr( $settings['label_position'] ) . ' merchant-onsale-shape-' . esc_attr( $settings['label_shape'] ) . '" style="' . merchant_array_to_css( $styles )
					. '">' . esc_html( Merchant_Translator::translate( $label_text ) ) . '</span></div>';
		}

		return '';
	}

	/**
	 * Get label HTML.
	 *
	 * @return string
	 */
	public function label( $label_data ) {
		$html = '';
		if ( empty( $label_data['label'] ) ) {
			return $html;
		}

		$label_position = $label_data['label_position'] ?? 'top-left';

        $label_type = $label_data['label_type'] ?? 'text';

        $styles = array();

        if ( $label_type === 'text' ) {
	        $html = trim( esc_html( Merchant_Translator::translate( $label_data['label'] ) ) );
        } else {
	        $styles['width']  = ( $label_data['label_width'] ?? 45 ) . 'px';
	        $styles['height'] = ( $label_data['label_height'] ?? 45 ) . 'px';

            $custom_shape_id = $label_data['label_image_shape_custom'] ?? false;
            $shape_url       = $custom_shape_id ? wp_get_attachment_url( $custom_shape_id ) : MERCHANT_URI . 'assets/images/icons/' . self::MODULE_ID . '/' . ( $label_data['label_image_shape'] ?? 'image-shape-1' ) .'.svg';

	        $html = '<img src="' . esc_url( $shape_url ) . '"/>';
        }


		$label = '<span class="merchant-label merchant-label-' . esc_attr( $label_position ) . '" style="' . merchant_array_to_css( $styles ) . '">'
				. $html . '</span>';

		/**
		 * Filter the single product label.
		 *
		 * @param string $label      The label HTML.
		 * @param array  $label_data The label data.
		 *
		 * @since 1.6
		 */
		return apply_filters( 'merchant_product_label', $label, $label_data );
	}

	/**
	 * Get label data.
	 *
	 * @param $product WC_Product product object.
	 *
	 * @return bool
	 */
	private function is_on_sale( $product ) {
		return $product !== null && $product->is_on_sale();
	}

	/**
	 * Check if product is in categories.
	 *
	 * @param $product WC_Product product object.
	 * @param $slugs    array category slugs.
	 *
	 * @return bool
	 */
	private function is_in_category( $product, $slugs ) {
		if ( ! is_array( $slugs ) ) {
			$slugs = array( $slugs );
		}

		$terms = get_the_terms( $product->get_id(), 'product_cat' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( in_array( $term->slug, $slugs, true ) ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Check if product is out of stock.
	 *
	 * @param $product WC_Product product object.
	 *
	 * @return bool
	 */
	private function is_out_of_stock( $product ) {
		return $product !== null && ! $product->is_in_stock();
	}

	/**
	 * Check if product is featured.
	 *
	 * @param $product WC_Product product object.
	 *
	 * @return bool
	 */
	private function is_featured( $product ) {
		return $product !== null && $product->is_featured();
	}

	/**
	 * Check if the product is new.
	 *
	 * @param $product WC_Product product object.
	 * @param $days    number of days to check.
	 *
	 * @return bool
	 */
	private function is_new( $product, $days ) {
		$product_creation_date = get_the_date( 'Y-m-d', $product->get_id() );

		$current_date    = gmdate( 'Y-m-d' );
		$days_difference = abs( strtotime( $current_date ) - strtotime( $product_creation_date ) ) / DAY_IN_SECONDS;

		return $days_difference <= $days;
	}
}

// Initialize the module.
add_action( 'init', function () {
	new Merchant_Product_Labels();
} );
