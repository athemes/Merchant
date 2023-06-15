<?php

// Lazy load Images
function merchant_loaded() {

	$lazy_load_images = Merchant_Option::get( 'global-settings', 'lazy_load_images', 1 );

	if ( ! empty( $lazy_load_images ) ) {

		add_filter( 'wp_img_tag_add_loading_attr', '__return_true' );
		add_filter( 'wp_lazy_loading_enabled', '__return_true' );

	} else {

		add_filter( 'wp_img_tag_add_loading_attr', '__return_false' );
		add_filter( 'wp_lazy_loading_enabled', '__return_false' );

	}

}
add_action( 'init', 'merchant_loaded' );

function merchant_head_custom_js() {

	// Custom JS
	$custom_js = Merchant_Option::get( 'global-settings', 'custom_js', '' );

	if ( ! empty( $custom_js ) ) {
		if ( current_theme_supports( 'html5', 'script' ) ) {
			echo sprintf( '<script>%s</script>', $custom_js );
		} else {
			echo sprintf( '<script type="text/javascript">%s</script>', $custom_js );
		}
	}

	// Custom JS First - runs at the beginning of Merchant
	$custom_js_first = Merchant_Option::get( 'global-settings', 'custom_js_first', '' );

	if ( ! empty( $custom_js_first ) ) {
		if ( current_theme_supports( 'html5', 'script' ) ) {
			echo sprintf( '<script>%s</script>', $custom_js_first );
		} else {
			echo sprintf( '<script type="text/javascript">%s</script>', $custom_js_first );
		}
	}

}

add_action( 'wp_head', 'merchant_head_custom_js' );

function merchant_footer_custom_js() {

	// Custom JS Last - runs at the end of Merchant
	$custom_js_last = Merchant_Option::get( 'global-settings', 'custom_js_last', '' );

	if ( ! empty( $custom_js_last ) ) {
		if ( current_theme_supports( 'html5', 'script' ) ) {
			echo sprintf( '<script>%s</script>', $custom_js_last );
		} else {
			echo sprintf( '<script type="text/javascript">%s</script>', $custom_js_last );
		}
	}

}

add_action( 'wp_footer', 'merchant_footer_custom_js', 99 );
