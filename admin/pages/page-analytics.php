<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$reports = new Merchant_Analytics_Data_Reports();
?>
<div class="merchant-analytics-overview-section">
    <div class="overview-head">
        <div class="head-wrapper">
            <div class="title">
                <span class="title-text"><?php
	                esc_html_e( 'Overall Overview', 'merchant' ); ?></span>
            </div>
            <div class="date-range">
                <span class="merchant-analytics-loading-spinner"></span>
                <span>
                    <input type="text" class="date-range-input" placeholder="<?php
                    esc_attr_e( 'Date range', 'merchant' ); ?>">
                </span>
                <span class="compare-text">
                    Comparing to
                </span>
                <span>
                    <input type="text" class="date-range-input" placeholder="<?php
                    esc_attr_e( 'Date range', 'merchant' ); ?>">
                </span>
            </div>
        </div>
    </div>

    <div class="overview-cards">
        <div class="overview-card">
			<?php
			echo wc_price( 20 ) ?>
        </div>
        <div class="overview-card">
			<?php
			echo wc_price( 20 ) ?>
        </div>
        <div class="overview-card">
			<?php
			echo wc_price( 20 ) ?>
        </div>
        <div class="overview-card">
			<?php
			echo wc_price( 20 ) ?>
        </div>
        <div class="overview-card">
			<?php
			echo wc_price( 20 ) ?>
        </div>
    </div>
</div>
<div class="merchant-analytics-section revenue-chart-section">
    <div class="chart-head">
        <div class="head-wrapper">
            <div class="title">
                <span class="title-text"><?php
	                esc_html_e( 'Daily added revenue by Merchant', 'merchant' ); ?></span>
            </div>
            <div class="date-range">
                <span class="merchant-analytics-loading-spinner"></span>
                <span>
                    <input type="text" class="date-range-input" placeholder="<?php
                    esc_attr_e( 'Select date range', 'merchant' ); ?>">
                </span>
            </div>
        </div>
    </div>
    <div class="chart" data-period="<?php
	echo esc_attr( wp_json_encode( $reports->get_revenue_chart_report( '2024-01-01 00:00:00', '2024-12-29 23:59:59' ) ) )
	?>"></div>
</div>
<div class="merchant-analytics-section aov-chart-section">
    <div class="chart-head">
        <div class="head-wrapper">
            <div class="title">
                <span class="title-text"><?php
	                esc_html_e( 'Average order value', 'merchant' ); ?></span>
            </div>
            <div class="date-range">
                <span class="merchant-analytics-loading-spinner"></span>
                <span>
                    <input type="text" class="date-range-input" placeholder="<?php
                    esc_attr_e( 'Select date range', 'merchant' ); ?>">
                </span>
            </div>
        </div>
    </div>
    <div class="chart" data-period="<?php
	echo esc_attr( wp_json_encode( $reports->get_aov_chart_report( '2024-01-01 00:00:00', '2024-12-29 23:59:59' ) ) )
	?>"></div>
</div>
<div class="merchant-analytics-section impressions-chart-section">
    <div class="chart-head">
        <div class="head-wrapper">
            <div class="title">
                <span class="title-text"><?php
	                esc_html_e( 'Number of impressions', 'merchant' ); ?></span>
            </div>
            <div class="date-range">
                <span class="merchant-analytics-loading-spinner"></span>
                <span>
                    <input type="text" class="date-range-input" placeholder="<?php
                    esc_attr_e( 'Select date range', 'merchant' ); ?>">
                </span>
            </div>
        </div>
    </div>
    <div class="chart" data-period="<?php
	echo esc_attr( wp_json_encode( $reports->get_impressions_chart_report( '2024-12-01 00:00:00', '2025-11-30 23:59:59' ) ) )
	?>"></div>
</div>
