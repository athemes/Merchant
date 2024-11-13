<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor Builder compatibility layer
 */
if ( ! class_exists( 'Merchant_Elementor_Builder' ) ) {
	class Merchant_Elementor_Builder {

		/**
		 * Constructor.
		 */
		public function __construct() {
			if ( ( is_admin() && ! wp_doing_ajax() ) || ! merchant_is_elementor_active() ) {
				return;
			}

			/**
			 * Elementor Pro Hook for Single Product Page when using Template
			 *
			 * Single Product Template works for Pro only
			 *
			 * Using `woocommerce_after_add_to_cart_form` as other hooks that use inside each module doesn't seem to be working.
			 */
			add_action( 'elementor/theme/before_do_single', function( $locations_manager ) {

				// Payment Logos
				if ( Merchant_Modules::is_module_active( Merchant_Payment_Logos::MODULE_ID ) ) {
					$pl = new Merchant_Payment_Logos();
					add_action( 'woocommerce_after_add_to_cart_form', array( $pl, 'payment_logos_output' ), 15 );
				}

				// Trust Badges
				if ( Merchant_Modules::is_module_active( Merchant_Trust_Badges::MODULE_ID ) ) {
					$tb = new Merchant_Trust_Badges();
					add_action( 'woocommerce_after_add_to_cart_form', array( $tb, 'trust_badges_output' ), 20 );
				}

				if ( merchant_is_pro_active() ) {

					// Wishlist
					if ( Merchant_Modules::is_module_active( Merchant_Wishlist::MODULE_ID ) ) {
						// Display on single product.
						$display_on_single_product = Merchant_Admin_Options::get( Merchant_Wishlist::MODULE_ID, 'display_on_single_product', true );
						if ( $display_on_single_product ) {
							$wishlist = new Merchant_Pro_Wishlist( Merchant_Modules::get_module( Merchant_Wishlist::MODULE_ID ) );;

							add_action( 'woocommerce_after_add_to_cart_form', array( $wishlist, 'wishlist_link' ) );
							//add_action( 'elementor/element/woocommerce-product-content/section_style/after_section_end', array( $wishlist, 'wishlist_link' ), 99 );
							//add_action( 'elementor/element/woocommerce-product-meta/section_product_meta_style/after_section_end', array( $wishlist, 'wishlist_link' ) );
						}
					}

					// Product Brand Image
					if ( Merchant_Modules::is_module_active( Merchant_Product_Brand_Image::MODULE_ID ) ) {
						$pbi = new Merchant_Pro_Product_Brand_Image( Merchant_Modules::get_module( Merchant_Product_Brand_Image::MODULE_ID ) );
						add_action( 'woocommerce_after_add_to_cart_form', array( $pbi, 'brand_image' ), 25 );
					}

					// Product Navigation Links
					if ( Merchant_Modules::is_module_active( Merchant_Product_Navigation_Links::MODULE_ID ) ) {
						$pnl = new Merchant_Pro_Product_Navigation_Links( Merchant_Modules::get_module( Merchant_Product_Navigation_Links::MODULE_ID ) );
						add_action( 'woocommerce_after_add_to_cart_form', array( $pnl, 'render_output_html' ), 100 );
					}
				}
			} );

			add_filter( 'merchant_enqueue_module_styles', array( $this, 'should_enqueue_styles' ), 10, 2 );
			add_filter( 'merchant_enqueue_module_scripts', array( $this, 'should_enqueue_scripts' ), 10, 2 );

			// Custom CSS.
			add_filter( 'merchant_custom_css', array( $this, 'frontend_custom_css' ) );
		}

		/**
		 * Determines whether to enqueue styles for modules.
		 *
		 * @param $enqueue
		 * @param $module
		 *
		 * @return boolean
		 */
		public function should_enqueue_styles( $enqueue, $module ) {
			$module_id = $module->module_id ?? '';

			if ( \Elementor\Plugin::$instance->preview->is_preview_mode() && Merchant_Modules::is_module_active( $module_id ) ) {
				return true;
			}

			// Todo: check if required for other modules as well
			if ( ! \Elementor\Plugin::$instance->preview->is_preview_mode() && Merchant_Modules::is_module_active( $module_id ) && ( $module_id === 'wishlist' ) ) {
				global $post;

				if ( empty( $post ) ) {
					return $enqueue;
				}

				$elementor_data = get_post_meta( $post->ID, '_elementor_data', true );
				if (
					$elementor_data &&
					( strpos( $elementor_data, 'woocommerce-products' ) !== false || strpos( $elementor_data, 'wc-products' ) !== false ) ) {
					return true;
				}
			}

			return $enqueue;
		}

		/**
		 * Determines whether to enqueue scripts for modules.
		 *
		 * @param $enqueue
		 * @param $module
		 *
		 * @return mixed|true
		 */
		public function should_enqueue_scripts( $enqueue, $module ) {
			$module_id = $module->module_id ?? '';

			if ( \Elementor\Plugin::$instance->preview->is_preview_mode() && Merchant_Modules::is_module_active( $module_id ) ) {
				return true;
			}

			// Todo: check if required for other modules as well
			if ( ! \Elementor\Plugin::$instance->preview->is_preview_mode() && Merchant_Modules::is_module_active( $module_id ) && ( $module_id === 'wishlist' ) ) {
				global $post;

				if ( empty( $post ) ) {
					return $enqueue;
				}

				$elementor_data = get_post_meta( $post->ID, '_elementor_data', true );
				if (
					$elementor_data &&
					( strpos( $elementor_data, 'woocommerce-products' ) !== false || strpos( $elementor_data, 'wc-products' ) !== false ) ) {
					return true;
				}
			}

			return $enqueue;
		}

		/**
		 * Frontend custom CSS.
		 *
		 * @param string $css The custom CSS.
		 * @return string $css The custom CSS.
		 */
		public function frontend_custom_css( $css ) {
			$css .= Merchant_Side_Cart::get_module_custom_css();

			// Quick View
			if ( Merchant_Modules::is_module_active( Merchant_Quick_View::MODULE_ID ) ) {
				$css .= '
				    .merchant-quick-view-button {
					    padding: .618em 1em !important;
					}
				';
			}

			// Payment Logos
			if ( Merchant_Modules::is_module_active( Merchant_Payment_Logos::MODULE_ID ) ) {
				$css .= '
				    .single-product li .merchant-payment-logos {
				        display: none !important;
				    }
				';
			}

			// Trust Badges
			if ( Merchant_Modules::is_module_active( Merchant_Trust_Badges::MODULE_ID ) ) {
				$css .= '
				    .single-product li .merchant-trust-badges {
				        display: none !important;
				    }
				';
			}

			if ( merchant_is_pro_active() ) {

				// Variation Swatches
				if ( Merchant_Modules::is_module_active( Merchant_Stock_Scarcity::MODULE_ID ) ) {
					$css .= '
					    .single-product form.cart {
					        flex-wrap: wrap !important;
					    }
					';
				}

				// Wishlist
				if ( Merchant_Modules::is_module_active( Merchant_Wishlist::MODULE_ID ) ) {
					$css .= '
					    .single-product .merchant-wishlist-button {
					        position: static;
					    }
					    .single-product li .merchant-wishlist-button {
					        position: absolute;
					    }
					    
					    .merchant-wishlist-button ~ .merchant-product-swatches .merchant-wishlist-button {
					        display: none !important;
					    }
					';
				}

				// Product Brand Image
				if ( Merchant_Modules::is_module_active( Merchant_Product_Brand_Image::MODULE_ID ) ) {
					$css .= '
					    .single-product li .merchant-product-brand-image {
					        display: none !important;
					    }
					';
				}

				// Product Navigation Links
				if ( Merchant_Modules::is_module_active( Merchant_Product_Navigation_Links::MODULE_ID ) ) {
					$css .= '
					    .single-product li .merchant-product-navigation {
					        display: none !important;
					    }
					';
				}
			}

			return $css;
		}
	}

	add_action( 'init', function() {
		new Merchant_Elementor_Builder();
	} );
}
