<?php
/**
 * Template for added to cart popup layout 3.
 *
 * @var $args array template args
 *
 * @since 1.9.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="popup layout-3">
	<?php
    /**
     * Popup header.
     *
     * @param array $args template args
     *
     * @since 1.9.7
     */
	do_action( 'merchant_added_to_cart_popup_header', $args );
    ?>
    <div class="popup-body">
        <div class="top-area">
            <div class="product-component">
				<?php
				/**
				 * Product info.
				 *
				 * @param array $args template args
				 *
				 * @since 1.9.7
				 */
				do_action( 'merchant_added_to_cart_popup_product_info', $args );
				?>
            </div>
            <div class="actions-component">
				<?php
				/**
				 * Product info.
				 *
				 * @param array $args template args
				 *
				 * @since 1.9.7
				 */
				do_action( 'merchant_added_to_cart_popup_action_buttons', $args );
				?>
            </div>
        </div>
		<?php
		/**
		 * Modules content.
		 *
		 * @param array $args template args
		 *
		 * @since 1.9.7
		 */
		do_action( 'merchant_added_to_cart_popup_modules_content', $args );
		?>
    </div>
</div>
