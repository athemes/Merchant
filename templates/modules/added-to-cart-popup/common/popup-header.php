<?php
/**
 * Template for added to cart popup header.
 *
 * @var $args array template args
 *
 * @since 1.9.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$header_title       = Merchant_Admin_Options::get( 'added-to-cart-popup', 'popup_message', esc_html__( 'Added to Cart', 'merchant' ) );
$close_button_color = Merchant_Admin_Options::get( 'added-to-cart-popup', 'close_btn_color', '#000000' );
?>
<div class="popup-header">
    <h3 class="popup-header-title"><?php
		echo esc_html( Merchant_Translator::translate( $header_title ) ); ?>
    </h3>
    <div class="popup-close">
                <span class="close-button popup-close-js" title="<?php
                esc_attr_e( 'Close', 'merchant' ) ?>">
                    <svg width="12" height="11" viewBox="0 0 12 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                                d="M10.333 1.43359L1.73047 10.0361M1.73047 1.43359L10.333 10.0361"
                                stroke="<?php
		                        echo esc_attr( $close_button_color ); ?>"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                        />
                    </svg>
                </span>
    </div>
</div>
