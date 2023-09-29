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
?>
<div class="merchant-bogo">
    <p class="merchant-bogo-title">
		<?php echo isset( $settings['title'] ) ? esc_html( $settings['title'] ) : esc_html__( 'Buy One Get One', 'merchant' ) ?>
    </p>
    <div class="merchant-bogo-offers" data-nonce="<?php echo isset( $args['nonce'] ) ? esc_attr( $args['nonce'] ) : '' ?>" data-cart-url="<?php echo esc_url( wc_get_cart_url() ); ?>">
		<?php foreach ( $args['offers'] as $key => $offer ): ?>
			<?php if ( isset( $offer['product']['is_purchasable'] ) && ! $offer['product']['is_purchasable'] ) {
				continue;
			} ?>
            <div class="merchant-bogo-offer" data-product="<?php echo get_the_ID() ?>" data-offer="<?php echo esc_attr( $key ); ?>">
                <div class="merchant-bogo-product-x">
                    <div class="merchant-bogo-product-label merchant-bogo-product-buy-label">
						<?php echo isset( $settings['buy_label'] )
							? esc_html( str_replace( '{quantity}', $offer['buy_quantity'], $settings['buy_label'] ) )
							: esc_html( 
                                /* Translators: 1. quantity */
                                sprintf( __( 'Buy %s', 'merchant' ), $offer['buy_quantity'] ) 
                            ); ?>
                    </div>
                    <div class="merchant-bogo-product">
						<?php echo wp_kses_post( $offer['buy_product']['image'] ); ?>
                        <div class="merchant-bogo-product-contents">
                            <p class="woocommerce-loop-product__title">
                                <a href="<?php echo esc_url( $offer['buy_product']['permalink'] ); ?>" target="_blank">
									<?php echo esc_html( $offer['buy_product']['title'] ); ?>
                                </a>
                            </p>
							<?php echo $offer['buy_product']['price_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- previously escaped ?>
                        </div>
                    </div>
                    <div class="merchant-bogo-arrow">→</div>
                </div>
                <div class="merchant-bogo-product-y">
                    <form data-product="<?php echo esc_attr( $offer['product']['id'] ); ?>">
                        <div class="merchant-bogo-product-label merchant-bogo-product-get-label">
		                    <?php
		                    $discount = $offer['layout'] === 'percentage_discount'
			                    ? $offer['discount_value'] . '%'
			                    : wc_price( $offer['discount_value'] );
		                    echo isset( $settings['get_label'] )
			                    ? wp_kses( str_replace(
				                    array(
					                    '{quantity}',
					                    '{discount}'
				                    ),
				                    array(
					                    $offer['quantity'],
					                    $discount
				                    ),
				                    $settings['get_label'] 
                                ), merchant_kses_allowed_tags( ['bdi'] ) )
			                    : wp_kses( 
                                    /* Translators: 1. quantity 2. discount value*/
                                    sprintf( __( 'Get %1$s with %2$s off', 'merchant' ), $offer['quantity'], $discount ),
                                    merchant_kses_allowed_tags( ['bdi'] )
                                ); ?>
                        </div>
                        <div class="merchant-bogo-product">
		                    <?php echo wp_kses_post( $offer['product']['image'] ); ?>
                            <div class="merchant-bogo-product-contents">
                                <p class="woocommerce-loop-product__title">
                                    <a href="<?php echo esc_url( $offer['product']['permalink'] ); ?>" target="_blank">
					                    <?php echo esc_html( $offer['product']['title'] ); ?>
                                    </a>
                                </p>
                                <div class="merchant-bogo-product-price">
				                    <?php echo $offer['product']['price_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- previously escaped ?>
                                </div>
                            </div>
                        </div>
	                    <?php if ( isset( $offer['product']['attributes'] ) && ! empty( $offer['product']['attributes'] ) ) : ?>
                            <div class="merchant-bogo-product-attributes" data-nonce="<?php echo esc_attr( wp_create_nonce( 'mrc_get_variation_data_nonce' ) ); ?>">
			                    <?php foreach ( $offer['product']['attributes'] as $key => $attribute ) : ?>
                                    <select name="<?php echo esc_attr( $key ) ?>" required>
                                        <option value="">
                                            <?php echo esc_html( 
                                                /* Translators: 1. Attribute label */
                                                sprintf( __( 'Select %s', 'merchant' ), $attribute['label'] ) 
                                            ); ?>
                                        </option>
					                    <?php foreach ( $attribute['terms'] as $_term ) : ?>
                                            <option value="<?php echo esc_attr( $_term['slug'] ) ?>"><?php echo esc_html( $_term['name'] ) ?></option>
					                    <?php endforeach; ?>
                                    </select>
			                    <?php endforeach; ?>
                            </div>
	                    <?php endif; ?>
                        <button type="submit" name="merchant-bogo-add-to-cart" value="97" class="button alt wp-element-button merchant-bogo-add-to-cart">
		                    <?php echo isset( $settings['button_text'] ) ? esc_html( $settings['button_text'] ) : esc_html__( 'Add To Cart', 'merchant' ); ?>
                        </button>
                        <div class="merchant-bogo-offer-error"></div>
                    </form>
                </div>
            </div>
		<?php endforeach; ?>
    </div>
</div>
