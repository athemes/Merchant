<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="merchant-cart-preview">
    <div class="my-cart">
        <div class="cart-title"><?php
			esc_html_e( 'My Cart', 'merchant' ); ?></div>
        <table class="cart-table">
            <tr>
                <th class="product-col"><?php
					esc_html_e( 'PRODUCT', 'merchant' ); ?></th>
                <th class="price-col"><?php
					esc_html_e( 'PRICE', 'merchant' ); ?></th>
                <th class="quantity-col"><?php
					esc_html_e( 'QUANTITY', 'merchant' ); ?></th>
                <th class="total-col"><?php
					esc_html_e( 'TOTAL', 'merchant' ); ?></th>
            </tr>
            <tr class="cart-item">
                <td class="product-column">
                    <div class="product">
                        <div class="product-image"></div>
                        <div class="product-info">
                            <div class="product-name"><?php
								esc_html_e( 'Your Product Name', 'merchant' ); ?></div>
                            <p class="upsell-offer"><?php
								esc_html_e( 'Add', 'merchant' ); ?></p>
                            <div class="upsell-product">
                                <div class="upsell-image"></div>
                                <div class="upsell-info">
                                    <div class="upsell-name"><?php
										esc_html_e( 'Product Name', 'merchant' ); ?></div>
                                    <button class="add-to-cart"><?php
										esc_html_e( 'Add To Cart', 'merchant' ); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="price-col">
                        <span class="original-price"><?php
	                        echo wp_kses( wc_price( 16 ), merchant_kses_allowed_tags( array( 'bdi' ) ) ) ?></span>
                    <span class="discounted-price"><?php
						echo wp_kses( wc_price( 12 ), merchant_kses_allowed_tags( array( 'bdi' ) ) ) ?></span>
                </td>
                <td class="quantity-col">
                    <div class="quantity-control">
                        <button class="decrease">-</button>
                        <input type="text" value="1" min="1">
                        <button class="increase">+</button>
                    </div>
                </td>
                <td class="total-col"><?php
					echo wp_kses( wc_price( 300 ), merchant_kses_allowed_tags( array( 'bdi' ) ) ) ?></td>
            </tr>
        </table>
    </div>
</div>