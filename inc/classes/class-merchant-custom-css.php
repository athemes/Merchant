<?php
/**
 * Merchant_Custom_CSS Class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Merchant_Custom_CSS' ) ) {

	class Merchant_Custom_CSS {

		/**
		 * The single class instance.
		 */
		private static $instance = null;

		/**
		 * Instance.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor.
		 */
		public function __construct() {

			add_action( 'wp_enqueue_scripts', array( $this, 'print_styles' ), 15 );
			add_action( 'admin_enqueue_scripts', array( $this, 'print_styles_admin' ), 15 );
		}

		/**
		 * Print Styles.
		 */
		public function print_styles() {

			$css = $this->output_css();

			wp_add_inline_style( 'merchant', $css );

		}

		/**
		 * Print Styles In Admin
		 */
		public function print_styles_admin() {

			$page = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
			$module = ( ! empty( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : '';

			if ( ! empty( $page ) && false !== strpos( $page, 'merchant' ) && !empty($module) ) {
				$css = $this->output_css();

				wp_add_inline_style( 'merchant-admin', $css );
			}

		}

		/**
		 * Output CSS.
		 */
		public function output_css() {

			$css = '';

			// Scroll To Top Button
			if ( Merchant_Modules::is_module_active( 'scroll-to-top-button' ) ) {

				$css .= $this->get_variable_css( 'scroll-to-top-button', 'icon-color', '#ffffff', '.merchant-scroll-to-top-button', '--merchant-icon-color' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'icon-hover-color', '#ffffff', '.merchant-scroll-to-top-button', '--merchant-icon-hover-color' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'text-color', '#ffffff', '.merchant-scroll-to-top-button', '--merchant-text-color' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'text-hover-color', '#ffffff', '.merchant-scroll-to-top-button', '--merchant-text-hover-color' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'border-color', '#212121', '.merchant-scroll-to-top-button', '--merchant-border-color' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'border-hover-color', '#757575', '.merchant-scroll-to-top-button', '--merchant-border-hover-color' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'background-color', '#212121', '.merchant-scroll-to-top-button', '--merchant-background-color' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'background-hover-color', '#757575', '.merchant-scroll-to-top-button', '--merchant-background-hover-color' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'border-radius', 30, '.merchant-scroll-to-top-button', '--merchant-border-radius', 'px' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'padding', 15, '.merchant-scroll-to-top-button', '--merchant-padding', 'px' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'text-size', 18, '.merchant-scroll-to-top-button', '--merchant-text-size', 'px' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'icon-size', 18, '.merchant-scroll-to-top-button', '--merchant-icon-size', 'px' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'border-size', 2, '.merchant-scroll-to-top-button', '--merchant-border-size', 'px' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'side-offset', 30, '.merchant-scroll-to-top-button', '--merchant-side-offset', 'px' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'bottom-offset', 30, '.merchant-scroll-to-top-button', '--merchant-bottom-offset', 'px' );

				// Mobile Styles
				$css .= '@media (max-width: 768px) {';
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'side-offset-mobile', '30', '.merchant-scroll-to-top-button', '--merchant-side-offset', 'px' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'bottom-offset-mobile', '30', '.merchant-scroll-to-top-button', '--merchant-bottom-offset', 'px' );
				$css .= '}';

			}

			// Pre Orders
			if ( Merchant_Modules::is_module_active( 'pre-orders' ) ) {

				$css .= $this->get_variable_css( 'pre-orders', 'text-color', '#ffffff', '.merchant-pre-ordered-product', '--merchant-text-color' );
				$css .= $this->get_variable_css( 'pre-orders', 'text-hover-color', '#ffffff', '.merchant-pre-ordered-product', '--merchant-text-hover-color' );
				$css .= $this->get_variable_css( 'pre-orders', 'border-color', '#212121', '.merchant-pre-ordered-product', '--merchant-border-color' );
				$css .= $this->get_variable_css( 'pre-orders', 'border-hover-color', '#414141', '.merchant-pre-ordered-product', '--merchant-border-hover-color' );
				$css .= $this->get_variable_css( 'pre-orders', 'background-color', '#212121', '.merchant-pre-ordered-product', '--merchant-background-color' );
				$css .= $this->get_variable_css( 'pre-orders', 'background-hover-color', '#414141', '.merchant-pre-ordered-product', '--merchant-background-hover-color' );
				
			}

			// Trust Badges
			if ( Merchant_Modules::is_module_active( 'trust-badges' ) ) {

				$css .= $this->get_variable_css( 'trust-badges', 'font-size', 18, '.merchant-trust-badges', '--merchant-font-size', 'px' );
				$css .= $this->get_variable_css( 'trust-badges', 'text-color', '#212121', '.merchant-trust-badges', '--merchant-text-color' );
				$css .= $this->get_variable_css( 'trust-badges', 'border-color', '#e5e5e5', '.merchant-trust-badges', '--merchant-border-color' );
				$css .= $this->get_variable_css( 'trust-badges', 'margin-top', 20, '.merchant-trust-badges', '--merchant-margin-top', 'px' );
				$css .= $this->get_variable_css( 'trust-badges', 'margin-bottom', 20, '.merchant-trust-badges', '--merchant-margin-bottom', 'px' );
				$css .= $this->get_variable_css( 'trust-badges', 'align', 'flex-start', '.merchant-trust-badges', '--merchant-align' );
				$css .= $this->get_variable_css( 'trust-badges', 'image-max-width', 100, '.merchant-trust-badges', '--merchant-image-max-width', 'px' );
				$css .= $this->get_variable_css( 'trust-badges', 'image-max-height', 100, '.merchant-trust-badges', '--merchant-image-max-height', 'px' );

			}

			// Cookie Banner
			if ( Merchant_Modules::is_module_active( 'cookie-banner' ) ) {

				$css .= $this->get_variable_css( 'cookie-banner', 'modal_width', 750, '.merchant-cookie-banner', '--merchant-modal-width', 'px' );
				$css .= $this->get_variable_css( 'cookie-banner', 'modal_height', 50, '.merchant-cookie-banner', '--merchant-modal-height', 'px' );
				$css .= $this->get_variable_css( 'cookie-banner', 'background_color', '#000000', '.merchant-cookie-banner', '--merchant-background' );
				$css .= $this->get_variable_css( 'cookie-banner', 'text_color', '#ffffff', '.merchant-cookie-banner', '--merchant-text-color' );
				$css .= $this->get_variable_css( 'cookie-banner', 'link_color', '#aeaeae', '.merchant-cookie-banner', '--merchant-link-color' );
				$css .= $this->get_variable_css( 'cookie-banner', 'button_background_color', '#dddddd', '.merchant-cookie-banner', '--merchant-button-background' );
				$css .= $this->get_variable_css( 'cookie-banner', 'button_text_color', '#222222', '.merchant-cookie-banner', '--merchant-button-text-color' );

			}

			// Real Time Search
			if ( Merchant_Modules::is_module_active( 'real-time-search' ) ) {

				$css .= $this->get_variable_css( 'real-time-search', 'results_box_width', 500, '.merchant-ajax-search-wrapper', '--merchant-results-box-width', 'px' );

			}

			// Code Snippets
			if ( Merchant_Modules::is_module_active( 'code-snippets' ) ) {
	
				$css .= Merchant_Option::get( 'code-snippets', 'custom_css', '' );
	
			}

			$css .= Merchant_Option::get( 'global-settings', 'custom_css', '' );

			/**
			 * Hook: merchant_custom_css
			 * 
			 * @since 1.0
			 */
			$css .= apply_filters( 'merchant_custom_css', '', $this );

			$css = $this->minify( $css );

			return $css;

		}

		/**
		 * Get variable CSS
		 * 
		 * @param string $module Module name.
		 * @param string $setting Setting name.
		 * @param string $default Default value.
		 * @param string $selector CSS selector.
		 * @param string $variable CSS variable.
		 * @param string $unit CSS unit.
		 * @param string $condition CSS condition.
		 * 
		 */
		public static function get_variable_css( $module, $setting = '', $default = null, $selector = '', $variable = '', $unit = '', $condition = '' ) {

			$value = self::get_option( $module, $setting, $default );

			if ( '' === $value || null === $value ) {
				return '';
			}

			if ( ! empty( $condition ) ) {

				switch ( $condition ) {

					case 'pass_empty':
						if ( empty( $value ) ) {
							return;
						}

						break;

				}
				
			}

			if( class_exists( 'Merchant_Admin_Preview' ) ) {
				Merchant_Admin_Preview::instance()->set_css( $setting, $selector, $variable, $unit );
			}

			return $selector . '{ ' . esc_attr( $variable ) . ':' . esc_attr( $value ) . esc_attr( $unit ) . '; }' . "\n";

		}

		public static function get_option( $module, $setting, $default ) {

			$options = get_option( 'merchant', array() );

			$value = $default;

			if ( isset( $options[ $module ] ) && isset( $options[ $module ][ $setting ] ) ) {
				$value = $options[ $module ][ $setting ];
			}

			return $value;

		}

		/**
		 * CSS code minification.
		 */
		private function minify( $css ) {

			$css = preg_replace( '/\s+/', ' ', $css );
			$css = preg_replace( '/\/\*[^\!](.*?)\*\//', '', $css );
			$css = preg_replace( '/(,|:|;|\{|}) /', '$1', $css );
			$css = preg_replace( '/ (,|;|\{|})/', '$1', $css );
			$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
			$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );

			return trim( $css );

		}

	}

	Merchant_Custom_CSS::instance();

}
