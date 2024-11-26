<?php
/**
 * Template for displaying the Free-Gifts module widget contents
 *
 * @var $args array template args
 *
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings = isset( $args['settings'] ) ? $args['settings'] : array();
//$cart_total = $args['cart_total'] ?? WC()->cart->get_subtotal();

$is_cart_page = wp_doing_ajax() ? filter_var( $_POST['is_cart_page'] ?? false, FILTER_VALIDATE_BOOLEAN ) : is_cart(); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended

foreach ( $args['offers'] as $offer ) :
	if ( empty( $offer['product'] ) ) {
		continue;
	}

	$goal_amount = $offer['amount'] ?? 0;
	$amount_more = $offer['amount_more'] ?? $goal_amount;
	$price_html  = wc_price( $amount_more );

	$gift_product_id       = $offer['product']['id'] ?? 0;
	$spending_text_0       = $offer['spending_text_0'] ?? '';
	$spending_text_1_to_99 = $offer['spending_text_1_to_99'] ?? '';
	$spending_text_100     = $offer['spending_text_100'] ?? '';
    $text_before_claim     = $offer['text_before_claim'] ?? '';
	$text_after_claim      = $offer['text_after_claim'] ?? '';

	$rules      = $offer['rules_to_apply'] ?? '';
	$offer_type = $offer['offer_type'] ?? '';

	$spending_text     = '';
	$_title            = '';
	$title_shortcode   = '';
	$show_claim_button = false;

    // Prepare data based on offer type & offer rules.
	if ( $offer_type === 'spending' ) {
		switch ( $rules ) {
			case 'product':
				if ( ! empty( $offer['product_to_purchase'] ) ) {
					$product            = wc_get_product( $offer['product_to_purchase'] );
					$cart_total         = $offer['cart_total_product'] ?? 0;
					$_title             = $product->get_title();
					$title_shortcode    = '{productName}';

					if ( $cart_total >= $goal_amount ) {
						$spending_text = $spending_text_100;
					} elseif ( $cart_total > 0 ) {
						$spending_text = $spending_text_1_to_99;
					} else {
						$spending_text = $spending_text_0;
					}
				}
				break;

			case 'categories':
				if ( ! empty( $offer['category_slugs'] ) ) {
					$categories = array();
					foreach ( $offer['category_slugs'] as $category_slug ) {
						$category_data = get_term_by( 'slug', $category_slug, 'product_cat' );
						if ( $category_data ) {
							$categories[] = $category_data->name;
						}
					}

					if ( empty( $categories ) ) {
						break;
					}

					$cart_total      = $offer['cart_total_category'] ?? 0;
					$_title          = sprintf(
					    /* Translators: 1. Term Name */
						_n( '%s category', '%s categories', count( $categories ), 'merchant' ),
						implode( ', ', $categories )
					);
					$title_shortcode = '{categories}';

					if ( $cart_total >= $goal_amount ) {
						$spending_text = $spending_text_100;
					} elseif ( $cart_total > 0 ) {
						$spending_text = $spending_text_1_to_99;
					} else {
						$spending_text = $spending_text_0;
					}
				}
				break;

			case 'tags':
				if ( ! empty( $offer['tag_slugs'] ) ) {
					$tags = array();
					foreach ( $offer['tag_slugs'] as $tag_slug ) {
						$tag_data = get_term_by( 'slug', $tag_slug, 'product_tag' );
						if ( $tag_data ) {
							$tags[] = $tag_data->name;
						}
					}

					if ( empty( $tags ) ) {
						break;
					}

					$cart_total      = $offer['cart_total_tag'] ?? 0;
					$_title          = sprintf(
					/* Translators: 1. Term Name */
						_n( '%s tag', '%s tags', count( $tags ), 'merchant' ),
						implode( ', ', $tags )
					);
					$title_shortcode = '{tags}';

					if ( $cart_total >= $goal_amount ) {
						$spending_text = $spending_text_100;
					} elseif ( $cart_total > 0 ) {
						$spending_text = $spending_text_1_to_99;
					} else {
						$spending_text = $spending_text_0;
					}
				}
				break;

			case 'all':
				$cart_total = $offer['cart_total_all'] ?? 0;
				if ( $cart_total >= $goal_amount ) {
					$spending_text = $spending_text_100;
				} elseif ( $cart_total > 0 ) {
					$spending_text = $spending_text_1_to_99;
				} else {
					$spending_text = $spending_text_0;
				}
				break;
		}

		$show_claim_button = ( $cart_total >= $goal_amount ) && empty( $offer['is_gift_claimed'] );
	} elseif ( $offer_type === 'coupon' ) {
		$coupon = $offer['coupon'] ?? '';

		$applied_coupons = WC()->cart->get_applied_coupons();
        $is_coupon_added = in_array( $coupon, $applied_coupons, true );

		$show_claim_button = $is_coupon_added && empty( $offer['is_gift_claimed'] );

        // Don't show the offer on widget unless coupon is entered
        if ( ! $is_coupon_added ) {
            continue;
        }

        /* Translators: 1. Amount */
		$spending_text = $is_coupon_added ? $spending_text_100 : sprintf(  __( 'Use %s coupon to get this product', 'merchant' ), $coupon );
	}

	// Different text based on whether the gift has been claimed or not.
	if ( ! empty( $offer['is_gift_claimed'] ) ) {
		$spending_text = $text_after_claim;
	} elseif ( $is_cart_page && $show_claim_button ) {
		$spending_text = $text_before_claim;
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
						$title_shortcode,
					),
					array(
						$price_html,
						$price_html,
						$price_html,
						$_title,
					),
					sanitize_text_field( Merchant_Translator::translate( $spending_text ) )
				),
				merchant_kses_allowed_tags( array( 'bdi' ) )
			);
			?>
        </div>

        <div class="merchant-free-gifts-widget-offer-product">
			<?php echo wp_kses_post( $offer['product']['image'] ?? '' ); ?>
            <div class="merchant-free-gifts-widget-offer-product-contents">
                <p class="woocommerce-loop-product__title">
                    <a class="merchant-free-gifts-widget-offer-product-title" href="<?php echo esc_url( $offer['product']['permalink'] ?? '' ); ?>" target="_blank">
						<?php echo esc_html( $offer['product']['title'] ?? '' ); ?>
                    </a>
                </p>
                <div class="merchant-free-gifts-widget-offer-product-claim">
					<?php
					if ( $show_claim_button ) {
						echo wp_kses( Merchant_Pro_Free_Gifts::get_claim_button( $gift_product_id ), merchant_kses_allowed_tags( array( 'forms' ) ) );
					}
					?>
                </div>
                <div class="merchant-free-gifts-widget-offer-product-price price">
                    <del><?php echo wp_kses( wc_price( $offer['product']['price'] ?? '' ), merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?></del>
                    <strong class="merchant-free-gifts-widget-offer-product-free">
						<?php echo isset( $settings['free_text'] ) ? esc_html( Merchant_Translator::translate( $settings['free_text'] ) ) : esc_html__( 'Free', 'merchant' ); ?>
                    </strong>
                </div>
            </div>
        </div>
    </div>
    <?php
endforeach;
