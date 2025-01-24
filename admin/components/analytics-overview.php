<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$reports         = new Merchant_Analytics_Data_Reports();
$date_ranges     = $reports->get_last_and_previous_7_days_ranges();
$added_revenue   = $reports->get_reveue_card_report( $date_ranges['previous_7_days'], $date_ranges['last_7_days'] );
$added_orders    = $reports->get_total_new_orders_card_report( $date_ranges['previous_7_days'], $date_ranges['last_7_days'] );
$aov_rate        = $reports->get_aov_card_report( $date_ranges['previous_7_days'], $date_ranges['last_7_days'] );
$conversion_rate = $reports->get_conversion_rate_card_report( $date_ranges['previous_7_days'], $date_ranges['last_7_days'] );
$impressions     = $reports->get_impressions_card_report( $date_ranges['previous_7_days'], $date_ranges['last_7_days'] );
?>
<div class="merchant-analytics-overview-section">
	<div class="overview-head">
		<div class="head-wrapper">
			<div class="title">
                <span class="title-text"><?php
	                esc_html_e( 'Merchant Analytics Dashboard', 'merchant' ); ?></span>
			</div>
			<div class="date-range">
				<span class="merchant-analytics-loading-spinner"></span>
                <span class="second-date-range">
                    <input type="text" class="date-range-input" readonly value="<?php
                    echo esc_attr( implode( ' - ', array_values( $date_ranges['last_7_days'] ) ) ) ?>" placeholder="<?php
                    esc_attr_e( 'Date range', 'merchant' ); ?>">
                </span>

				<span class="compare-text"><?php esc_html_e( 'Comparing to', 'merchant' ); ?></span>
                <span class="first-date-range">
                    <input type="text" class="date-range-input" readonly value="<?php
                    echo esc_attr( implode( ' - ', array_values( $date_ranges['previous_7_days'] ) ) ) ?>" placeholder="<?php
                    esc_attr_e( 'Date range', 'merchant' ); ?>">
                </span>
			</div>
		</div>
	</div>
	<div class="overview-cards">
		<div class="overview-card revenue">
			<div class="card-title"><?php
				esc_html_e( 'Added revenue', 'merchant' ); ?></div>
			<div class="card-value"><?php
				echo wp_kses( wc_price( $added_revenue['revenue_second_period'] ), merchant_kses_allowed_tags( array( 'all' ) ) ) ?></div>
			<div class="card-change <?php
			echo esc_html( $added_revenue['revenue_change'][1] ) ?>"><?php
				echo esc_html( wc_format_decimal( $added_revenue['revenue_change'][0], 2 ) ) ?>%
			</div>
			<span class="info-icon" data-tooltip="<?php
			esc_attr_e( 'Revenue added by Merchant.', 'merchant' ); ?>"></span>
		</div>
		<div class="overview-card total-orders">
			<div class="card-title"><?php
				esc_html_e( 'Total orders', 'merchant' ); ?></div>
			<div class="card-value"><?php
				echo esc_html( $added_orders['orders_second_period'] ) ?></div>
			<div class="card-change <?php
			echo esc_html( $added_orders['orders_change'][1] ) ?>"><?php
				echo esc_html( wc_format_decimal( $added_orders['orders_change'][0], 2 ) ) ?>%
			</div>
			<span class="info-icon" data-tooltip="<?php
			esc_attr_e( 'Total number of orders involving Merchant.', 'merchant' ); ?>"></span>
		</div>
		<div class="overview-card aov">
			<div class="card-title"><?php
				esc_html_e( 'Average order value', 'merchant' ); ?></div>
			<div class="card-value"><?php
				echo wp_kses( wc_price( $aov_rate['aov_second_period'] ), merchant_kses_allowed_tags( array( 'all' ) ) ) ?></div>
			<div class="card-change <?php
			echo esc_attr( $aov_rate['change'][1] ) ?>"><?php
				echo esc_html( wc_format_decimal( $aov_rate['change'][0], 2 ) ) ?>%
			</div>
			<span class="info-icon" data-tooltip="<?php
			esc_attr_e( 'Average order value for Merchant orders.', 'merchant' ); ?>"></span>
		</div>
		<div class="overview-card conversion-rate">
			<div class="card-title"><?php
				esc_html_e( 'Conversion rate', 'merchant' ); ?></div>
			<div class="card-value"><?php
				echo esc_html( wc_format_decimal( $conversion_rate['conversion_second_period'], 2 ) ) ?>%
			</div>
			<div class="card-change <?php
			echo esc_attr( $conversion_rate['change'][1] ) ?>"><?php
				echo esc_html( wc_format_decimal( $conversion_rate['change'][0], 2 ) ) ?>%
			</div>
			<span class="info-icon" data-tooltip="<?php
			esc_attr_e( 'The percentage of Merchant offer viewers who made a purchase.', 'merchant' ); ?>"></span>
		</div>
		<div class="overview-card impressions">
			<div class="card-title"><?php
				esc_html_e( 'Impressions', 'merchant' ); ?></div>
			<div class="card-value"><?php
				echo esc_html( $impressions['impressions_second_period'] ) ?>
			</div>
			<div class="card-change <?php
			echo esc_attr( $impressions['change'][1] ) ?>"><?php
				echo esc_html( wc_format_decimal( $impressions['change'][0], 2 ) ) ?>%
			</div>
			<span class="info-icon" data-tooltip="<?php
			esc_attr_e( 'The number of times Merchant offers were seen.', 'merchant' ); ?>"></span>
		</div>
	</div>
</div>
