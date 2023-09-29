<?php
/**
 * Template for frequently bought together module content on single product.
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
<div class="merchant-frequently-bought-together <?php echo isset( $settings['single_product_placement'] ) ? esc_attr( $settings['single_product_placement'] ) : 'after-summary' ?>">
    <h3 class="merchant-frequently-bought-together-title">
		<?php echo isset( $settings['title'] ) ? esc_html( $settings['title'] ) : esc_html__( 'Frequently Bought Together', 'merchant' ) ?>
    </h3>
    <div class="merchant-frequently-bought-together-bundles" data-nonce="<?php echo isset( $args['nonce'] ) ? esc_attr( $args['nonce'] ) : '' ?>" data-cart-url="<?php echo esc_attr( wc_get_cart_url() ) ?>">
		<?php foreach ( $args['bundles'] as $key => $bundle ) :
			if ( empty( $bundle['products'] ) )
				continue ?>
            <div class="merchant-frequently-bought-together-bundle">
                <form data-product="<?php echo get_the_ID() ?>" data-bundle="<?php echo esc_attr( $key ); ?>">
                    <div class="merchant-frequently-bought-together-bundle-products">
		                <?php foreach ( $bundle['products'] as $product_key => $product ) : ?>
                            <div class="merchant-frequently-bought-together-bundle-product" data-product="<?php echo esc_attr( $product['id'] ) ?>" data-key="<?php echo esc_attr( $product_key ) ?>">
				                <?php echo wp_kses_post( $product['image'] ); ?>
                                <div class="merchant-frequently-bought-together-bundle-product-contents">
                                    <p class="woocommerce-loop-product__title">
                                        <a href="<?php echo esc_url( $product['permalink'] ); ?>" target="_blank">
							                <?php echo esc_html( $product['title'] ); ?>
                                        </a>
                                    </p>
                                    <div class="merchant-frequently-bought-together-bundle-product-price">
						                <?php echo $product['price_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    </div>
					                <?php if ( $product_key !== 0 && isset( $product['attributes'] ) && ! empty( $product['attributes'] ) ) : ?>
                                        <div class="merchant-frequently-bought-together-bundle-product-attributes" data-nonce="<?php echo esc_attr( wp_create_nonce( 'mrc_get_variation_data_nonce' ) ); ?>">
	                                        <?php foreach ( $product['attributes'] as $key => $attribute ) : ?>
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
                                </div>
				                <?php if ( $product_key !== ( count( $bundle['products'] ) - 1 ) ) : ?>
                                    <div class="merchant-frequently-bought-together-bundle-product-plus">+</div>
				                <?php endif; ?>
                            </div>
		                <?php endforeach; ?>
                    </div>
                    <div class="merchant-frequently-bought-together-bundle-offer">
                        <p class="merchant-frequently-bought-together-bundle-total">
			                <?php echo isset( $settings['price_label'] ) ? esc_html( $settings['price_label'] ) : esc_html__( 'Bundle price', 'merchant' ); ?>
                        </p>
                        <p class="merchant-frequently-bought-together-bundle-total-price price">
                            <del aria-hidden="true"><?php echo wc_price( $bundle['total_price'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></del>
                            <ins><?php echo wc_price( $bundle['total_discounted_price'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></ins>
                        </p>
                        <p class="merchant-frequently-bought-together-bundle-save">
			                <?php echo isset( $settings['save_label'] )
				                ? wp_kses( str_replace( '{amount}', wc_price( $bundle['total_discount'] ), $settings['save_label'] ), merchant_kses_allowed_tags( ['bdi'] ) )
				                : wp_kses( 
                                    /* Translators: 1. Total discount */
                                    sprintf( __( 'You save: %s', 'merchant' ), wc_price( $bundle['total_discount'] ) ),
                                    merchant_kses_allowed_tags( ['bdi'] ) 
                                ); ?>
                        </p>
                        <button type="submit" name="merchant-buy-bundle" value="97" class="button alt wp-element-button merchant-add-bundle-to-cart">
			                <?php echo isset( $settings['button_text'] ) ? esc_html( $settings['button_text'] ) : esc_html__( 'Add to cart', 'merchant' ); ?>
                        </button>
                        <div class="merchant-frequently-bought-together-bundle-error"></div>
                    </div>
                </form>
            </div>

		<?php endforeach; ?>
    </div>
</div>
