<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="merchant-module-page">

	<div class="merchant-module-page-content mrc-w-100">

		<form method="post" action="" class="merchant-module-page-ajax-form">

			<input type="hidden" name="merchant_nonce" value="<?php echo esc_attr( wp_create_nonce( 'merchant_nonce' ) ); ?>" />

			<div class="merchant-module-page-ajax-header">
				<div class="merchant-module-page-ajax-notice"><?php esc_html_e( 'Your settings have been modified. Save?', 'merchant' ); ?></div>
				<div class="merchant-module-page-ajax-buttons">
					<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'merchant' ) ) ); ?>" class="merchant-module-discard-button"><?php esc_html_e( 'Discard', 'merchant' ); ?></a>
					<button type="submit" name="merchant_save" value="save" class="merchant-module-save-button"><span class="merchant-module-save-button-label"><?php esc_html_e( 'Save', 'merchant' ); ?></span><i class="dashicons dashicons-update-alt"></i></button>
				</div>
			</div>

			<div class="merchant-module-page-header">

				<div class="merchant-module-page-header-content">

					<div class="merchant-module-page-back">
						<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'merchant' ), 'admin.php' ) ); ?>">
							<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M13.3329 7.20013H4.46628L7.19961 4.20013L6.46628 3.4668L2.59961 7.6668L6.46628 11.5335L7.19961 10.8001L4.53294 8.20013H13.3329V7.20013Z" fill="#1D2327"/>
							</svg>
							<span><?php esc_html_e( 'Go Back', 'merchant' ); ?></span>
						</a>
					</div>

					<div class="merchant-module-page-heading">
						<div class="merchant-module-page-info">
							<div class="merchant-module-page-title">
								<?php esc_html_e( 'Global Settings', 'merchant' ); ?>
							</div>
							<div class="merchant-module-page-desc"><?php esc_html_e( 'License activation, operating mode selection and custom code.', 'merchant' ); ?></div>
						</div>
					</div>

				</div>

			</div>

			<div class="merchant-module-page-content mrc-w-100">

				<?php

				/**
				 * Hook: 'merchant_admin_settings_before_options'
				 * 
				 * @since 1.0
				 */
				do_action( 'merchant_admin_settings_before_options' );

				Merchant_Admin_Options::create( array(
					'module'    => 'global-settings',
					'title'     => esc_html__( 'Merchant Operating Mode', 'merchant' ),
					'fields'    => array(

						array(
							'id'      => 'operating_mode',
							'type'    => 'radio_alt',
							'options' => array(
								'active'   => array(
									'title' => esc_html__( 'Active', 'merchant' ),
									'desc'  => esc_html__( 'Merchant is active and visible for all customers. This is the standard operating mode.', 'merchant' ),
								),
								'preview'   => array(
									'title' => esc_html__( 'Preview', 'merchant' ),
									'desc'  => esc_html__( 'All Merchant modules are active and visible only to admins. Use this mode to test Merchant modules before going live.', 'merchant' ),
								),
								'inactive'   => array(
									'title' => esc_html__( 'Inactive', 'merchant' ),
									'desc'  => esc_html__( 'Merchant is inactive for everyone, including you. While in this mode, your store operates like Merchant is not installed.', 'merchant' ),
								),
							),
							'default' => 'active',
						),

					),
				) );

				Merchant_Admin_Options::create( array(
					'module'    => 'global-settings',
					'title'     => esc_html__( 'Global Customizations', 'merchant' ),
					'fields'    => array(

						array(
							'type'    => 'warning',
							'content' => esc_html__( 'Do not modify these settings unless you\'re an experienced developer', 'merchant' ),
						),

						array(
							'type' => 'divider',
						),

						array(
							'id'    => 'custom_css',
							'type'  => 'textarea',
							'title' => esc_html__( 'Custom CSS', 'merchant' ),
						),

						array(
							'id'    => 'custom_js_first',
							'type'  => 'textarea',
							'title' => esc_html__( 'Custom JS First - runs at the beginning of Merchant', 'merchant' ),
						),

						array(
							'id'    => 'custom_js_last',
							'type'  => 'textarea',
							'title' => esc_html__( 'Custom JS Last - runs at the end of Merchant', 'merchant' ),
						),

						array(
							'id'    => 'custom_js',
							'type'  => 'textarea',
							'title' => esc_html__( 'Custom JS', 'merchant' ),
						),

					),
				) );

				/**
				 * Hook: 'merchant_admin_settings_after_options'
				 * 
				 * @since 1.0
				 */
				do_action( 'merchant_admin_settings_after_options' );

				?>

			</div>

		</form>

	</div>

</div>
