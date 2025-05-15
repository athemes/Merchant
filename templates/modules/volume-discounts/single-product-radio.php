<?php

/**
 * Template for Bulk Discounts Single Product
 *
 * @var array $args template args
 *
 * @since 1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$product    = $args['product'];
$product_id = $product->get_id();
?>
<div class="merchant-volume-discounts radio-style">
	<?php
    $in_cart = 'false';
	$quantity = 0;
	if ( ! empty( $args['in_cart'] ) ) {
		$in_cart = 'true';
	}
    if ( ! empty( $args['product_cart_quantity'] ) ) {
        $quantity = $args['product_cart_quantity'];
    }
    $i = 0;
    $offers_count = count( $args['discount_tiers'] );
	foreach ( $args['discount_tiers'] as $offer_id => $discount_tier ) :
		if ( isset( $discount_tier['discount_type'], $discount_tier['product_single_page']['save_label'], $discount_tier['product_single_page']['item_text'], $discount_tier['product_single_page']['total_text'] ) ) {
			$is_variable     = $product->is_type( 'variable' );
            $discount_target = $discount_tier['discount_target'] ?? 'sale';

			if ( $is_variable ) {
				$regular_price = (float) $product->get_variation_regular_price( 'min' );
				$sale_price    = (float) $product->get_variation_sale_price( 'min' );
			} else {
				$regular_price = (float) $product->get_regular_price();
				$sale_price    = (float) $product->get_sale_price();
			}

			$product_price = ( $discount_target === 'regular' || empty( $sale_price ) ) ? $regular_price : $sale_price;

            $discount = $discount_tier['discount_type'] === 'percentage_discount'
				? ( $product_price * $discount_tier['discount'] ) / 100
				: $discount_tier['discount'];

            $discount_percent = ( $discount_tier['discount'] ?? 0 ) . '%';
            $discount_qty = (int) ( $discount_tier['quantity'] ?? 1 );

			$discounted_price = $product_price - $discount;
			$total_discount   = $discount_qty * $discount;
			$total_price      = $discount_qty * $discounted_price;
			$clickable        = '';

			if ( ! $product->is_type( 'variable' ) && $quantity < $discount_qty ) {
				$clickable = ' clickable';
			}

			// Get available variations and attributes
			$available_variations = $is_variable ? $product->get_available_variations() : array();
			$attributes           = $is_variable ? $product->get_variation_attributes() : array();
            ?>
                <div class="merchant-volume-discounts-item-container">

                    <div class="merchant-volume-discounts-item-wrapper">
                        <?php
                        if ( isset( $discount_tier['product_single_page']['table_title'] ) && ! empty( $discount_tier['product_single_page']['table_title'] ) ) : ?>
                            <div class="merchant-volume-discounts-title" style="<?php
	                        echo isset( $discount_tier['product_single_page']['title_font_weight'] ) ? esc_attr( 'font-weight: ' . $discount_tier['product_single_page']['title_font_weight'] . ';' ) : '';
	                        echo isset( $discount_tier['product_single_page']['title_font_size'] ) ? esc_attr( 'font-size: ' . $discount_tier['product_single_page']['title_font_size'] . 'px;' ) : '';
	                        echo isset( $discount_tier['product_single_page']['title_text_color'] ) ? esc_attr( 'color: ' . $discount_tier['product_single_page']['title_text_color'] . ';' ) : ''; ?>">
                                <?php
		                        echo esc_html( Merchant_Translator::translate( $discount_tier['product_single_page']['table_title'] ) ); ?>
                            </div>
                        <?php
                        endif;

                        $item_classes  = 'merchant-volume-discounts-item';
                        $item_classes .= ' merchant-volume-discounts-item-' . esc_attr( $product->get_type() );
                        $item_classes .= ' merchant-volume-discounts-item-' . esc_attr( $i );
                        if ( $product->get_type() === 'simple' ) {
	                        $item_classes .= ' valid';
                        }

                        ?>
                        <label class="merchant-volume-discounts-label">
                            <div
                                class="<?php echo esc_attr( $item_classes ); ?>"
                                title="<?php echo esc_attr__( 'Add offer to cart', 'merchant' ); ?>"
                                data-in-cart="<?php echo esc_attr( $in_cart ); ?>"
                                data-product-id="<?php echo esc_attr( $product_id ); ?>"
                                data-offer-quantity="<?php echo esc_attr( $discount_qty ); ?>"
                                data-flexible-id="<?php echo isset( $discount_tier['flexible_id'] ) ? esc_attr( $discount_tier['flexible_id'] ) : ''; ?>"
                                data-offer-id="<?php echo esc_attr( $offer_id ); ?>" data-variations="<?php echo esc_attr( wp_json_encode( $available_variations ) ); ?>"
                                data-discount-target="<?php echo esc_attr( $discount_target ); ?>"
                                style="<?php
                                    // Inline CSS variables to use in CSS. Will work per item.
                                    echo isset( $discount_tier['product_single_page']['table_item_text_color'] ) ? '--merchant-item-text-color:' . esc_attr( $discount_tier['product_single_page']['table_item_text_color'] ). ';' : '';
                                    echo isset( $discount_tier['product_single_page']['table_item_bg_color'] ) ? '--merchant-item-bg-color:' . esc_attr( $discount_tier['product_single_page']['table_item_bg_color'] ). ';' : '';
                                    echo isset( $discount_tier['product_single_page']['table_item_border_color'] ) ? '--merchant-item-border-color:' . esc_attr( $discount_tier['product_single_page']['table_item_border_color'] ). ';' : '';
                                    echo isset( $discount_tier['product_single_page']['table_item_text_color_hover'] ) ? '--merchant-item-text-color-hover:' . esc_attr( $discount_tier['product_single_page']['table_item_text_color_hover'] ). ';' : '';
                                    echo isset( $discount_tier['product_single_page']['table_item_bg_color_hover'] ) ? '--merchant-item-bg-color-hover:' . esc_attr( $discount_tier['product_single_page']['table_item_bg_color_hover'] ). ';' : '';
                                    echo isset( $discount_tier['product_single_page']['table_item_border_color_hover'] ) ? '--merchant-item-border-color-hover:' . esc_attr( $discount_tier['product_single_page']['table_item_border_color_hover'] ). ';' : ''; ?>">

                                <div class="merchant-volume-discounts-buy-label">
                                    <input type="radio" name="merchant-selected-offer" class="merchant-selected-offer" <?php
                                    if ( $offers_count === 1 ) {
                                        echo 'checked style="display:none;"';
                                    } elseif( $i === 0 ) {
                                        echo 'checked';
                                    }
                                    ?>>
                                    <?php
                                    /**
                                     * Previously wrong variable `{amount}` was used for this field. Correct one should be `{quantity}`.
                                     * Still keeping the wrong one `{amount}` for backward compatibility.
                                     */
                                    if ( isset( $discount_tier['product_single_page']['buy_text'] ) ) {
                                        echo wp_kses(
                                            str_replace(
                                                array(
                                                    '{amount}',
                                                    '{quantity}',
                                                    '{discount}',
                                                    '{percent}',
                                                ),
                                                array(
                                                    '<strong>' . esc_html( $discount_qty ) . '</strong>',
                                                    '<strong>' . esc_html( $discount_qty ) . '</strong>',
                                                    '<strong>' . wc_price( $discount ) . '</strong>',
                                                    '<strong>' . esc_html( $discount_percent ) . '</strong>',
                                                ),
                                                esc_html( Merchant_Translator::translate( $discount_tier['product_single_page']['buy_text'] ) )
                                            ),
                                            merchant_kses_allowed_tags( array( 'bdi' ) )
                                        );
                                    } else {
                                        echo wp_kses(
                                            str_replace(
                                                array(
                                                    '{quantity}',
                                                    '{discount}',
                                                ),
                                                array(
                                                    '<strong class="tier-quantity">' . esc_html( $discount_qty ) . '</strong>',
                                                    '<strong class="tier-discount">' . wc_price( $discount ) . '</strong>',
                                                ),
                                                esc_html__( 'Buy {quantity}, get {discount} off each', 'merchant' )
                                            ),
                                            merchant_kses_allowed_tags( array( 'bdi' ) )
                                        );
                                    }
                                    ?>
                                </div>
                                <ul>
                                    <li>
                                        <div></div>
                                        <div class="merchant-volume-discounts-item-price-strikethrough">
                                            <del><?php echo wp_kses( wc_price( $product_price ), merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?></del>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="merchant-volume-discounts-item-text">
                                            <?php echo isset( $discount_tier['product_single_page']['item_text'] ) ? esc_html( Merchant_Translator::translate( $discount_tier['product_single_page']['item_text'] ) ) : esc_html__( 'Per item:', 'merchant' ); ?>
                                        </div>
                                        <div class="merchant-volume-discounts-item-price" data-item-price="<?php echo esc_attr( $discounted_price ); ?>">
                                            <strong style="color: #ff0000 !important;">
                                                <?php echo wp_kses( wc_price( $discounted_price ), merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?>
                                            </strong>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="merchant-volume-discounts-total-text">
                                            <?php echo isset( $discount_tier['product_single_page']['total_text'] )
                                                ? esc_html( Merchant_Translator::translate( $discount_tier['product_single_page']['total_text'] ) )
                                                : esc_html__( 'Total price:', 'merchant' ); ?>
                                        </div>
                                        <div class="merchant-volume-discounts-item-price-total" data-total-price="<?php echo esc_attr( $total_price ); ?>">
                                            <strong>
                                                <?php echo wp_kses( wc_price( $total_price ), merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?>
                                            </strong>
                                        </div>
                                    </li>
                                </ul>
                                <div class="merchant-volume-discounts-item-label">
                                    <span class="merchant-volume-discounts-save-label"
                                        style="<?php
                                        echo isset( $discount_tier['product_single_page']['table_label_text_color'] ) ? '--merchant-label-text-color:' . esc_attr( $discount_tier['product_single_page']['table_label_text_color'] ). ';' : '';
                                        echo isset( $discount_tier['product_single_page']['table_label_bg_color'] ) ? '--merchant-label-bg-color:' . esc_attr( $discount_tier['product_single_page']['table_label_bg_color'] ). ';' : '';?>">
                                        <?php
                                        if ( isset( $discount_tier['product_single_page']['save_label'] ) ) {
                                            echo wp_kses(
                                                str_replace(
                                                    array(
                                                        '{amount}',
                                                        '{percent}',
                                                    ),
                                                    array(
                                                        wc_price( $total_discount ),
                                                        esc_html( $discount_percent ),
                                                    ),
                                                    esc_html( Merchant_Translator::translate( $discount_tier['product_single_page']['save_label'] ) )
                                                ),
                                                merchant_kses_allowed_tags( array( 'bdi' ) )
                                            );
                                        } else {
                                            echo wp_kses(
                                                str_replace(
                                                    array(
                                                        '{amount}',
                                                        '{percent}',
                                                    ),
                                                    array(
                                                        wc_price( $total_discount ),
                                                        esc_html( $discount_percent ),
                                                    ),
                                                    esc_html__( 'Save {amount}', 'merchant' )
                                                ),
                                                merchant_kses_allowed_tags( array( 'bdi' ) )
                                            );
                                        } ?>
                                    </span>
                                </div>
                                <div class="offer-form">
                                    <?php
                                    if ( $is_variable && ! empty( $attributes ) ) : ?>
                                        <div class="variation-form">
                                            <?php
                                            foreach ( $attributes as $attribute_name => $options ) {
                                                echo '<div class="variations variation-dropdown">';
                                                    wc_dropdown_variation_attribute_options(
                                                        array(
                                                            'options'          => $options,
                                                            'attribute'        => $attribute_name,
                                                            'product'          => $product,
                                                            'required'         => true,
                                                            /* Translators: 1. Attribute name */
                                                            'show_option_none' => sprintf( __( 'Select %s', 'merchant' ), wc_attribute_label( $attribute_name ) ),
                                                        )
                                                    );
                                                echo '</div>';
                                            }
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="user-message"><span class="message-text"></span></div>
                            </div>
                        </label>
                    </div>
                </div>
			<?php
		}
        ++$i ;
	endforeach;
	if ( $i ) {
		?>
        <div class="add-to-cart-wrapper">
            <button type="button" class="merchant-blk-discounts-add-to-cart button alt"><?php esc_html_e( 'Add to cart', 'merchant' ); ?></button>
        </div>
		<?php
	}
    ?>
</div>