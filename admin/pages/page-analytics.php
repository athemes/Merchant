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
                <span class="first-date-range">
                    <input type="text" class="date-range-input" readonly value="<?php
                    echo esc_attr( implode( ',', array_values( $date_ranges['previous_7_days'] ) ) ) ?>" placeholder="<?php
                    esc_attr_e( 'Date range', 'merchant' ); ?>">
                </span>
                <span class="compare-text">
                    Comparing to
                </span>
                <span class="second-date-range">
                    <input type="text" class="date-range-input" readonly value="<?php
                    echo esc_attr( implode( ',', array_values( $date_ranges['last_7_days'] ) ) ) ?>" placeholder="<?php
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
			esc_attr_e( '💰 How much extra cash flowed in between your dates.', 'merchant' ); ?>"></span>
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
			esc_attr_e( '📦 More or fewer orders? Here’s the gap.', 'merchant' ); ?>"></span>
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
			esc_attr_e( '💸 Did customers spend more or less per order?', 'merchant' ); ?>"></span>
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
			esc_attr_e( '📊 Did more visitors turn into buyers?', 'merchant' ); ?>"></span>
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
			esc_attr_e( '👀 How many more eyes saw your content?', 'merchant' ); ?>"></span>
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
                    <input type="text" class="date-range-input" readonly value="<?php
                    echo esc_attr( implode( ',', array_values( $date_ranges['last_7_days'] ) ) ) ?>" placeholder="<?php
                    esc_attr_e( 'Select date range', 'merchant' ); ?>">
                </span>
            </div>
        </div>
    </div>
    <div class="chart" data-period="<?php
	echo esc_attr( wp_json_encode( $reports->get_revenue_chart_report( $date_ranges['last_7_days']['start'], $date_ranges['last_7_days']['end'] ) ) )
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
                    <input type="text" class="date-range-input" readonly value="<?php
                    echo esc_attr( implode( ',', array_values( $date_ranges['last_7_days'] ) ) ) ?>" placeholder="<?php
                    esc_attr_e( 'Select date range', 'merchant' ); ?>">
                </span>
            </div>
        </div>
    </div>
    <div class="chart" data-period="<?php
	echo esc_attr( wp_json_encode( $reports->get_aov_chart_report( $date_ranges['last_7_days']['start'], $date_ranges['last_7_days']['end'] ) ) )
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
                    <input type="text" class="date-range-input" readonly value="<?php
                    echo esc_attr( implode( ',', array_values( $date_ranges['last_7_days'] ) ) ) ?>" placeholder="<?php
                    esc_attr_e( 'Select date range', 'merchant' ); ?>">
                </span>
            </div>
        </div>
    </div>
    <div class="chart" data-period="<?php
	echo esc_attr( wp_json_encode( $reports->get_impressions_chart_report( $date_ranges['last_7_days']['start'], $date_ranges['last_7_days']['end'] ) ) )
	?>"></div>
</div>
<div class="merchant-analytics-section campaigns-table">
    <div class="overview-head">
        <div class="head-wrapper">
            <div class="title">
                <span class="title-text"><?php
	                esc_html_e( 'Top performing campaigns', 'merchant' ); ?></span>
            </div>
            <div class="date-range">
                <span class="merchant-analytics-loading-spinner"></span>
                <span class="first-date-range">
                    <input type="text" class="date-range-input" readonly value="<?php
                    echo esc_attr( implode( ',', array_values( $date_ranges['previous_7_days'] ) ) ) ?>" placeholder="<?php
                    esc_attr_e( 'Date range', 'merchant' ); ?>">
                </span>
                <span class="compare-text">
                    Comparing to
                </span>
                <span class="second-date-range">
                    <input type="text" class="date-range-input" readonly value="<?php
                    echo esc_attr( implode( ',', array_values( $date_ranges['last_7_days'] ) ) ) ?>" placeholder="<?php
                    esc_attr_e( 'Date range', 'merchant' ); ?>">
                </span>
            </div>
        </div>
    </div>
    <div class="campaigns-table-wrapper">
        <table>
            <thead>
            <tr>
                <th class="module" data-sort="string"><?php esc_html_e('Modules', 'merchant'); ?></th>
                <th class="impressions" data-sort="int"><?php esc_html_e('Impressions', 'merchant'); ?></th>
                <th class="clicks" data-sort="int"><?php esc_html_e('Clicks', 'merchant'); ?></th>
                <th class="ctr" data-sort="float"><?php esc_html_e('CTR', 'merchant'); ?></th>
                <th class="orders" data-sort="int"><?php esc_html_e('Orders', 'merchant'); ?></th>
                <th class="revenue" data-sort="float"><?php esc_html_e('Revenue', 'merchant'); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>FBT:Campaign1</td>
                <td>9000</td>
                <td>7203</td>
                <td class="increase">12.2</td>
                <td>1567</td>
                <td><?php
					echo wc_price( '97.45' ) ?></td>
            </tr>
            <tr>
                <td>FBT:Campaign2</td>
                <td>7000</td>
                <td>600</td>
                <td class="decrease">52.2</td>
                <td>3567</td>
                <td><?php
					echo wc_price( '87.45' ) ?></td>
            </tr>
            <tr>
                <td>FBT:Campaign3</td>
                <td>9800</td>
                <td>2303</td>
                <td class="increase">22.5</td>
                <td>567</td>
                <td><?php
					echo wc_price( '22.35' ) ?></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>