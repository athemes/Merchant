<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$reports         = new Merchant_Analytics_Data_Reports();
$date_ranges     = $reports->get_last_and_previous_7_days_ranges();
$campaigns_table = $reports->get_top_performing_campaigns( $date_ranges['recent_period'] );
$overview_data   = $reports->main_analytics_cards_report();

/**
 * Hook: merchant_analytics_page_before.
 *
 * @hooked merchant_analytics_page_before - 10
 *
 * @since 2.0
 */
do_action( 'merchant_analytics_page_before', $reports, $date_ranges );
require_once MERCHANT_DIR . 'admin/components/analytics-overview.php'; ?>
<div class="merchant-analytics-section revenue-chart-section">
    <div class="chart-head">
        <div class="head-wrapper">
            <div class="title">
                <span class="title-text"><?php
	                esc_html_e( 'Daily added revenue by Merchant', 'merchant' ); ?></span>
            </div>
            <div class="date-range">
                <span class="merchant-analytics-loading-spinner"></span>
                <span class="single-date-range" data-title="<?php echo esc_attr__( 'Date:', 'merchant' ); ?>">
                    <input type="text" class="date-range-input" readonly value="<?php
                    echo esc_attr( implode( ' - ', array_values( $date_ranges['recent_period'] ) ) ) ?>" placeholder="<?php
                    esc_attr_e( 'Select date range', 'merchant' ); ?>">
                </span>
            </div>
        </div>
    </div>
    <div class="chart" data-period="<?php
	echo esc_attr( wp_json_encode( $reports->get_revenue_chart_report( $date_ranges['recent_period']['start'], $date_ranges['recent_period']['end'] ) ) )
	?>"></div>
</div>
<?php
/**
 * Hook: merchant_analytics_page_between_revenue_and_aov.
 *
 * @hooked merchant_analytics_page_between_revenue_and_aov - 10
 *
 * @since 2.0
 */
do_action( 'merchant_analytics_page_between_revenue_and_aov', $reports, $date_ranges ); ?>
<div class="merchant-analytics-section aov-chart-section">
    <div class="chart-head">
        <div class="head-wrapper">
            <div class="title">
                <span class="title-text"><?php
	                esc_html_e( 'Average order value', 'merchant' ); ?></span>
            </div>
            <div class="date-range">
                <span class="merchant-analytics-loading-spinner"></span>
                <span class="single-date-range" data-title="<?php echo esc_attr__( 'Date:', 'merchant' ); ?>">
                    <input type="text" class="date-range-input" readonly value="<?php
                    echo esc_attr( implode( ' - ', array_values( $date_ranges['recent_period'] ) ) ) ?>" placeholder="<?php
                    esc_attr_e( 'Select date range', 'merchant' ); ?>">
                </span>
            </div>
        </div>
    </div>
    <div class="chart" data-period="<?php
	echo esc_attr( wp_json_encode( $reports->get_aov_chart_report( $date_ranges['recent_period']['start'], $date_ranges['recent_period']['end'] ) ) )
	?>"></div>
</div>
<?php
/**
 * Hook: merchant_analytics_page_between_aov_and_impressions.
 *
 * @hooked merchant_analytics_page_between_aov_and_impressions - 10
 *
 * @since 2.0
 */
do_action( 'merchant_analytics_page_between_aov_and_impressions', $reports, $date_ranges ); ?>
<div class="merchant-analytics-section impressions-chart-section">
    <div class="chart-head">
        <div class="head-wrapper">
            <div class="title">
                <span class="title-text"><?php
	                esc_html_e( 'Number of impressions', 'merchant' ); ?></span>
            </div>
            <div class="date-range">
                <span class="merchant-analytics-loading-spinner"></span>
                <span class="single-date-range" data-title="<?php echo esc_attr__( 'Date:', 'merchant' ); ?>">
                    <input type="text" class="date-range-input" readonly value="<?php
                    echo esc_attr( implode( ' - ', array_values( $date_ranges['recent_period'] ) ) ) ?>" placeholder="<?php
                    esc_attr_e( 'Select date range', 'merchant' ); ?>">
                </span>
            </div>
        </div>
    </div>
    <div class="chart" data-period="<?php
	echo esc_attr( wp_json_encode( $reports->get_impressions_chart_report( $date_ranges['recent_period']['start'], $date_ranges['recent_period']['end'] ) ) )
	?>"></div>
</div>
<?php
/**
 * Hook: merchant_analytics_page_between_impressions_and_campaigns_table.
 *
 * @hooked merchant_analytics_page_between_impressions_and_campaigns_table - 10
 *
 * @since 2.0
 */
do_action( 'merchant_analytics_page_between_impressions_and_campaigns_table', $reports, $date_ranges );
?>
<div class="merchant-analytics-section campaigns-table">
    <div class="overview-head">
        <div class="head-wrapper">
            <div class="title">
                <span class="title-text"><?php
	                esc_html_e( 'Top performing campaigns', 'merchant' ); ?></span>
            </div>
            <div class="date-range">
                <span class="merchant-analytics-loading-spinner"></span>
                <span class="first-date-range first-date-range-top-campaigns" data-title="<?php echo esc_attr__( 'Date:', 'merchant' ); ?>">
                    <input type="text" class="date-range-input" readonly value="<?php
                    echo esc_attr( implode( ' - ', array_values( $date_ranges['recent_period'] ) ) ) ?>" placeholder="<?php
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
                        <td class="ctr"><?php
	                        echo esc_html( $campaign['ctr'] ) ?></td>
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
                    <td colspan="6" style="text-align: center;background: #fff;font-size: 20px;padding-block: 50px;color: #101517;"><?php esc_html_e( 'No data available', 'merchant' ); ?></td>
                </tr>
				<?php
			}
			?>
            </tbody>
        </table>
    </div>
</div>
<?php
/**
 * Hook: merchant_analytics_page_after.
 *
 * @hooked merchant_analytics_page_after - 10
 *
 * @since 2.0
 */
do_action( 'merchant_analytics_page_after', $reports, $date_ranges );