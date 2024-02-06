<?php
/**
 * Template for buy x get y module content on single product.
 *
 * @var $args array template args
 *
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$settings = isset( $args['settings'] ) ? $args['settings'] : array();
$product  = isset( $args['product'] ) ? Merchant_Pro_Buy_X_Get_Y::product_args( wc_get_product( $args['product'] ) ) : wc_get_product();
?>
<div class="merchant-bogo">
    <p class="merchant-bogo-title">
		<?php
		echo isset( $settings['title'] ) ? esc_html( Merchant_Translator::translate( $settings['title'] ) ) : esc_html__( 'Buy One Get One', 'merchant' ) ?>
    </p>
    <div class="merchant-bogo-offers" data-nonce="<?php
	echo isset( $args['nonce'] ) ? esc_attr( $args['nonce'] ) : '' ?>" data-cart-url="<?php
	echo esc_url( wc_get_cart_url() ); ?>">
		<?php
		foreach ( $args['offers'] as $key => $offer ): ?>
			<?php
			$buy_product = $offer['customer_get_product_ids'] ? Merchant_Pro_Buy_X_Get_Y::product_args( wc_get_product( $offer['customer_get_product_ids'] ) ) : null;
			if ( isset( $product['is_purchasable'] ) && ! $product['is_purchasable'] ) {
				continue;
			} ?>
            <div class="merchant-bogo-offer" data-product="<?php
			echo esc_attr( $product['id'] ) ?>" data-offer="<?php
			echo esc_attr( $key ); ?>">
                <div class="merchant-bogo-product-x">
                    <div class="merchant-bogo-product-label merchant-bogo-product-buy-label">
						<?php
						echo isset( $settings['buy_label'] )
							? esc_html( str_replace( '{quantity}', $offer['min_quantity'], Merchant_Translator::translate( $settings['buy_label'] ) ) )
							: esc_html(
							/* Translators: 1. quantity */
								sprintf( __( 'Buy %s', 'merchant' ), $offer['min_quantity'] )
							); ?>
                    </div>
                    <div class="merchant-bogo-product">
						<?php
						echo wp_kses_post( $product['image'] ); ?>
                        <div class="merchant-bogo-product-contents">
                            <p class="woocommerce-loop-product__title">
                                <a href="<?php
								echo esc_url( $product['permalink'] ); ?>" target="_blank">
									<?php
									echo esc_html( $product['title'] ); ?>
                                </a>
                            </p>
							<?php
							echo wp_kses( $product['price_html'], merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?>
                        </div>
                    </div>
                    <div class="merchant-bogo-arrow">→</div>
                </div>
                <div class="merchant-bogo-product-y">
                    <form class="merchant-bogo-form" data-product="<?php
					echo esc_attr( $buy_product['id'] ); ?>">
                        <div class="merchant-bogo-product-label merchant-bogo-product-get-label">
							<?php
							$discount = $offer['discount_type'] === 'percentage'
								? $offer['discount'] . '%'
								: wc_price( $offer['discount'] );
							echo isset( $settings['get_label'] )
								? wp_kses( str_replace(
									array(
										'{quantity}',
										'{discount}',
									),
									array(
										$offer['quantity'],
										$discount,
									),
									Merchant_Translator::translate( $settings['get_label'] )
								), merchant_kses_allowed_tags( array( 'bdi' ) ) )
								: wp_kses(
								/* Translators: 1. quantity 2. discount value*/
									sprintf( __( 'Get %1$s with %2$s off', 'merchant' ), $offer['quantity'], $discount ),
									merchant_kses_allowed_tags( array( 'bdi' ) )
								); ?>
                        </div>
						<?php
						if ( $buy_product ) {
							?>
                            <div class="merchant-bogo-product">
								<?php
								echo wp_kses_post( $buy_product['image'] ); ?>
                                <div class="merchant-bogo-product-contents">
                                    <p class="woocommerce-loop-product__title">
                                        <a href="<?php
										echo esc_url( $buy_product['permalink'] ); ?>" target="_blank">
											<?php
											echo esc_html( $buy_product['title'] ); ?>
                                        </a>
                                    </p>
                                    <div class="merchant-bogo-product-price">
										<?php
										echo wp_kses( $buy_product['price_html'], merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?>
                                    </div>
                                </div>
                            </div>
							<?php
							if ( isset( $buy_product['attributes'] ) && ! empty( $buy_product['attributes'] ) ) : ?>
                                <div class="merchant-bogo-product-attributes" data-nonce="<?php
								echo esc_attr( wp_create_nonce( 'mrc_get_variation_data_nonce' ) ); ?>">
									<?php
									foreach ( $buy_product['attributes'] as $buy_product_key => $attribute ) : ?>
                                        <select class="merchant-bogo-select-attribute" name="<?php
										echo esc_attr( $buy_product_key ) ?>" required>
                                            <option value="">
												<?php
												echo esc_html(
												/* Translators: 1. Attribute label */
													sprintf( __( 'Select %s', 'merchant' ), $attribute['label'] )
												); ?>
                                            </option>
											<?php
											foreach ( $attribute['terms'] as $_term ) : ?>
                                                <option value="<?php
												echo esc_attr( $_term['slug'] ) ?>"><?php
													echo esc_html( $_term['name'] ) ?></option>
											<?php
											endforeach; ?>
                                        </select>
									<?php
									endforeach; ?>
                                </div>
							<?php
							endif; ?>
                            <button type="submit" name="merchant-bogo-add-to-cart" value="97" class="button alt wp-element-button merchant-bogo-add-to-cart">
								<?php
								echo isset( $settings['button_text'] ) ? esc_html( Merchant_Translator::translate( $settings['button_text'] ) )
									: esc_html__( 'Add To Cart', 'merchant' ); ?>
                            </button>
                            <div class="merchant-bogo-offer-error"></div>
							<?php
						} ?>
                    </form>
                </div>
            </div>
		<?php
		endforeach; ?>
    </div>
</div>
