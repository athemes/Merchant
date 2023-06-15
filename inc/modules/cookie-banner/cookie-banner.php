<?php

function merchant_cookie_banner() {

	if ( Merchant_Modules::is_module_active( 'cookie-banner' ) ) {

		$close_button       = Merchant_Admin_Options::get( 'cookie-banner', 'close_button', 'hide' );
		$banner_theme       = Merchant_Admin_Options::get( 'cookie-banner', 'banner_theme', 'floating' );
		$banner_text        = Merchant_Admin_Options::get( 'cookie-banner', 'banner_text', esc_html__( 'We use cookies to improve your experience and track website usage.', 'merchant' ) );
		$privacy_policy_url = Merchant_Admin_Options::get( 'cookie-banner', 'privacy_policy_url', '' );
		$learn_more_text    = Merchant_Admin_Options::get( 'cookie-banner', 'learn_more_text', esc_html__( 'Learn More', 'merchant' ) );
		$accept_text        = Merchant_Admin_Options::get( 'cookie-banner', 'accept_text', esc_html__( 'I Understand', 'merchant' ) );

		?>

			<div class="merchant-cookie-banner merchant-cookie-banner-<?php echo sanitize_html_class( $banner_theme ); ?>">
				<?php if ( $close_button === 'show' ) : ?>
					<div class="merchant-cookie-close-button">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
							<path d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z"></path>
						</svg>
					</div>
				<?php endif; ?>
				<div class="merchant-cookie-banner-content">
					<div class="merchant-cookie-banner-text">
						<?php echo esc_html( $banner_text ); ?>
						<?php if ( ! empty( $privacy_policy_url ) ) : ?>
							<a href="<?php echo esc_url( $privacy_policy_url ); ?>"><?php echo esc_html( $learn_more_text ); ?></a>
						<?php endif; ?>
					</div>
					<div class="merchant-cookie-banner-button"><?php echo esc_html( $accept_text ); ?></div>
				</div>
			</div>
			
		<?php 

	}

}

add_action( 'wp_footer', 'merchant_cookie_banner' );
