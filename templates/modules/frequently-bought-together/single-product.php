<?php
/**
 * Template for frequently bought together module content on single product.
 *
 * @var $args array template args
 *
 * @since 1.0
 */

$settings = isset( $args['settings'] ) ? $args['settings'] : array();
?>
<div class="merchant-frequently-bought-together <?php echo isset( $settings['single_product_placement'] ) ? $settings['single_product_placement'] : 'after-summary' ?>">
    <h3 class="merchant-frequently-bought-together-title">
		<?php echo isset( $settings['title'] ) ? $settings['title'] : __( 'Frequently Bought Together', 'merchant' ) ?>
    </h3>
    <div class="merchant-frequently-bought-together-bundles" data-nonce="<?php echo isset( $args['nonce'] ) ? $args['nonce'] : '' ?>" data-cart-url="<?php echo esc_attr( wc_get_cart_url() ) ?>">
		<?php foreach ( $args['bundles'] as $key => $bundle ) :
			if ( empty( $bundle['products'] ) )
				continue ?>
            <div class="merchant-frequently-bought-together-bundle">
                <form data-product="<?php echo get_the_ID() ?>" data-bundle="<?php echo $key ?>">
                    <div class="merchant-frequently-bought-together-bundle-products">
		                <?php foreach ( $bundle['products'] as $product_key => $product ) : ?>
                            <div class="merchant-frequently-bought-together-bundle-product" data-product="<?php echo esc_attr( $product['id'] ) ?>" data-key="<?php echo esc_attr( $product_key ) ?>">
				                <?php echo $product['image']; ?>
                                <div class="merchant-frequently-bought-together-bundle-product-contents">
                                    <p class="woocommerce-loop-product__title">
                                        <a href="<?php echo $product['permalink'] ?>" target="_blank">
							                <?php echo $product['title']; ?>
                                        </a>
                                    </p>
                                    <div class="merchant-frequently-bought-together-bundle-product-price">
						                <?php echo $product['price_html']; ?>
                                    </div>
					                <?php if ( $product_key !== 0 && isset( $product['attributes'] ) && ! empty( $product['attributes'] ) ) : ?>
                                        <div class="merchant-frequently-bought-together-bundle-product-attributes">
	                                        <?php foreach ( $product['attributes'] as $key => $attribute ) : ?>
                                                <select name="<?php echo esc_attr( $key ) ?>" required>
                                                    <option value=""><?php echo sprintf( __( 'Select %s', 'merchant' ), $attribute['label'] ) ?></option>
			                                        <?php foreach ( $attribute['terms'] as $term ) : ?>
                                                        <option value="<?php echo esc_attr( $term['slug'] ) ?>"><?php echo esc_html( $term['name'] ) ?></option>
			                                        <?php endforeach; ?>
                                                </select>
	                                        <?php endforeach; ?>
                                        </div>
					                <?php endif; ?>
                                </div>
				                <?php if ( $product_key !== ( count( $bundle['products'] ) - 1 ) ) : ?>
                                    <div class="merchant-frequently-bought-together-bundle-product-plus">+</div>
				                <?php endif; ?>
                            </div>
		                <?php endforeach; ?>
                    </div>
                    <div class="merchant-frequently-bought-together-bundle-offer">
                        <p class="merchant-frequently-bought-together-bundle-total">
			                <?php echo isset( $settings['price_label'] ) ? $settings['price_label'] : __( 'Bundle price', 'merchant' ) ?>
                        </p>
                        <p class="merchant-frequently-bought-together-bundle-total-price price">
                            <del aria-hidden="true"><?php echo wc_price( $bundle['total_price'] ); ?></del>
                            <ins><?php echo wc_price( $bundle['total_discounted_price'] ) ?></ins>
                        </p>
                        <p class="merchant-frequently-bought-together-bundle-save">
			                <?php echo isset( $settings['save_label'] )
				                ? str_replace( '{amount}', wc_price( $bundle['total_discount'] ), $settings['save_label'] )
				                : sprintf( __( 'You save: %s', 'merchant' ), wc_price( $bundle['total_discount'] ) ) ?>
                        </p>
                        <button type="submit" name="merchant-buy-bundle" value="97" class="button alt wp-element-button merchant-add-bundle-to-cart">
			                <?php echo isset( $settings['button_text'] ) ? $settings['button_text'] : __( 'Add to cart', 'merchant' ) ?>
                        </button>
                        <div class="merchant-frequently-bought-together-bundle-error"></div>
                    </div>
                </form>
            </div>

		<?php endforeach; ?>
    </div>
</div>
