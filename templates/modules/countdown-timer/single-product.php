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

if ( empty( $args ) ) {
    return;
}

$theme = $args['theme'] ?? 'classic';
$align = $args['sale_ending_alignment'] ?? 'left';

$min_expiration_deadline_days    = (int) ( $args['min_expiration_deadline_days'] ?? 0 );
$min_expiration_deadline_hours   = (int) ( $args['min_expiration_deadline'] ?? 0 );
$min_expiration_deadline_minutes = (int) ( $args['min_expiration_deadline_minutes'] ?? 0 );
$max_expiration_deadline_days    = (int) ( $args['max_expiration_deadline_days'] ?? 0 );
$max_expiration_deadline_hours   = (int) ( $args['max_expiration_deadline'] ?? 0 );
$max_expiration_deadline_minutes = (int) ( $args['max_expiration_deadline_minutes'] ?? 0 );

// Min Total minutes
$min_total_seconds = $min_expiration_deadline_days * 24 * 60 * 60;
$min_total_seconds += $min_expiration_deadline_hours * 60 * 60;
$min_total_seconds += $min_expiration_deadline_minutes * 60;

// Max total seconds
$max_total_seconds = $max_expiration_deadline_days * 24 * 60 * 60;
$max_total_seconds += $max_expiration_deadline_hours * 60 * 60;
$max_total_seconds += $max_expiration_deadline_minutes * 60;

$cool_off_period =  (int) ( $args['cool_off_period'] ?? 0 ) * 60;
$countdown_type  = $args['end_date'] ?? 'sale-dates';

$sale_start_date = $args['sale_start_date'] ?? '';
$sale_end_date   = $args['sale_end_date'] ?? '';

$variations_sale_dates = $args['variations_sale_dates'] ?? array();
$sale_ending_text      = $args['sale_ending_text'] ?? '';

$classes  = 'merchant-countdown-timer';
$classes .= ' merchant-countdown-timer-' . $theme;
$classes .= ' merchant-countdown-timer--' . $align;
?>
<div class="<?php echo esc_attr( $classes ); ?>"
	data-theme="<?php echo esc_attr( $theme ); ?>"
	data-max-expiration="<?php echo esc_attr( $max_total_seconds ); ?>"
	data-min-expiration="<?php echo esc_attr( $min_total_seconds ); ?>"
	data-off-period="<?php echo esc_attr( $cool_off_period ); ?>"
	data-countdown-type="<?php echo esc_attr( $countdown_type ); ?>"
	data-start-date="<?php echo esc_attr( $sale_start_date ); ?>"
	data-end-date="<?php echo esc_attr( $sale_end_date ); ?>"
	<?php if ( ! empty( $variations_sale_dates ) ) : ?>
		data-variations-dates="<?php echo wc_esc_json( wp_json_encode( $variations_sale_dates ) ); ?>"
	<?php endif; ?>
	style="display: none">

	<div class="merchant-countdown-timer-inner">
		<?php if ( $theme === 'classic' || is_admin() ) : ?>
			<svg fill="currentColor" height="24px" width="24px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 296.228 296.228">
				<g>
					<path d="m167.364,48.003v-23.003h10.5c6.903,0 12.5-5.597 12.5-12.5s-5.596-12.5-12.5-12.5h-59.5c-6.903,0-12.5,5.597-12.5,12.5s5.597,12.5 12.5,12.5h10.5v23.003c-59.738,9.285-105.604,61.071-105.604,123.37-3.55271e-15,68.845 56.01,124.854 124.854,124.854s124.854-56.01 124.854-124.854c0-62.299-45.866-114.085-105.604-123.37zm-19.25,223.225c-55.06,0-99.854-44.795-99.854-99.854s44.795-99.854 99.854-99.854 99.854,44.795 99.854,99.854-44.794,99.854-99.854,99.854z"/>
					<path d="m160.614,166.18v-58.889c0-6.903-5.597-12.5-12.5-12.5s-12.5,5.597-12.5,12.5v66.1c0,2.033 0.81,3.982 2.25,5.416l34.969,34.822c4.893,4.872 12.806,4.854 17.678-0.037 4.871-4.892 4.854-12.807-0.037-17.678l-29.86-29.734z"/>
				</g>
			</svg>
		<?php endif; ?>

		<?php if ( $sale_ending_text ) : ?>
			<span class="merchant-countdown-timer-text"><?php echo esc_html( Merchant_Translator::translate( $sale_ending_text ) ); ?> </span>
		<?php endif; ?>

		<div class="merchant-countdown-timer-countdown" id="merchant-countdown-timer"></div>
	</div>
</div>
