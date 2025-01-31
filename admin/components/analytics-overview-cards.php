<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * @var $overview_data array Overview data.
 */

$cards         = $overview_data['cards'];
foreach ( $cards as $key => $card ) : ?>
<div class="overview-card <?php echo esc_attr( $key ); ?>">
    <div class="card-title"><?php echo esc_html( $card['title'] ); ?></div>
    <div class="card-value"><?php echo wp_kses( $card['value'], merchant_kses_allowed_tags( array( 'all' ) ) ); ?></div>
    <div class="card-change <?php echo esc_attr( $card['change']['class'] ); ?>"><?php echo esc_html( $card['change']['value'] ); ?></div>
    <?php if( isset( $card['tooltip'] ) ) { ?>
        <span class="info-icon" data-tooltip="<?php echo esc_attr( $card['tooltip'] ); ?>"></span>
    <?php } ?>
</div>
<?php endforeach; ?>