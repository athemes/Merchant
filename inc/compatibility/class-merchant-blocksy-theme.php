<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Blocksy theme compatibility layer
 */
if ( ! class_exists( 'Merchant_Blocksy_Theme' ) ) {
	class Merchant_Blocksy_Theme {

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'product_video_module' ) );
		}

		/**
		 * Handle with all required compatibility with 'Product Video' module.
		 * 
		 * @return void
		 */
		public function product_video_module() {
			if ( ! merchant_is_blocksy_active() ) {
				return;
			}

			if ( ! Merchant_Modules::is_module_active( 'product-video' ) ) {
				return;
			}

			// Removes the theme's default product image on archive pages.
			add_filter( 'theme_mod_woo_card_layout', function($mod_settings){
				global $post;

				if ( ! isset( $post ) ) {
					return $mod_settings;
				}

				$has_featured_video = get_post_meta( $post->ID, '_merchant_enable_featured_video', true );
				if ( ! $has_featured_video ) {
					return $mod_settings;
				}

				foreach( $mod_settings as $index => $setting ) {
					if ( isset( $setting['id'] ) && $setting['id'] === 'product_image' ) {
						$mod_settings[$index]['enabled'] = false;
					}
				}

				return $mod_settings;
			} );

			// Replaces the theme's default image gallery with the default from WooCommerce. 
			add_filter( 'blocksy:woocommerce:product-view:use-default', '__return_true' );
		}
	}

	/**
	 * The class object can be accessed with "global $blocksy_compatibility", to allow removing actions.
	 * Improving Third-party integrations.
	 */
	$merchant_blocksy_compatibility = new Merchant_Blocksy_Theme();
}
