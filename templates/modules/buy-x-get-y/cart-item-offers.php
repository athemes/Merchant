<?php
/**
 * Template for buy x get y module content on cart page.
 *
 * @var $args array template args
 *
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! empty( $args['offers'] ) ) : ?>
    <div class="bogo-cart-item-offers">
		<?php
		foreach ( $args['offers'] as $offer_key => $offer ) :
			if ( $args['quantity'] >= $offer['min_quantity'] ) {
				continue;
			}
			$customer_get_product = wc_get_product( $offer['customer_get_product_ids'] );
			if ( $customer_get_product ) {
				$variations = array();
				if ( $customer_get_product->is_type( 'variable' ) ) {
					foreach ( $customer_get_product->get_variation_attributes() as $attribute => $terms ) {
						$attribute_label = wc_attribute_label( $attribute );
						$variations[]    = array(
							/* Translators: 1. Attribute label */
							'default'   => sprintf( __( 'Select %s', 'merchant' ), $attribute_label ),
							'label'     => $attribute_label,
							'attribute' => $attribute,
							'terms'     => array_map( static function ( $term ) {
								return array(
									'value' => $term,
									'label' => ucfirst( $term ),
								);
							}, $terms ),
						);
					}
				}
				?>
                <div class="bogo-cart-item-offer__container<?php
				echo $customer_get_product->is_type( 'variable' ) ? ' is-variable' : '' ?>" data-variations="<?php
				echo esc_attr( wp_json_encode( $variations ) ) ?>" data-offer-key="<?php
				echo esc_attr( $offer_key ) ?>" data-offer-product-id="<?php
				echo esc_attr( $customer_get_product->get_id() ); ?>" data-quantity="1" data-cart-item-key="<?php
				echo esc_attr( $args['cart_item_key'] ) ?>" data-nonce="<?php
				echo esc_html( wp_create_nonce( 'mrc_get_variation_data_nonce' ) ) ?>">
                    <div class="bogo-cart-item-offer">
                        <div class="item-row">
                            <div class="column_1">
                                <div class="product_image">
                                    <a href="<?php
		                            echo esc_url( $customer_get_product->get_permalink() ) ?>" title="<?php
		                            echo esc_attr( $customer_get_product->get_name() ); ?>"><?php
			                            echo wp_kses_post( $customer_get_product->get_image( 'medium' ) );
			                            ?></a>
                                </div>
                            </div>
                            <div class="column_3">
                                <div class="product-details">
                                    <div class="offer-description"><?php
			                            $extra_quantity = $offer['min_quantity'] - $args['quantity'];
			                            $offer_quantity = $offer['quantity'];
			                            $discount       = $offer['discount_type'] === 'percentage'
				                            ? $offer['discount'] . '%'
				                            : wc_price( $offer['discount'] );
			                            printf(
			                            // translators: %1$s: min quantity, %2$s: offer quantity
				                            esc_html__( 'Buy %1$s Get %2$s with %3$s off', 'merchant' ),
				                            esc_html( $extra_quantity ),
				                            esc_html( $offer_quantity ),
				                            wp_kses( $discount, merchant_kses_allowed_tags( array( 'bdi' ) ) )
			                            );
			                            ?></div>
                                    <div class="product-name"><a href="<?php
			                            echo esc_url( $customer_get_product->get_permalink() ) ?>" title="<?php
			                            echo esc_attr( $customer_get_product->get_name() ); ?>"><?php
				                            echo esc_html( $customer_get_product->get_name() ); ?></a></div>
                                    <div class="price-area">
                                        <span class="price"><?php
	                                    echo wp_kses_post( $customer_get_product->get_price_html() ); ?></span>
                                    </div>
                                    <div class="item-footer">
                                        <div class="product-variations-wrapper"></div>
                                        <div class="add-to-cart">
                                            <button class="button add-to-cart-button alt<?php
                                            echo $customer_get_product->is_type( 'variable' ) ? ' disabled' : '' ?>" type="button" <?php
				                            echo $customer_get_product->is_type( 'variable' ) ? ' disabled' : '' ?>><?php
					                            echo esc_html( $offer['button_text'] ) ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
			}
		endforeach; ?>
    </div>
<?php
endif;