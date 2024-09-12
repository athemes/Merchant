<?php

/**
 * Template for Bulk Discounts Single Product
 *
 * @var array $args template args
 *
 * @since 1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="merchant-volume-discounts">
	<?php
    $in_cart = 'false';
	$quantity = 0;
	if ( ! empty( $args['in_cart'] ) ) {
		$in_cart = 'true';
	}
    if ( ! empty( $args['product_cart_quantity'] ) ) {
        $quantity = $args['product_cart_quantity'];
    }
	foreach ( $args['discount_tiers'] as $discount_tier ) :
		if ( isset( $discount_tier['discount_type'], $discount_tier['product_single_page']['save_label'], $discount_tier['product_single_page']['item_text'], $discount_tier['product_single_page']['total_text'] ) ) {
			$discount = $discount_tier['discount_type'] === 'percentage_discount'
				? ( $args['product_price'] * $discount_tier['discount'] ) / 100
				: $discount_tier['discount'];

            $discount_percent = ( $discount_tier['discount'] ?? 0 ) . '%';
            $discount_qty = (int) ( $discount_tier['quantity'] ?? 1 );

			$discounted_price = $args['product_price'] - $discount;
			$total_discount   = intval( $discount_qty ) * $discount;
			$total_price      = intval( $discount_qty ) * $discounted_price;
			$clickable        = '';
            $product_id       = $args['product_id'] ?? get_the_ID();
            $product_type     = '';

			if ( $product_id ) {
				$product = wc_get_product( $product_id );
				$product_type = $product->get_type();
				if ( $product && ! $product->is_type( 'variable' ) && $quantity < $discount_qty ) {
					$clickable = ' clickable';
				}
			}

			if ( isset( $discount_tier['product_single_page']['table_title'] ) && ! empty( $discount_tier['product_single_page']['table_title'] ) ) : ?>
                <div class="merchant-volume-discounts-title" style="<?php
				echo isset( $discount_tier['product_single_page']['title_font_weight'] ) ? esc_attr( 'font-weight: ' . $discount_tier['product_single_page']['title_font_weight'] . ';' ) : '';
				echo isset( $discount_tier['product_single_page']['title_font_size'] ) ? esc_attr( 'font-size: ' . $discount_tier['product_single_page']['title_font_size'] . 'px;' ) : '';
				echo isset( $discount_tier['product_single_page']['title_text_color'] ) ? esc_attr( 'color: ' . $discount_tier['product_single_page']['title_text_color'] . ';' ) : ''; ?>"><?php
					echo esc_html( Merchant_Translator::translate( $discount_tier['product_single_page']['table_title'] ) ); ?>
                </div>
			<?php
			endif;

			$item_classes  = 'merchant-volume-discounts-item' . esc_attr( $clickable );
			$item_classes .= $product_type ? ' merchant-volume-discounts-item-' . esc_attr( $product_type ) : '';
            ?>
            <div class="<?php echo esc_attr( $item_classes ); ?>" title="<?php echo esc_attr__( 'Add offer to cart', 'merchant' ); ?>" data-in-cart="<?php
            echo esc_attr( $in_cart ); ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>" data-offer-quantity="<?php echo esc_attr( $discount_qty ); ?>" style="<?php
			echo isset( $discount_tier['product_single_page']['table_item_bg_color'] ) ? esc_attr( 'background-color: ' . $discount_tier['product_single_page']['table_item_bg_color'] . ';' ) : '';
			echo isset( $discount_tier['product_single_page']['table_item_border_color'] ) ? esc_attr( 'border-color: ' . $discount_tier['product_single_page']['table_item_border_color'] . ';' ) : '';
			echo isset( $discount_tier['product_single_page']['table_item_text_color'] ) ? esc_attr( 'color: ' . $discount_tier['product_single_page']['table_item_text_color'] . ';' ) : ''; ?>">
                <div class="merchant-volume-discounts-buy-label">
					<?php
					/**
					 * Previously wrong variable `{amount}` was used for this field. Correct one should be `{quantity}`.
					 * Still keeping the wrong one `{amount}` for backward compatibility.
					 */
					if ( isset( $discount_tier['product_single_page']['buy_text'] ) ) {
						echo wp_kses(
							str_replace(
								array(
									'{amount}',
									'{quantity}',
									'{discount}',
									'{percent}',
								),
								array(
									'<strong>' . esc_html( $discount_qty ) . '</strong>',
									'<strong>' . esc_html( $discount_qty ) . '</strong>',
									'<strong>' . wc_price( $discount ) . '</strong>',
									'<strong>' . esc_html( $discount_percent ) . '</strong>',
								),
								esc_html( Merchant_Translator::translate( $discount_tier['product_single_page']['buy_text'] ) )
							),
							merchant_kses_allowed_tags( array( 'bdi' ) )
						);
					} else {
						echo wp_kses(
							str_replace(
								array(
									'{quantity}',
									'{discount}',
								),
								array(
									'<strong>' . esc_html( $discount_qty ) . '</strong>',
									'<strong>' . wc_price( $discount ) . '</strong>',
								),
								esc_html__( 'Buy {quantity}, get {discount} off each', 'merchant' )
							),
							merchant_kses_allowed_tags( array( 'bdi' ) )
						);
					}
					?>
                </div>
                <ul>
                    <li>
                        <div></div>
                        <div>
                            <del><?php
								echo wp_kses( wc_price( $args['product_price'] ), merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?></del>
                        </div>
                    </li>
                    <li>
                        <div class="merchant-volume-discounts-item-text"><?php
							echo isset($discount_tier['product_single_page']['item_text']) ? esc_html( Merchant_Translator::translate( $discount_tier['product_single_page']['item_text'] ) ) : esc_html__( 'Per item:', 'merchant' ); ?></div>
                        <div><strong><?php
								echo wp_kses( wc_price( $discounted_price ), merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?></strong></div>
                    </li>
                    <li>
                        <div class="merchant-volume-discounts-total-text"><?php
							echo isset( $discount_tier['product_single_page']['total_text'] )
								? esc_html( Merchant_Translator::translate( $discount_tier['product_single_page']['total_text'] ) )
								: esc_html__( 'Total price:', 'merchant' ); ?></div>
                        <div><strong><?php
								echo wp_kses( wc_price( $total_price ), merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?></strong></div>
                    </li>
                </ul>
                <div class="merchant-volume-discounts-item-label">
                    <span class="merchant-volume-discounts-save-label" style="<?php
                    echo isset( $discount_tier['product_single_page']['table_label_bg_color'] ) ? esc_attr( 'background-color: ' . $discount_tier['product_single_page']['table_label_bg_color'] . ';' ) : '';
                    echo isset( $discount_tier['product_single_page']['table_label_text_color'] ) ? esc_attr( 'color: ' . $discount_tier['product_single_page']['table_label_text_color'] . ';' ) : ''; ?>">
                        <?php
                        if ( isset( $discount_tier['product_single_page']['save_label'] ) ) {
	                        echo wp_kses(
		                        str_replace(
			                        array(
				                        '{amount}',
				                        '{percent}',
			                        ),
			                        array(
				                        wc_price( $total_discount ),
				                        esc_html( $discount_percent ),
			                        ),
			                        esc_html( Merchant_Translator::translate( $discount_tier['product_single_page']['save_label'] ) )
		                        ),
		                        merchant_kses_allowed_tags( array( 'bdi' ) )
	                        );
                        } else {
	                        echo wp_kses(
		                        str_replace(
			                        array(
				                        '{amount}',
				                        '{percent}',
			                        ),
			                        array(
				                        wc_price( $total_discount ),
				                        esc_html( $discount_percent ),
			                        ),
			                        esc_html__( 'Save {amount}', 'merchant' )
		                        ),
		                        merchant_kses_allowed_tags( array( 'bdi' ) )
	                        );
                        } ?>
                    </span>
                </div>
            </div>
			<?php
		}
	endforeach; ?>
</div>