<?php
/**
 * All Campaigns.
 *
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$reports     = new Merchant_Analytics_Data_Reports();
$date_ranges = $reports->get_last_and_previous_7_days_ranges();

$campaigns_data = $reports->get_all_campaigns( $date_ranges['recent_period'] );

$total_rows = array_reduce(
	$campaigns_data,
	function ( $count, $module ) {
		return $count + count( $module['campaigns'] ?? array() );
	},
	0
);

/**
 * `merchant_all_campaigns_items_per_page`
 *
 * @since 2.0.0
 */
$rows_per_page = apply_filters( 'merchant_all_campaigns_items_per_page', 25 );

$current_page  = isset( $_GET['page'] ) ? max( 1, (int) $_GET['page'] ) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$total_pages   = ceil( $total_rows / $rows_per_page );
$start_row     = ( $current_page - 1 ) * $rows_per_page + 1;
$end_row       = min( $current_page * $rows_per_page, $total_rows );
?>
<div class="merchant-modules-header-heading merchant-modules-header-heading__campaigns">
    <?php esc_html_e( 'All Campaigns', 'merchant' ); ?>
</div>

<div class="merchant-module-page merchant-page-campaigns merchant-analytics-section all-campaigns-table">
    <div class="merchant__campaigns-table-nav">
        <div class="alignleft">
            <div class="alignleft bulk-action">
                <select class="bulk-action-selector">
                    <option value=""><?php echo esc_html__( 'Bulk Action', 'merchant' ); ?></option>
                    <option value="active"><?php echo esc_html__( 'Enable', 'merchant' ); ?></option>
                    <option value="inactive"><?php echo esc_html__( 'Disable', 'merchant' ); ?></option>
                </select>
                <button class="button js-bulk-action"><?php echo esc_html__( 'Apply', 'merchant' ); ?></button>
            </div>

            <div class="alignleft search-campaign-box">
                <button class="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 15 15" fill="none">
                        <path d="M13.8906 13.5742L10.582 10.2656C10.5 10.2109 10.418 10.1562 10.3359 10.1562H9.98047C10.8281 9.17188 11.375 7.85938 11.375 6.4375C11.375 3.32031 8.80469 0.75 5.6875 0.75C2.54297 0.75 0 3.32031 0 6.4375C0 9.58203 2.54297 12.125 5.6875 12.125C7.10938 12.125 8.39453 11.6055 9.40625 10.7578V11.1133C9.40625 11.1953 9.43359 11.2773 9.48828 11.3594L12.7969 14.668C12.9336 14.8047 13.1523 14.8047 13.2617 14.668L13.8906 14.0391C14.0273 13.9297 14.0273 13.7109 13.8906 13.5742ZM5.6875 10.8125C3.25391 10.8125 1.3125 8.87109 1.3125 6.4375C1.3125 4.03125 3.25391 2.0625 5.6875 2.0625C8.09375 2.0625 10.0625 4.03125 10.0625 6.4375C10.0625 8.87109 8.09375 10.8125 5.6875 10.8125Z" fill="#939393"/>
                    </svg>
                </button>
                <input type="search" class="js-campaign-search"  placeholder="<?php echo esc_html__( 'Search Campaigns', 'merchant' ); ?>">
            </div>
        </div>

        <div class="alignright">
            <div class="alignleft filter-campaign">
                <select class="filter-campaign-selector js-filter-module">
                    <option value=""><?php echo esc_html__( 'All Campaigns', 'merchant' ); ?></option>
                </select>
            </div>

            <div class="alignright overview-head">
                <div class="date-range">
                    <span class="merchant-analytics-loading-spinner"></span>
                    <span class="first-date-range first-date-range-all-campaigns" data-title="<?php echo esc_attr__( 'Date:', 'merchant' ); ?>">
                        <input type="text" class="date-range-input" readonly value="<?php
                        echo esc_attr( implode( ' - ', array_values( $date_ranges['recent_period'] ) ) ) ?>" placeholder="<?php
                        esc_attr_e( 'Date range', 'merchant' ); ?>">
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="campaigns-table-wrapper merchant-page-campaigns">
        <table class="wp-list-table widefat table-view-list merchant__campaigns-table js-campaigns-table">
            <thead>
                <tr>
                    <th class="no-sort"><input type="checkbox"/></th>
                    <th class="merchant-sort" data-sort="string"><?php echo esc_html__( 'Campaign Name', 'merchant' ); ?></th>
                    <th class="merchant-sort" data-sort="string"><?php echo esc_html__( 'Module Name', 'merchant' ); ?></th>
                    <th><?php echo esc_html__( 'Status', 'merchant' ); ?></th>
                    <th class="merchant-sort" data-sort="int"><?php echo esc_html__( 'Impressions', 'merchant' ); ?></th>
                    <th class="merchant-sort" data-sort="int"> <?php echo esc_html__( 'Clicks', 'merchant' ); ?></th>
                    <th class="merchant-sort" data-sort="float"><?php echo esc_html__( 'Revenue', 'merchant' ); ?></th>
                    <th class="merchant-sort" data-sort="float"><?php echo esc_html__( 'CTR', 'merchant' ); ?></th>
                    <th class="merchant-sort" data-sort="int"><?php echo esc_html__( 'Orders', 'merchant' ); ?></th>
                    <th><?php echo esc_html__( 'Action', 'merchant' ); ?></th>
                </tr>
            </thead>

            <tbody>
                <?php
                $count = 0;
                foreach ( $campaigns_data as $module_index => $module ) :
                    $module_id = $module['module_id'] ?? '';
                    if ( empty( $module_id ) ) {
                        continue;
                    }

                    foreach ( $module['campaigns'] as $campaign_index => $campaign ) :
                        ++$count;
                        $hide = $count > $rows_per_page;
                        ?>
                        <tr
                            class="<?php echo $hide ? esc_attr( 'is-hidden' ) : ''; ?>"
                            <?php if ( $hide ) : ?>
                                style="display: none;"
                            <?php endif; ?>
                            data-module-id="<?php echo esc_attr( $module_id ); ?>"
                            data-campaign-key="<?php echo esc_attr( $campaign['campaign_key'] ?? '' ); ?>"
                            data-campaign-id="<?php echo esc_attr( $campaign['campaign_id'] ); ?>"
                            data-row-count="<?php echo esc_attr( $count ); ?>">
                            <td><input type="checkbox" name="campaign_select[]" value="<?php echo esc_attr( $campaign['title'] ); ?>" /></td>
                            <td class="merchant__campaign-name js-campaign-name"><?php echo esc_html( $campaign['title'] ); ?></td>
                            <td class="merchant__module-name js-module-name" data-module-id="<?php echo esc_attr( $module['module_id'] ); ?>"><?php echo esc_html( $module['module_name'] ); ?></td>
                            <td class="merchant__status merchant-module-page-setting-field-switcher js-status">
                                <?php
                                if ( in_array( $campaign['status'], array( 'active', 'inactive' ), true ) ) {
                                    $_id = $module_id . '-campaign-' . $module_index . '-' . $campaign_index;
                                    Merchant_Admin_Options::switcher( array( 'id' => $_id ), $campaign['status'] === 'active', $module_id );
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                            <td class="merchant__impressions"><?php echo esc_html( $campaign['impression'] ); ?></td>
                            <td class="merchant__clicks"><?php echo esc_html( $campaign['clicks'] ); ?></td>
                            <td class="merchant__revenue"><?php echo wp_kses($campaign['revenue'], merchant_kses_allowed_tags( array( 'all' ) ) ); ?></td>
                            <td class="merchant__ctr"><?php echo esc_html( $campaign['ctr'] ); ?></td>
                            <td class="merchant__orders"><?php echo esc_html( $campaign['orders'] ); ?></td>
                            <td class="merchant__edit">
                                <a href="<?php echo esc_url( $campaign['url'] ); ?>" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                        <path d="M8.30399 1.00174C8.90067 0.405063 9.8596 0.405063 10.4563 1.00174L10.7333 1.27876C11.33 1.87543 11.33 2.83437 10.7333 3.43104L6.51398 7.65037C6.3435 7.82085 6.10909 7.97001 5.85338 8.03394L3.7224 8.65192C3.55193 8.69454 3.36014 8.65192 3.23228 8.50276C3.08311 8.3749 3.04049 8.18311 3.08311 8.01263L3.70109 5.88166C3.76502 5.62594 3.91419 5.39154 4.08467 5.22106L8.30399 1.00174ZM9.73175 1.72627C9.53996 1.53448 9.22031 1.53448 9.02852 1.72627L8.38923 2.34425L9.39079 3.3458L10.0088 2.70651C10.2006 2.51473 10.2006 2.19508 10.0088 2.00329L9.73175 1.72627ZM4.68134 6.15869L4.31908 7.41596L5.57635 7.0537C5.66159 7.03239 5.72552 6.98977 5.78945 6.92584L8.66626 4.04903L7.68601 3.06878L4.8092 5.94559C4.74527 6.00952 4.70265 6.07345 4.68134 6.15869ZM4.61741 1.83281C4.89444 1.83281 5.12885 2.06722 5.12885 2.34425C5.12885 2.64258 4.89444 2.85568 4.61741 2.85568H2.23072C1.7406 2.85568 1.37834 3.23926 1.37834 3.70807V9.50431C1.37834 9.99444 1.7406 10.3567 2.23072 10.3567H8.02697C8.49578 10.3567 8.87936 9.99444 8.87936 9.50431V7.11763C8.87936 6.8406 9.09245 6.60619 9.39079 6.60619C9.66782 6.60619 9.90222 6.8406 9.90222 7.11763V9.50431C9.90222 10.5485 9.04983 11.3796 8.02697 11.3796H2.23072C1.18655 11.3796 0.355469 10.5485 0.355469 9.50431V3.70807C0.355469 2.6852 1.18655 1.83281 2.23072 1.83281H4.61741Z" fill="#565865"/>
                                    </svg>
                                    <?php echo esc_html__( 'Edit', 'merchant' ); ?>
                                </a>
                            </td>
                        </tr>
                    <?php
                    endforeach;
                endforeach;
                ?>
            </tbody>
        </table>

        <?php if ( ! $total_rows ) : ?>
            <div class="merchant__campaigns-no-results">
                <img src="<?php echo esc_url( MERCHANT_URI ); ?>assets/images/admin/no-campaigns.svg" alt="<?php echo esc_attr__( 'No campaign available', 'merchant' ); ?>"/>
                <span><?php esc_html_e( 'No campaign available', 'merchant' ); ?></span>
                <span><?php esc_html_e( 'Start by creating and publishing your offers', 'merchant' ); ?></span>
            </div>
        <?php endif; ?>

        <div class="merchant__campaigns-pagination-section">
            <div
                class="merchant__campaigns-pagination js-pagination"
                data-total-rows="<?php echo esc_attr( $total_rows ); ?>"
                data-total-rows-initial="<?php echo esc_attr( $total_rows ); ?>"
                data-total-pages="<?php echo esc_attr( $total_pages ); ?>"
                data-total-pages-initial="<?php echo esc_attr( $total_pages ); ?>"
                data-rows-per-page="<?php echo esc_attr( $rows_per_page ); ?>"
                data-current-page="<?php echo esc_attr( $current_page ); ?>">
		        <?php if ( $total_pages > 1 ) : ?>
                    <button
                        class="pagination-button prev-page"
                        data-page="<?php echo esc_attr( $current_page - 1 ); ?>"
				        <?php if ( $current_page === 1 ) : ?>
                            style="display: none;"
				        <?php endif; ?>>
                        <svg xmlns="http://www.w3.org/2000/svg" width="7" height="12" viewBox="0 0 7 12" fill="#565865">
                            <path d="M5.16797 11.3301L0.521484 6.48047C0.394531 6.32812 0.34375 6.17578 0.34375 6.02344C0.34375 5.89648 0.394531 5.74414 0.496094 5.61719L5.14258 0.767578C5.37109 0.513672 5.77734 0.513672 6.00586 0.742188C6.25977 0.970703 6.25977 1.35156 6.03125 1.60547L1.79102 6.02344L6.05664 10.4922C6.28516 10.7207 6.28516 11.127 6.03125 11.3555C5.80273 11.584 5.39648 11.584 5.16797 11.3301Z"/>
                        </svg>
                    </button>

			        <?php for ( $_page = 1; $_page <= $total_pages; $_page++ ) : ?>
                        <button class="pagination-button<?php echo esc_attr( $_page === $current_page ? ' pagination-active' : '' ); ?>"  data-page="<?php echo esc_attr( $_page ); ?>"><?php echo esc_html( $_page ); ?></button>
			        <?php endfor;?>

                    <button
                        class="pagination-button next-page"
                        data-page="<?php echo esc_attr( $current_page + 1 ); ?>"
				        <?php if ( $current_page >= $total_pages ) : ?>
                            style="display: none;"
				        <?php endif; ?>>
                        <svg xmlns="http://www.w3.org/2000/svg" width="7" height="12" viewBox="0 0 7 12" fill="#565865">
                            <path d="M1.80664 0.742188L6.45312 5.5918C6.55469 5.71875 6.63086 5.87109 6.63086 6.02344C6.63086 6.17578 6.55469 6.32812 6.45312 6.42969L1.80664 11.2793C1.57812 11.5332 1.17188 11.5332 0.943359 11.3047C0.689453 11.0762 0.689453 10.6953 0.917969 10.4414L5.18359 5.99805L0.917969 1.58008C0.689453 1.35156 0.689453 0.945312 0.943359 0.716797C1.17188 0.488281 1.57812 0.488281 1.80664 0.742188Z"/>
                        </svg>
                    </button>
		        <?php endif; ?>
            </div>
            <?php if ( $total_pages > 1 ) : ?>
            <div class="merchant__campaigns-pagination-results js-pagination-results">
                <?php
                printf(
                    /* translators: 1: Start number, 2: End number, 3: Total rows */
	                esc_html__( 'Showing %1$s to %2$s of %3$s items', 'merchant' ),
	                '<span class="pagination-start-row">' . esc_html( $start_row ) . '</span>',
	                '<span class="pagination-end-row">' . esc_html( $end_row ) . '</span>',
	                '<span class="pagination-total-rows">' . esc_html( $total_rows ) . '</span>'
                );
                ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
