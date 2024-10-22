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

$reason = $args['reason'] ?? array();

if ( empty( $reason ) ) {
    return;
}

$_title = $reason['title'] ?? '';
$items  = $reason['items'] ?? array();

$title_color = $reason['title_color'] ?? '#212121';
$items_color = $reason['items_color'] ?? '#777';
$icon_color  = $reason['icon_color'] ?? '#212121';
$spacing     = $reason['spacing'] ?? 5;

/**
 * Hook 'merchant_reasons_to_buy_wrapper_class'
 *
 * @since 1.0
 */
$wrapper_classes = apply_filters( 'merchant_reasons_to_buy_wrapper_class', array( 'merchant-reasons-list' ) );
?>
<div class="<?php echo wp_kses( implode( ' ', $wrapper_classes ), array() ); ?>">
	<?php if ( ! empty( $_title ) ) : ?>
		<strong style="color: <?php echo esc_attr( $title_color ); ?>" class="merchant-reasons-list-title"><?php echo esc_html( $_title ); ?></strong>
	<?php endif; ?>

	<?php foreach ( $items as $index => $item ) :
        if ( empty( trim( $item ) ) ) {
            continue;
        }

		// Check if it's the last item
		$is_last = ( $index === array_key_last( $items ) );
        ?>
        <div class="merchant-reasons-list-item" style="margin-bottom: <?php echo esc_attr( $is_last ? 0 : $spacing . 'px'  ); ?>">
            <?php if ( ! empty( $reason['display_icon'] ) ) : ?>
                <div class="merchant-reasons-list-item-icon" style="color: <?php echo esc_attr( $icon_color ); ?>">
		            <?php
		            /**
		             * Hook: merchant_reasons_list_icon
		             *
		             * @since 1.0.0
		             *
		             */
		            echo wp_kses( apply_filters( 'merchant_reasons_list_icon', Merchant_SVG_Icons::get_svg_icon( $reason['icon'] ?? '' ) ), merchant_kses_allowed_tags( array(), false ) );
		            ?>
                </div>
            <?php endif; ?>

            <p class="merchant-reasons-list-item-text" style="color: <?php echo esc_attr( $items_color ); ?>;">
				<?php echo wp_kses_post( Merchant_Translator::translate( $item ) ); ?>
            </p>
        </div>
	<?php endforeach; ?>
</div>
