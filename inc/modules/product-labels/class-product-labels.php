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
			'label_text'           => __( 'Spring Special', 'merchant' ),
			'display_percentage'   => 0,
			'percentage_text'      => '-{value}%',
			'label_position'       => 'top-left',
			'label_shape'          => 0,
			'label_text_transform' => 'uppercase',
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

		// Inject module content in the products.
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'loop_product_output' ) );
		add_action( 'woocommerce_product_thumbnails', array( $this, 'single_product_output' ) );
		add_action( 'woostify_product_images_box_end', array( $this, 'single_product_output' ) );
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
		$settings = $this->get_module_settings();

		$label_text = ! empty( $settings['display_percentage'] ) ? str_replace( '{value}', 20, $settings['percentage_text'] ) : $settings['label_text'];

		?>

        <div class="merchant-product-labels-preview">
            <div class="image-wrapper">
                <span class="merchant-label merchant-onsale-<?php
                echo esc_attr( $settings['label_position'] ); ?> merchant-onsale-shape-<?php
                echo esc_attr( $settings['label_shape'] ) ?>"><?php
	                echo esc_html( $label_text ); ?></span>
            </div>
            <h3><?php
				echo esc_html__( 'Product Title', 'merchant' ); ?></h3>
            <p><?php
				echo esc_html__( 'The product description normally goes here.', 'merchant' ); ?></p>
        </div>

		<?php
	}

	/**
	 * Calculate the percentage and display it replacing the label text.
	 *
	 * @return string label
	 */
	public function percentage_label( $product, $label ) {
		$label_text = isset( $label['label'] ) ? $label['label'] : '';
		if ( ! empty( $label['percentage_text'] ) ) {
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

			$label_text = str_replace( '{value}', $percentage, Merchant_Translator::translate( $label['percentage_text'] ) );
		}

		$label['label'] = $label_text;

		return $label;
	}

	/**
	 * Custom CSS.
	 *
	 * @return string
	 */
	public function get_module_custom_css() {
		$css = '';

		// Border Radius.
		$css .= Merchant_Custom_CSS::get_variable_css( 'product-labels', 'label_shape', 0, '.merchant-label', '--mrc-pl-border-radius', 'px' );

		// Text Transform.
		$css .= Merchant_Custom_CSS::get_variable_css( 'product-labels', 'label_text_transform', 'uppercase', '.merchant-label', '--mrc-pl-text-transform' );

		// Padding.
		$css .= Merchant_Custom_CSS::get_variable_css( 'product-labels', 'padding', 8, '.merchant-label', '--mrc-pl-padding', 'px' );

		// Font Size.
		$css .= Merchant_Custom_CSS::get_variable_css( 'product-labels', 'font-size', 14, '.merchant-label', '--mrc-pl-font-size', 'px' );


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
			),
			'strong' => array(),
			'span'   => array(
				'class' => array(),
				'style' => array(),
			),
		) );
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
			),
			'strong' => array(),
			'span'   => array(
				'class' => array(),
				'style' => array(),
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
				if ( ! isset( $label['pages_to_display'] ) ) {
					continue;
				}
				if ( $label['pages_to_display'] !== 'both' ) {
					if ( $label['pages_to_display'] === 'single' && $context !== 'single' ) {
						continue;
					}
					if ( $label['pages_to_display'] === 'archive' && $context !== 'archive' ) {
						continue;
					}
				}
				if ( isset( $label['display_rules'] ) ) {
					switch ( $label['display_rules'] ) {
						case 'featured_products':
							if ( $this->is_featured( $product ) ) {
								$product_labels_html .= $this->label( $label );
							}
							break;
						case 'products_on_sale':
							if ( $this->is_on_sale( $product ) ) {
								$product_labels_html .= $this->label( $this->percentage_label( $product, $label ) );
							}
							break;
						case 'by_category':
							if ( isset( $label['product_cats'] ) && $this->is_in_category( $product, $label['product_cats'] ) ) {
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
					}
				}
			}
		} else {
			// legacy mode support.
			return $this->legacy_product_label( $product );
		}

		if ( $product_labels_html ) {
			return '<div class="merchant-product-labels position-' . esc_attr( $settings['label_position'] ) . '">' . $product_labels_html . '</div>';
		}

		return '';
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
			$styles['text-transform']   = isset( $settings['label_text_transform'] ) ? $settings['label_text_transform'] : 'uppercase';
			$styles['padding']          = isset( $settings['padding'] ) ? $settings['padding'] . 'px' : 8 . 'px';
			$styles['font-size']        = isset( $settings['font-size'] ) ? $settings['font-size'] . 'px' : 14 . 'px';
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
		$label_position = Merchant_Admin_Options::get( self::MODULE_ID, 'label_position', 'top-left' );
		$label_shape    = Merchant_Admin_Options::get( self::MODULE_ID, 'label_shape', 0 );
		$styles         = array();
		if ( ! empty( $label_data['background_color'] ) ) {
			$styles['background-color'] = $label_data['background_color'];
		}
		if ( ! empty( $label_data['text_color'] ) ) {
			$styles['color'] = $label_data['text_color'];
		}
		if ( empty( $label_data['label'] ) ) {
			return '';
		}
		$label = '<span class="merchant-label merchant-label-' . esc_attr( $label_position ) . ' merchant-label-shape-'
				. esc_attr( $label_shape ) . '" style="' . merchant_array_to_css( $styles ) . '">'
				. trim( esc_html( Merchant_Translator::translate( $label_data['label'] ) ) ) . '</span>';

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
	 * Check if product is in category.
	 *
	 * @param $product WC_Product product object.
	 * @param $slug    string category slug.
	 *
	 * @return bool
	 */
	private function is_in_category( $product, $slug ) {
		$terms = get_the_terms( $product->get_id(), 'product_cat' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( $term->slug === $slug ) {
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
