<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function merchant_add_payment_logos_add_to_cart_form() {

	if ( Merchant_Modules::is_module_active( 'payment-logos' ) && ! is_archive() ) {

		$logos = Merchant_Admin_Options::get( 'payment-logos', 'logos', '' );
		$title = Merchant_Admin_Options::get( 'payment-logos', 'title', esc_html__( 'Checkout safely using your preferred payment method', 'merchant' ) );

		$logos = explode( ',', $logos );

		if ( empty( $logos ) ) {
			return;
		}

		?>

			<div class="merchant-payment-logos">

				<?php if ( ! empty( $title ) ) : ?>
					<div class="merchant-payment-logos-title">
						<strong><?php echo esc_html( $title ); ?></strong>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $logos ) ) : ?>

					<div class="merchant-payment-logos-images">

						<?php foreach ( $logos as $image_id ) : ?>

							<?php $imagedata = wp_get_attachment_image_src( $image_id, 'full' ); ?>

							<?php if ( ! empty( $imagedata ) && ! empty( $imagedata[0] ) ) : ?>

								<?php echo sprintf( '<img src="%s" />', esc_url( $imagedata[0] ) ); ?>

							<?php endif; ?>

						<?php endforeach; ?>

					</div>

				<?php endif; ?>

			</div>
			
		<?php 

	}

}

add_action( 'woocommerce_after_add_to_cart_form', 'merchant_add_payment_logos_add_to_cart_form' );
