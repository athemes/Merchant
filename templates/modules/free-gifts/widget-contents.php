<?php
/**
 * Template for displaying the free gifts module widget contents
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

<?php foreach ( $args['offers'] as $offer ): ?>
	<?php if ( $args['cart_total'] < $offer['amount'] ) : ?>
        <div class="merchant-free-gifts-widget-offer">
            <div class="merchant-free-gifts-widget-offer-label">
				<?php
				$amount = wc_price( $offer['amount'] - $args['cart_total'] );
				echo isset( $settings['spending_text'] )
					? esc_html( str_replace(
						'{amount}',
						$amount,
						sanitize_text_field( $settings['spending_text'] )
					) )
					: esc_html( 
                        /* Translators: 1. Amount */
                        sprintf( __( 'Spend %s more to receive this free gift!', 'merchant' ), $amount )
                    ); ?>
            </div>
            <div class="merchant-free-gifts-widget-offer-product">
				<?php echo wp_kses_post( $offer['product']['image'] ); ?>
                <div class="merchant-free-gifts-widget-offer-product-contents">
                    <p class="woocommerce-loop-product__title">
                        <a class="merchant-free-gifts-widget-offer-product-title" href="<?php echo esc_url( $offer['product']['permalink'] ); ?>" target="_blank">
							<?php echo esc_html( $offer['product']['title'] ); ?>
                        </a>
                    </p>
                    <div class="merchant-free-gifts-widget-offer-product-price price">
                        <del>
							<?php echo wc_price( $offer['product']['price'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </del>
                        <strong class="merchant-free-gifts-widget-offer-product-free">
							<?php echo isset( $settings['free_text'] ) ? esc_html( $settings['free_text'] ) : esc_html__( 'Free', 'merchant' ) ?>
                        </strong>
                    </div>
                </div>
            </div>
        </div>
	<?php endif; ?>
<?php endforeach; ?>