<?php

function merchant_product_labels() {
	
	global $product;

	if ( ! empty( $product ) && $product->is_on_sale() && Merchant_Modules::is_module_active( 'product-labels' ) ) {

		$label_text         = Merchant_Admin_Options::get( 'product-labels', 'label_text', esc_html__( 'Spring Special', 'merchant' ) );
		$label_position     = Merchant_Admin_Options::get( 'product-labels', 'label_position', 'top-left' );
		$label_shape        = Merchant_Admin_Options::get( 'product-labels', 'label_shape', 'square' );
		$display_percentage = Merchant_Admin_Options::get( 'product-labels', 'display_percentage', '' );
		$percentage_text    = Merchant_Admin_Options::get( 'product-labels', 'percentage_text', '-{value}%' );

		if ( ! empty( $display_percentage ) ) {
			
			if ( $product->is_type('variable' ) ) {

				$percentages = array();
				$prices      = $product->get_variation_prices();
			
				foreach ( $prices['price'] as $key => $price ) {
					if ( $prices['regular_price'][$key] !== $price ) {
						$percentages[] = round( 100 - ( floatval( $prices['sale_price'][ $key ] ) / floatval( $prices['regular_price'][ $key ] ) * 100 ) );
					}
				}
				
				$percentage = max( $percentages );
				
			} elseif ( $product->is_type('grouped') ) {
				
				$percentages  = array();
				$children_ids = $product->get_children();
				
				foreach ( $children_ids as $child_id ) {
					
					$child_product = wc_get_product($child_id);
					$regular_price = (float) $child_product->get_regular_price();
					$sale_price    = (float) $child_product->get_sale_price();
					
					if ( 0 != $sale_price || ! empty( $sale_price ) ) {
						$percentages[] = round( 100 - ( ( $sale_price / $regular_price ) * 100 ) );
					}
					
				}
				$percentage = max( $percentages );
				
			} else {
				
				$regular_price = (float) $product->get_regular_price();
				$sale_price    = (float) $product->get_sale_price();
				
				if ( 0 != $sale_price || ! empty( $sale_price ) ) {
					$percentage = round( 100 - ( ( $sale_price / $regular_price ) * 100 ) );
				}
				
			}
			
			$label_text = str_replace( '{value}', $percentage, $percentage_text );
			
		}
		
		echo '<span class="merchant-onsale merchant-onsale-' . sanitize_html_class( $label_position ) . ' merchant-onsale-shape-' . sanitize_html_class( $label_shape ) . '">' . esc_html( $label_text ) . '</span>';

	}
	
}
add_action( 'woocommerce_before_shop_loop_item_title', 'merchant_product_labels' );
add_action( 'woocommerce_product_thumbnails', 'merchant_product_labels' );
add_action( 'woostify_product_images_box_end', 'merchant_product_labels' );
