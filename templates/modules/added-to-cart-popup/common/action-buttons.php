<?php
/**
 * Template for added to cart popup action buttons.
 *
 * @var $args array template args
 *
 * @since 1.9.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if (
	! isset( $args['settings']['show_view_cart_button'] )
	|| ! isset( $args['settings']['show_checkout_button'] )
	|| ! isset( $args['settings']['show_view_continue_shopping_button'] )
) {
	return;
}
?>
<div class="popup-actions">
	<?php
	// View cart button.
	if ( isset( $args['settings']['show_view_cart_button'] ) && $args['settings']['show_view_cart_button'] ) { ?>
        <a href="<?php
		echo esc_url( wc_get_cart_url() ); ?>" class="merchant-button button-filled view-cart">
			<?php
			echo $args['settings']['view_cart_button_label']
				? esc_html( Merchant_Translator::translate( $args['settings']['view_cart_button_label'] ) )
				: esc_html__
				( 'View Cart', 'merchant' ); ?>
        </a>
		<?php
	} ?>
	<?php
	// Continue shopping button.
	if ( isset( $args['settings']['show_view_continue_shopping_button'] ) && $args['settings']['show_view_continue_shopping_button'] ) { ?>
        <a href="#" class="merchant-button continue-shopping popup-close-js">
			<?php
			echo $args['settings']['view_continue_shopping_button_label']
				? esc_html( Merchant_Translator::translate( $args['settings']['view_continue_shopping_button_label'] ) )
				: esc_html__( 'Continue Shopping', 'merchant' ); ?>
        </a>
		<?php
	} ?>
	<?php
	// Checkout button.
	if ( isset( $args['settings']['show_checkout_button'] ) && $args['settings']['show_checkout_button'] ) { ?>
        <a href="<?php
		echo esc_url( wc_get_checkout_url() ); ?>" class="merchant-button checkout"><?php
			esc_html_e( 'Checkout', 'merchant' ); ?></a>
		<?php
	} ?>
</div>