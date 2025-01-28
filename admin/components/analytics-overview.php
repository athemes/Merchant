<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * @var $overview_data array Overview data.
 */

$date_ranges   = $overview_data['date_ranges'];
?>
<div class="merchant-analytics-overview-section" data-action="<?php echo esc_attr( $overview_data['action'] ) ?>">
    <div class="overview-head">
        <div class="head-wrapper">
            <div class="title">
                <span class="title-text"><?php echo esc_html( $overview_data['section_title'] ); ?></span>
            </div>
            <div class="date-range">
                <span class="merchant-analytics-loading-spinner"></span>
                <span class="second-date-range" data-title="<?php echo esc_attr__( 'Date:', 'merchant' ); ?>">
                    <input type="text" class="date-range-input" readonly value="<?php echo esc_attr( implode( ' - ', array_values( $date_ranges['recent_period'] ) ) ); ?>" placeholder="<?php esc_attr_e( 'Date range', 'merchant' ); ?>">
                </span>
                <span class="compare-text"><?php esc_html_e( 'comparing to', 'merchant' ); ?></span>
                <span class="first-date-range first-date-range-analytics-overview">
                    <input type="text" class="date-range-input" readonly value="<?php echo esc_attr( implode( ' - ', array_values( $date_ranges['last_period'] ) ) ); ?>" placeholder="<?php esc_attr_e( 'Date range', 'merchant' ); ?>">
                </span>
            </div>
        </div>
    </div>
    <div class="overview-cards">
		<?php require_once MERCHANT_DIR . 'admin/components/analytics-overview-cards.php'; ?>
    </div>
</div>