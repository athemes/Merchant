<?php
/**
 * Template for displaying the free gifts module widget contents
 *
 * @var $args array template args
 *
 * @since 1.0
 */

$settings = isset( $args['settings'] ) ? $args['settings'] : array();
?>

<?php foreach ( $args['offers'] as $offer ): ?>
	<?php if ( $args['cart_total'] < $offer['amount'] ) : ?>
        <div class="merchant-free-gifts-widget-offer">
            <div class="merchant-free-gifts-widget-offer-label">
				<?php
				$amount = wc_price( $offer['amount'] - $args['cart_total'] );
				echo isset( $settings['spending_text'] )
					? str_replace(
						'{amount}',
						$amount,
						sanitize_text_field( $settings['spending_text'] )
					)
					: sprintf( __( 'Spend %s more to receive this free gift!' ), $amount ); ?>
            </div>
            <div class="merchant-free-gifts-widget-offer-product">
				<?php echo $offer['product']['image']; ?>
                <div class="merchant-free-gifts-widget-offer-product-contents">
                    <p class="woocommerce-loop-product__title">
                        <a class="merchant-free-gifts-widget-offer-product-title" href="<?php echo $offer['product']['permalink'] ?>" target="_blank">
							<?php echo $offer['product']['title']; ?>
                        </a>
                    </p>
                    <div class="merchant-free-gifts-widget-offer-product-price price">
                        <del>
							<?php echo wc_price( $offer['product']['price'] ); ?>
                        </del>
                        <strong class="merchant-free-gifts-widget-offer-product-free">
							<?php echo isset( $settings['free_text'] ) ? sanitize_text_field( $settings['free_text'] ) : __( 'Free', 'merchant' ) ?>
                        </strong>
                    </div>
                </div>
            </div>
        </div>
	<?php endif; ?>
<?php endforeach; ?>