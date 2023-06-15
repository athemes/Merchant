<?php

function merchant_sale_flash() {
	
	global $product;

	if ( ! empty( $product ) && $product->is_on_sale() && Merchant_Modules::is_module_active( 'sale-tags' ) ) {

		$badge_text         = Merchant_Admin_Options::get( 'sale-tags', 'badge_text', esc_html__( 'Sale!', 'merchant' ) );
		$badge_position     = Merchant_Admin_Options::get( 'sale-tags', 'badge_position', 'left' );
		$display_percentage = Merchant_Admin_Options::get( 'sale-tags', 'display_percentage', '' );
		$percentage_text    = Merchant_Admin_Options::get( 'sale-tags', 'percentage_text', '-{value}%' );

		if ( ! empty( $display_percentage ) ) {
			
			if ( $product->is_type('variable' ) ) {

				$percentages = array();
				$prices = $product->get_variation_prices();
			
				foreach ( $prices['price'] as $key => $price ) {
					if ( $prices['regular_price'][$key] !== $price ) {
						$percentages[] = round( 100 - ( floatval( $prices['sale_price'][ $key ] ) / floatval( $prices['regular_price'][ $key ] ) * 100 ) );
					}
				}
				
				$percentage = max( $percentages );
				
			} else if ( $product->is_type('grouped') ) {
				
				$percentages = array();
				$children_ids = $product->get_children();
				
				foreach ( $children_ids as $child_id ) {
					
					$child_product = wc_get_product($child_id);
					$regular_price = (float) $child_product->get_regular_price();
					$sale_price    = (float) $child_product->get_sale_price();
					
					if ( $sale_price != 0 || ! empty( $sale_price ) ) {
						$percentages[] = round( 100 - ( ( $sale_price / $regular_price ) * 100 ) );
					}
					
				}
				$percentage = max( $percentages );
				
			} else {
				
				$regular_price = (float) $product->get_regular_price();
				$sale_price    = (float) $product->get_sale_price();
				
				if ( $sale_price != 0 || ! empty( $sale_price ) ) {
					$percentage = round( 100 - ( ( $sale_price / $regular_price ) * 100 ) );
				}
				
			}
			
			$badge_text = str_replace( '{value}', $percentage, $percentage_text );
			
		}
		
		echo '<span class="merchant-onsale merchant-onsale-'. sanitize_html_class( $badge_position ) .'">'. esc_html( $badge_text ) .'</span>';

	}
	
}
add_action( 'woocommerce_before_shop_loop_item_title', 'merchant_sale_flash' );
add_action( 'woocommerce_product_thumbnails', 'merchant_sale_flash' );
add_action( 'woostify_product_images_box_end', 'merchant_sale_flash' );
