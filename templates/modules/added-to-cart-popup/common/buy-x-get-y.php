<?php
/**
 * Template for added to cart popup Buy X Get Y content.
 *
 * @var $args array template args
 *
 * @since 1.9.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( empty( $args['product_offers'] ) ) {
	return;
}
foreach ( $args['product_offers'] as $offer ) {
	?>
    <div class="buy-x-get-y">
        <h3 class="section-title"><?php
			echo esc_html( Merchant_Translator::translate( $offer['title'] ) ); ?></h3>
        <div class="offer-products">
            <div class="offer-column">
                <div class="offer-product first-product">
					<?php
					if ( isset( $offer['min_quantity'] ) ) { ?>
                        <div class="offer-title" style="<?php
						echo isset( $offer['label_text_color'] ) ? esc_attr( 'color: ' . $offer['label_text_color'] . ';' ) : '';
						echo isset( $offer['label_bg_color'] ) ? esc_attr( 'background-color: ' . $offer['label_bg_color'] . ';' ) : '';
						?>"><?php
							echo isset( $offer['buy_label'] )
								? esc_html( str_replace( '{quantity}', $offer['min_quantity'], Merchant_Translator::translate( $offer['buy_label'] ) ) )
								: esc_html(
								/* Translators: 1. quantity */
									sprintf( __( 'Buy %s', 'merchant' ), $offer['min_quantity'] )
								); ?>
                        </div>
						<?php
					} ?>
                    <div class="image-wrapper">
                        <a href="<?php
						echo esc_url( $args['popup_data']['product_url'] ); ?>">
							<?php
							echo wp_kses_post( $args['popup_data']['product_image_large'] ); ?>
                        </a>
                    </div>
                    <div class="product-summary">
                        <a href="<?php
						echo esc_url( $args['popup_data']['product_url'] ); ?>">
                            <h3><?php
								echo esc_html( wp_trim_words(
									$args['popup_data']['product_name'],
									/**
									 * Recently viewed products product title words count.
									 *
									 * @param int   $words      Words count.
									 * @param Array $popup_data Popup dara
									 *
									 * @since 1.9.7
									 */
									apply_filters( 'merchant_popup_bogo_products_product_title_words', 10, $args['popup_data'] ),
									/**
									 * Recently viewed products product title suffix.
									 *
									 * @param string $suffix     Suffix.
									 * @param Array  $popup_data Popup dara
									 *
									 * @since 1.9.7
									 */
									apply_filters( 'merchant_popup_bogo_products_product_title_suffix', '...', $args['popup_data'] )
								) ); ?></h3></a>
                        <div class="product-price"><?php
							echo wp_kses_post( $args['popup_data']['product_price'] ); ?></div>
                    </div>
                    <div class="arrow-icon" style="<?php
					echo isset( $offer['arrow_bg_color'] ) ? esc_attr( 'background-color: ' . $offer['arrow_bg_color'] . ';' ) : ''; ?>">
                        <svg width="15" height="8" viewBox="0 0 15 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14.1031 4.29105C14.2983 4.09579 14.2983 3.77921 14.1031 3.58395L10.9211 0.401966C10.7258 0.206704 10.4092 0.206704 10.214 0.401966C10.0187 0.597228 10.0187 0.913811 10.214 1.10907L13.0424 3.9375L10.214 6.76593C10.0187 6.96119 10.0187 7.27777 10.214 7.47303C10.4092 7.6683 10.7258 7.6683 10.9211 7.47303L14.1031 4.29105ZM0.59375 4.4375H13.7495V3.4375H0.59375V4.4375Z"
                                    fill="<?php
									echo isset( $offer['arrow_text_color'] ) ? esc_attr( $offer['arrow_text_color'] ) : 'white'; ?>"/>
                        </svg>
                    </div>
                </div>
            </div>
			<?php
			if ( isset( $offer['customer_get_product_ids'] ) && is_numeric( $offer['customer_get_product_ids'] ) ) {
				$product = wc_get_product( $offer['customer_get_product_ids'] );
				if ( $product ) {
					?>
                    <div class="offer-column">
                        <div class="offer-product second-product" style="<?php
						echo isset( $offer['offer_border_color'] ) ? esc_attr( 'border-color: ' . $offer['offer_border_color'] . ';' ) : '';
						echo isset( $offer['offer_border_radius'] ) ? esc_attr( 'border-radius: ' . $offer['offer_border_radius'] . 'px;' ) : '';
						?>">
                            <div class="offer-title" style="<?php
							echo isset( $offer['label_text_color'] ) ? esc_attr( 'color: ' . $offer['label_text_color'] . ';' ) : '';
							echo isset( $offer['label_bg_color'] ) ? esc_attr( 'background-color: ' . $offer['label_bg_color'] . ';' ) : '';
							?>"><?php
								$discount = $offer['discount_type'] === 'percentage'
									? $offer['discount'] . '%'
									: wc_price( $offer['discount'] );
								echo isset( $offer['get_label'] )
									? wp_kses( str_replace(
										array(
											'{quantity}',
											'{discount}',
										),
										array(
											$offer['quantity'],
											$discount,
										),
										Merchant_Translator::translate( $offer['get_label'] )
									), merchant_kses_allowed_tags( array( 'bdi' ) ) )
									: wp_kses(
									/* Translators: 1. quantity 2. discount value*/
										sprintf( __( 'Get %1$s with %2$s off', 'merchant' ), $offer['quantity'], $discount ),
										merchant_kses_allowed_tags( array( 'bdi' ) )
									); ?></div>
                            <div class="image-wrapper">
                                <a href="<?php
								echo esc_url( $product->get_permalink() ); ?>">
									<?php
									echo wp_kses_post( $product->get_image( 'full' ) ); ?>
                                </a>
                            </div>
                            <div class="product-summary">
                                <a href="<?php
								echo esc_url( $product->get_permalink() ); ?>">
                                    <h3><?php
										echo esc_html( wp_trim_words(
											$product->get_name(),
											/**
											 * Product title words count.
											 *
											 * @param int        $words   Words count.
											 * @param WC_Product $product Product object.
											 *
											 * @since 1.9.7
											 */
											apply_filters( 'merchant_popup_bogo_products_product_title_words_second_product', 10, $product ),
											/**
											 * Product title suffix.
											 *
											 * @param string     $suffix  Suffix.
											 * @param WC_Product $product Product object.
											 *
											 * @since 1.9.7
											 */
											apply_filters( 'merchant_popup_bogo_products_product_title_suffix_second_product', '...', $product )
										) ); ?></h3></a>
                                <div class="product-price"><?php
									echo wp_kses_post( $product->get_price_html() ); ?></div>
                            </div>
                        </div>
                    </div>
					<?php
				}
			} ?>
        </div>
    </div>
	<?php
}
//echo '<pre>';
//print_r( $args['popup_data'] );
//echo '</pre>';
//echo '<pre>';
//print_r( $args['product_offers'] );
//echo '</pre>';
