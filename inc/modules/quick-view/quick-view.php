<?php

function merchant_quick_view_button() {

	global $product;

	$button_type     = Merchant_Admin_Options::get( 'quick-view', 'button_type', 'text' );
	$button_position = Merchant_Admin_Options::get( 'quick-view', 'button_position', 'after' );
	$button_text     = Merchant_Admin_Options::get( 'quick-view', 'button_text', esc_html__( 'Quick View', 'merchant' ) );
	$button_icon     = Merchant_Admin_Options::get( 'quick-view', 'button_icon', 'eye' );
	$product_id      = $product->get_id();

	$button_text_html = '';
	$button_icon_html = '';

	if ( $button_type === 'icon' || $button_type === 'icon-text' ) {
		if ( $button_icon === 'eye' ) {
			$button_icon_html = '<svg width="26" height="17" viewBox="0 0 26 17" xmlns="http://www.w3.org/2000/svg"><path d="M13 0C7.2653 0 2.32275 3.38057 0.0522984 8.25897C-0.0174328 8.4068 -0.0174328 8.57973 0.0522984 8.73035C2.32275 13.6088 7.2653 16.9893 13 16.9893C18.7347 16.9893 23.6773 13.6088 25.9477 8.73035C26.0174 8.58252 26.0174 8.40959 25.9477 8.25897C23.6773 3.38057 18.7347 0 13 0ZM13 14.3563C9.76168 14.3563 7.13978 11.7316 7.13978 8.49606C7.13978 5.25774 9.76447 2.63584 13 2.63584C16.2383 2.63584 18.8602 5.26053 18.8602 8.49606C18.8602 11.7316 16.2355 14.3563 13 14.3563Z" /><path d="M12.9996 12.2453C15.0715 12.2453 16.7511 10.5656 16.7511 8.49373C16.7511 6.42181 15.0715 4.74219 12.9996 4.74219C10.9277 4.74219 9.24805 6.42181 9.24805 8.49373C9.24805 10.5656 10.9277 12.2453 12.9996 12.2453Z" /></svg>';
		} else if ( $button_icon === 'cart' ) {
			$button_icon_html = '<svg width="22" height="22" viewBox="0 0 22 22" xmlns="http://www.w3.org/2000/svg"><path d="M6.6 17.6C5.3845 17.6 4.411 18.5845 4.411 19.8C4.411 21.0155 5.3845 22 6.6 22C7.8155 22 8.8 21.0155 8.8 19.8C8.8 18.5845 7.8155 17.6 6.6 17.6ZM0 0V2.2H2.2L6.1545 10.5435L4.6695 13.2385C4.499 13.5575 4.4 13.915 4.4 14.3C4.4 15.5155 5.3845 16.5 6.6 16.5H19.8V14.3H7.0675C6.9135 14.3 6.7925 14.179 6.7925 14.025C6.7925 13.9755 6.8035 13.9315 6.8255 13.893L7.81 12.1H16.005C16.83 12.1 17.5505 11.6435 17.93 10.967L21.8625 3.828C21.9505 3.674 22 3.4925 22 3.3C22 2.6895 21.505 2.2 20.9 2.2H4.6365L3.5915 0H0ZM17.6 17.6C16.3845 17.6 15.411 18.5845 15.411 19.8C15.411 21.0155 16.3845 22 17.6 22C18.8155 22 19.8 21.0155 19.8 19.8C19.8 18.5845 18.8155 17.6 17.6 17.6Z" /></svg>';
		}
	}

	if ( $button_type === 'text' || $button_type === 'icon-text' ) {
		$button_text_html = '<span>'. $button_text .'</span>';
	}

	?>
		<a href="#" class="button wp-element-button merchant-quick-view-open merchant-quick-view-position-<?php echo sanitize_html_class( $button_position ); ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>"><?php echo wp_kses( $button_icon_html, merchant_get_svg_args() ) . wp_kses_post( $button_text_html ); ?></a>
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

	if ( defined( 'BOTIGA_PRO_URI' ) && defined( 'BOTIGA_PRO_VERSION' ) ) {
		add_action( 'wp_enqueue_scripts', 'merchant_quick_view_modal_botiga_swatch_script_enqueue');
	}

}
add_action( 'wp', 'merchant_quick_view_loaded' );

function merchant_quick_view_modal_botiga_swatch_script_enqueue() {

	if ( ! wp_script_is( 'botiga-product-swatch' ) ) {
		wp_enqueue_script( 'botiga-product-swatch', BOTIGA_PRO_URI . 'assets/js/botiga-product-swatch.min.js', array(), BOTIGA_PRO_VERSION, true );
	}

	if ( ! wp_script_is( 'botiga-checkout-quantity-input' ) ) {
		wp_enqueue_script( 'botiga-checkout-quantity-input', BOTIGA_PRO_URI . 'assets/js/botiga-checkout-quantity-input.min.js', array( 'jquery' ), BOTIGA_PRO_VERSION, true );
	}

}

function merchant_quick_view_modal() {

	global $product;

	if ( Merchant_Modules::is_module_active( 'quick-view' ) ) {

		$place_product_image = Merchant_Admin_Options::get( 'quick-view', 'place_product_image', 'thumbs-at-left' );

		?>
			<div class="single-product merchant-quick-view-modal merchant-quick-view-<?php echo sanitize_html_class( $place_product_image ); ?>">
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

	$show_quantity = Merchant_Admin_Options::get( 'quick-view', 'show_quantity', 1 );
	$hide_quantity = ( empty( $show_quantity ) ) ? 'merchant-hide-quantity' : '';

	$place_product_description = Merchant_Admin_Options::get( 'quick-view', 'place_product_description', 'top' );
	$description_style = Merchant_Admin_Options::get( 'quick-view', 'description_style', 'short' );

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
						<?php if ( $place_product_description === 'top' ) : ?>
							<?php if ( $description_style === 'full' ) : ?>
								<div class="merchant-quick-view-product-excerpt"><?php echo the_content( $args['product_id'] ); ?></div>
							<?php else : ?>
								<div class="merchant-quick-view-product-excerpt"><?php woocommerce_template_single_excerpt(); ?></div>
							<?php endif; ?>
						<?php endif; ?>
						<div class="merchant-quick-view-product-add-to-cart <?php echo sanitize_html_class( $hide_quantity ); ?>"><?php woocommerce_template_single_add_to_cart(); ?></div>
						<?php if ( $place_product_description === 'bottom' ) : ?>
							<?php if ( $description_style === 'full' ) : ?>
								<div class="merchant-quick-view-product-excerpt"><?php echo the_content( $args['product_id'] ); ?></div>
							<?php else : ?>
								<div class="merchant-quick-view-product-excerpt"><?php woocommerce_template_single_excerpt(); ?></div>
							<?php endif; ?>
						<?php endif; ?>
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
