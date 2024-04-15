<?php
/**
 * Template for added to cart popup recently viewed products content.
 *
 * @var $args array template args
 *
 * @since 1.9.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( empty( $args['product_offers'] ) ) {
	return;
}
foreach ( $args['product_offers'] as $offer ) {
	?>
    <div class="buy-x-get-y">
        <h3 class="section-title"><?php
			echo esc_html( Merchant_Translator::translate( $offer['title'] ) ); ?></h3>
    </div>
	<?php
}
//echo '<pre>';
//print_r( $args['popup_data'] );
//echo '</pre>';
echo '<pre>';
print_r( $args['product_offers'] );
echo '</pre>';
