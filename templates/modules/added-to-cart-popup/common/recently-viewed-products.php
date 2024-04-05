<?php
/**
 * Template for added to cart popup layout 1.
 *
 * @var $args array template args
 *
 * @since 1.9.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="recently-viewed-products">
	<h3 class="section-title"><?php
		esc_html_e( 'Recently Viewed Products', 'merchant' ); ?></h3>
	<ul class="products columns-4">
		<?php
		foreach ( $args['recently_viewed_products'] as $product ) {
			/**
			 * @var WC_Product $product
			 */
			?>
			<li class="product">
				<div class="image-wrapper">
					<a href="<?php
					echo esc_url( $product->get_permalink() ); ?>">
						<?php
						echo wp_kses_post( $product->get_image() ); ?>
					</a>
				</div>
				<div class="product-summary">
					<a href="<?php
					echo esc_url( $product->get_permalink() ); ?>">
						<h3><?php
							echo esc_html( $product->get_name() ); ?></h3></a>
					<div class="product-price"><?php
						echo wp_kses_post( $product->get_price_html() ); ?></div>
				</div>
			</li>
			<?php
		} ?>
</div>