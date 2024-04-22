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
$view_cart_toggle         = Merchant_Admin_Options::get( 'added-to-cart-popup', 'show_view_cart_button', true );
$checkout_toggle          = Merchant_Admin_Options::get( 'added-to-cart-popup', 'show_checkout_button', true );
$continue_shopping_toggle = Merchant_Admin_Options::get( 'added-to-cart-popup', 'show_view_continue_shopping_button', true );
$view_cart_label          = Merchant_Admin_Options::get( 'added-to-cart-popup', 'view_cart_button_label', __( 'View Cart', 'merchant' ) );
$continue_shopping_label  = Merchant_Admin_Options::get( 'added-to-cart-popup', 'view_continue_shopping_button_label', __( 'Continue Shopping', 'merchant' ) );
if (
	! $view_cart_toggle
	&& ! $checkout_toggle
	&& ! $continue_shopping_toggle
) {
	return;
}
?>
<div class="popup-actions">
	<?php
	// View cart button.
	if ( $view_cart_toggle ) { ?>
        <a href="<?php
		echo esc_url( wc_get_cart_url() ); ?>" class="merchant-button button-filled view-cart">
			<?php
			echo
			esc_html( Merchant_Translator::translate( $view_cart_label ) ); ?>
        </a>
		<?php
	} ?>
	<?php
	// Continue shopping button.
	if ( $continue_shopping_toggle ) { ?>
        <a href="#" class="merchant-button continue-shopping popup-close-js">
			<?php
			echo
			esc_html( Merchant_Translator::translate( $continue_shopping_label ) ); ?>
        </a>
		<?php
	} ?>
	<?php
	// Checkout button.
	if ( $checkout_toggle ) { ?>
        <a href="<?php
		echo esc_url( wc_get_checkout_url() ); ?>" class="merchant-button checkout"><?php
			esc_html_e( 'Checkout', 'merchant' ); ?></a>
		<?php
	} ?>
</div>