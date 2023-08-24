<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function merchant_scroll_to_top_button_htm() {
	$style = Merchant_Option::get( 'scroll-to-top-button', 'style', 'merchant-style-filled' );

	$type = Merchant_Option::get( 'scroll-to-top-button', 'type', 'icon' );

	$position = Merchant_Option::get( 'scroll-to-top-button', 'position', 'merchant-position-right' );

	$visibility = Merchant_Admin_Options::get( 'scroll-to-top-button', 'visibility', 'all' );

	$html = '<div class="merchant-scroll-to-top-button ' . esc_attr( $position ) . ' ' . esc_attr( $style ) . ' merchant-visibility-' . esc_attr( $visibility ) . '">';

	if ( 'text-icon' === $type ) {
		$text = Merchant_Option::get( 'scroll-to-top-button', 'text', 'Back to top' );

		$html .= '<span>' . esc_html( $text ) . '</span>';
	}

	$arrow = Merchant_Option::get( 'scroll-to-top-button', 'icon', 'arrow-1' );

	switch ( $arrow ) {
		case 'arrow-1':
			$html .= '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 15L12 8L19 15" stroke-width="1.5" stroke-linejoin="round"></path></svg>';
			break;

		case 'arrow-2':
			$html .= '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 15l7-7 7 7" stroke-width="3" stroke-linejoin="round"></path></svg>';
			break;

		case 'arrow-3':
			$html .= '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 12l5.5-5.5m0 0L18 12m-5.5-5.5V19" stroke-width="1.5" stroke-linejoin="round"></path></svg>';
			break;

		case 'arrow-4':
			$html .= '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 12l5.5-5.5m0 0L18 12m-5.5-5.5V19" stroke-width="3" stroke-linejoin="round"></path></svg>';
			break;
	}

	$html .= '</div>';

	return $html;
}

function merchant_scroll_to_top_button() {
	if ( Merchant_Modules::is_module_active( 'scroll-to-top-button' ) ) {
		echo merchant_scroll_to_top_button_htm();
	}
}

add_action( 'wp_footer', 'merchant_scroll_to_top_button' );

if ( ! function_exists( 'merchant_scroll_to_top_button_preview' ) ) {
	add_filter( 'merchant_module_preview', 'merchant_scroll_to_top_button_preview', 10, 2 );

	/**
	 * Render admin preview
	 *
	 * @param Merchant_Admin_Preview $preview
	 * @param string $module
	 *
	 * @return Merchant_Admin_Preview
	 */
	function merchant_scroll_to_top_button_preview( $preview, $module ) {
		if ( $module === 'scroll-to-top-button' ) {
			$preview->set_html( merchant_scroll_to_top_button_htm() );
			$preview->set_class( 'position', '.merchant-scroll-to-top-button', array( 'merchant-position-right', 'merchant-position-left' ) );
			$preview->set_class( 'style', '.merchant-scroll-to-top-button', array( 'merchant-style-filled', 'merchant-style-outline' ) );

			$preview->set_css( 'side-offset', '.merchant-scroll-to-top-button', '--merchant-side-offset', 'px' );
			$preview->set_css( 'bottom-offset', '.merchant-scroll-to-top-button', '--merchant-bottom-offset', 'px' );

			$preview->set_css( 'icon-size', '.merchant-scroll-to-top-button', '--merchant-icon-size', 'px' );
			$preview->set_css( 'padding', '.merchant-scroll-to-top-button', '--merchant-padding' , 'px');
			$preview->set_css( 'border-radius', '.merchant-scroll-to-top-button', '--merchant-border-radius', 'px' );

			$preview->set_css( 'icon-color', '.merchant-scroll-to-top-button svg', '--merchant-icon-color' );
			$preview->set_css( 'icon-hover-color', '.merchant-scroll-to-top-button svg', '--merchant-icon-hover-color' );
			$preview->set_css( 'background-color', '.merchant-scroll-to-top-button', '--merchant-background-color' );
			$preview->set_css( 'background-hover-color', '.merchant-scroll-to-top-button', '--merchant-background-hover-color' );

		}

		return $preview;
	}
}

if ( ! function_exists( 'merchant_scroll_to_top_button_admin_scripts' ) ) {
	add_action( 'admin_enqueue_scripts', 'merchant_scroll_to_top_button_admin_scripts' );

	function merchant_scroll_to_top_button_admin_scripts() {
		$page   = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
		$module = ( ! empty( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : '';

		if ( $page === 'merchant' && $module === 'scroll-to-top-button' ) {
			wp_enqueue_style( 'merchant', MERCHANT_URI . 'assets/css/merchant.min.css', array(), MERCHANT_VERSION );
		}
	}
}

add_filter( 'merchant_custom_css', function ( $css, $instance ) {
	$page   = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
	$module = ( ! empty( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : '';

	if ( is_admin() && $page === 'merchant' && $module === 'scroll-to-top-button' ) {
		$css .= '.merchant-module-page-preview-browser-inner .merchant-scroll-to-top-button { position: absolute; opacity: 1; visibility: visible; }';
	}

	return $css;
}, 10, 2 );