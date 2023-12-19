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
					? wp_kses( str_replace(
						'{amount}',
						$amount,
						sanitize_text_field( Merchant_Translator::translate( $settings['spending_text'] ) )
                    ), merchant_kses_allowed_tags( array( 'bdi' ) ) )
					: wp_kses( 
                        /* Translators: 1. Amount */
                        sprintf( __( 'Spend %s more to receive this free gift!', 'merchant' ), $amount ),
                        merchant_kses_allowed_tags( array( 'bdi' ) )
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
							<?php echo wp_kses( wc_price( $offer['product']['price'] ), merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?>
                        </del>
                        <strong class="merchant-free-gifts-widget-offer-product-free">
							<?php echo isset( $settings['free_text'] ) ? esc_html( Merchant_Translator::translate( $settings['free_text'] ) ) : esc_html__( 'Free', 'merchant' ) ?>
                        </strong>
                    </div>
                </div>
            </div>
        </div>
	<?php endif; ?>
<?php endforeach; ?>