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
    <div class="frequently-bought-together-popup<?php
	echo isset( $args['settings']['popup_size'] ) && $args['settings']['popup_size'] < 800 ? ' force-mobile-view' : ' ' ?>">
        <h3 class="section-title"><?php
			echo esc_html( Merchant_Translator::translate( $offer['title'] ) ); ?></h3>
        <div class="offer-products">
            <div class="offer-column">
                <div class="offer-product main-product">
                    <div class="image-wrapper">
                        <a href="<?php
						echo esc_url( $offer['products'][0]['permalink'] ); ?>">
							<?php
							echo wp_kses_post( $offer['products'][0]['image_big'] ); ?>
                        </a>
                    </div>
                    <div class="product-summary">
                        <a href="<?php
						echo esc_url( $offer['products'][0]['permalink'] ); ?>">
                            <h3><?php
								echo esc_html( $offer['products'][0]['title'] ); ?></h3></a>
                        <div class="product-price"><?php
							echo wp_kses_post( $offer['products'][0]['price_html'] ); ?></div>
                    </div>
                </div>
            </div>
            <div class="offer-column half-width computer-only">
                <div class="offer-icon">
                    <span class="plus-icon"></span>
                </div>
            </div>
			<?php
			unset( $offer['products'][0] ); // Prevent the main product from being displayed in the slider
			if ( isset( $args['settings']['popup_size'] ) && $args['settings']['popup_size'] >= 800 ) { ?>
                <div class="offer-column slider-area computer-only">
                    <div class="products-slider-container">
                        <div class="products-slider<?php
						echo count( $offer['products'] ) > 1 ? esc_attr( ' multiple-slides' ) : ''; ?>">
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
												echo esc_html( wp_trim_words(
													$product_data['title'],
													/**
													 * Product title words count.
													 *
													 * @param int   $words        Words count.
													 * @param Array $product_data Product data.
													 *
													 * @since 1.9.7
													 */
													apply_filters( 'merchant_popup_fbt_product_title_words', 10, $product_data ),
													/**
													 * Product title suffix.
													 *
													 * @param string $suffix       Suffix.
													 * @param Array  $product_data Product data.
													 *
													 * @since 1.9.7
													 */
													apply_filters( 'merchant_popup_fbt_product_title_suffix', '...', $product_data )
												) ); ?></h3></a>
                                        <div class="product-price"><?php
											echo wp_kses_post( $product_data['price_html'] ); ?></div>
                                    </div>
                                </div>
								<?php
							} ?>
                        </div>
                    </div>
                </div>
				<?php
			} ?>
            <div class="offer-column<?php
			echo isset( $args['settings']['popup_size'] ) && $args['settings']['popup_size'] >= 800 ? ' mobile-only' : '' ?>">
                <div class="offer-products">
					<?php
					foreach ( $offer['products'] as $product_data ) { ?>
                        <div class="offer-product other-products">
                            <div class="offer-icon">
                                <span class="plus-icon"></span>
                            </div>
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
										echo esc_html( wp_trim_words(
											$product_data['title'],
											/**
											 * Product title words count.
											 *
											 * @param int   $words        Words count.
											 * @param Array $product_data Product data.
											 *
											 * @since 1.9.7
											 */
											apply_filters( 'merchant_fbt_product_title_words', 10, $product_data ),
											/**
											 * Product title suffix.
											 *
											 * @param string $suffix       Suffix.
											 * @param Array  $product_data Product data.
											 *
											 * @since 1.9.7
											 */
											apply_filters( 'merchant_fbt_product_title_suffix', '...', $product_data )
										) ); ?></h3></a>
                                <div class="product-price"><?php
									echo wp_kses_post( $product_data['price_html'] ); ?></div>
                            </div>
                        </div>
						<?php
					} ?>
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

