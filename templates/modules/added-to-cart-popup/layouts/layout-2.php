<?php
/**
 * Template for added to cart popup layout 2.
 *
 * @var $args array template args
 *
 * @since 1.9.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="popup layout-2">
    <div class="popup-header">
        <h3 class="popup-header-title"><?php
			echo ! empty( $args['settings']['popup_message'] )
				? esc_html( Merchant_Translator::translate( $args['settings']['popup_message'] ) )
				: esc_html__( 'Added to Cart', 'merchant' ); ?>
        </h3>
        <div class="popup-close">
                <span class="close-button popup-close-js" title="<?php
                esc_attr_e( 'Close', 'merchant' ) ?>">
                    <svg width="12" height="11" viewBox="0 0 12 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                                d="M10.333 1.43359L1.73047 10.0361M1.73047 1.43359L10.333 10.0361"
                                stroke="black"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                        />
                    </svg>
                </span>
        </div>
    </div>
    <div class="popup-body">
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
        <div class="merchant-hide-computer">
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
		<?php
		if (
			isset(
				$args['settings']['show_suggested_products'],
				$args['settings']['suggested_products_module']
			)
			&& $args['settings']['suggested_products_module'] === 'recently_viewed_products'
			&& $args['settings']['show_suggested_products']
			&& ! empty( $args['recently_viewed_products'] )
		) {
			/**
			 * Recently viewed products module.
			 *
			 * @param array $args template args
			 *
			 * @since 1.9.7
			 */
			do_action( 'merchant_added_to_cart_popup_recently_viewed_products', $args );
		}
		?>
        <div class="merchant-hide-mobile">
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
</div>
