<?php
/**
 * Template for stock scarcity module content on single product.
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
<div class="merchant-stock-scarcity">
    <div class="merchant-stock-scarcity-message">
		<?php 
		if ( ! empty( $args['is_simple'] ) ) {
			$low_inventory_text = ! empty( $settings['low_inventory_text_simple'] ) ? Merchant_Translator::translate( $settings['low_inventory_text_simple'] ) : esc_html__( 'Hurry, low stock.', 'merchant' );
		} elseif ( $args['stock'] > 1 ) {
			$low_inventory_text = isset( $settings['low_inventory_text_plural'] ) ? Merchant_Translator::translate( $settings['low_inventory_text_plural'] ) : '';
		} else {
			$low_inventory_text = isset( $settings['low_inventory_text'] ) ? Merchant_Translator::translate( $settings['low_inventory_text'] ) : '';
		}

		echo ! empty( $low_inventory_text )
			? esc_html( str_replace(
				'{stock}',
				$args['stock'],
				$low_inventory_text
			) )
			: esc_html( 
				/* Translators: 1. Quantity of units */
				sprintf( _n( 'Hurry! Only %s unit left in stock!', 'Hurry! Only %s units left in stock!', $args['stock'], 'merchant' ), $args['stock'] )
			); ?>
    </div>
    <div class="merchant-stock-scarcity-content">
        <div class="merchant-stock-scarcity-progress-bar" style="width: <?php echo esc_attr( $args['percentage'] ) . '%'; ?>"></div>
    </div>
</div>
