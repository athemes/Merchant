<?php

/**
 * Template for reasons to buy module content.
 * 
 * @var array $args module settings.
 * 
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$icon            = Merchant_SVG_Icons::get_svg_icon( $args[ 'icon' ] );

/**
 * Hook 'merchant_reasons_to_buy_wrapper_class'
 * 
 * @since 1.0
 */
$wrapper_classes = apply_filters( 'merchant_reasons_to_buy_wrapper_class', array( 'merchant-reasons-list' ) );

$reasons = array();

if ( ! empty( $args[ 'reasons' ] ) ) {
	foreach ( $args[ 'reasons' ] as $reason ) {
        if ( empty( $reason ) ) {
            continue;
        }
		$reasons[] = $reason;
    }
}

if ( empty( $reasons ) ) {
    return '';
}
?>
<div class="<?php echo wp_kses( implode( ' ', $wrapper_classes ), array() ); ?>">
	<?php if ( ! empty( $args[ 'title' ] ) ) : ?>
		<strong class="merchant-reasons-list-title"><?php echo esc_html( $args[ 'title' ] ); ?></strong>
	<?php endif; ?>

	<?php foreach ( $reasons as $reason ) : ?>
        <div class="merchant-reasons-list-item">
            <div class="merchant-reasons-list-item-icon">

				<?php
				/**
				 * Hook: merchant_reasons_list_icon
				 *
				 * @since 1.0.0
				 *
				 */
				echo wp_kses( apply_filters( 'merchant_reasons_list_icon', $icon ), merchant_kses_allowed_tags( array(), false ) );

				?>
            </div>
            <p class="merchant-reasons-list-item-text">
				<?php echo wp_kses_post( Merchant_Translator::translate( $reason ) ); ?>
            </p>
        </div>

	<?php endforeach; ?>
</div>
