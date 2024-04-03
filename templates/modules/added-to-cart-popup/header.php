<?php
/**
 * Template for added to cart popup header.
 *
 * @var $args array template args
 *
 * @since 1.9.5
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
<div class="merchant-added-to-cart-popup<?php
echo esc_attr( $device_visibility_classes ) ?>">
	<div class="overlay"></div>