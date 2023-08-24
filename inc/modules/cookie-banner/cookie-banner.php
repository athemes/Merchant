<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


function merchant_cookie_banner_html() {
	ob_start();
	$theme               = Merchant_Admin_Options::get( 'cookie-banner', 'theme', 'merchant-cookie-banner-floating' );
	$bar_text            = Merchant_Admin_Options::get( 'cookie-banner', 'bar_text', esc_html__( '🍪 We\'re using cookies to give you the best experience on our site.', 'merchant' ) );
	$privacy_policy_url  = Merchant_Admin_Options::get( 'cookie-banner', 'privacy_policy_url', get_privacy_policy_url() );
	$privacy_policy_text = Merchant_Admin_Options::get( 'cookie-banner', 'privacy_policy_text', esc_html__( 'Learn More', 'merchant' ) );
	$button_text         = Merchant_Admin_Options::get( 'cookie-banner', 'button_text', esc_html__( 'I Understand', 'merchant' ) );
	$close_button        = Merchant_Admin_Options::get( 'cookie-banner', 'close_button', 0 );
	?>

    <div class="merchant-cookie-banner <?php echo sanitize_html_class( $theme ); ?>">
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
	return ob_get_clean();
}

function merchant_cookie_banner() {
	if ( Merchant_Modules::is_module_active( 'cookie-banner' ) ) {
		echo merchant_cookie_banner_html();
	}
}

add_action( 'wp_footer', 'merchant_cookie_banner' );

if ( ! function_exists( 'merchant_cookie_banner_preview' ) ) {
	add_filter( 'merchant_module_preview', 'merchant_cookie_banner_preview', 10, 2 );

	/**
	 * Render admin preview
	 *
	 * @param Merchant_Admin_Preview $preview
	 * @param string $module
	 *
	 * @return Merchant_Admin_Preview
	 */
	function merchant_cookie_banner_preview( $preview, $module ) {
		if ( $module === 'cookie-banner' ) {
			$preview->set_html( merchant_cookie_banner_html() );
			$preview->set_class( 'theme', '.merchant-cookie-banner', array( 'merchant-cookie-banner-floating', 'merchant-cookie-banner-fixed-bottom' ) );
			$preview->set_text( 'bar_text', '.merchant-cookie-banner-text' );
			$preview->set_text( 'button_text', '.merchant-cookie-banner-button' );
			$preview->set_css( 'background_color', '.merchant-cookie-banner-inner', '--merchant-background' );
			$preview->set_css( 'text_color', '.merchant-cookie-banner-inner', '--merchant-text-color' );
			$preview->set_css( 'button_background_color', '.merchant-cookie-banner-button', '--merchant-button-background' );
			$preview->set_css( 'button_text_color', '.merchant-cookie-banner-button', '--merchant-button-text-color' );
			$preview->set_css( 'modal_height', '.merchant-cookie-banner-inner', '--merchant-modal-height', 'px' );
		}

		return $preview;
	}
}

if ( ! function_exists( 'merchant_cookie_banner_admin_scripts' ) ) {
	add_action( 'admin_enqueue_scripts', 'merchant_cookie_banner_admin_scripts' );

	function merchant_cookie_banner_admin_scripts() {
		$page   = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
		$module = ( ! empty( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : '';

		if ( $page === 'merchant' && $module === 'cookie-banner' ) {
			wp_enqueue_style( 'merchant', MERCHANT_URI . 'assets/css/merchant.min.css', array(), MERCHANT_VERSION );
		}
	}
}

add_filter( 'merchant_custom_css', function ( $css, $instance ) {
	$page   = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
	$module = ( ! empty( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : '';

	if ( is_admin() && $page === 'merchant' && $module === 'cookie-banner' ) {
		$css .= '.merchant-module-page-preview-browser-inner .merchant-cookie-banner { position: absolute; display: block; }';
	}

	return $css;
}, 10, 2 );