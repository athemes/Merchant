<div class="merchant-module-page">

	<div class="merchant-module-page-content">

		<form method="post" action="" class="merchant-module-page-ajax-form">

			<input type="hidden" name="merchant_nonce" value="<?php echo wp_create_nonce( 'merchant_nonce' ); ?>" />

			<div class="merchant-module-page-ajax-header">
				<div class="merchant-module-page-ajax-notice"><?php esc_html_e( 'Your settings have been modified. Save?', 'merchant' ); ?></div>
				<div class="merchant-module-page-ajax-buttons">
					<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'merchant' ) ) ); ?>" class="merchant-module-discard-button"><?php esc_html_e( 'Discard', 'merchant' ); ?></a>
					<button type="submit" name="merchant_save" value="save" class="merchant-module-save-button"><span class="merchant-module-save-button-label"><?php esc_html_e( 'Save', 'merchant' ); ?></span><i class="dashicons dashicons-update-alt"></i></button>
				</div>
			</div>

			<div class="merchant-module-page-header">

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
							<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M12.3334 29.3333L11.8001 25.0666C11.5112 24.9555 11.2392 24.8222 10.9841 24.6666C10.7281 24.5111 10.4779 24.3444 10.2334 24.1666L6.26676 25.8333L2.6001 19.5L6.03343 16.9C6.01121 16.7444 6.0001 16.5942 6.0001 16.4493V15.5493C6.0001 15.4053 6.01121 15.2555 6.03343 15.1L2.6001 12.5L6.26676 6.16663L10.2334 7.83329C10.4779 7.65551 10.7334 7.48885 11.0001 7.33329C11.2668 7.17774 11.5334 7.0444 11.8001 6.93329L12.3334 2.66663H19.6668L20.2001 6.93329C20.489 7.0444 20.7614 7.17774 21.0174 7.33329C21.2725 7.48885 21.5223 7.65551 21.7668 7.83329L25.7334 6.16663L29.4001 12.5L25.9668 15.1C25.989 15.2555 26.0001 15.4053 26.0001 15.5493V16.4493C26.0001 16.5942 25.9779 16.7444 25.9334 16.9L29.3668 19.5L25.7001 25.8333L21.7668 24.1666C21.5223 24.3444 21.2668 24.5111 21.0001 24.6666C20.7334 24.8222 20.4668 24.9555 20.2001 25.0666L19.6668 29.3333H12.3334ZM16.0668 20.6666C17.3557 20.6666 18.4557 20.2111 19.3668 19.3C20.2779 18.3888 20.7334 17.2888 20.7334 16C20.7334 14.7111 20.2779 13.6111 19.3668 12.7C18.4557 11.7888 17.3557 11.3333 16.0668 11.3333C14.7557 11.3333 13.6499 11.7888 12.7494 12.7C11.8499 13.6111 11.4001 14.7111 11.4001 16C11.4001 17.2888 11.8499 18.3888 12.7494 19.3C13.6499 20.2111 14.7557 20.6666 16.0668 20.6666ZM16.0668 18C15.5112 18 15.0392 17.8053 14.6508 17.416C14.2614 17.0275 14.0668 16.5555 14.0668 16C14.0668 15.4444 14.2614 14.9724 14.6508 14.584C15.0392 14.1946 15.5112 14 16.0668 14C16.6223 14 17.0948 14.1946 17.4841 14.584C17.8725 14.9724 18.0668 15.4444 18.0668 16C18.0668 16.5555 17.8725 17.0275 17.4841 17.416C17.0948 17.8053 16.6223 18 16.0668 18ZM14.6668 26.6666H17.3001L17.7668 23.1333C18.4557 22.9555 19.0948 22.6942 19.6841 22.3493C20.2725 22.0053 20.8112 21.5888 21.3001 21.1L24.6001 22.4666L25.9001 20.2L23.0334 18.0333C23.1445 17.7222 23.2223 17.3942 23.2668 17.0493C23.3112 16.7053 23.3334 16.3555 23.3334 16C23.3334 15.6444 23.3112 15.2942 23.2668 14.9493C23.2223 14.6053 23.1445 14.2777 23.0334 13.9666L25.9001 11.8L24.6001 9.53329L21.3001 10.9333C20.8112 10.4222 20.2725 9.99418 19.6841 9.64929C19.0948 9.30529 18.4557 9.0444 17.7668 8.86663L17.3334 5.33329H14.7001L14.2334 8.86663C13.5445 9.0444 12.9059 9.30529 12.3174 9.64929C11.7281 9.99418 11.189 10.4111 10.7001 10.9L7.4001 9.53329L6.1001 11.8L8.96676 13.9333C8.85565 14.2666 8.77788 14.6 8.73343 14.9333C8.68899 15.2666 8.66676 15.6222 8.66676 16C8.66676 16.3555 8.68899 16.7 8.73343 17.0333C8.77788 17.3666 8.85565 17.7 8.96676 18.0333L6.1001 20.2L7.4001 22.4666L10.7001 21.0666C11.189 21.5777 11.7281 22.0053 12.3174 22.3493C12.9059 22.6942 13.5445 22.9555 14.2334 23.1333L14.6668 26.6666Z" fill="#787C82"/>
							</svg>
							<?php esc_html_e( 'Global Settings', 'merchant' ); ?>
						</div>
						<div class="merchant-module-page-desc"><?php esc_html_e( 'Further enhance and customize the Merchant modules to your own needs.', 'merchant' ); ?></div>
					</div>
				</div>

			</div>

			<div class="merchant-module-page-content">

				<?php

					Merchant_Admin_Options::create( array(
						'module'    => 'global-settings',
						'title'     => esc_html__( 'Settings', 'merchant' ),
						'subtitle'  => esc_html__( 'Choose who can see Merchant on your store. Note: it can take few seconds for changes to take effect.', 'merchant' ),
						'fields'    => array(

							array(
								'id'      => 'lazy_load_images',
								'type'    => 'checkbox',
								'label'   => esc_html__( 'Lazy Load Images', 'merchant' ),
								'desc'    => esc_html__( 'Load images when the visitor scrolls, to speed up the initial page load. This affects all modules that show images such as product reviews, recently viewed, related products, etc.', 'merchant' ),
								'default' => 1,
							),

						),
					) );

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

				?>

			</div>

		</form>

	</div>

</div>
