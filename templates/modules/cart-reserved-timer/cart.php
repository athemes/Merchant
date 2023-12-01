<?php
/**
 * Template for cart reserved timer module on cart page.
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
<div class="merchant-cart-reserved-timer" data-duration="<?php echo esc_attr( $args['duration'] ); ?>" data-expires="<?php echo esc_attr( $args['time_expires'] ); ?>" style="<?php echo esc_attr( $args['css'] ); ?>">
    <div class="merchant-cart-reserved-timer-icon">
        <img src="<?php echo esc_url( $args['icon']["src"] ); ?>" alt="<?php echo esc_attr( $args['icon']["alt"] ); ?>">
        </div>
    <div class="merchant-cart-reserved-timer-content">
        <p class="merchant-cart-reserved-timer-content-title"><?php echo esc_html( Merchant_Translator::translate( $args['reserved_message'] ) ); ?></p>
        <p class="merchant-cart-reserved-timer-content-desc minutes"><?php echo wp_kses_post( Merchant_Translator::translate( $args['timer_message_minutes'] ) ); ?></p>
        <p class="merchant-cart-reserved-timer-content-desc seconds" style="display: none"><?php echo wp_kses_post( Merchant_Translator::translate( $args['timer_message_seconds'] ) ); ?></p>
        </div>
    </div>