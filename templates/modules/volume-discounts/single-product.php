<?php

/**
 * Template for Volume Discounts Single Product
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
	foreach ( $args['discount_tiers'] as $discount_tier ) :
		if ( isset( $discount_tier['discount_type'], $discount_tier['save_label'], $discount_tier['item_text'], $discount_tier['total_text'] ) ) {
			$discount = $discount_tier['discount_type'] === 'percentage_discount'
				? ( $args['product_price'] * $discount_tier['discount'] ) / 100
				: $discount_tier['discount'];

			$discounted_price = $args['product_price'] - $discount;
			$total_discount   = intval( $discount_tier['quantity'] ) * $discount;
			$total_price      = intval( $discount_tier['quantity'] ) * $discounted_price; ?>
			<?php
			if ( isset( $discount_tier['table_title'] ) && ! empty( $discount_tier['table_title'] ) ): ?>
                <div class="merchant-volume-discounts-title" style="<?php
				echo isset( $discount_tier['title_font_weight'] ) ? esc_attr( 'font-weight: ' . $discount_tier['title_font_weight'] . ';' ) : '';
				echo isset( $discount_tier['title_font_size'] ) ? esc_attr( 'font-size: ' . $discount_tier['title_font_size'] . 'px;' ) : '';
				echo isset( $discount_tier['title_text_color'] ) ? esc_attr( 'color: ' . $discount_tier['title_text_color'] . ';' ) : ''; ?>"><?php
					echo esc_html( Merchant_Translator::translate( $discount_tier['table_title'] ) ) ?></div>
			<?php
			endif; ?>
            <div class="merchant-volume-discounts-item" style="<?php
			echo isset( $discount_tier['table_item_bg_color'] ) ? esc_attr( 'background-color: ' . $discount_tier['table_item_bg_color'] . ';' ) : '';
			echo isset( $discount_tier['table_item_border_color'] ) ? esc_attr( 'border-color: ' . $discount_tier['table_item_border_color'] . ';' ) : '';
			echo isset( $discount_tier['table_item_text_color'] ) ? esc_attr( 'color: ' . $discount_tier['table_item_text_color'] . ';' ) : ''; ?>">
                <div class="merchant-volume-discounts-buy-label">
					<?php
					echo wp_kses(
						str_replace(
							array(
								'{amount}',
								'{discount}',
							),
							array(
								'<strong>' . esc_attr( $discount_tier['quantity'] ) . '</strong>',
								'<strong>' . wc_price( $discount ) . '</strong>',
							),
							esc_html( Merchant_Translator::translate( $discount_tier['buy_text'] ) )
						),
						merchant_kses_allowed_tags( array( 'bdi' ) )
					);
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
							echo esc_html( Merchant_Translator::translate( $discount_tier['item_text'] ) ) ?></div>
                        <div><strong><?php
								echo wp_kses( wc_price( $discounted_price ), merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?></strong></div>
                    </li>
                    <li>
                        <div class="merchant-volume-discounts-total-text"><?php
							echo esc_html( Merchant_Translator::translate( $discount_tier['total_text'] ) ) ?></div>
                        <div><strong><?php
								echo wp_kses( wc_price( $total_price ), merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?></strong></div>
                    </li>
                </ul>
                <div class="merchant-volume-discounts-item-label">
			<span class="merchant-volume-discounts-save-label" style="<?php
			echo isset( $discount_tier['table_label_bg_color'] ) ? esc_attr( 'background-color: ' . $discount_tier['table_label_bg_color'] . ';' ) : '';
			echo isset( $discount_tier['table_label_text_color'] ) ? esc_attr( 'color: ' . $discount_tier['table_label_text_color'] . ';' ) : ''; ?>">
				<?php
				echo wp_kses(
					str_replace(
						'{amount}',
						wc_price( $total_discount ),
						esc_html( Merchant_Translator::translate( $discount_tier['save_label'] ) )
					),
					merchant_kses_allowed_tags( array( 'bdi' ) )
				); ?>
			</span>
                </div>
            </div>
			<?php
		}
	endforeach; ?>
</div>