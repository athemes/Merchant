<?php
/**
 * Template for buy x get y module content on single product.
 *
 * @var $args array template args
 *
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Prevent older merchant pro versions from breaking.
if ( ! method_exists( 'Merchant_Pro_Buy_X_Get_Y', 'product_args' ) && ! is_admin() ) {
	if ( current_user_can( 'manage_options' ) ) {
		echo '<div class="error"><p>' . esc_html__( 'Please update Merchant Pro plugin to the latest version to use this feature..', 'merchant' ) . '</p></div>';
	}

	return;
}

$settings     = $args['settings'] ?? array();
$main_product = isset( $args['product'] ) ? wc_get_product( $args['product'] ) : wc_get_product();

if ( empty( $main_product ) ) {
    return;
}

$is_main_product_in_stock = false;
$main_product_type        = $main_product->get_type();

if ( $main_product_type === 'simple' ) {
	$is_main_product_in_stock = $main_product->is_in_stock();
} elseif ( $main_product_type === 'variable' ) {
	$available_variations = $main_product->get_available_variations();
    foreach ( $available_variations as $variation ) {
	    if ( ! empty( $variation['is_in_stock'] ) ) {
            $is_main_product_in_stock = true;
            break;
        }
    }
}

if ( ! is_admin() && ! $is_main_product_in_stock ) {
	return;
}
?>
<div class="merchant-bogo">
    <div class="merchant-bogo-offers" data-nonce="<?php echo isset( $args['nonce'] ) ? esc_attr( $args['nonce'] ) : '' ?>" data-cart-url="<?php echo esc_url( wc_get_cart_url() ); ?>">
		<?php
		foreach ( $args['offers'] as $key => $offer ) :
            if ( isset( $offer['offer_product'] ) ) {
                $product = wc_get_product( $offer['offer_product'] );
            } else {
                $product = $main_product;
            }
            $buy_product = wc_get_product( $offer['customer_get_product_ids'] );
            if ( $product && $buy_product ) {
                $is_purchasable     = $product->is_purchasable();
                $product_id         = $product->get_id();
                $product_type       = $product->get_type();
                $product_image      = $product->get_image( 'woocommerce_gallery_thumbnail' );
                $product_permalink  = $product->get_permalink();
                $product_title      = $product->get_title();
                $product_price_html = $product->get_price_html();
                $product_price      = ! empty( $product->get_price() ) ? $product->get_price() : 0;
            } else {
                continue;
            }

		    $is_in_stock = ( $buy_product->is_type( 'simple' ) || $buy_product->is_type( 'variation' ) ) ? $buy_product->is_in_stock() : true; // For variable products it'll be handled by JS when a variable will be selected
		    ?>
            <p class="merchant-bogo-title" data-flexible-id="<?php echo ! empty( $offer['flexible_id'] ) ? esc_attr( $offer['flexible_id'] ) : '' ?>" style="<?php
            echo isset( $offer['product_single_page']['title_font_weight'] ) ? esc_attr( 'font-weight: ' . $offer['product_single_page']['title_font_weight'] . ';' ) : '';
            echo isset( $offer['product_single_page']['title_font_size'] ) ? esc_attr( 'font-size: ' . $offer['product_single_page']['title_font_size'] . 'px;' ) : '';
            echo isset( $offer['product_single_page']['title_text_color'] ) ? esc_attr( 'color: ' . $offer['product_single_page']['title_text_color'] . ';' ) : ''; ?>">
                <?php echo isset( $offer['product_single_page']['title'] ) ? esc_html( Merchant_Translator::translate( $offer['product_single_page']['title'] ) ) : esc_html__( 'Buy One Get One', 'merchant' ); ?>
            </p>
            <?php
            if ( ! $is_purchasable ) {
                continue;
            }
            ?>
        <div
            class="merchant-bogo-offer" data-product="<?php echo esc_attr( $product_id ) ?>"
            data-offer="<?php echo esc_attr( $key ); ?>"
            data-flexible-id="<?php echo ! empty( $offer['flexible_id'] ) ? esc_attr( $offer['flexible_id'] ) : '' ?>">
            <div class="merchant-bogo-product-x is-<?php echo esc_attr( $product_type ); ?>">
                <div class="merchant-bogo-product-label merchant-bogo-product-buy-label" style="<?php
                echo isset( $offer['product_single_page']['label_bg_color'] ) ? esc_attr( 'background-color: ' . $offer['product_single_page']['label_bg_color'] . ';' ) : '';
                echo isset( $offer['product_single_page']['label_text_color'] ) ? esc_attr( 'color: ' . $offer['product_single_page']['label_text_color'] . ';' ) : ''; ?>">
                    <?php
                    echo isset( $offer['product_single_page']['buy_label'] )
                        ? esc_html( str_replace( '{quantity}', $offer['min_quantity'], Merchant_Translator::translate( $offer['product_single_page']['buy_label'] ) ) )
                        : esc_html(
                        /* Translators: 1. quantity */
                            sprintf( __( 'Buy %s', 'merchant' ), $offer['min_quantity'] )
                        ); ?>
                </div>
                <div class="merchant-bogo-product">
                    <?php echo wp_kses_post( $product_image ); ?>
                    <div class="merchant-bogo-product-contents">
                        <p class="woocommerce-loop-product__title">
                            <a href="<?php echo esc_url( $product_permalink ); ?>" target="_blank">
                                <?php echo esc_html( $product_title ); ?>
                            </a>
                        </p>
                        <?php echo wp_kses( $product_price_html, merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?>
                    </div>
                </div>
                <div class="merchant-bogo-arrow" style="<?php
                echo isset( $offer['product_single_page']['arrow_bg_color'] ) ? esc_attr( 'background-color: ' . $offer['product_single_page']['arrow_bg_color'] . ';' ) : '';
                echo isset( $offer['product_single_page']['arrow_text_color'] ) ? esc_attr( 'color: ' . $offer['product_single_page']['arrow_text_color'] . ';' ) : ''; ?>">→
                </div>
            </div>
            <div
                class="merchant-bogo-product-y is-<?php echo esc_attr( $product_type ); ?>"
                style="<?php
                    echo isset( $offer['product_single_page']['offer_border_color'] ) ? esc_attr( 'border-color: ' . $offer['product_single_page']['offer_border_color'] . ';' ) : '';
                    echo isset( $offer['product_single_page']['offer_border_radius'] ) ? esc_attr( 'border-radius: ' . $offer['product_single_page']['offer_border_radius'] . 'px;' ) : '';
                ?>">
                <form class="merchant-bogo-form" data-product="<?php echo esc_attr( $buy_product->get_id() ); ?>">
                    <div class="merchant-bogo-product-label merchant-bogo-product-get-label" style="<?php
                    echo isset( $offer['product_single_page']['label_bg_color'] ) ? esc_attr( 'background-color: ' . $offer['product_single_page']['label_bg_color'] . ';' ) : '';
                    echo isset( $offer['product_single_page']['label_text_color'] ) ? esc_attr( 'color: ' . $offer['product_single_page']['label_text_color'] . ';' ) : ''; ?>">
                        <?php
                        if($offer['discount_type'] === 'shipping'){
	                        $discount = 'free shipping';
                        } else {
	                        $discount = $offer['discount_type'] === 'percentage' ? $offer['discount'] . '%' : wc_price( $offer['discount'] );
                        }

                        echo isset( $offer['product_single_page']['get_label'] )
	                        ? wp_kses( str_replace(
		                        array(
			                        '{quantity}',
			                        '{discount}',
		                        ),
		                        array(
			                        $offer['quantity'],
			                        $discount,
		                        ),
		                        Merchant_Translator::translate( $offer['product_single_page']['get_label'] )
	                        ), merchant_kses_allowed_tags( array( 'bdi' ) ) )
	                        : wp_kses(
	                        /* Translators: 1. quantity 2. discount value*/
		                        sprintf( __( 'Get %1$s with %2$s off', 'merchant' ), $offer['quantity'], $discount ),
		                        merchant_kses_allowed_tags( array( 'bdi' ) )
	                        );
                        ?>
                    </div>
                    <?php
                    if ( $buy_product ) :
                        ?>
                        <div class="merchant-bogo-product">
                            <?php echo wp_kses_post( $buy_product->get_image( 'woocommerce_gallery_thumbnail' ) ); ?>
                            <div class="merchant-bogo-product-contents">
                                <p class="woocommerce-loop-product__title">
                                    <a href="<?php echo esc_url( $buy_product->get_permalink() ); ?>" target="_blank">
                                        <?php echo esc_html( $buy_product->get_name() ); ?>
                                    </a>
                                </p>
                                <div class="merchant-bogo-product-price">
                                    <?php
                                    $discount_target = $offer['discount_target'] ?? 'sale';

                                    if ( $buy_product->is_type( 'variable' ) ) {
	                                    $regular_price = (float) $buy_product->get_variation_regular_price( 'min' );
	                                    $sale_price    = (float) $buy_product->get_variation_sale_price( 'min' );
                                    } else {
	                                    $regular_price = (float) $buy_product->get_regular_price();
	                                    $sale_price    = (float) $buy_product->get_sale_price();
                                    }

                                    $product_price = ( $discount_target === 'regular' || empty( $sale_price ) ) ? $regular_price : $sale_price;

                                    // Need to apply storewide sale discount for variation product.
                                    if ( $discount_target === 'sale' && $buy_product->is_type( 'variation' ) ) {
	                                    /**
	                                     * `merchant_storewide_sale_variation_sale_price`
	                                     *
	                                     * This filter is used to get the sale price of the product
	                                     * when a discount is applied through the Storewide Sale module.
	                                     *
	                                     * @since 2.0.3
	                                     */
	                                    $product_price = apply_filters( 'merchant_storewide_sale_variation_sale_price', $product_price, $buy_product, $args['module_id'] ?? '', 'template' );
                                    }

                                    if ( $is_in_stock ) {
	                                    if ( $offer['discount_type'] === 'shipping' ) {
		                                    echo wp_kses( $buy_product->get_price_html(), merchant_kses_allowed_tags( array( 'bdi' ) ) );
	                                    } else {
		                                    if ( $offer['discount_type'] === 'percentage' ) {
			                                    $buy_product_reduced_price = $product_price - ( $product_price * $offer['discount'] / 100 );
		                                    } else {
			                                    $buy_product_reduced_price = $product_price - ( $offer['discount'] / $offer['quantity'] );
		                                    }
		                                    echo wp_kses( wc_format_sale_price( $product_price, $buy_product_reduced_price ), merchant_kses_allowed_tags( array( 'bdi' ) ) );
	                                    }
                                    } else {
	                                    echo '<span class="error">' . esc_html__( 'Out of stock', 'merchant' ) . '</span>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        if ( $buy_product->is_type( 'variable' ) ) : ?>
                            <div class="merchant-bogo-product-attributes" data-nonce="<?php echo esc_attr( wp_create_nonce( 'mrc_get_variation_data_nonce' ) ); ?>">
	                            <?php
	                            $attributes = $buy_product->get_variation_attributes();
	                            foreach ( $attributes as $attribute_name => $options ) {
		                            echo '<div class="variations variation-dropdown">';
                                        wc_dropdown_variation_attribute_options(
                                            array(
                                                'options'          => $options,
                                                'attribute'        => $attribute_name,
                                                'product'          => $buy_product,
                                                'required'         => true,
                                                'class'            => 'merchant-bogo-select-attribute',
                                                /* Translators: 1. Attribute name */
                                                'show_option_none' => sprintf( __( 'Select %s', 'merchant' ), wc_attribute_label( $attribute_name ) ),
                                            )
                                        );
		                            echo '</div>';
	                            }
	                            ?>
                            </div>
                        <?php endif; ?>
                        <button type="submit" name="merchant-bogo-add-to-cart" value="97" class="button alt wp-element-button merchant-bogo-add-to-cart" <?php if ( ! $is_in_stock ) : ?>
                            disabled="disabled"
                        <?php endif; ?>>
                            <?php
                            echo isset( $offer['product_single_page']['button_text'] )
                                ? esc_html( Merchant_Translator::translate( $offer['product_single_page']['button_text'] ) )
                                : esc_html__( 'Add To Cart', 'merchant' ); ?>
                        </button>
                        <div class="merchant-bogo-offer-error"></div>
                    <?php endif; ?>
                </form>
        </div>
    </div>
    <?php endforeach; ?>
    </div>
</div>
