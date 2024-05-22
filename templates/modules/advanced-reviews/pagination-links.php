<?php

/**
 * Template for advanced reviews pagination links.
 * 
 * @var $args array template args
 * 
 * @since 1.0
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_rewrite;

$current_page = $args['cpage'] ?? 1;
$total_pages  = ceil( $args[ 'total' ] ?? 1 );

$pagination_args = array(
	'base'         => add_query_arg( 'cpage', '%#%' ),
	'format'       => '',
	'current'      => $current_page,
	'total'        => $total_pages,
	'echo'         => true,
	'type'         => 'list',
	'prev_text'    => is_rtl() ? '&rarr;' : '&larr;',
	'next_text'    => is_rtl() ? '&larr;' : '&rarr;',
	'add_fragment' => '#comments',
);

/**
 * Hook: `merchant_adv_pagination_args`
 *
 * @since 2.0.0
 */
$pagination_args = apply_filters( 'merchant_adv_pagination_args', $pagination_args );

if ( $wp_rewrite->using_permalinks() ) {
	$defaults['base'] = user_trailingslashit( trailingslashit( get_permalink( $args[ 'product_id' ] ) ) . $wp_rewrite->comments_pagination_base . '-%#%', 'commentpaged' );
}
?>
<nav class="woocommerce-pagination merchant-pagination merchant-adv-reviews-pagination" data-current-page="<?php echo esc_attr( $current_page ); ?>" data-total-pages="<?php echo esc_attr( $total_pages ); ?>">
	<?php echo wp_kses_post( paginate_links( $pagination_args ) ); ?>
</nav>
