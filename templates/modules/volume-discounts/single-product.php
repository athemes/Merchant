<?php

/**
 * Template for Volume Discounts Single Product
 *
 * @var array $args template args
 *
 * @since 1.4
 */
?>

<div class="merchant-volume-discounts">
    <div class="merchant-volume-discounts-title"><?php echo esc_html( $args['settings']['table_title'] ) ?></div>
	<?php foreach ( $args['discount_tiers'] as $discount_tier ) :
		$discount = $discount_tier['layout'] === 'percentage_discount'
			?  ( $args['product_price'] * $discount_tier['discount'] ) / 100
			: $discount_tier['discount'];

		$discounted_price = $args['product_price'] - $discount;
		$total_discount   = intval( $discount_tier['quantity'] ) * $discount;
		$total_price      = intval( $discount_tier['quantity'] ) * $discounted_price; ?>
        <div class="merchant-volume-discounts-item">
            <div class="merchant-volume-discounts-buy-label">
				<?php echo str_replace(
					array(
						'{amount}',
						'{discount}'
					),
					array(
						'<strong>' . esc_attr( $discount_tier['quantity'] ) . '</strong>',
						'<strong>' . wc_price( $discount ) . '</strong>'
					),
					esc_html( $args['settings']['buy_text'] )
				); ?>
            </div>
            <ul>
                <li>
                    <div></div>
                    <div>
                        <del><?php echo wc_price( $args['product_price'] ) ?></del>
                    </div>
                </li>
                <li>
                    <div class="merchant-volume-discounts-item-text"><?php echo esc_html( $args['settings']['item_text'] ) ?></div>
                    <div><strong><?php echo wc_price( $discounted_price ) ?></strong></div>
                </li>
                <li>
                    <div class="merchant-volume-discounts-total-text"><?php echo esc_html( $args['settings']['total_text'] ) ?></div>
                    <div><strong><?php echo wc_price( $total_price ) ?></strong></div>
                </li>
            </ul>
            <div class="merchant-volume-discounts-item-label">
			<span class="merchant-volume-discounts-save-label">
				<?php echo str_replace(
					'{amount}',
					wc_price( $total_discount ),
					esc_html( $args['settings']['save_label'] )
				) ?>
			</span>
            </div>
        </div>
	<?php endforeach; ?>
</div>