<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Divi theme compatibility layer
 */
if ( ! class_exists( 'Merchant_Divi_Theme' ) ) {
	class Merchant_Divi_Theme {

		/**
		 * Constructor.
		 */
		public function __construct() {

			if ( ( is_admin() && ! wp_doing_ajax() ) || ! merchant_is_divi_active() ) {
				return;
			}

			if ( Merchant_Modules::is_module_active( Merchant_Quick_View::MODULE_ID ) ) {
				add_filter( 'merchant_quick_view_description', array( $this, 'quick_view_description' ) );
			}

			if ( merchant_is_pro_active() ) {}

			//add_filter( 'merchant_module_settings', array( $this, 'alter_settings' ), 10, 2 );

			add_filter( 'merchant_module_shortcode_error_message_html', array( $this, 'shortcode_error_message' ), 10, 2 );

			// Custom CSS.
			add_filter( 'merchant_custom_css', array( $this, 'frontend_custom_css' ) );
		}

		/**
		 * Remove Divi shortcodes from description.
		 *
		 * @param $description
		 *
		 * @return array|string|string[]|null
		 */
		public function quick_view_description( $description ) {
			return $this->et_strip_shortcodes( $description );
		}

		/**
		 * For some reason in edit mode it can't detect is_singular( 'product' ) or is_product() tag and shows the error message.
		 *
		 * In that case change the error message.
		 *
		 * @param $content
		 * @param $module_id
		 *
		 * @return mixed|string
		 */
		public function shortcode_error_message( $content, $module_id ) {
			// Todo: check other way to find if current page is product page and return custom message. is_singular( 'product' ) or is_product() doesn't work
			if ( function_exists( 'is_et_pb_preview' ) && is_et_pb_preview() ) {
				return '<div class="merchant-shortcode-wrong-placement">' . esc_html__( 'Please view the product page to see this content.', 'merchant' ) . '</div>';
			}

			return $content;
		}

		/**
		 * Modify settings.
		 *
		 * @param $settings
		 * @param $module_id
		 *
		 * @return mixed
		 */
		public function alter_settings( $settings, $module_id ) {
			if ( $module_id === 'quick-view' ) {
				// $settings['description_style'] = 'short';
			}

			return $settings;
		}

		/**
		 * Frontend custom CSS.
		 *
		 * @param string $css The custom CSS.
		 * @return string $css The custom CSS.
		 */
		public function frontend_custom_css( $css ) {
			if ( is_cart() ) {
				$css .= '
					.woocommerce .merchant-cart-offers .cart-item-offer__container .add-to-cart .add-to-cart-button {
						font-size: 8px !important;
						line-height: 1.6 !important;
						font-weight: 700 !important;
					}
				';
			}

			// Quick View
			if ( Merchant_Modules::is_module_active( Merchant_Quick_View::MODULE_ID ) ) {
				$css .= '
				    .merchant-quick-view-button:after {
					    content: none !important;
					}
				';
			}

			if ( merchant_is_pro_active() ) {

				// Variation Swatches
				if ( Merchant_Modules::is_module_active( Merchant_Stock_Scarcity::MODULE_ID ) ) {
					$css .= '
					    .merchant-variation-text:after {
					        content: none !important;
					    }
					';
				}

				// Wishlist
				if ( Merchant_Modules::is_module_active( Merchant_Wishlist::MODULE_ID ) ) {
					$css .= '
					    .single-product .merchant-wishlist-button {
					        position: static;
					    }
					    .single-product li .merchant-wishlist-button {
					        position: absolute;
					    }
					';
				}

				// Wishlist
				if ( Merchant_Modules::is_module_active( Merchant_Size_Chart::MODULE_ID ) ) {
					$css .= '
					    table.merchant-product-size-chart-modal-table {
					        width: 100%;
					    }
					';
				}
			}

			return $css;
		}

		/**
		 * For some reason Divi's et_strip_shortcodes giving error in AJAX.
		 *
		 * So re-defining the function here
		 *
		 * @param $content
		 * @param $truncate_post_based_shortcodes_only
		 *
		 * @return array|string|string[]|null
		 */
		public function et_strip_shortcodes( $content, $truncate_post_based_shortcodes_only = false ) {
			global $shortcode_tags;

			$content = trim( $content );

			$strip_content_shortcodes = array(
				'et_pb_code',
				'et_pb_fullwidth_code',
				'et_pb_social_media_follow_network',
			);

			// list of post-based shortcodes.
			if ( $truncate_post_based_shortcodes_only ) {
				$strip_content_shortcodes = array(
					'et_pb_post_slider',
					'et_pb_fullwidth_post_slider',
					'et_pb_blog',
					'et_pb_comments',
				);
			}

			foreach ( $strip_content_shortcodes as $shortcode_name ) {
				$regex = sprintf(
					'(\[%1$s[^\]]*\][^\[]*\[\/%1$s\]|\[%1$s[^\]]*\])',
					esc_html( $shortcode_name )
				);

				$content = preg_replace( $regex, '', $content );
			}

			// do not proceed if we need to truncate post-based shortcodes only.
			if ( $truncate_post_based_shortcodes_only ) {
				return $content;
			}

			$shortcode_tag_names = array();
			foreach ( $shortcode_tags as $shortcode_tag_name => $shortcode_tag_cb ) {
				if ( 0 !== strpos( $shortcode_tag_name, 'et_pb_' ) ) {
					continue;
				}

				$shortcode_tag_names[] = $shortcode_tag_name;
			}

			$et_shortcodes = implode( '|', $shortcode_tag_names );

			$regex_opening_shortcodes = sprintf( '(\[(%1$s)[^\]]+\])', esc_html( $et_shortcodes ) );
			$regex_closing_shortcodes = sprintf( '(\[\/(%1$s)\])', esc_html( $et_shortcodes ) );

			$content = preg_replace( $regex_opening_shortcodes, '', $content );
			$content = preg_replace( $regex_closing_shortcodes, '', $content );

			return $content;
		}
	}

	add_action( 'init', function() {
		new Merchant_Divi_Theme();
	} );
}
