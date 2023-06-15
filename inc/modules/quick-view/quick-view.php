<?php

function merchant_quick_view_button() {

	global $product;

	$visibility      = Merchant_Admin_Options::get( 'quick-view', 'visibility', 'all' );
	$button_type     = Merchant_Admin_Options::get( 'quick-view', 'button_type', 'button' );
	$button_position = Merchant_Admin_Options::get( 'quick-view', 'button_position', 'after' );
	$button_title    = Merchant_Admin_Options::get( 'quick-view', 'quick_view_button_title', esc_html__( 'Quick View', 'merchant' ) );
	$product_id      = $product->get_id();

	?>
		<a href="#" class="button wp-element-button merchant-quick-view-open-<?php echo sanitize_html_class( $button_type ); ?> merchant-quick-view-open merchant-visibility-<?php echo sanitize_html_class( $visibility ); ?> merchant-quick-view-position-<?php echo sanitize_html_class( $button_position ); ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>"><?php echo esc_html( $button_title ); ?></a>
	<?php

}

function merchant_quick_view_loaded() {

	if ( ! Merchant_Modules::is_module_active( 'quick-view' ) ) {
		return;
	}

	$button_position = Merchant_Admin_Options::get( 'quick-view', 'button_position', 'after' );

	if ( $button_position === 'before' ) {

		add_action( 'woocommerce_after_shop_loop_item', 'merchant_quick_view_button', 5 );

	} else if ( $button_position === 'after' )  {

		add_action( 'woocommerce_after_shop_loop_item', 'merchant_quick_view_button', 15 );

	} else if ( $button_position === 'overlay' )  {

		add_action( 'woocommerce_after_shop_loop_item', 'merchant_quick_view_button', 15 );

	}

}
add_action( 'wp', 'merchant_quick_view_loaded' );

function merchant_quick_view_modal() {

	global $product;

	if ( Merchant_Modules::is_module_active( 'quick-view' ) ) {
		?>
			<div class="single-product merchant-quick-view-modal">
				<div class="merchant-quick-view-overlay"></div>
				<div class="merchant-quick-view-loader">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
						<path opacity="0.4" d="M478.71 364.58zm-22 6.11l-27.83-15.9a15.92 15.92 0 0 1-6.94-19.2A184 184 0 1 1 256 72c5.89 0 11.71.29 17.46.83-.74-.07-1.48-.15-2.23-.21-8.49-.69-15.23-7.31-15.23-15.83v-32a16 16 0 0 1 15.34-16C266.24 8.46 261.18 8 256 8 119 8 8 119 8 256s111 248 248 248c98 0 182.42-56.95 222.71-139.42-4.13 7.86-14.23 10.55-22 6.11z" />
						<path d="M271.23 72.62c-8.49-.69-15.23-7.31-15.23-15.83V24.73c0-9.11 7.67-16.78 16.77-16.17C401.92 17.18 504 124.67 504 256a246 246 0 0 1-25 108.24c-4 8.17-14.37 11-22.26 6.45l-27.84-15.9c-7.41-4.23-9.83-13.35-6.2-21.07A182.53 182.53 0 0 0 440 256c0-96.49-74.27-175.63-168.77-183.38z" />
					</svg>
				</div>
				<div class="merchant-quick-view-inner">
					<a href="#" class="merchant-quick-view-close-button" title="<?php echo esc_attr__( 'Close quick view modal', 'merchant' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
							<path d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z"/>
						</svg>
					</a>
					<div class="merchant-quick-view-content"></div>
				</div>
			</div>
		<?php 
	}

}

add_action( 'wp_footer', 'merchant_quick_view_modal' );


/**
 * Quick view ajax callback
 */
function merchant_quick_view_content_callback() {

	check_ajax_referer( 'merchant-nonce', 'nonce' );
	
	if( ! isset( $_POST['product_id'] ) || ! function_exists( 'wc_get_product' ) ) {
		wp_send_json_error();
	}
	
	$args = array(
		'product_id' => absint( $_POST['product_id'] )
	);
	
	global $post;
	global $product;

	$product = wc_get_product( $args['product_id'] ); 
	
	if ( is_wp_error( $product ) || empty( $product ) ) {
		wp_send_json_error();
	}
	
	$post = get_post( $args['product_id'] ); 
	$product_id = $product->get_id(); 

	ob_start();
	
	?>
	
		<div id="product-<?php echo absint( $product_id ); ?>" <?php wc_product_class( '', $product ); ?>>

			<div class="merchant-quick-view-row">

				<div class="merchant-quick-view-column">
					<div class="merchant-quick-view-product-gallery">
						<?php woocommerce_show_product_images(); ?>
					</div>
				</div>
				
				<div class="merchant-quick-view-column">

					<div class="merchant-quick-view-summary">

						<div class="merchant-quick-view-product-title"><?php woocommerce_template_single_title(); ?></div>
						<div class="merchant-quick-view-product-rating"><?php woocommerce_template_single_rating(); ?></div>
						<div class="merchant-quick-view-product-price"><?php woocommerce_template_single_price(); ?></div>
						<div class="merchant-quick-view-product-excerpt"><?php woocommerce_template_single_excerpt(); ?></div>
						<div class="merchant-quick-view-product-add-to-cart"><?php woocommerce_template_single_add_to_cart(); ?></div>
						<div class="merchant-quick-view-product-meta"><?php woocommerce_template_single_meta(); ?></div>

					</div>

				</div>

			</div>

		</div>

	<?php

	$content = ob_get_contents();

	ob_get_clean();

	wp_send_json_success( $content );

}
add_action( 'wp_ajax_merchant_quick_view_content', 'merchant_quick_view_content_callback' );
add_action( 'wp_ajax_nopriv_merchant_quick_view_content', 'merchant_quick_view_content_callback' );
