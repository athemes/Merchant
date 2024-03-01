<?php
/**
 * Template for countdown timer module on single product page..
 *
 * @var $args array template args
 *
 * @since 1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="merchant-countdown-timer <?php echo esc_attr( $args['sale_ending_alignment'] ); ?>"
    data-max="<?php echo esc_attr( $args['max_expiration_deadline'] ); ?>"
    data-min="<?php echo esc_attr( $args['min_expiration_deadline'] ); ?>"
    data-cop="<?php echo esc_attr( $args['cool_off_period'] ); ?>"
    data-type="<?php echo esc_attr( $args['end_date'] ); ?>"
    data-date="<?php echo isset( $args['sale_end_date'] ) ? esc_attr( $args['sale_end_date'] ) : '' ?>"
	<?php if ( ! empty( $args['sale_end_date_variations'] ) ) : ?>
		data-date-variations="<?php echo wc_esc_json( wp_json_encode( $args['sale_end_date_variations'] ) ); ?>"
	<?php endif; ?>
    style="display: none">
	<svg fill="currentColor" height="24px" width="24px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 296.228 296.228">
		<g>
			<path d="m167.364,48.003v-23.003h10.5c6.903,0 12.5-5.597 12.5-12.5s-5.596-12.5-12.5-12.5h-59.5c-6.903,0-12.5,5.597-12.5,12.5s5.597,12.5 12.5,12.5h10.5v23.003c-59.738,9.285-105.604,61.071-105.604,123.37-3.55271e-15,68.845 56.01,124.854 124.854,124.854s124.854-56.01 124.854-124.854c0-62.299-45.866-114.085-105.604-123.37zm-19.25,223.225c-55.06,0-99.854-44.795-99.854-99.854s44.795-99.854 99.854-99.854 99.854,44.795 99.854,99.854-44.794,99.854-99.854,99.854z"/>
			<path d="m160.614,166.18v-58.889c0-6.903-5.597-12.5-12.5-12.5s-12.5,5.597-12.5,12.5v66.1c0,2.033 0.81,3.982 2.25,5.416l34.969,34.822c4.893,4.872 12.806,4.854 17.678-0.037 4.871-4.892 4.854-12.807-0.037-17.678l-29.86-29.734z"/>
		</g>
	</svg>
	<div>
		<span class="merchant-countdown-timer-text"><?php echo esc_html( Merchant_Translator::translate( $args['sale_ending_text'] ) ); ?> </span>
		<span class="merchant-countdown-timer-countdown" id="merchant-countdown-timer"></span>
	</div>
</div>