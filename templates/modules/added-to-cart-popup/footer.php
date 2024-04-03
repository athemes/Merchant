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
	merchant_get_template_part(
		'modules/added-to-cart-popup/layouts',
		'layout-1',
		$args
	);
	?>
</div>