<?php

// Temporary place for this data. Move to a better place maybe in Analytics file later
$campaigns_data   = array();
$analytics_module = array( 'volume-discounts', 'buy-x-get-y', 'pre-orders', 'frequently-bought-together', 'storewide-sale' );

$options = get_option( 'merchant', array() );

foreach ( $options as $module_id => $option ) {
    if ( ! in_array( $module_id, $analytics_module, true ) ) {
        continue;
    }

    $campaigns = $option['offers'] ?? $option['rules'] ?? array();
    
    if ( empty( $campaigns ) ) {
        continue;
    }

	$module_info = Merchant_Admin_Modules::get_module_info( $module_id );

    $data = array();
    foreach ( $campaigns as $index => $campaign ) {
	    $campaign_url = add_query_arg( array( 'page' => 'merchant', 'module' => $module_id, 'campaign' => $index ), 'admin.php' );

        $data[] = array(
		    'title' => $campaign['offer-title'] ?? '',
		    'status'     => empty( $campaign['disable_campaign'] ) ? 'active' : 'inactive',
		    'impression' => $campaign['impression'] ?? wp_rand( 5000, 15000 ), // using rand temporary, change to - dashes for the final code
		    'clicks'     => $campaign['clicks'] ?? wp_rand( 1000, 8000 ),
		    'revenue'    => $campaign['revenue'] ?? wp_rand( 500, 5000 ),
		    'ctr'        => $campaign['ctr'] ?? wp_rand( -15, 15 ),
		    'orders'     => $campaign['orders'] ?? wp_rand( 50, 500 ),
		    'url'        => $campaign_url,
        );
    }

	$campaigns_data[] = array(
		'module_id'   => $module_id,
		'module_name' => esc_html( $module_info['title'] ?? '' ),
		'campaigns'   => $data,
    );
}

$sorting_indicator_html = '<span class="sorting-indicators"><span class="sorting-indicator asc" aria-hidden="true"></span><span class="sorting-indicator desc" aria-hidden="true"></span></span>';
?>
<table class="wp-list-table widefat table-view-list merchant-campaigns-table">
    <thead>
        <tr>
            <th><input type="checkbox" /></th>
            <th>
                <a href="#" class="sort" data-sort="campaign-name">
                    <?php echo esc_html__( 'Campaign Name', 'merchant' ); ?>
                    <?php echo wp_kses_post( $sorting_indicator_html ); ?>
                </a>
            </th>
            <th>
                <a href="#" class="sort" data-sort="module-name">
                    <?php echo esc_html__( 'Module Name', 'merchant' ); ?>
                    <?php echo wp_kses_post( $sorting_indicator_html ); ?>
                </a>
            </th>
            <th>
                <a href="#" class="sort" data-sort="campaign-status">
	                <?php echo esc_html__( 'Status', 'merchant' ); ?>
		            <?php echo wp_kses_post( $sorting_indicator_html ); ?>
                </a>
            </th>
            <th>
                <a href="#" class="sort" data-sort="impressions">
                    <?php echo esc_html__( 'Impressions', 'merchant' ); ?>
                    <?php echo wp_kses_post( $sorting_indicator_html ); ?>
                </a>
            </th>
            <th>
                <a href="#" class="sort" data-sort="clicks">
                    <?php echo esc_html__( 'Clicks', 'merchant' ); ?>
                    <?php echo wp_kses_post( $sorting_indicator_html ); ?>
                </a>
            </th>
            <th>
                <a href="#" class="sort" data-sort="revenue">
                    <?php echo esc_html__( 'Revenue', 'merchant' ); ?>
                    <?php echo wp_kses_post( $sorting_indicator_html ); ?>
                </a>
            </th>
            <th>
                <a href="#" class="sort" data-sort="ctr">
                    <?php echo esc_html__( 'CTR', 'merchant' ); ?>
                    <?php echo wp_kses_post( $sorting_indicator_html ); ?>
                </a>
            </th>
            <th>
                <a href="#" class="sort" data-sort="orders">
                    <?php echo esc_html__( 'Orders', 'merchant' ); ?>
                    <?php echo wp_kses_post( $sorting_indicator_html ); ?>
                </a>
            </th>
            <th><?php echo esc_html__( 'Action', 'merchant' ); ?></th>
        </tr>
    </thead>

    <tbody>
        <?php
        foreach ( $campaigns_data as $module_index => $module ) :
            $module_id = $module['module_id'] ?? '';
            if ( empty( $module_id ) ) {
                continue;
            }

            foreach ( $module['campaigns'] as $campaign_index => $campaign ) :
                $ctr_color = $campaign['ctr'] < 0 ? 'red' : 'green';
                $ctr_icon  = $campaign['ctr'] < 0 ? '' : '＋';
                ?>
                <tr>
                    <td><input type="checkbox" name="campaign_select[]" value="<?php echo esc_attr( $campaign['title'] ); ?>" /></td>
                    <td class="campaign-name"><?php echo esc_html( $campaign['title'] ); ?></td>
                    <td class="module-name"><?php echo esc_html( $module['module_name'] ); ?></td>
                    <td class="status merchant-module-page-setting-field-switcher">
                        <?php
                        $_id = $module_id . '-campaign-' . $module_index . '-' . $campaign_index;
                        Merchant_Admin_Options::switcher( array( 'id' => $_id ), $campaign['status'] === 'active', $module_id );
                        ?>
                    </td>
                    <td class="impressions"><?php echo esc_html( number_format( $campaign['impression'] ) ); ?></td>
                    <td class="clicks"><?php echo esc_html( number_format( $campaign['clicks'] ) ); ?></td>
                    <td class="revenue"><?php echo wp_kses_post( wc_price( $campaign['revenue'] ) ); ?></td>
                    <td class="ctr" style="color: <?php echo esc_attr( $ctr_color ); ?>;">
                        <?php echo esc_html( $ctr_icon ) . esc_html( $campaign['ctr'] . '%' ); ?>
                    </td>
                    <td class="orders"><?php echo esc_html( number_format( $campaign['orders'] ) ); ?></td>
                    <td>
                        <a href="<?php echo esc_url( $campaign['url'] ); ?>">
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
