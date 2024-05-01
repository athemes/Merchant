<?php
/**
 * Template for displaying the free gifts module widget contents
 *
 * @var $args array template args
 *
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$settings = isset( $args['settings'] ) ? $args['settings'] : array();

$cart_total = $args['cart_total'] ?? WC()->cart->get_subtotal();

// Different text based on cart total
$spending_text_0       = $settings['spending_text'] ?? '';
$spending_text_1_to_99 = $settings['spending_text_1_to_99'] ?? '';
$spending_text_100     = $settings['spending_text_100'] ?? '';
?>

<?php foreach ( $args['offers'] as $offer ): ?>
	<?php
	if ( ! isset( $offer['product'] ) ) {
		continue;
	}

	$spending_text_0       = $offer['spending_text_0'] ?? '';
	$spending_text_1_to_99 = $offer['spending_text_1_to_99'] ?? '';
	$spending_text_100     = $offer['spending_text_100'] ?? '';

	if ( isset( $offer['rules_to_apply'], $offer['category_slugs'] )
		&& 'categories' === $offer['rules_to_apply']
		&& ! merchant_is_already_in_cart( $offer['product']['id'] )
	) :
		$categories = array();
		foreach ( $offer['category_slugs'] as $category_slug ) {
			$category_data = get_term_by( 'slug', $category_slug, 'product_cat' );
			if ( $category_data ) {
				$categories[] = $category_data->name;
			}
		}

		if ( empty( $categories ) ) {
			continue;
		}
		?>
		<div class="merchant-free-gifts-widget-offer">
			<div class="merchant-free-gifts-widget-offer-label">
				<?php
                $price = wc_price( 30 ); // Todo: Get cart price for product under `$categories`

                $category_text = sprintf(
                    /* Translators: 1. Term */
	                _n( '%s category', '%s categories', count( $categories ), 'merchant' ),
	                implode( ', ', $categories )
                );

                echo wp_kses(
					str_replace(
						array(
							'{amount}',
							'{goalAmount}',
							'{amountMore}',
							'{categories}',
						),
						array(
							$price,
							$price,
							$price,
							$category_text,
						),
						sanitize_text_field( Merchant_Translator::translate( $spending_text_0 ) )
					),
					merchant_kses_allowed_tags( array( 'bdi' ) )
				);
                ?>
			</div>
			<div class="merchant-free-gifts-widget-offer-product">
				<?php echo wp_kses_post( $offer['product']['image'] ); ?>
				<div class="merchant-free-gifts-widget-offer-product-contents">
					<p class="woocommerce-loop-product__title">
						<a class="merchant-free-gifts-widget-offer-product-title" href="<?php echo esc_url( $offer['product']['permalink'] ); ?>" target="_blank">
							<?php echo esc_html( $offer['product']['title'] ); ?>
						</a>
					</p>
                    <div class="merchant-free-gifts-widget-offer-product-attributes">
						<?php
						echo wp_kses(
							Merchant_Pro_Free_Gifts::get_variations_select_html( $offer['product']['id'] ?? 0 ),
							merchant_kses_allowed_tags( array( 'forms' ) )
						);
						?>
                    </div>
					<div class="merchant-free-gifts-widget-offer-product-price price">
						<del>
							<?php echo wp_kses( wc_price( $offer['product']['price'] ), merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?>
						</del>
						<strong class="merchant-free-gifts-widget-offer-product-free">
							<?php echo isset( $settings['free_text'] ) ? esc_html( Merchant_Translator::translate( $settings['free_text'] ) ) : esc_html__( 'Free', 'merchant' ) ?>
						</strong>
					</div>
				</div>
			</div>
		</div>
			<?php
	elseif ( isset( $offer['rules_to_apply'], $offer['product_to_purchase'] )
		&& 'product' === $offer['rules_to_apply']
		&& ! merchant_is_already_in_cart( $offer['product_to_purchase'] )
	) :
		$product = wc_get_product( $offer['product_to_purchase'] );

		?>
		<div class="merchant-free-gifts-widget-offer">
			<div class="merchant-free-gifts-widget-offer-label">
				<?php echo wp_kses(
				/* Translators: 1. Amount */
					sprintf( __( 'Buy %s to receive this free gift!', 'merchant' ), $product->get_title() ),
					merchant_kses_allowed_tags( array( 'bdi' ) )
				); ?>
			</div>
			<div class="merchant-free-gifts-widget-offer-product">
				<?php echo wp_kses_post( $offer['product']['image'] ); ?>
				<div class="merchant-free-gifts-widget-offer-product-contents">
					<p class="woocommerce-loop-product__title">
						<a class="merchant-free-gifts-widget-offer-product-title" href="<?php echo esc_url( $offer['product']['permalink'] ); ?>" target="_blank">
							<?php echo esc_html( $offer['product']['title'] ); ?>
						</a>
					</p>
                    <div class="merchant-free-gifts-widget-offer-product-attributes">
						<?php
						echo wp_kses(
							Merchant_Pro_Free_Gifts::get_variations_select_html( $offer['product']['id'] ?? 0 ),
							merchant_kses_allowed_tags( array( 'forms' ) )
						);
						?>
                    </div>
					<div class="merchant-free-gifts-widget-offer-product-price price">
						<del>
							<?php echo wp_kses( wc_price( $offer['product']['price'] ), merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?>
						</del>
						<strong class="merchant-free-gifts-widget-offer-product-free">
							<?php echo isset( $settings['free_text'] ) ? esc_html( Merchant_Translator::translate( $settings['free_text'] ) ) : esc_html__( 'Free', 'merchant' ) ?>
						</strong>
					</div>
				</div>
			</div>
		</div>
	<?php elseif ( isset( $offer['amount'], $args['cart_total'] ) ) : ?>
		<div class="merchant-free-gifts-widget-offer">
			<div class="merchant-free-gifts-widget-offer-label">
				<?php
				$goal_amount = $offer['amount'] ?? 0;
				$amount_more = max( 0, $goal_amount - $cart_total );
				$price_html  = wc_price( $amount_more );

				if ( $cart_total >= $goal_amount ) {
					$spending_text = $spending_text_100;
				} elseif ( $cart_total > 0 ) {
					$spending_text = $spending_text_1_to_99;
				} else {
					$spending_text = $spending_text_0;
				}

				echo wp_kses(
					str_replace(
						array(
							'{amount}',
							'{goalAmount}',
							'{amountMore}',
						),
						array(
							$price_html,
							$price_html,
							$price_html,
						),
						sanitize_text_field( Merchant_Translator::translate( $spending_text ) )
					),
					merchant_kses_allowed_tags( array( 'bdi' ) )
				);
				?>
			</div>
			<div class="merchant-free-gifts-widget-offer-product">
				<?php echo wp_kses_post( $offer['product']['image'] ); ?>
				<div class="merchant-free-gifts-widget-offer-product-contents">
					<p class="woocommerce-loop-product__title">
						<a class="merchant-free-gifts-widget-offer-product-title" href="<?php echo esc_url( $offer['product']['permalink'] ); ?>" target="_blank">
							<?php echo esc_html( $offer['product']['title'] ); ?>
						</a>
					</p>
                    <div class="merchant-free-gifts-widget-offer-product-attributes">
	                    <?php
	                    echo wp_kses(
		                    Merchant_Pro_Free_Gifts::get_variations_select_html( $offer['product']['id'] ?? 0 ),
		                    merchant_kses_allowed_tags( array( 'forms' ) )
	                    );
	                    ?>
                    </div>
					<div class="merchant-free-gifts-widget-offer-product-price price">
						<del>
							<?php echo wp_kses( wc_price( $offer['product']['price'] ), merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?>
						</del>
						<strong class="merchant-free-gifts-widget-offer-product-free">
							<?php echo isset( $settings['free_text'] ) ? esc_html( Merchant_Translator::translate( $settings['free_text'] ) ) : esc_html__( 'Free', 'merchant' ) ?>
						</strong>
					</div>
				</div>
			</div>
		</div>
	<?php else:
		if ( isset( $offer['coupon'] ) && ! empty( $offer['coupon'] ) ) {
			$coupon = $offer['coupon'];
		} else {
			continue;
		}
		$applied_coupons = WC()->cart->get_applied_coupons();
		if ( in_array( $coupon, $applied_coupons, true ) ) {
			continue;
		}
		?>
		<div class="merchant-free-gifts-widget-offer">
			<div class="merchant-free-gifts-widget-offer-label">
				<?php
				echo wp_kses(
					/* Translators: 1. Amount */
						sprintf( __( 'Use %s coupon to get this product', 'merchant' ), $coupon ),
						merchant_kses_allowed_tags( array( 'bdi' ) )
					); ?>
			</div>
			<div class="merchant-free-gifts-widget-offer-product">
				<?php echo wp_kses_post( $offer['product']['image'] ); ?>
				<div class="merchant-free-gifts-widget-offer-product-contents">
					<p class="woocommerce-loop-product__title">
						<a class="merchant-free-gifts-widget-offer-product-title" href="<?php echo esc_url( $offer['product']['permalink'] ); ?>" target="_blank">
							<?php echo esc_html( $offer['product']['title'] ); ?>
						</a>
					</p>
                    <div class="merchant-free-gifts-widget-offer-product-attributes">
						<?php
						echo wp_kses(
							Merchant_Pro_Free_Gifts::get_variations_select_html( $offer['product']['id'] ?? 0 ),
							merchant_kses_allowed_tags( array( 'forms' ) )
						);
						?>
                    </div>
					<div class="merchant-free-gifts-widget-offer-product-price price">
						<del>
							<?php echo wp_kses( wc_price( $offer['product']['price'] ), merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?>
						</del>
						<strong class="merchant-free-gifts-widget-offer-product-free">
							<?php echo isset( $settings['free_text'] ) ? esc_html( Merchant_Translator::translate( $settings['free_text'] ) ) : esc_html__( 'Free', 'merchant' ) ?>
						</strong>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
<?php endforeach; ?>