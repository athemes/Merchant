<?php
/**
 * Template for added to cart popup container.
 *
 * @var $args array template args
 *
 * @since 1.9.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$device_visibility_classes = '';
if ( isset( $args['settings']['show_devices'] ) && is_array( $args['settings']['show_devices'] ) ) {
	$device_visibility_classes = ' hidden-on-mobile hidden-on-desktop';
	foreach ( $args['settings']['show_devices'] as $device ) {
		$device_visibility_classes .= ' show-on-' . $device;
	}
}
?>
<div class="merchant-added-to-cart-popup-container">
	<?php
	/**
	 * Before added to cart popup layout html.
	 *
	 * @param array $args template args
	 *
	 * @since 1.9.7
	 */
	do_action( 'merchant_added_to_cart_popup_layout_before_popup_html', $args );
	?>
    <div class="merchant-added-to-cart-popup<?php
	echo esc_attr( $device_visibility_classes ) ?>">
        <div class="overlay"></div>
    </div>
	<?php
	/**
	 * After added to cart popup layout html.
	 *
	 * @param array $args template args
	 *
	 * @since 1.9.7
	 */
	do_action( 'merchant_added_to_cart_popup_layout_after_popup_html', $args );
	?>
</div>