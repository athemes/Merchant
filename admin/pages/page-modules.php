<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$merchant_modules = Merchant_Admin_Modules::get_modules();
?>
<div class="merchant-modules-header">

    <div class="merchant-modules-header-left">

        <div class="merchant-module-page-back">
            <a href="<?php echo esc_url( add_query_arg( array( 'page' => 'merchant' ), 'admin.php' ) ); ?>">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.3329 7.20013H4.46628L7.19961 4.20013L6.46628 3.4668L2.59961 7.6668L6.46628 11.5335L7.19961 10.8001L4.53294 8.20013H13.3329V7.20013Z" fill="#1D2327"/>
                </svg>
                <span><?php esc_html_e( 'Go Back', 'merchant' ); ?></span>
            </a>
        </div>

        <div class="merchant-modules-header-heading"><?php esc_html_e( 'Explore All Merchant Modules', 'merchant' ); ?></div>

        <div class="merchant-modules-header-subheading"><?php esc_html_e( 'Select any module to configure its settings and enable it for your store.', 'merchant' ); ?></div>

        <div class="merchant-modules-header-status">
            <div class="merchant-modules-header-status-box">
				<?php esc_html_e( 'Total Modules', 'merchant' ); ?>
                <strong>
					<?php
					$merchant_count          = 0;
					$merchant_active_modules = 0;
					$merchant_option_modules = get_option( 'merchant-modules', array() );

					if ( ! empty( $merchant_modules ) ) {
						foreach ( $merchant_modules as $merchant_data ) {
							if ( ! empty( $merchant_data['modules'] ) ) {
								foreach ( $merchant_data['modules'] as $merchant_module_id => $merchant_module ) {
									if ( ! empty( $merchant_option_modules[ $merchant_module_id ] ) ) {
										++$merchant_active_modules;
									}

									++$merchant_count;
								}
							}
						}
					}

					echo esc_attr( $merchant_count );
					?>
                </strong>
            </div>
            <div class="merchant-modules-header-status-box">
                <span><?php esc_html_e( 'Active Modules', 'merchant' ); ?><i class="merchant-modules-indicator"></i></span>
                <strong><?php echo esc_html( $merchant_active_modules ); ?></strong>
            </div>
        </div>
    </div>

    <div class="merchant-modules-header-right">
        <div class="merchant-modules-header-image">
            <img src="<?php echo esc_url( MERCHANT_URI ); ?>assets/images/modules-hero.svg" alt="<?php echo esc_attr__( 'Merchant', 'merchant' ); ?>"/>
        </div>
    </div>
</div>

<div class="merchant-modules-panel">
	<?php require_once MERCHANT_DIR . 'admin/components/all-modules.php'; ?>
</div>
