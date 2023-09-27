<?php
/**
 * Template for stock scarcity module content on single product.
 *
 * @var $args array template args
 *
 * @since 1.0
 */

$settings = isset( $args['settings'] ) ? $args['settings'] : array();
?>
<div class="merchant-stock-scarcity">
    <div class="merchant-stock-scarcity-message">
		<?php echo isset( $settings['low_inventory_text'] )
			? esc_html( str_replace(
				'{stock}',
				$args['stock'],
				$settings['low_inventory_text']
			) )
			: esc_html( 
				/* Translators: 1. Quantity of units */
				sprintf( __( 'Hurry! Only %s units left in stock!', 'merchant' ), $args['stock'] )
			); ?>
    </div>
    <div class="merchant-stock-scarcity-content">
        <div class="merchant-stock-scarcity-progress-bar" style="width: <?php echo esc_attr( $args['percentage'] ) . '%'; ?>"></div>
    </div>
</div>
