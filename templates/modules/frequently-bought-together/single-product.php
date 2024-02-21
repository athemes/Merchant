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
		<?php echo isset( $settings['title'] ) ? esc_html( Merchant_Translator::translate( $settings['title'] ) ) : esc_html__( 'Frequently Bought Together', 'merchant' ) ?>
	</h3>
	<div class="merchant-frequently-bought-together-bundles" data-nonce="<?php echo isset( $args['nonce'] ) ? esc_attr( $args['nonce'] ) : '' ?>" data-cart-url="<?php echo esc_attr( wc_get_cart_url() ) ?>">
		<?php foreach($args['bundles'] as $parent_id => $bundles ) : ?>
			<?php foreach ( $bundles as $key => $bundle ) : 
				$bundle_has_variable_product = false;
				$discount_type        = isset( $bundle['discount_type'] ) ? $bundle['discount_type'] : '';
				$discount_value       = isset( $bundle['discount_value'] ) ? $bundle['discount_value'] : 0;
				$has_no_discount      = $discount_value <= 0;

				?>
				<?php if ( empty( $bundle['products'] ) ) continue ?>
				<div class="merchant-frequently-bought-together-bundle<?php echo ( $has_no_discount ) ? ' has-no-discount' : ''; ?>">
					<form class="merchant-frequently-bought-together-form" data-product="<?php echo esc_attr( $parent_id ) ?>" data-bundle="<?php echo esc_attr( $key ); ?>" data-bundle-discount-type="<?php echo esc_attr( $discount_type ); ?>" data-bundle-discount-value="<?php echo esc_attr( $discount_value ); ?>">
						<div class="merchant-frequently-bought-together-bundle-products">
							<?php foreach ( $bundle['products'] as $product_key => $product ) : 
								$is_variable_product  = isset( $product['type'] ) && 'variable' === $product['type'] ? true : false;

								if( $is_variable_product ) {
									$bundle_has_variable_product = true;
								}

								?>

								<div class="merchant-frequently-bought-together-bundle-product<?php echo $is_variable_product ? ' is-variable' : ''; ?>" data-product="<?php echo esc_attr( $product['id'] ) ?>" data-key="<?php echo esc_attr( $product_key ) ?>" data-product-price="<?php echo esc_attr( $product['price'] ); ?>">
									<?php echo wp_kses_post( $product['image'] ); ?>
									<div class="merchant-frequently-bought-together-bundle-product-contents">
										<p class="woocommerce-loop-product__title">
											<a href="<?php echo esc_url( $product['permalink'] ); ?>" target="_blank">
												<?php echo esc_html( $product['title'] ); ?>
											</a>
										</p>
										<div class="merchant-frequently-bought-together-bundle-product-price">
											<?php echo wp_kses( $product['price_html'], merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?>
										</div>
										<?php if ( isset( $product['attributes'] ) && ! empty( $product['attributes'] ) ) : ?>
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
															<option value="<?php echo esc_attr( $_term['slug'] ) ?>" <?php selected( $_term['selected'], true, true ); ?>><?php echo esc_html( $_term['name'] ) ?></option>
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
								<?php echo isset( $settings['price_label'] ) ? esc_html( Merchant_Translator::translate( $settings['price_label'] ) ) : esc_html__( 'Bundle price', 'merchant' ); ?>
							</p>
							<?php if ( $bundle_has_variable_product ) : ?>
								<?php if ( $has_no_discount ) : ?>
									<p class="merchant-frequently-bought-together-bundle-variable-default-message"><?php echo esc_html( Merchant_Translator::translate( $settings['no_variation_selected_text_has_no_discount'] ) ); ?></p>
								<?php else : ?>
									<p class="merchant-frequently-bought-together-bundle-variable-default-message"><?php echo esc_html( Merchant_Translator::translate( $settings['no_variation_selected_text'] ) ); ?></p>
								<?php endif; ?>
							<?php endif; ?>
							
							<p class="merchant-frequently-bought-together-bundle-total-price price<?php echo $bundle_has_variable_product ? ' merchant-hidden' : ''; ?>">
								<?php if ( $has_no_discount ) : ?>
									<ins class="mrc-fbt-total-price"><?php echo wp_kses( wc_price( $bundle['total_price'] ), merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?></ins>
								<?php else : ?>
									<del class="mrc-fbt-total-price" aria-hidden="true"><?php echo wp_kses( wc_price( $bundle['total_price'] ), merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?></del>
									<ins class="mrc-fbt-total-discounted-price"><?php echo wp_kses( wc_price( $bundle['total_discounted_price'] ), merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?></ins>
								<?php endif; ?>
							</p>
							<?php if ( ! $has_no_discount ) : ?>
								<p class="merchant-frequently-bought-together-bundle-save<?php echo $bundle_has_variable_product ? ' merchant-hidden' : ''; ?>">
									<?php echo isset( $settings['save_label'] )
										? wp_kses( str_replace( '{amount}', wc_price( $bundle['total_discount'] ), Merchant_Translator::translate( $settings['save_label'] ) ), merchant_kses_allowed_tags( array( 'bdi' ) ) )
										: wp_kses(
											/* Translators: 1. Total discount */
											sprintf( __( 'You save: %s', 'merchant' ), wc_price( $bundle['total_discount'] ) ),
											merchant_kses_allowed_tags( array( 'bdi' ) )
										); ?>
								</p>
							<?php endif; ?>
							<button type="submit" name="merchant-buy-bundle" value="97" class="button alt wp-element-button merchant-add-bundle-to-cart">
								<?php echo isset( $settings['button_text'] ) ? esc_html( Merchant_Translator::translate( $settings['button_text'] ) ) : esc_html__( 'Add to cart', 'merchant' ); ?>
							</button>
							<div class="merchant-frequently-bought-together-bundle-error"></div>
						</div>
					</form>
				</div>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</div>
</div>
