<?php

/**
 * Template for advanced reviews pagination links.
 * 
 * $args module settings.
 * 
 * @since 1.0
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wp_rewrite;

if ( ! is_singular() ) {
	return;
}

$_page = get_query_var( 'cpage' );
if ( ! $_page ) {
	$_page = 1;
}

$max_page = ceil( $args[ 'cpages' ] );
$defaults = array(
	'base'       => add_query_arg( 'cpage', '%#%' ),
	'format'       => '',
	'total'     => $max_page,
	'current'     => $_page,
	'echo'       => true,
	'type'       => 'plain',
	'add_fragment' => '#comments',
);
if ( $wp_rewrite->using_permalinks() ) {
	$defaults['base'] = user_trailingslashit( trailingslashit( get_permalink( $args[ 'product_id' ] ) ) . $wp_rewrite->comments_pagination_base . '-%#%', 'commentpaged' );
}

$pagination_args = wp_parse_args( $args[ 'pagination_args' ], $defaults );
$page_links      = paginate_links( $pagination_args );

echo wp_kses_post( $page_links );
