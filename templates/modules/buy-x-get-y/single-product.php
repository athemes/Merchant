<?php
/**
 * Template for buy x get y module content on single product.
 *
 * @var $args array template args
 *
 * @since 1.0
 */

$settings = isset( $args['settings'] ) ? $args['settings'] : array();
?>
<div class="merchant-bogo">
    <p class="merchant-bogo-title">
		<?php echo isset( $settings['title'] ) ? $settings['title'] : __( 'Buy One Get One', 'merchant' ) ?>
    </p>
    <div class="merchant-bogo-offers" data-nonce="<?php echo isset( $args['nonce'] ) ? $args['nonce'] : '' ?>" data-cart-url="<?php echo wc_get_cart_url() ?>">
		<?php foreach ( $args['offers'] as $key => $offer ): ?>
			<?php if ( isset( $offer['product']['is_purchasable'] ) && ! $offer['product']['is_purchasable'] ) {
				continue;
			} ?>
            <div class="merchant-bogo-offer" data-product="<?php echo get_the_ID() ?>" data-offer="<?php echo $key ?>">
                <div class="merchant-bogo-product-x">
                    <div class="merchant-bogo-product-label merchant-bogo-product-buy-label">
						<?php echo isset( $settings['buy_label'] )
							? str_replace( '{quantity}', $offer['buy_quantity'], $settings['buy_label'] )
							: sprintf( __( 'Buy %s' ), $offer['buy_quantity'] ) ?>
                    </div>
                    <div class="merchant-bogo-product">
						<?php echo $offer['buy_product']['image']; ?>
                        <div class="merchant-bogo-product-contents">
                            <p class="woocommerce-loop-product__title">
                                <a href="<?php echo $offer['buy_product']['permalink'] ?>" target="_blank">
									<?php echo $offer['buy_product']['title']; ?>
                                </a>
                            </p>
							<?php echo $offer['buy_product']['price_html']; ?>
                        </div>
                    </div>
                    <div class="merchant-bogo-arrow">→</div>
                </div>
                <div class="merchant-bogo-product-y">
                    <form data-product="<?php echo $offer['product']['id']; ?>">
                        <div class="merchant-bogo-product-label merchant-bogo-product-get-label">
		                    <?php
		                    $discount = $offer['layout'] === 'percentage_discount'
			                    ? $offer['discount_value'] . '%'
			                    : wc_price( $offer['discount_value'] );
		                    echo isset( $settings['get_label'] )
			                    ? str_replace(
				                    array(
					                    '{quantity}',
					                    '{discount}'
				                    ),
				                    array(
					                    $offer['quantity'],
					                    $discount
				                    ),
				                    $settings['get_label'] )
			                    : sprintf( __( 'Get %s with %s off' ), $offer['quantity'], $discount ) ?>
                        </div>
                        <div class="merchant-bogo-product">
		                    <?php echo $offer['product']['image']; ?>
                            <div class="merchant-bogo-product-contents">
                                <p class="woocommerce-loop-product__title">
                                    <a href="<?php echo $offer['product']['permalink'] ?>" target="_blank">
					                    <?php echo $offer['product']['title']; ?>
                                    </a>
                                </p>
                                <div class="merchant-bogo-product-price">
				                    <?php echo $offer['product']['price_html']; ?>
                                </div>
                            </div>
                        </div>
	                    <?php if ( isset( $offer['product']['attributes'] ) && ! empty( $offer['product']['attributes'] ) ) : ?>
                            <div class="merchant-bogo-product-attributes">
			                    <?php foreach ( $offer['product']['attributes'] as $key => $attribute ) : ?>
                                    <select name="<?php echo esc_attr( $key ) ?>" required>
                                        <option value=""><?php echo sprintf( __( 'Select %s', 'merchant' ), $attribute['label'] ) ?></option>
					                    <?php foreach ( $attribute['terms'] as $term ) : ?>
                                            <option value="<?php echo esc_attr( $term['slug'] ) ?>"><?php echo esc_html( $term['name'] ) ?></option>
					                    <?php endforeach; ?>
                                    </select>
			                    <?php endforeach; ?>
                            </div>
	                    <?php endif; ?>
                        <button type="submit" name="merchant-bogo-add-to-cart" value="97" class="button alt wp-element-button merchant-bogo-add-to-cart">
		                    <?php echo isset( $settings['button_text'] ) ? $settings['button_text'] : __( 'Add To Cart', 'merchant' ) ?>
                        </button>
                        <div class="merchant-bogo-offer-error"></div>
                    </form>
                </div>
            </div>
		<?php endforeach; ?>
    </div>
</div>
