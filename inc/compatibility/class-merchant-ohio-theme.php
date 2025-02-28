<?php
/**
 * Ohio Theme compatibility layer
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ohio Theme compatibility layer
 */
if ( ! class_exists( 'Merchant_Ohio_Theme' ) ) {
	class Merchant_Ohio_Theme {

		/**
		 * Constructor.
		 */
		public function __construct() {
			if ( ( is_admin() && ! wp_doing_ajax() ) || ! merchant_is_ohio_active() ) {
				return;
			}

			// Custom CSS.
			add_filter( 'merchant_custom_css', array( $this, 'frontend_custom_css' ) );

			$ajax_cart = ! class_exists( 'OhioOptions' ) || OhioOptions::get( 'woocommerce_product_ajax_cart', true );

            // Custom scripts
			add_action( 'wp_footer', function() {
				?>
                <script>
					jQuery( document ).ready( function ( $ ) {

						/**
                         * Ohio theme using their own AJAX scripts and not using 'added_to_cart' event which is required.
                         * So adding `added_to_cart` event here
 						 */
						const originalAjax = $.ajax;
						$.ajax = function( options ) {
							if (
                                options.url === wc_cart_fragments_params.ajax_url
                                && ( options.data.action === 'ohio_ajax_add_to_cart_woo' || options.data.action === 'ohio_ajax_add_to_cart_woo_single' ) ) {
								const originalSuccess = options.success;
								options.success = function(response) {
									// Call the original Ohio success handler
									originalSuccess.apply( this, arguments );
									if ( response && ! response.error ) {
										// Ensure fragments and cart_hash are present
										const fragments = response.fragments || {};
										const cart_hash = response.cart_hash || '';
										const $button = $( '.data_button_ajax.loading' ); // Find the button that triggered it

										// Trigger added_to_cart with the response data
										$( document.body ).trigger( 'added_to_cart', [ fragments, cart_hash, $button ] );
									}
								};
							}
							return originalAjax.apply( this, arguments );
						};

						// Add loading class to show loading indicator
						$( '.merchant-sticky-add-to-cart-wrapper button.button' ).addClass( 'data_button_ajax' );
						$( 'body' ).on( 'adding_to_cart', function( event, $button, data ) {
							if ( $button.closest( '.merchant-quick-view-content' ).length || $button.closest( '.merchant-sticky-add-to-cart-wrapper' ).length ) {
								$button.addClass( 'btn-loading' );
                            }
                        } );

						// Quick View, Wishlist, Product Labels, Product Video/Audio Position
						$( '.merchant-product-labels, .merchant-quick-view-button, .merchant-wishlist-button, .merchant-product-video, .merchant-product-audio' ).each( function() {
							const $product = $( this ).closest( 'li.product' );
							const $thumbnail = $product.find( '.product-item-thumbnail' );

							if ( $thumbnail.length ) {
								$( this )
									.appendTo( $thumbnail )
									.css( { 'visibility': 'visible' } );
							} else {
								$( this )
                                    .appendTo( $product )
									.css( { 'visibility': 'visible' } );
							}
						} );
					} );
                </script>
				<?php
			} );

			// Product labels
			if ( Merchant_Modules::is_module_active( Merchant_Product_Labels::MODULE_ID ) ) {
				$Product_Labels = Merchant_Modules::get_module( Merchant_Product_Labels::MODULE_ID );

				// Archives/Loop
				remove_action( 'woocommerce_before_shop_loop_item', array( $Product_Labels, 'loop_product_output' ) );
				add_action( 'woocommerce_after_shop_loop_item_title', array( $Product_Labels, 'loop_product_output' ) );

				// Single Product
				remove_action( 'woocommerce_product_thumbnails', array( $Product_Labels, 'single_product_output' ) );
				add_action( 'woocommerce_before_single_product_summary', array( $Product_Labels, 'single_product_output' ) );
			}

			// Quick View
			if ( Merchant_Modules::is_module_active( Merchant_Quick_View::MODULE_ID ) && $ajax_cart ) {
				$Quick_View = Merchant_Modules::get_module( Merchant_Quick_View::MODULE_ID );
				remove_action( 'woocommerce_after_shop_loop_item', array( $Quick_View, 'quick_view_button' ) );
				add_action( 'woocommerce_after_shop_loop_item_title', array( $Quick_View, 'quick_view_button' ) );
			}

			// Buy Now
			if ( Merchant_Modules::is_module_active( Merchant_Buy_Now::MODULE_ID ) && $ajax_cart ) {
				$Buy_Now = Merchant_Modules::get_module( Merchant_Buy_Now::MODULE_ID );
				remove_action( 'woocommerce_after_shop_loop_item', array( $Buy_Now, 'shop_archive_product_buy_now_button' ) );
				add_action( 'woocommerce_after_shop_loop_item_title', array( $Buy_Now, 'shop_archive_product_buy_now_button' ) );
			}

			if ( merchant_is_pro_active() ) {
				// FBT
				if ( Merchant_Modules::is_module_active( Merchant_Frequently_Bought_Together::MODULE_ID ) ) {
					add_filter( 'merchant_frequently_bought_together_woo_hook', function( $hook ) {
						return 'woocommerce_product_after_tabs';
					} );
				}

				// Recently Viewed Products
				if ( Merchant_Modules::is_module_active( Merchant_Recently_Viewed_Products::MODULE_ID ) ) {
					add_filter( 'merchant_recently_viewed_products_woo_hook', function( $hook ) {
						return 'woocommerce_product_after_tabs';
					} );

                    add_filter( 'merchant_recently_viewed_products_wrapper_classes', function( $classes ) {
	                    $classes[] = 'woo-products';

						return $classes;
					} );
				}

				// Wishlist
				if ( Merchant_Modules::is_module_active( Merchant_Wishlist::MODULE_ID ) ) {
					add_filter( 'merchant_wishlist_archive_woo_hook', function( $hook ) {
						return 'woocommerce_after_shop_loop_item_title';
					} );
                }

				// Advanced Reviews
				if ( Merchant_Modules::is_module_active( Merchant_Advanced_Reviews::MODULE_ID ) ) {
					add_filter( 'merchant_advanced_review_woo_hook', function( $hook ) {
						return 'woocommerce_product_after_tabs';
					} );
                }

				// Side Cart
				if ( Merchant_Modules::is_module_active( Merchant_Side_Cart::MODULE_ID ) ) {
                    // remove_filter( 'woocommerce_cart_item_name', 'ohio_add_cart_product_category', 99 );
				}

				// Product Video/Audio
				if ( Merchant_Modules::is_module_active( Merchant_Product_Video::MODULE_ID ) || Merchant_Modules::is_module_active( Merchant_Product_Audio::MODULE_ID ) ) {
                    // Archive
                    add_filter( 'merchant_product_video_before_woo_hook', function() {
                        return 'woocommerce_after_shop_loop_item_title';
                    } );

                    add_filter( 'merchant_product_audio_before_woo_hook', function() {
                        return 'woocommerce_after_shop_loop_item_title';
                    } );

                    /*
                     * Single Product
                     *
                     * `YITH_Featured_Audio_Video_Init` function is needed in Ohio theme for firing `woocommerce_single_product_image_thumbnail_html` action
                     * That's why just defining it here to make sure it exists.
                     * No need any implementation
                     *
                     */
					if ( ! function_exists( 'YITH_Featured_Audio_Video_Init' ) ) {
						function YITH_Featured_Audio_Video_Init() {}
                    }
				}
			}
		}

		/**
		 * Frontend custom CSS.
		 *
		 * @param string $css The custom CSS.
		 * @return string $css The custom CSS.
		 */
		public function frontend_custom_css( $css ) {
			$css .= Merchant_Side_Cart::get_module_custom_css();

            $css .= '
                body .cart_item .product-name .variation,
                body .cart_item .product-total .variation {
                    display: block;
                }
                body .cart_item .product-name .variation dd,
                body .cart_item .product-total .variation dd {
                    margin-inline: 0;
                }
                body .cart_item .product-name .variation p,
                body .cart_item .product-total .variation p {
                    margin: 0;
                }
            ';

			// Product labels
			if ( Merchant_Modules::is_module_active( Merchant_Product_Labels::MODULE_ID ) ) {
				$css .= '
				    .product-item {
					    position: relative;
					}
					li.product .merchant-product-labels {
                        visibility: hidden;
                    }
				';
			}

			// Quick View
			if ( Merchant_Modules::is_module_active( Merchant_Quick_View::MODULE_ID ) ) {
				$css .= '
				    li.product .merchant-quick-view-button {
                        visibility: hidden;
                    }
				';
			}

            // Buy Now
			if ( Merchant_Modules::is_module_active( Merchant_Buy_Now::MODULE_ID ) ) {
				$css .= '
				    .data_button_ajax.btn-loading.loading:before {
                        position: absolute;
                    }
				';
			}

			if ( merchant_is_ohio_active() ) {
				// Wishlist
				if ( Merchant_Modules::is_module_active( Merchant_Wishlist::MODULE_ID ) ) {
					$css .= '
                        li.product .merchant-wishlist-button {
                            visibility: hidden;
                        }
                    ';
				}

				// Side Cart
				if ( Merchant_Modules::is_module_active( Merchant_Side_Cart::MODULE_ID ) ) {
					$css .= '
					    .merchant-side-cart-item .woo-product-name,
					    .merchant-side-cart-item .woo-category {
                            display: none !important;
                        }
                        .merchant-side-cart-widget .product_list_widget .merchant-quantity-wrap span.merchant-cart-item-name a {
                             display: block !important;
                        }
					';
				}

				// Advanced Review
				if ( Merchant_Modules::is_module_active( Merchant_Advanced_Reviews::MODULE_ID ) ) {
					$css .= '
						.merchant-adv-reviews-modal-photo-slider .star-rating:before,
						.merchant-adv-reviews .star-rating:before {
                            content: "★★★★★" !important;
                        }
					';
				}

                // Product Video/Audio
				if ( Merchant_Modules::is_module_active( Merchant_Product_Video::MODULE_ID ) || Merchant_Modules::is_module_active( Merchant_Product_Audio::MODULE_ID ) ) {
					$css .= '
						li.product:has(.merchant-product-video) .product-item-thumbnail .image-holder,
						li.product:has(.merchant-product-audio) .product-item-thumbnail .image-holder {
                            display: none;
                        }
                        li.product .merchant-product-video,
                        li.product .merchant-product-audio {
                            visibility: hidden;
                        }
					';
				}
			}

			return $css;
		}
	}

	add_action( 'init', function() {
		new Merchant_Ohio_Theme();
	} );
}
