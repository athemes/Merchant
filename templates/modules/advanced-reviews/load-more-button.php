<?php

/**
 * Template for advanced reviews load more button.
 * 
 * $args module settings.
 * 
 * @since 1.0
 */

global $product;

$total_pages = count( get_comments( array(
	'post_id' => $product->get_id(),
	'fields'  => 'ids',
	'status'  => 'approve',
) ) );

$total_pages = ceil( $total_pages / get_option( 'comments_per_page' ) );

$single_product_reviews_advanced_pagination_type = get_theme_mod( 'single_product_reviews_advanced_pagination_type', 'default' ); 

if ( $total_pages <= 1 ) {
	return;
}
?>
<div class="merchant-pagination-wrapper">
	<a href="javascript:void(0);" class="merchant-pagination-button<?php echo ( 'infinite-scroll' === $single_product_reviews_advanced_pagination_type ) ? ' loading-anim' : ''; ?>" role="button" data-current-page="1" data-total-pages="<?php echo esc_attr( $total_pages ); ?>" data-pagination-type="<?php echo esc_attr( $single_product_reviews_advanced_pagination_type ); ?>">
		<span class="merchant-pagination-button__label">
			<?php echo esc_html__( 'Load More', 'merchant' ); ?>
		</span>
		<span class="merchant-pagination-button__loader">
			<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 512 512" aria-hidden="true" focusable="false">
				<path fill="#FFF" d="M288 39.056v16.659c0 10.804 7.281 20.159 17.686 23.066C383.204 100.434 440 171.518 440 256c0 101.689-82.295 184-184 184-101.689 0-184-82.295-184-184 0-84.47 56.786-155.564 134.312-177.219C216.719 75.874 224 66.517 224 55.712V39.064c0-15.709-14.834-27.153-30.046-23.234C86.603 43.482 7.394 141.206 8.003 257.332c.72 137.052 111.477 246.956 248.531 246.667C393.255 503.711 504 392.788 504 256c0-115.633-79.14-212.779-186.211-240.236C302.678 11.889 288 23.456 288 39.056z" />
			</svg>
		</span>
	</a>
</div>
