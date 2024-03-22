<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Blocksy theme compatibility layer
 */
if ( ! class_exists( 'Merchant_Blocksy_Theme' ) ) {
	class Merchant_Blocksy_Theme {

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
			add_action( 'init', array( $this, 'product_video_module' ) );
			add_action( 'init', array( $this, 'product_audio_module' ) );
			add_filter('theme_mod_woo_card_layout', array( $this, 'remove_theme_default_add_to_cart' ) );
			add_filter('merchant_product_swatch_shop_catalog_add_to_cart_button_html', array( $this, 'fix_add_to_cart_button_structure' ) );
			add_filter('woocommerce_loop_add_to_cart_link', array( $this, 'fix_add_to_cart_button_structure' ) );
		}

		/**
		 * Add a div to wrap the add to cart button.
		 *
		 * @param $button_html string add to cart button html.
		 *
		 * @return string The add to cart button html.
		 */
		public function fix_add_to_cart_button_structure( $button_html ) {
			if ( ! merchant_is_blocksy_active() ) {
				return $button_html;
			}
			if ( ! Merchant_Modules::is_module_active( 'product-swatches' ) ) {
				return $button_html;
			}

			return '<div class="ct-woo-card-actions">' . $button_html . '</div>';
		}

		/**
		 * Disable the theme's add to cart button in the shop loop while product swatches module is active.
		 *
		 * @param $layout_values array The layout values.
		 *
		 * @see https://developer.wordpress.org/reference/hooks/theme_mod_name/
		 *
		 * @return array The layout values after disable the add to cart button.
		 */
		public function remove_theme_default_add_to_cart( $layout_values ) {
			if ( ! merchant_is_blocksy_active() ) {
				return $layout_values;
			}
			if ( ! Merchant_Modules::is_module_active( 'product-swatches' ) ) {
				return $layout_values;
			}

			if ( ! empty( $layout_values ) ) {

				foreach ( $layout_values as $key => $value ) {
					if ( $value['id'] === 'product_add_to_cart' ) {
						$layout_values[ $key ]['enabled'] = false;
					}
				}
			}

			return $layout_values;
		}

		/**
		 * Load compatibility styles if the Blocksy theme is installed and active.
		 *
		 * @return void
		 */
		public function styles() {
			if ( ! merchant_is_blocksy_active() ) {
				return;
			}

			wp_enqueue_style(
				'merchant-blocksy-compatibility',
				MERCHANT_URI . 'assets/css/compatibility/blocksy/style.min.css',
				array(),
				MERCHANT_VERSION
			);
		}

		/**
		 * Handle with all required compatibility with 'Product Video' module.
		 * 
		 * @return void
		 */
		public function product_video_module() {
			if ( ! merchant_is_blocksy_active() ) {
				return;
			}

			if ( ! Merchant_Modules::is_module_active( 'product-video' ) ) {
				return;
			}

			// Removes the theme's default product image on archive pages.
			add_filter( 'theme_mod_woo_card_layout', function($mod_settings){
				global $post;

				if ( ! isset( $post ) ) {
					return $mod_settings;
				}

				$has_featured_video = get_post_meta( $post->ID, '_merchant_enable_featured_video', true );
				if ( ! $has_featured_video ) {
					return $mod_settings;
				}

				foreach( $mod_settings as $index => $setting ) {
					if ( isset( $setting['id'] ) && $setting['id'] === 'product_image' ) {
						$mod_settings[$index]['enabled'] = false;
					}
				}

				return $mod_settings;
			} );

			// Replaces the theme's default image gallery with the default from WooCommerce. 
			add_filter( 'blocksy:woocommerce:product-view:use-default', '__return_true' );
		}

		/**
		 * Handle with all required compatibility with 'Product Audio' module.
		 * 
		 * @return void
		 */
		public function product_audio_module() {
			if ( ! merchant_is_blocksy_active() ) {
				return;
			}

			if ( ! Merchant_Modules::is_module_active( 'product-audio' ) ) {
				return;
			}

			// Removes the theme's default product image on archive pages.
			add_filter( 'theme_mod_woo_card_layout', function($mod_settings){
				global $post;

				if ( ! isset( $post ) ) {
					return $mod_settings;
				}

				$has_featured_audio = get_post_meta( $post->ID, '_merchant_enable_featured_audio', true );
				if ( ! $has_featured_audio ) {
					return $mod_settings;
				}

				foreach( $mod_settings as $index => $setting ) {
					if ( isset( $setting['id'] ) && $setting['id'] === 'product_image' ) {
						$mod_settings[$index]['enabled'] = false;
					}
				}

				return $mod_settings;
			} );

			// Replaces the theme's default image gallery with the default from WooCommerce. 
			add_filter( 'blocksy:woocommerce:product-view:use-default', '__return_true' );
		}
	}

	/**
	 * The class object can be accessed with "global $blocksy_compatibility", to allow removing actions.
	 * Improving Third-party integrations.
	 */
	$merchant_blocksy_compatibility = new Merchant_Blocksy_Theme();
}
