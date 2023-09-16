<?php
/**
 * Template for cart reserved timer module on cart page.
 *
 * @var $args array template args
 *
 * @since 1.0
 */

$settings = isset( $args['settings'] ) ? $args['settings'] : array();
?>
<div class="merchant-cart-reserved-timer" data-duration="<?php echo $args['duration'] ?>" data-expires="<?php echo $args['time_expires'] ?>" style="<?php echo $args['css'] ?>">
    <div class="merchant-cart-reserved-timer-icon">
        <img src="<?php echo $args['icon']["src"] ?>" alt="<?php echo $args['icon']["alt"] ?>">
        </div>
    <div class="merchant-cart-reserved-timer-content">
        <p class="merchant-cart-reserved-timer-content-title"><?php echo $args['reserved_message'] ?></p>
        <p class="merchant-cart-reserved-timer-content-desc minutes"><?php echo $args['timer_message_minutes'] ?></p>
        <p class="merchant-cart-reserved-timer-content-desc seconds" style="display: none"><?php echo $args['timer_message_seconds'] ?></p>
        </div>
    </div>