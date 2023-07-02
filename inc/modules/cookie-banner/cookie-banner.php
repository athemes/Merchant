<?php

function merchant_cookie_banner() {

	if ( Merchant_Modules::is_module_active( 'cookie-banner' ) ) {

		$theme               = Merchant_Admin_Options::get( 'cookie-banner', 'theme', 'floating' );
		$bar_text            = Merchant_Admin_Options::get( 'cookie-banner', 'bar_text', esc_html__( '🍪 We\'re using cookies to give you the best experience on our site.', 'merchant' ) );
		$privacy_policy_url  = Merchant_Admin_Options::get( 'cookie-banner', 'privacy_policy_url', get_privacy_policy_url() );
		$privacy_policy_text = Merchant_Admin_Options::get( 'cookie-banner', 'privacy_policy_text', esc_html__( 'Learn More', 'merchant' ) );
		$button_text         = Merchant_Admin_Options::get( 'cookie-banner', 'button_text', esc_html__( 'I Understand', 'merchant' ) );
		$close_button        = Merchant_Admin_Options::get( 'cookie-banner', 'close_button', 0 );
		?>

			<div class="merchant-cookie-banner merchant-cookie-banner-<?php echo sanitize_html_class( $theme ); ?>">
				<div class="merchant-cookie-banner-inner">
					<?php if ( ! empty( $close_button ) ) : ?>
						<div class="merchant-cookie-close-button">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
								<path d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z"></path>
							</svg>
						</div>
					<?php endif; ?>
					<div class="merchant-cookie-banner-content">
						<div class="merchant-cookie-banner-text">
							<?php echo esc_html( $bar_text ); ?>
							<?php if ( ! empty( $privacy_policy_url ) ) : ?>
								<a href="<?php echo esc_url( $privacy_policy_url ); ?>"><?php echo esc_html( $privacy_policy_text ); ?></a>
							<?php endif; ?>
						</div>
						<div class="merchant-cookie-banner-button"><?php echo esc_html( $button_text ); ?></div>
					</div>
				</div>
			</div>
			
		<?php 

	}

}

add_action( 'wp_footer', 'merchant_cookie_banner' );
