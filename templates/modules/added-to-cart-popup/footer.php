<?php
/**
 * Template for added to cart popup footer.
 *
 * @var $args array template args
 *
 * @since 1.9.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
</div>
<div class="merchant-hidden-popup-structure">
	<?php
	/**
	 * Filter the allowed added to cart popup layouts.
	 *
	 * @param array $allowed_layouts The allowed layouts.
	 *
	 * @since 1.9.6
	 */
	$allowed_layouts = apply_filters( 'merchant_added_to_cart_popup_allowed_layouts', array( 'layout-1', 'layout-2', 'layout-3' ) );
	$layout          = 'layout-1';
    $settings = $args['settings'];
	if ( isset( $settings['layout'] ) && in_array( $settings['layout'], $allowed_layouts, true ) ) {
		$layout = $settings['layout'];
	}
	merchant_get_template_part(
		'modules/added-to-cart-popup/layouts',
		/**
		 * Filter the added to cart popup layout.
		 *
		 * @param string $layout The layout name.
		 *
		 * @return string The layout name.
		 *
		 * @since 1.9.6
		 */
		apply_filters( 'merchant_added_to_cart_popup_layout', $layout ),
		$args
	);
	?>
</div>
</div>