<?php

function merchant_add_trust_badges_add_to_cart_form() {

	if ( Merchant_Modules::is_module_active( 'trust-badges' ) ) {

		$badges = Merchant_Admin_Options::get( 'trust-badges', 'badges', '' );
		$title  = Merchant_Admin_Options::get( 'trust-badges', 'title', '' );

		$badges = explode( ',', $badges );

		if ( empty( $badges ) ) {
			return;
		}

		?>

			<fieldset class="merchant-trust-badges">

				<?php if ( ! empty( $title ) ) : ?>
					<legend class="merchant-trust-badges-title"><?php echo esc_html( $title ); ?></legend>
				<?php endif; ?>

				<?php if ( ! empty( $badges ) ) : ?>

					<div class="merchant-trust-badges-images">

						<?php foreach ( $badges as $image_id ) : ?>

							<?php $imagedata = wp_get_attachment_image_src( $image_id, 'full' ); ?>

							<?php if ( ! empty( $imagedata ) && ! empty( $imagedata[0] ) ) : ?>

								<?php echo sprintf( '<img src="%s" />', esc_url( $imagedata[0] ) ); ?>

							<?php endif; ?>

						<?php endforeach; ?>

					</div>

				<?php endif; ?>

			</fieldset>
			
		<?php 

	}

}

add_action( 'woocommerce_after_add_to_cart_form', 'merchant_add_trust_badges_add_to_cart_form' );