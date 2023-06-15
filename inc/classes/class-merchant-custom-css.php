<?php
/**
 * Merchant_Custom_CSS Class.
 */
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

		}

		/**
		 * Print Styles.
		 */
		public function print_styles() {

			$css = $this->output_css();

			wp_add_inline_style( 'merchant', $css );

		}

		/**
		 * Output CSS.
		 */
		public function output_css() {

			$css    = '';
			$css768 = '';

			// Scroll To Top Button Styles
			if ( Merchant_Modules::is_module_active( 'scroll-to-top-button' ) ) {

				$css .= $this->get_variable_css( 'scroll-to-top-button', 'icon-color', '#ffffff', '.merchant-scroll-to-top-button', '--merchant-icon-color' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'icon-hover-color', '#ffffff', '.merchant-scroll-to-top-button', '--merchant-icon-hover-color' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'background-color', '#212121', '.merchant-scroll-to-top-button', '--merchant-background-color' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'background-hover-color', '#757575', '.merchant-scroll-to-top-button', '--merchant-background-hover-color' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'border-radius', 30, '.merchant-scroll-to-top-button', '--merchant-border-radius', 'px' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'padding', 15, '.merchant-scroll-to-top-button', '--merchant-padding', 'px' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'icon-size', 18, '.merchant-scroll-to-top-button', '--merchant-icon-size', 'px' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'side-offset', 30, '.merchant-scroll-to-top-button', '--merchant-side-offset', 'px' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'bottom-offset', 30, '.merchant-scroll-to-top-button', '--merchant-bottom-offset', 'px' );

				// Mobile Styles
				$css .= '@media (max-width: 768px) {';
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'side-offset-mobile', '30', '.merchant-scroll-to-top-button', '--merchant-side-offset', 'px' );
				$css .= $this->get_variable_css( 'scroll-to-top-button', 'bottom-offset-mobile', '30', '.merchant-scroll-to-top-button', '--merchant-bottom-offset', 'px' );
				$css .= '}';

			}

			// Animated Add to Cart
			if ( Merchant_Modules::is_module_active( 'animated-add-to-cart' ) ) {

				$animation = $this->get_option( 'animated-add-to-cart', 'animation', 'bounce' );

				$css .= '.add_to_cart_button:not(.merchant_buy_now_button),';
				$css .= '.product_type_grouped:not(.merchant_buy_now_button){';
				$css .= 'transition: all .3s ease-in;';
				$css .= '}';

				$trigger = Merchant_Admin_Options::get( 'animated-add-to-cart', 'trigger', 'every-seconds' );

				if ( in_array( $trigger, array( 'on-hover', 'on-hover-seconds' ) ) ) {
					$css .= '.add_to_cart_button:not(.merchant_buy_now_button):hover,';
					$css .= '.product_type_grouped:not(.merchant_buy_now_button):hover,';
					$css .= '.single_add_to_cart_button:not(.merchant_buy_now_button):hover,';
				}


				$css .= '.add_to_cart_button:not(.merchant_buy_now_button).merchant-active,';
				$css .= '.product_type_grouped:not(.merchant_buy_now_button).merchant-active,';
				$css .= '.single_add_to_cart_button:not(.merchant_buy_now_button).merchant-active{';

				switch ( $animation ) {

					case 'bounce':
						$css .= 'animation: merchant-bounce .3s alternate;';
						$css .= 'animation-iteration-count: 4;';
					break;

					case 'zoom-in':
						$css .= 'transform: scale(1.2);';
					break;

					case 'shake':
						$css .= 'animation: merchant-shake .3s;';
						$css .= 'animation-iteration-count: 2;';
					break;

					case 'pulse':
						$css .= 'animation: merchant-pulse 1.5s ease-in-out infinite both;';
					break;

					case 'jello-shake':
						$css .= 'animation: merchant-jello-shake 1.5s infinite both;';
					break;

					case 'wobble':
						$css .= 'animation: merchant-wobble 1.5s ease-in-out infinite both;';
					break;

					case 'vibrate':
						$css .= 'animation: merchant-vibrate .3s linear 4 both;';
					break;

					case 'swing':
						$css .= 'animation: merchant-swing 2s ease-in-out infinite alternate;';
					break;

					case 'tada':
						$css .= 'animation: merchant-tada 1s infinite both;';
					break;

				}

				$css .= '}';

			}

			// Payment Logos
			if ( Merchant_Modules::is_module_active( 'payment-logos' ) ) {

				$css .= $this->get_variable_css( 'payment-logos', 'margin-top', '20', '.merchant-payment-logos', '--merchant-margin-top', 'px' );
				$css .= $this->get_variable_css( 'payment-logos', 'margin-bottom', '20', '.merchant-payment-logos', '--merchant-margin-bottom', 'px' );
				$css .= $this->get_variable_css( 'payment-logos', 'align', 'flex-start', '.merchant-payment-logos', '--merchant-align' );
				$css .= $this->get_variable_css( 'payment-logos', 'image-max-width', 100, '.merchant-payment-logos', '--merchant-image-max-width', 'px' );
				$css .= $this->get_variable_css( 'payment-logos', 'image-max-height', 100, '.merchant-payment-logos', '--merchant-image-max-height', 'px' );

			}

			// Product Trust Badge
			if ( Merchant_Modules::is_module_active( 'product-trust-badge' ) ) {

				$css .= $this->get_variable_css( 'product-trust-badge', 'margin-top', '20', '.merchant-product-trust-badge', '--merchant-margin-top', 'px' );
				$css .= $this->get_variable_css( 'product-trust-badge', 'margin-bottom', '20', '.merchant-product-trust-badge', '--merchant-margin-bottom', 'px' );
				$css .= $this->get_variable_css( 'product-trust-badge', 'image-width', '300', '.merchant-product-trust-badge', '--merchant-image-width', 'px' );
				$css .= $this->get_variable_css( 'product-trust-badge', 'align', 'flex-start', '.merchant-product-trust-badge', '--merchant-align' );
				$css .= $this->get_variable_css( 'product-trust-badge', 'border-color', '#e5e5e5', '.merchant-product-trust-badge', '--border-color' );

			}

			// Sale Tags
			if ( Merchant_Modules::is_module_active( 'sale-tags' ) ) {

				$css .= $this->get_variable_css( 'sale-tags', 'text_color', '#ffffff', '.merchant-onsale', '--merchant-text-color' );
				$css .= $this->get_variable_css( 'sale-tags', 'background_color', '#212121', '.merchant-onsale', '--merchant-background-color' );
				$css .= $this->get_variable_css( 'sale-tags', 'top-offset', 10, '.merchant-onsale', '--merchant-top-offset', 'px' );
				$css .= $this->get_variable_css( 'sale-tags', 'side-offset', 10, '.merchant-onsale', '--merchant-side-offset', 'px' );
				$css .= $this->get_variable_css( 'sale-tags', 'border-radius', 0, '.merchant-onsale', '--merchant-border-radius', 'px' );
				$css .= $this->get_variable_css( 'sale-tags', 'font-size', 14, '.merchant-onsale', '--merchant-font-size', 'px' );

				$css .= $this->get_variable_css( 'sale-tags', 'tb-spacing', 5, '.merchant-onsale', '--merchant-tb-spacing', 'px' );
				$css .= $this->get_variable_css( 'sale-tags', 'lr-spacing', 20, '.merchant-onsale', '--merchant-lr-spacing', 'px' );

				// Mobile
				$css768 .= $this->get_variable_css( 'sale-tags', 'font-size-768', 16, '.merchant-onsale', '--merchant-font-size', 'px' );

				$css .= '.woocommerce .ast-onsale-card,';
				$css .= '.woocommerce .onsale{ display: none !important; }';

			}

			if ( ! empty( $css768 ) ) {
				$css .= '@media (max-width: 768px) {';
				$css .= $css768;
				$css .= '}';
			}

			// Quick View
			if ( Merchant_Modules::is_module_active( 'quick-view' ) ) {

				$css .= $this->get_variable_css( 'quick-view', 'modal_width', 1000, '.merchant-quick-view-modal', '--merchant-quick-view-modal-width', 'px' );
				$css .= $this->get_variable_css( 'quick-view', 'modal_height', 500, '.merchant-quick-view-modal', '--merchant-quick-view-modal-height', 'px' );
				$css .= $this->get_variable_css( 'quick-view', 'modal_overlay_color', 'rgba(0, 0, 0, 0.9)', '.merchant-quick-view-modal', '--merchant-quick-view-modal-overlay-color' );

			}

			// Cookie Banner
			if ( Merchant_Modules::is_module_active( 'cookie-banner' ) ) {

				$css .= $this->get_variable_css( 'cookie-banner', 'background_color', '#000000', '.merchant-cookie-banner', '--merchant-cookie-banner-background' );
				$css .= $this->get_variable_css( 'cookie-banner', 'text_color', '#ffffff', '.merchant-cookie-banner', '--merchant-cookie-banner-text-color' );
				$css .= $this->get_variable_css( 'cookie-banner', 'link_color', '#aeaeae', '.merchant-cookie-banner', '--merchant-cookie-banner-link-color' );
				$css .= $this->get_variable_css( 'cookie-banner', 'button_background_color', '#dddddd', '.merchant-cookie-banner', '--merchant-cookie-banner-button-background' );
				$css .= $this->get_variable_css( 'cookie-banner', 'button_text_color', '#222222', '.merchant-cookie-banner', '--merchant-cookie-banner-button-text-color' );

			}

			// Ajax Real Time Search
			if ( Merchant_Modules::is_module_active( 'ajax-real-time-search' ) ) {

				$css .= $this->get_variable_css( 'ajax-real-time-search', 'results_box_width', 500, '.merchant-ajax-search-wrapper', '--merchant-results-box-width', 'px' );

			}

			// Code Snippets
			if ( Merchant_Modules::is_module_active( 'code-snippets' ) ) {
	
				$css .= Merchant_Option::get( 'code-snippets', 'custom_css', '' );
	
			}

			$css .= Merchant_Option::get( 'global-settings', 'custom_css', '' );

			$css = $this->minify( $css );

			return $css;

		}

		/**
		 * Get variable CSS
		 */
		public function get_variable_css( $module, $setting = '', $default = null, $selector = '', $variable = '', $unit = '', $condition = '' ) {

			$value = $this->get_option( $module, $setting, $default );

			if ( $setting === 'font-size-768' ) {
				$value = 10;
			}

			if ( $value === '' || $value === NULL || $value == $default ) {
				return '';
			}

			if ( ! empty( $condition ) ) {

				switch ( $condition ) {

					case 'pass_empty';

						if ( empty( $value ) ) {
							return;
						}

					break;

				}
				
			}

			return $selector . '{ '. esc_attr( $variable ) .':' . esc_attr( $value ) . esc_attr( $unit ) .'; }' . "\n";

		}

		public function get_option( $module, $setting, $default ) {

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
