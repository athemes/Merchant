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
			? str_replace(
				'{stock}',
				$args['stock'],
				sanitize_text_field( $settings['low_inventory_text'] )
			)
			: sprintf(
				__( 'Hurry! Only %s units left in stock!' ),
				$args['stock']
			) ?>
    </div>
    <div class="merchant-stock-scarcity-content">
        <div class="merchant-stock-scarcity-progress-bar" style="width: <?php echo $args['percentage'] . '%' ?>"></div>
    </div>
</div>
