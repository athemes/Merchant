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
?>

<?php foreach ( $args['offers'] as $offer ): ?>
	<?php
	if ( ! isset( $offer['product'] ) ) {
		continue;
	}

	$spending_text_0       = $offer['spending_text_0'] ?? '';
	$spending_text_1_to_99 = $offer['spending_text_1_to_99'] ?? '';
	$spending_text_100     = $offer['spending_text_100'] ?? '';

	$goal_amount = $offer['amount'] ?? 0;
	$amount_more = $offer['amount_more'] ?? $goal_amount;
	$price_html  = wc_price( $amount_more );

    $merchant_hash = $offer['merchant_hash'] ?? '';

    // Offer for Categories
	if ( isset( $offer['rules_to_apply'], $offer['category_slugs'] ) && 'categories' === $offer['rules_to_apply'] ) :
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

        $cart_total_category = $offer['cart_total_category'] ?? 0;

		if ( $cart_total_category >= $goal_amount ) {
			$spending_text = $spending_text_100;
		} elseif ( $cart_total_category > 0 ) {
			$spending_text = $spending_text_1_to_99;
		} else {
			$spending_text = $spending_text_0;
		}
		?>
		<div class="merchant-free-gifts-widget-offer">
			<div class="merchant-free-gifts-widget-offer-label">
				<?php
                $category_text = sprintf(
                    /* Translators: 1. Term Name */
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
							$price_html,
							$price_html,
							$price_html,
							$category_text,
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
						if ( ( $cart_total_category >= $goal_amount ) && empty( $offer['is_gift_claimed'] ) ) {
							echo wp_kses(
								Merchant_Pro_Free_Gifts::get_variations_select_html( $offer['product']['id'] ?? 0, $merchant_hash ),
								merchant_kses_allowed_tags( array( 'forms' ) )
							);
						}
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
    // Offer for Specific Product
	elseif ( isset( $offer['rules_to_apply'], $offer['product_to_purchase'] ) && 'product' === $offer['rules_to_apply'] ) :
		$product            = wc_get_product( $offer['product_to_purchase'] );
		$cart_total_product = $offer['cart_total_product'] ?? 0;

		if ( $cart_total_product >= $goal_amount ) {
			$spending_text = $spending_text_100;
		} elseif ( $cart_total_product > 0 ) {
			$spending_text = $spending_text_1_to_99;
		} else {
			$spending_text = $spending_text_0;
		}
		?>
		<div class="merchant-free-gifts-widget-offer">
			<div class="merchant-free-gifts-widget-offer-label">
				<?php
				echo wp_kses(
					str_replace(
						array(
							'{amount}',
							'{goalAmount}',
							'{amountMore}',
							'{productName}',
						),
						array(
							$price_html,
							$price_html,
							$price_html,
							$product->get_title(),
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
						if ( ( $cart_total_product >= $goal_amount ) && empty( $offer['is_gift_claimed'] ) ) {
							echo wp_kses(
								Merchant_Pro_Free_Gifts::get_variations_select_html( $offer['product']['id'] ?? 0, $merchant_hash ),
								merchant_kses_allowed_tags( array( 'forms' ) )
							);
						}
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
    // Offer for Any product
    elseif ( isset( $offer['amount'] ) ) :
        if ( $cart_total >= $goal_amount ) {
            $spending_text = $spending_text_100;
        } elseif ( $cart_total > 0 ) {
            $spending_text = $spending_text_1_to_99;
        } else {
            $spending_text = $spending_text_0;
        }
    ?>
		<div class="merchant-free-gifts-widget-offer">
			<div class="merchant-free-gifts-widget-offer-label">
				<?php
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
	                    if ( ( $cart_total >= $goal_amount ) && empty( $offer['is_gift_claimed'] ) ) {
		                    echo wp_kses(
			                    Merchant_Pro_Free_Gifts::get_variations_select_html( $offer['product']['id'] ?? 0, $merchant_hash ),
			                    merchant_kses_allowed_tags( array( 'forms' ) )
		                    );
	                    }
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
							Merchant_Pro_Free_Gifts::get_variations_select_html( $offer['product']['id'] ?? 0, $merchant_hash ),
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