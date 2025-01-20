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
$campaigns_table = $reports->get_top_performing_campaigns( $date_ranges['previous_7_days'], $date_ranges['last_7_days'] );
?>
<?php require_once MERCHANT_DIR . 'admin/components/analytics-overview.php'; ?>

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
                <span class="second-date-range">
                    <input type="text" class="date-range-input" readonly value="<?php
                    echo esc_attr( implode( ',', array_values( $date_ranges['last_7_days'] ) ) ) ?>" placeholder="<?php
                    esc_attr_e( 'Date range', 'merchant' ); ?>">
                </span>
                <span class="compare-text">
                    Comparing to
                </span>
                <span class="first-date-range">
                    <input type="text" class="date-range-input" readonly value="<?php
                    echo esc_attr( implode( ',', array_values( $date_ranges['previous_7_days'] ) ) ) ?>" placeholder="<?php
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
			<?php
			if ( ! empty( $campaigns_table ) ) {
				foreach ( $campaigns_table as $campaign ) {
					?>
                    <tr>
                        <td><?php
							echo esc_html( $campaign['campaign_info']['module_name'] . ': ' . $campaign['campaign_info']['campaign_title'] ) ?></td>
                        <td><?php
							echo esc_html( $campaign['impressions'] ) ?></td>
                        <td><?php
							echo esc_html( $campaign['clicks'] ) ?></td>
                        <td class="<?php
                        echo esc_attr( $campaign['ctr']['change'][1] ) ?>"><?php
	                        echo esc_html( $campaign['ctr']['change'][0] === 0 ? '-' : $campaign['ctr']['change'][0]  ) ?></td>
                        <td><?php
							echo esc_html( $campaign['orders'] ) ?></td>
                        <td><?php
							echo wp_kses( wc_price( $campaign['revenue'] ), merchant_kses_allowed_tags( array( 'all' ) ) ) ?></td>
                    </tr>
					<?php
				}
			} else {
				?>
                <tr>
                    <td colspan="6" style="text-align: center"><?php
						esc_html_e( 'No data available', 'merchant' ); ?></td>
                </tr>
				<?php
			}
			?>
            </tbody>
        </table>
    </div>
</div>