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
			$page   = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$module = ( ! empty( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			if ( ! empty( $page ) && false !== strpos( $page, 'merchant' ) && ! empty( $module ) ) {
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

			// Global Settings.
			if ( ! is_admin() ) {
				$css .= Merchant_Option::get( 'global-settings', 'custom_css', '' );
			}

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
		 * @param string $module      Module name.
		 * @param string $setting     Setting name.
		 * @param string $default_val Default value.
		 * @param string $selector    CSS selector.
		 * @param string $variable    CSS variable.
		 * @param string $unit        CSS unit.
		 * @param string $condition   CSS condition.
		 *
		 */
		public static function get_variable_css( $module, $setting = '', $default_val = null, $selector = '', $variable = '', $unit = '', $condition = '' ) {
			$value = self::get_option( $module, $setting, $default_val );

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

			// Remove everything after ":"in case a selector like a:hover is used.
			// phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found
			if ( ( $position = strpos( $selector, ':' ) ) !== false ) {
				$selector = substr( $selector, 0, $position );
			}

			if ( class_exists( 'Merchant_Admin_Preview' ) ) {
				Merchant_Admin_Preview::instance()->set_css( $setting, $selector, $variable, $unit );
			}

			return $selector . '{ ' . esc_attr( $variable ) . ':' . esc_attr( $value ) . esc_attr( $unit ) . '; }' . "\n";
		}

		/**
		 * CSS (can pass css prop and unit)
		 *
		 * @param string      $module      Module name.
		 * @param string      $setting     Setting name.
		 * @param string      $default_val Default value.
		 * @param string      $selector    CSS selector.
		 * @param string      $css_prop    CSS prop.
		 * @param string      $unit        Unit value.
		 * @param bool        $important   Important rule.
		 * @param bool|string $variable    Whether to auto-create a variable
		 *
		 * @return string
		 */
		public static function get_css( $module, $setting = '', $default_val = '', $selector = '', $css_prop = '', $unit = 'px', $important = false, $variable = false ) {
			$css_output = '';

			if ( $variable === false ) {
				$css_value = self::get_option( $module, $setting, $default_val ) . ( isset( $css['unit'] ) ? $css['unit'] : '' );
			} else {
				$css_variable = is_string( $variable ) ? $variable : '--merchant-' . str_replace( '_', '-', sanitize_title( $setting ) );
				$css_value = "var({$css_variable})";
				$css_output  .= static::get_variable_css( $module, $setting, $default_val, $selector, $css_variable, $unit );
			}

			if( is_array( $css_prop ) ) {
				foreach( $css_prop as $css ) {
					$css_output .= $selector . '{ '. $css['prop'] .':' . esc_attr( $css_value  ) . ( $important ? '!important' : '' ) . ';}' . "\n";
				}
			} else {
				$css_output .= $selector . '{ '. $css_prop .':' . esc_attr( $css_value  ) . ( $important ? '!important' : '' ) . ';}' . "\n";
			}

			return $css_output;
		}

		/**
		 * Get color CSS
		 *
		 * @param string      $module      Module name.
		 * @param string      $setting     Setting name.
		 * @param string      $default_val Default value.
		 * @param string      $selector    CSS selector.
		 * @param bool        $important   Important rule.
		 * @param bool|string $variable    Whether to auto-create a variable
		 *
		 * @return string
		 */
		public static function get_color_css( $module, $setting = '', $default_val = '', $selector = '', $important = false, $variable = false ) {
			return self::get_css( $module, $setting, $default_val, $selector, 'color', '', $important, $variable );
		}

		/**
		 * Get border color CSS
		 *
		 * @param string      $module      Module name.
		 * @param string      $setting     Setting name.
		 * @param string      $default_val Default value.
		 * @param string      $selector    CSS selector.
		 * @param bool        $important   Important rule.
		 * @param bool|string $variable    Whether to auto-create a variable
		 *
		 * @return string
		 */
		public static function get_border_color_css( $module, $setting = '', $default_val = '', $selector = '', $important = false, $variable = false ) {
			return self::get_css( $module, $setting, $default_val, $selector, 'border-color', '', $important, $variable );
		}

		/**
		 * Get background color CSS
		 *
		 * @param string      $module      Module name.
		 * @param string      $setting     Setting name.
		 * @param string      $default_val Default value.
		 * @param string      $selector    CSS selector.
		 * @param bool        $important   Important rule.
		 * @param bool|string $variable    Whether to auto-create a variable
		 *
		 * @return string
		 */
		public static function get_background_color_css( $module, $setting = '', $default_val = '', $selector = '', $important = false, $variable = false ) {
			return self::get_css( $module, $setting, $default_val, $selector, 'background-color', '', $important, $variable );
		}

		/**
		 * Responsive dimensions
		 *
		 * @param string $module Module name.
		 * @param string $setting Setting name.
		 * @param array $defaults Default values.
		 * @param string $selector CSS selector.
		 * @param string $css_prop CSS prop.
		 * @param bool $important Important rule.
		 *
		 * @return string
		 */
		public static function get_responsive_dimensions_css( $module, $setting = '', $defaults = array(), $selector = '', $css_prop = '', $important = false ) {
			$devices = array(
				'desktop' => '@media (min-width: 992px)',
				'tablet'  => '@media (min-width: 576px) and (max-width:  991px)',
				'mobile'  => '@media (max-width: 575px)',
			);

			$css = '';

			foreach ( $devices as $device => $media ) {
				$value = self::get_option( $module, $setting, $defaults )[ $device ];

				$value['top']    = ! isset( $value['top'] ) || $value['top'] === '' ? 0 : $value['top'];
				$value['right']  = ! isset( $value['right'] ) || $value['right'] === '' ? 0 : $value['right'];
				$value['bottom'] = ! isset( $value['bottom'] ) || $value['bottom'] === '' ? 0 : $value['bottom'];
				$value['left']   = ! isset( $value['left'] ) || $value['left'] === '' ? 0 : $value['left'];

				$css_prop_value = "{$value['top']}{$value['unit']} {$value['right']}{$value['unit']} {$value['bottom']}{$value['unit']} {$value['left']}{$value['unit']}";
				$css            .= $media . ' { ' . $selector . ' { ' . $css_prop . ':' . esc_attr( $css_prop_value ) . ( $important ? '!important' : '' ) . '; } }' . "\n";
			}

			return $css;
		}

		/**
		 * Dimensions
		 *
		 * @param string $module      Module name.
		 * @param string $setting     Setting name.
		 * @param string $default_val Default value.
		 * @param string $selector    CSS selector.
		 * @param string $css_prop    CSS prop.
		 * @param bool   $important   Important rule.
		 *
		 * @return string
		 */
		public static function get_dimensions_css( $module, $setting = '', $default_val = '', $selector = '', $css_prop = '', $important = false ) {
			$value = self::get_option( $module, $setting, $default_val );

			$value['top']    = ! isset( $value['top'] ) || $value['top'] === '' ? 0 : $value['top'];
			$value['right']  = ! isset( $value['right'] ) || $value['right'] === '' ? 0 : $value['right'];
			$value['bottom'] = ! isset( $value['bottom'] ) || $value['bottom'] === '' ? 0 : $value['bottom'];
			$value['left']   = ! isset( $value['left'] ) || $value['left'] === '' ? 0 : $value['left'];

			$css_prop_value = "{$value['top']}{$value['unit']} {$value['right']}{$value['unit']} {$value['bottom']}{$value['unit']} {$value['left']}{$value['unit']}";

			if ( is_array( $css_prop ) ) {
				$css_output = '';

				foreach ( $css_prop as $css ) {
					$css_output .= $selector . '{ ' . $css['prop'] . ':' . esc_attr( $css_prop_value ) . ( $important ? '!important' : '' ) . ';}' . "\n";
				}

				return $css_output;
			} else {
				return $selector . '{ ' . $css_prop . ':' . esc_attr( $css_prop_value ) . ( $important ? '!important' : '' ) . ';}' . "\n";
			}
		}

		/**
		 * Responsive CSS (can pass css prop and unit)
		 *
		 * @param string $module Module name.
		 * @param string $setting Setting name.
		 * @param array $defaults Default values.
		 * @param string $selector CSS selector.
		 * @param string $css_prop CSS prop.
		 * @param string $unit Unit value.
		 * @param bool $important Important rule.
		 *
		 * @return string
		 */
		public static function get_responsive_css( $module, $setting = '', $defaults = array(), $selector = '', $css_prop = '', $unit = 'px', $important = false ) {
			$devices = array(
				'desktop' => '@media (min-width: 992px)',
				'tablet'  => '@media (min-width: 576px) and (max-width:  991px)',
				'mobile'  => '@media (max-width: 575px)',
			);

			$css = '';

			foreach ( $devices as $device => $media ) {
				$default = ( isset( $defaults[ $device ] ) ) ? $defaults[ $device ] : $defaults;

				$setting = self::get_option( $module, $setting . '_' . $device, $default );

				// Some properties need to be converted to be compatible with the respective css property
				$type = '';
				if ( strpos( $setting, '_visibility' ) !== false && $css_prop === 'display' ) {
					$type = 'display';
				}

				// Check and convert value to be compatible with 'display' css property
				if ( $css_prop === 'display' ) {
					if ( $setting === 'hidden' ) {
						$setting = 'none';
					} else {
						continue;
					}
				}

				$css .= $media . ' { ' . $selector . ' { ' . $css_prop . ':' . esc_attr( $setting ) . ( $unit ? $unit : '' ) . ( $important ? '!important' : '' ) . '; } }' . "\n";
			}

			return $css;
		}

		/**
		 * Get option
		 *
		 * @param string $module      Module name.
		 * @param string $setting     Setting name.
		 * @param mixed  $default_val Default value.
		 *
		 * @return mixed
		 */
		public static function get_option( $module, $setting, $default_val ) {
			$options = get_option( 'merchant', array() );

			$value = $default_val;

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
