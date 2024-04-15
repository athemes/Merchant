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
if ( empty( $args['product_offers'] ) ) {
	return;
}
foreach ( $args['product_offers'] as $offer ) {
	?>
    <div class="buy-x-get-y">
        <h3 class="section-title"><?php
			echo esc_html( Merchant_Translator::translate( $offer['title'] ) ); ?></h3>
        <div class="offer-products">
            <div class="offer-column">
                <div class="offer-product">
                    <div class="image-wrapper">
                        <a href="<?php
						echo esc_url( $args['popup_data']['product_url'] ); ?>">
							<?php
							echo wp_kses_post( $args['popup_data']['product_image_large'] ); ?>
                        </a>
                    </div>
                    <div class="product-summary">
                        <a href="<?php
						echo esc_url( $args['popup_data']['product_url'] ); ?>">
                            <h3><?php
								echo esc_html( $args['popup_data']['product_name'] ); ?></h3></a>
                        <div class="product-price"><?php
							echo wp_kses_post( $args['popup_data']['product_price'] ); ?></div>
                    </div>
                </div>
            </div>
            <div class="offer-column half-width">
                <div class="offer-icon">
                    <span class="plus-icon">+</span>
                </div>
            </div>
            <div class="offer-column wide">
                <div class="products-slider-container">
                    <div class="products-slider<?php echo count($offer['products']) > 1 ? esc_attr(' multiple-slides') : '';?>">
						<?php
						foreach ( $offer['products'] as $product_data ) { ?>
                            <div class="offer-product">
                                <div class="image-wrapper">
                                    <a href="<?php
									echo esc_url( $product_data['permalink'] ); ?>">
										<?php
										echo wp_kses_post( $product_data['image_big'] ); ?>
                                    </a>
                                </div>
                                <div class="product-summary">
                                    <a href="<?php
									echo esc_url( $product_data['permalink'] ); ?>">
                                        <h3><?php
											echo esc_html( $product_data['title'] ); ?></h3></a>
                                    <div class="product-price"><?php
										echo wp_kses_post( $product_data['price_html'] ); ?></div>
                                </div>
                            </div>
							<?php
						} ?>
                    </div>
                </div>
            </div>
            <div class="offer-column narrow">
                <div class="offer-price">
                    <h4>
						<?php
						esc_html_e( 'Bundle Price', 'merchant' ); ?>
                    </h4>
					<?php
					if (
						isset( $offer['total_discounted_price'] )
						&& $offer['total_discounted_price'] !== $offer['total_price']
						&& $offer['total_discounted_price'] > 0
					) {
						echo '<del>' . wp_kses_post( wc_price( $offer['total_price'] ) ) . '</del>';
						echo '<ins>' . wp_kses_post( wc_price( $offer['total_discounted_price'] ) ) . '</ins>';
					} else {
						echo wp_kses_post( wc_price( $offer['total_price'] ) );
					}
					?>
                </div>
            </div>
        </div>
    </div>
	<?php
}

