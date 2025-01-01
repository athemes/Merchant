<?php
/**
 * Date comparison fields.
 *
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$options = array(
	'dateFormat' => 'MM-dd-yyyy',
	'timeZone'   => wp_timezone_string(),
    'position'   => 'bottom right',
    'maxDate'    => gmdate( 'm-d-Y' ),
);
?>
<div class="merchant-date-comparison__fields">
	<div class="merchant-datetime-field merchant-date-comparison__field merchant-date-comparison__field-from" data-options="<?php echo esc_attr( wp_json_encode( $options ) ); ?>">
		<span><?php echo esc_html__( 'From', 'merchant' ); ?></span>
        <input type="text" name="from_date" value="<?php echo esc_attr( gmdate( 'm-d-Y', strtotime( '-1 month' ) ) ); ?>" placeholder="mm-dd-yyyy"/>
	</div>

	<div class="merchant-datetime-field merchant-date-comparison__field merchant-date-comparison__field-to" data-options="<?php echo esc_attr( wp_json_encode( $options ) ); ?>">
		<span><?php echo esc_html__( 'To', 'merchant' ); ?></span>
        <input type="text" name="to_date" value="<?php echo esc_attr( gmdate( 'm-d-Y' ) ); ?>" placeholder="mm-dd-yyyy"/>
	</div>
</div>
