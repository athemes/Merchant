<?php

/**
 * Template for sticky add to cart module content.
 * 
 * @var array $args module settings.
 * 
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

// Settings.
$settings = $args['settings'];

// Wrapper attributes.
$attributes = array();

// Wrapper class.
$classes    = array( 'merchant-sticky-add-to-cart-wrapper' );

// Position.
$classes[] = 'position-bottom' === $settings[ 'position' ] ? 'position-bottom' : 'position-top';

// Display after amount of scroll.
//$attributes[] = 'data-merchant-scroll-toggle-class="merchant-sticky-addtocart-active"';
$attributes[] = 'data-merchant-scroll-toggle-class-offset="' . $settings[ 'display_after_amount' ] . '"';

// Hide when scroll.
if ( $settings[ 'scroll_hide' ] ) {
	$classes[] = 'hide-when-scroll';
}

// Hide Product Image.
if( $settings[ 'hide_product_image' ] ) {
	$classes[] = 'hide-product-image';
}

// Hide Product Title.
if( $settings[ 'hide_product_title' ] ) {
	$classes[] = 'hide-product-title';
}

// Hide Product Price.
if( $settings[ 'hide_product_price' ] ) {
	$classes[] = 'hide-product-price';
}

// Visibility.
if ( 'desktop' === $settings[ 'visibility' ] ) {
	$classes[] = 'visible-desktop-only';
} elseif ( 'mobile' === $settings[ 'visibility' ] ) {
	$classes[] = 'visible-mobile-only';
}

// Mount class attribute.
if ( ! empty( $classes ) ) {
	$attributes[] = 'class="' . implode( ' ', $classes ) . '"';
}

// Ensure the attribute class will always be the first.
$attributes = array_reverse( $attributes );
?>

<div <?php echo wp_kses( implode( ' ', $attributes ), array() ); ?>>
	<div class="merchant-sticky-add-to-cart-wrapper-content">
		
		<?php 
		/**
		 * Hook 'merchant_sticky_add_to_cart_elements_order'
		 * 
		 * @since 1.0
		 */
		$elements_order = apply_filters( 'merchant_sticky_add_to_cart_elements_order', array( 'product_image', 'product_title', 'product_price', 'add_to_cart' ) );

		foreach ( $elements_order as $element ) {
			$class = '';
			switch ( $element ) {
				case 'product_image':
					$class = 'product-image';
					break;
				
				case 'product_title':
					$class = 'product-title';
					break;

				case 'product_price':
					$class = 'product-price';
					break;

				case 'add_to_cart':
					$class = 'product-addtocart';
					break;
			}

			echo '<div class="merchant-sticky-add-to-cart-item ' . esc_attr( $class ) . '">';
            echo wp_kses( $args['elements'][$element], merchant_kses_allowed_tags( array( 'bdi', 'forms' ) ) );
			echo '</div>';
		} 
		?>
	</div>
</div>
