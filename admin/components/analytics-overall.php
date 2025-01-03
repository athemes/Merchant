<?php
/**
 * Analytics overall.
 *
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$analytics_data = array(
	array(
		'id'          => 'added_revenue',
		'title'       => esc_html__( 'Added revenue', 'merchant' ),
		'value'       => '$44.20',
		'growth_rate' => 9,
		'tooltip'     => esc_html__( 'Total extra revenue generated this period', 'merchant' ),
	),
	array(
		'id'          => 'total_orders',
		'title'       => esc_html__( 'Total orders', 'merchant' ),
		'value'       => '57.45%',
		'growth_rate' => 13,
		'tooltip'     => esc_html__( 'Overall increase in the number of orders', 'merchant' ),
	),
	array(
		'id'          => 'average_order_value',
		'title'       => esc_html__( 'Average order value', 'merchant' ),
		'value'       => '45.30',
		'growth_rate' => 6,
		'tooltip'     => esc_html__( 'Average amount spent per order', 'merchant' ),
	),
	array(
		'id'          => 'conversion_rate',
		'title'       => esc_html__( 'Conversion rate', 'merchant' ),
		'value'       => '12.32%',
		'growth_rate' => -9,
		'tooltip'     => esc_html__( 'Percentage of visitors who made a purchase', 'merchant' ),
	),
	array(
		'id'          => 'impressions',
		'title'       => esc_html__( 'Impressions', 'merchant' ),
		'value'       => '32.8%',
		'growth_rate' => 6,
		'tooltip'     => esc_html__( 'Number of times products were displayed', 'merchant' ),
	),
);
?>
<div class="merchant-modules-analytics">
	<?php foreach ( $analytics_data as $data ) :
		$value = ! empty( $data['value'] ) ? $data['value'] : '-';
		?>
		<div class="merchant-modules-analytics__item">
			<div class="merchant-modules-analytics__title">
                <?php echo esc_html( $data['title'] ); ?>
            </div>
			<div class="merchant-modules-analytics__value <?php echo esc_attr( empty( $value ) ? 'merchant-modules-analytics__value--empty' :  '' ); ?>">
                <?php echo esc_html( $value ); ?>
            </div>
			<span class="merchant-modules-analytics__growth merchant-modules-analytics__growth--<?php echo esc_attr( $data['growth_rate'] > 0 ? 'up' : 'down' ); ?>">
				<?php if ( $data['growth_rate'] > 0 ) : ?>
					<span class="merchant-modules-analytics__growth-arrow merchant-modules-analytics__growth-arrow--up">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="13" viewBox="0 0 11 13" fill="none">
                            <path d="M0.514648 4.95483L5.2959 0.529297M5.2959 0.529297L10.1709 4.95483M5.2959 0.529297V12.2964" stroke="#00BD8A" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
				<?php else : ?>
					<span class="merchant-modules-analytics__growth-arrow merchant-modules-analytics__growth-arrow--down">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="13" viewBox="0 0 11 13" fill="none">
                            <path d="M0.514648 4.95483L5.2959 0.529297M5.2959 0.529297L10.1709 4.95483M5.2959 0.529297V12.2964" stroke="#FF1C1C" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
				<?php endif; ?>
				<?php echo esc_html( absint( $data['growth_rate'] ) ) . '%'; ?>
			</span>
            <div class="merchant-modules-list-item-badge-wrapper">
                <span class="merchant-pro-badge merchant-pro-tooltip merchant-modules-analytics__tooltip" data-tooltip-message="<?php echo esc_attr( $data['tooltip'] ); ?>">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.25 11.75H8.75V7.25H7.25V11.75ZM8 5.75C8.2125 5.75 8.39075 5.678 8.53475 5.534C8.67875 5.39 8.7505 5.212 8.75 5C8.7495 4.788 8.6775 4.61 8.534 4.466C8.3905 4.322 8.2125 4.25 8 4.25C7.7875 4.25 7.6095 4.322 7.466 4.466C7.3225 4.61 7.2505 4.788 7.25 5C7.2495 5.212 7.3215 5.39025 7.466 5.53475C7.6105 5.67925 7.7885 5.751 8 5.75ZM8 15.5C6.9625 15.5 5.9875 15.303 5.075 14.909C4.1625 14.515 3.36875 13.9808 2.69375 13.3063C2.01875 12.6318 1.4845 11.838 1.091 10.925C0.697501 10.012 0.500501 9.037 0.500001 8C0.499501 6.963 0.696501 5.988 1.091 5.075C1.4855 4.162 2.01975 3.36825 2.69375 2.69375C3.36775 2.01925 4.1615 1.485 5.075 1.091C5.9885 0.697 6.9635 0.5 8 0.5C9.0365 0.5 10.0115 0.697 10.925 1.091C11.8385 1.485 12.6323 2.01925 13.3063 2.69375C13.9803 3.36825 14.5148 4.162 14.9098 5.075C15.3048 5.988 15.5015 6.963 15.5 8C15.4985 9.037 15.3015 10.012 14.909 10.925C14.5165 11.838 13.9823 12.6318 13.3063 13.3063C12.6303 13.9808 11.8365 14.5152 10.925 14.9097C10.0135 15.3042 9.0385 15.501 8 15.5ZM8 14C9.675 14 11.0938 13.4187 12.2563 12.2562C13.4187 11.0937 14 9.675 14 8C14 6.325 13.4187 4.90625 12.2563 3.74375C11.0938 2.58125 9.675 2 8 2C6.325 2 4.90625 2.58125 3.74375 3.74375C2.58125 4.90625 2 6.325 2 8C2 9.675 2.58125 11.0937 3.74375 12.2562C4.90625 13.4187 6.325 14 8 14Z" fill="#ACAEC5"/>
                    </svg>
                </span>
            </div>
		</div>
	<?php endforeach; ?>
</div>
