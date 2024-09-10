<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Breakdance Builder compatibility layer
 */
if ( ! class_exists( 'Merchant_Breakdance_Builder' ) ) {
	class Merchant_Breakdance_Builder {

		/**
		 * Constructor.
		 */
		public function __construct() {
			if ( ( is_admin() && ! wp_doing_ajax() ) || ! merchant_is_breakdance_active() ) {
				return;
			}

			// Quick View
			if ( Merchant_Modules::is_module_active( Merchant_Quick_View::MODULE_ID ) ) {
				$button_position = Merchant_Admin_Options::get( Merchant_Quick_View::MODULE_ID, 'button_position', 'overlay' );

				if ( 'overlay' === $button_position ) {
					$merchant_quick_view = Merchant_Quick_View::get_instance();
					add_filter( 'breakdance_before_shop_loop_after_image', array( $merchant_quick_view, 'quick_view_button' ) );
					remove_action( 'woocommerce_after_shop_loop_item', array( $merchant_quick_view, 'quick_view_button' ), 15 );
				}
			}

			if ( merchant_is_pro_active() ) {
				// Variation Swatches
				if ( Merchant_Modules::is_module_active( Merchant_Product_Swatches::MODULE_ID ) ) {
					remove_action( 'breakdance_shop_loop_footer', 'woocommerce_template_loop_add_to_cart' );
				}

				// Wishlist
				if ( Merchant_Modules::is_module_active( Merchant_Wishlist::MODULE_ID ) ) {
					// Display on single product.
					$display_on_single_product = Merchant_Admin_Options::get( Merchant_Wishlist::MODULE_ID, 'display_on_single_product', true );
					if ( $display_on_single_product ) {
						$wishlist = new Merchant_Pro_Wishlist( Merchant_Modules::get_module( Merchant_Wishlist::MODULE_ID ) );;

						add_action( 'woocommerce_after_add_to_cart_form', array( $wishlist, 'wishlist_link' ) );
					}
				}


				// Side/Floating Mini Cart
				if ( Merchant_Modules::is_module_active( Merchant_Side_Cart::MODULE_ID ) || Merchant_Modules::is_module_active( Merchant_Floating_Mini_Cart::MODULE_ID ) ) {
					remove_action( 'woocommerce_widget_cart_item_quantity', '\Breakdance\WooCommerce\addQuantityInputToMiniCart' );
				}

				// Product Audio
				if ( Merchant_Modules::is_module_active( Merchant_Product_Audio::MODULE_ID ) || Merchant_Modules::is_module_active( Merchant_Product_Video::MODULE_ID ) ) {
					add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail');

					remove_action('woocommerce_before_shop_loop_item_title', '\Breakdance\WooCommerce\wrapThumbnailInADiv' );
				}
			}

			// Custom CSS.
			add_filter( 'merchant_custom_css', array( $this, 'frontend_custom_css' ) );
		}

		/**
		 * Frontend custom CSS.
		 *
		 * @param string $css The custom CSS.
		 * @return string $css The custom CSS.
		 */
		public function frontend_custom_css( $css ) {
			$css .= Merchant_Floating_Mini_Cart::get_module_custom_css();

			// Quick View
			if ( Merchant_Modules::is_module_active( Merchant_Quick_View::MODULE_ID ) ) {
				$css .= '
				    .breakdance-woocommerce .products .product .merchant-quick-view-button {
				        padding: var(--bde-button-padding-base);
				        font-size: var(--bde-button-font-size);
	                    line-height: var(--bde-button-line-height);
	                    font-weight: var(--bde-button-font-weight);
	                    border-radius: var(--bde-button-border-radius);
				    }
				    .breakdance-woocommerce .products .product .merchant-quick-view-position-before {
				        margin-bottom: 10px;
				    }
				    .breakdance-woocommerce .products .product .merchant-quick-view-position-after {
				        margin-top: 10px;
				    }
				    .breakdance-woocommerce .products .product .merchant-quick-view-position-overlay {
				        width: auto;
				        position: absolute;
				    }
				    .merchant-quick-view-modal .bde-quantity-button {
				        display: none !important;
				    }
				    .merchant-quick-view-modal .single_add_to_cart_button,
				    .merchant-quick-view-modal .merchant-bogo-add-to-cart,
				    .merchant-quick-view-modal .merchant-add-bundle-to-cart {
				        padding: var(--bde-button-padding-base);
				        font-size: var(--bde-button-font-size);
	                    line-height: var(--bde-button-line-height);
	                    font-weight: var(--bde-button-font-weight);
	                    border-radius: var(--bde-button-border-radius);
				    }
				    .merchant-quick-view-modal input[type=number] {
					    width: 80px;
					    text-align: center;
					    padding: 10px;
					}
					.merchant-quick-view-modal .variations td.value,
					.merchant-quick-view-modal .variations th.label {
					    display: block;
					    text-align: left;
					    margin: 7px 0;
					}
					.merchant-quick-view-modal .merchant-frequently-bought-together-bundle-product img {
						max-width: none;
					}
				';
			}

			if ( merchant_is_pro_active() ) {
				// Variation Swatches
				if ( Merchant_Modules::is_module_active( Merchant_Product_Swatches::MODULE_ID ) ) {
					$css .= '
					    .breakdance-woocommerce .products .product a.merchant-variation-item {
					        width: auto;
					    }
					    .breakdance-woocommerce .products .product table.variations {
					        margin-bottom: 10px;
					    }
					';
				}

				// Wishlist
				if ( Merchant_Modules::is_module_active( Merchant_Wishlist::MODULE_ID ) ) {
					$css .= '
					    .breakdance-woocommerce .products .product .merchant-wishlist-button {
					        width: auto;
					    }
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

				// Side/Floating Mini Cart
				if ( Merchant_Modules::is_module_active( Merchant_Side_Cart::MODULE_ID ) || Merchant_Modules::is_module_active( Merchant_Floating_Mini_Cart::MODULE_ID ) ) {
					$css .= '
					    .bde-mini-cart-offcanvas-footer .woocommerce-mini-cart__buttons a {
					        padding: var(--bde-button-padding-base);
					        font-size: var(--bde-button-font-size);
		                    line-height: var(--bde-button-line-height);
		                    font-weight: var(--bde-button-font-weight);
		                    border-radius: var(--bde-button-border-radius);
					    }
					    .merchant-floating-side-mini-cart-widget .merchant-quantity-inner .bde-quantity-button {
					        display: none !important;
					    }
					';
				}

				// Sticky Add to Cart
				if ( Merchant_Modules::is_module_active( Merchant_Sticky_Add_To_Cart::MODULE_ID ) ) {
					$css .= '
						.merchant-sticky-add-to-cart-item .button {
							padding: var(--bde-button-padding-base);
					        font-size: var(--bde-button-font-size);
		                    line-height: var(--bde-button-line-height);
		                    font-weight: var(--bde-button-font-weight);
		                    border-radius: var(--bde-button-border-radius);
						}
						.merchant-sticky-add-to-cart-item .quantity {
							position: relative;
						}
						.merchant-sticky-add-to-cart-item .quantity input {
							width: 80px;
							padding: 10px 5px;
							text-align: center;
						}
						.merchant-sticky-add-to-cart-item .quantity input::-webkit-outer-spin-button,
						.merchant-sticky-add-to-cart-item .quantity input::-webkit-inner-spin-button {
						  -webkit-appearance: none;
						  margin: 0;
						}
						.merchant-sticky-add-to-cart-item .quantity input[type=number] {
						  -moz-appearance: textfield;
						}
					';
				}

				// Recently Viewed Products
				if ( Merchant_Modules::is_module_active( Merchant_Recently_Viewed_Products::MODULE_ID ) ) {
					$css .= '
					    .merchant-recently-viewed-products-section {
					        width: 100%;
					    }
					    @media (max-width: 991px) and (min-width: 768px) {
					        .merchant-recently-viewed-products-section .merchant-carousel-wrapper li {
					            min-width: 100%;
					        }
					    }
					';
				}

				// Product Audio/Video
				if ( Merchant_Modules::is_module_active( Merchant_Product_Audio::MODULE_ID ) || Merchant_Modules::is_module_active( Merchant_Product_Video::MODULE_ID ) ) {
					$css .= '
						.products .bde-woo-product-footer {
						    margin: 0;
						}
					';
				}

				// Waitlist
				if ( Merchant_Modules::is_module_active( Merchant_Wait_List::MODULE_ID ) ) {
					$css .= '
						#merchant-wait-list input {
						    padding: 12px;
						    margin-top: 10px;
						}
						#merchant-wait-list .merchant-wait-list-submit {
						    padding: var(--bde-button-padding-base);
					        font-size: var(--bde-button-font-size);
		                    line-height: var(--bde-button-line-height);
		                    font-weight: var(--bde-button-font-weight);
		                    border-radius: var(--bde-button-border-radius);
						}
					';
				}

				// Advanced Reviews
				if ( Merchant_Modules::is_module_active( Merchant_Advanced_Reviews::MODULE_ID ) ) {
					$css .= '
						.merchant-adv-reviews-media-carousel {
							max-width: 100%;
						}
						.merchant-adv-reviews .star-rating > span {
							text-indent: -9999px;
						}
						.merchant-adv-reviews .star-rating:before,
						.merchant-adv-reviews-modal-rating .stars a {
							background: var(--mrc-adv-reviews-stars-bg-color, #777) !important;
						}

						.merchant-adv-reviews .star-rating span,
						.merchant-adv-reviews-modal-rating .stars.selected a,
						.merchant-adv-reviews-modal-rating .stars:hover a {
							background: var(--mrc-adv-reviews-stars-color, #FFA441) !important;
						}
						.merchant-adv-reviews-modal .star-rating {
							 position: relative;
							 height: var(--bde-woo-ratings__star-size);
							 width: calc(var(--bde-woo-ratings__star-size) * 5 + 16px);
							 color: transparent;
							 font-size: 0;
							 display: flex;
							 flex-direction: row;
							 margin-right: auto;
						}
						.merchant-adv-reviews-modal .star-rating:before {
							 content: "";
							 position: absolute;
							 inset: 0;
							 background: var(--mrc-adv-reviews-stars-bg-color, #777) !important;
							 mask-image: var(--bde-woo-ratings__empty-star-svg), var(--bde-woo-ratings__empty-star-svg), var(--bde-woo-ratings__empty-star-svg), var(--bde-woo-ratings__empty-star-svg), var(--bde-woo-ratings__empty-star-svg);
							 mask-repeat: no-repeat, no-repeat, no-repeat, no-repeat, no-repeat;
							 mask-position: 0 center, calc(var(--bde-woo-ratings__star-size) + 4px) center, calc(var(--bde-woo-ratings__star-size) * 2 + 8px) center, calc(var(--bde-woo-ratings__star-size) * 3 + 12px) center, calc(var(--bde-woo-ratings__star-size) * 4 + 16px) center;
							 mask-size: contain;
						}
						.merchant-adv-reviews-modal .star-rating span {
							 position: relative;
							 z-index: 1;
							 background: var(--mrc-adv-reviews-stars-color, #FFA441) !important;
							 mask-image: var(--bde-woo-ratings__filled-star-svg), var(--bde-woo-ratings__filled-star-svg), var(--bde-woo-ratings__filled-star-svg), var(--bde-woo-ratings__filled-star-svg), var(--bde-woo-ratings__filled-star-svg);
							 mask-repeat: no-repeat, no-repeat, no-repeat, no-repeat, no-repeat;
							 mask-position: 0 center, calc(var(--bde-woo-ratings__star-size) + 4px) center, calc(var(--bde-woo-ratings__star-size) * 2 + 8px) center, calc(var(--bde-woo-ratings__star-size) * 3 + 12px) center, calc(var(--bde-woo-ratings__star-size) * 4 + 16px) center;
							 mask-size: contain;
						     text-indent: -9999px;
						}
					';
				}

				// Login Popup
				if ( ! is_user_logged_in() && Merchant_Modules::is_module_active( Merchant_Login_Popup::MODULE_ID ) ) {
					$css .= '
						.merchant-login-popup-content .button {
						    padding: var(--bde-button-padding-base);
					        font-size: var(--bde-button-font-size);
		                    line-height: var(--bde-button-line-height);
		                    font-weight: var(--bde-button-font-weight);
		                    border-radius: var(--bde-button-border-radius);
						}
					';
				}

				// Checkout
				if ( Merchant_Modules::is_module_active( Merchant_Checkout::MODULE_ID ) ) {
					$css .= '
						.woocommerce-checkout .woocommerce,
						.woocommerce-checkout .shop_table {
							width: 100%;
						}
						.merchant-pro-multi-step-wrapper li {
							list-style: none;
						}
						.merchant-pro-multi-step-wrapper .form-row {
						    display: flex;
						    flex-direction: column;
						    gap: 10px;
						}
						.merchant-pro-multi-step-wrapper .form-row input,
						.merchant-pro-multi-step-wrapper .form-row select,
						.merchant-pro-multi-step-wrapper .form-row textarea {
						    width: 100%;
						    padding: 10px 15px;
						}
						.merchant-pro-multi-step-wrapper tr th:last-child, .merchant-pro-multi-step-wrapper tr td:last-child {
						    text-align: right;
						}
						.merchant-pro-multi-step-wrapper #payment ul {
						    padding: 0;
						}
						@media only screen and (min-width: 992px) {
							.merchant-pro-sc-layout-shopify .merchant-pro-sc-form {
								gap: 0 !important;
						    }
						}
					';
				}
			}

			return $css;
		}
	}

	add_action( 'init', function() {
		new Merchant_Breakdance_Builder();
	} );
}
