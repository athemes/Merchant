<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="merchant-checkout-preview">
	<div class="order-received">
		<div class="page-title"><?php
			esc_html_e( 'Checkout', 'merchant' ); ?></div>
		<br>
		<div class="upsell-offer">
			<div class="offer-title"><?php
				esc_html_e( 'Last chance to get', 'merchant' ); ?></div>
            <p class="offer-desc"><?php
				esc_html_e( 'Description', 'merchant' ); ?></p>
			<div class="product-details">
				<div class="product-image"></div>
				<div class="product-info">
					<div class="product-name"><?php
						esc_html_e( 'Your Product Name', 'merchant' ); ?></div>
					<button class="add-to-order"><?php
						esc_html_e( 'Add To My Order', 'merchant' ); ?></button>
				</div>
			</div>
		</div>
	</div>
</div>
