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
                <span>
                    <input type="text" class="date-range-input second-period">
                </span>
            </div>
        </div>
    </div>

    <div class="overview-cards">
        <div class="overview-card">
			<?php echo wc_price(20)?>
        </div>
        <div class="overview-card">
			<?php echo wc_price(20)?>
        </div>
        <div class="overview-card">
			<?php echo wc_price(20)?>
        </div>
        <div class="overview-card">
			<?php echo wc_price(20)?>
        </div>
        <div class="overview-card">
			<?php echo wc_price(20)?>
        </div>
    </div>
</div>
<div class="merchant-analytics-section">
    <div class="chart-head">
        <div class="head-wrapper">
            <div class="title">
                <span class="title-text"><?php
					esc_html_e( 'Daily added revenue by Merchant', 'merchant' ); ?></span>
            </div>
            <div class="date-range">
                <span>
                    <input type="text" class="date-range-input second-period">
                </span>
            </div>
        </div>
    </div>
    <div class="revenue-chart" data-period="<?php
	echo esc_attr( wp_json_encode( $reports->get_revenue( '2024-01-01 00:00:00', '2024-12-29 23:59:59' ) ) )
	?>"></div>
</div>
<div class="merchant-analytics-section">
    <div class="chart-head">
        <div class="head-wrapper">
            <div class="title">
                <span class="title-text"><?php
	                esc_html_e( 'Average order value', 'merchant' ); ?></span>
            </div>
            <div class="date-range">
                <span>
                    <input type="text" class="date-range-input">
                </span>
            </div>
        </div>
    </div>
    <div class="avg-order-value-chart" data-period="<?php
	echo esc_attr( wp_json_encode( $reports->get_average_order_value( '2024-01-01 00:00:00', '2024-12-29 23:59:59' ) ) )
	?>"></div>
</div>
<div class="merchant-analytics-section">
    <div class="chart-head">
        <div class="head-wrapper">
            <div class="title">
                <span class="title-text"><?php
	                esc_html_e( 'Number of impressions', 'merchant' ); ?></span>
            </div>
            <div class="date-range">
                <span>
                    <input type="text" class="date-range-input">
                </span>
            </div>
        </div>
    </div>
    <div class="impressions-chart" data-period="<?php
	echo esc_attr( wp_json_encode( $reports->get_impressions( '2024-12-01 00:00:00', '2025-11-30 23:59:59' ) ) )
	?>"></div>
</div>
