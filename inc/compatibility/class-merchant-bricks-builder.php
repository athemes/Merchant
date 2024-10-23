<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Bricks Builder compatibility layer
 */
if ( ! class_exists( 'Merchant_Bricks_Builder' ) ) {
	class Merchant_Bricks_Builder {

		/**
		 * Constructor.
		 */
		public function __construct() {

			if ( ( is_admin() && ! wp_doing_ajax() ) || ! merchant_is_bricks_builder_active() ) {
				return;
			}

			add_filter( 'merchant_enqueue_module_scripts', array( $this, 'should_enqueue_scripts' ), 10, 2 );

			// Custom CSS.
			add_filter( 'merchant_custom_css', array( $this, 'frontend_custom_css' ) );
		}

		/**
		 * Determines whether to enqueue scripts for modules.
		 *
		 * @param $enqueue
		 * @param $module
		 *
		 * @return mixed|true
		 */
		public function should_enqueue_scripts( $enqueue, $module ) {
			$module_id = $module->module_id ?? '';

			if ( $module_id === 'product-swatches' ) {
				return true;
			}

			return $enqueue;
		}

		/**
		 * Frontend custom CSS.
		 *
		 * @param string $css The custom CSS.
		 * @return string $css The custom CSS.
		 */
		public function frontend_custom_css( $css ) {
			return $css;
		}
	}

	add_action( 'init', function() {
		new Merchant_Bricks_Builder();
	} );
}
