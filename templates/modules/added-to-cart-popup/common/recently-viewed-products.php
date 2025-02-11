<?php
/**
 * Template for added to cart popup recently viewed products content.
 *
 * @var $args array template args
 *
 * @since 1.9.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="recently-viewed-products-section">
    <h3 class="section-title"><?php
		esc_html_e( 'Recently Viewed Products', 'merchant' ); ?></h3>
    <ul class="products-list">
		<?php
		foreach ( $args['product_offers'] as $product ) {
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
                    <a href="<?php echo esc_url( $product->get_permalink() ); ?>">
                        <h3>
                            <?php echo esc_html( wp_trim_words(
								$product->get_name(),
								/**
								 * Product title words count.
								 *
								 * @param int        $words   Words count.
								 * @param WC_Product $product Product object.
								 *
								 * @since 1.9.7
								 */
								apply_filters( 'merchant_popup_recently_viewed_products_product_title_words', 10, $product ),
								/**
								 * Product title suffix.
								 *
								 * @param string     $suffix  Suffix.
								 * @param WC_Product $product Product object.
								 *
								 * @since 1.9.7
								 */
								apply_filters( 'merchant_popup_recently_viewed_products_product_title_suffix', '...', $product )
							) ); ?>
                        </h3>
                    </a>
                    <div class="product-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
                    <div class="product-price-add-to-cart">
		                <?php
		                merchant_get_template_part(
			                Merchant_Added_To_Cart_Popup::MODULE_TEMPLATES_PATH . '/common',
			                'add-to-cart',
			                array(
				                'product' => $product,
			                )
		                );
		                ?>
                    </div>
                </div>
            </li>
			<?php
		} ?>
    </ul>
</div>