<?php

function merchant_add_product_trust_badge_add_to_cart_form() {

	if ( Merchant_Modules::is_module_active( 'product-trust-badge' ) ) {

		$badge = Merchant_Admin_Options::get( 'product-trust-badge', 'badge', '' );
		$title = Merchant_Admin_Options::get( 'product-trust-badge', 'title', esc_html__( 'Guaranteed Safe Checkout', 'merchant' ) );

		if ( empty( $badge ) ) {
			return;
		}

		?>

			<fieldset class="merchant-product-trust-badge">
				<?php if ( ! empty( $title ) ) : ?>
					<legend class="merchant-product-trust-badge-title"><?php echo esc_html( $title ); ?></legend>
				<?php endif; ?>
				<div class="merchant-product-trust-badge-image">
					<?php echo wp_get_attachment_image( $badge, 'full' ); ?>
				</div>
			</fieldset>
			
		<?php 

	}

}

add_action( 'woocommerce_after_add_to_cart_form', 'merchant_add_product_trust_badge_add_to_cart_form' );