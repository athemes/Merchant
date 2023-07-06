<?php

/**
 * Helper functions.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Allowed tags general.
 * Should be used to escape complex outputs like entire features html.
 * 
 */
function merchant_kses_allowed_tags( $extra = array(), $include_post_tags = true ) {

	// Default
	$allowed_tags = array_merge(
		array(
			// Meta
			'meta'   => array(
				'name' 	   => true,
				'content'  => true
			),

			// SVG Support
			'svg'     => array(
				'class'       => true,
				'xmlns'       => true,
				'width'       => true,
				'height'      => true,
				'viewbox'     => true,
				'aria-hidden' => true,
				'role'        => true,
				'focusable'   => true,
				'fill'      => true,
			),
			'path'    => array(
				'fill'      => true,
				'fill-rule' => true,
				'd'         => true,
				'transform' => true,
				'stroke'	=> true,
				'stroke-width' => true,
				'stroke-linejoin' => true
			),
			'polygon' => array(
				'fill'      => true,
				'fill-rule' => true,
				'points'    => true,
				'transform' => true,
				'focusable' => true,
			),
			'rect'    => array(
				'x'      => true,
				'y'      => true,
				'width'  => true,
				'height' => true,
				'transform' => true
			)			
		),
		$include_post_tags ? wp_kses_allowed_html( 'post' ) : array() 
	);

	// Include schema markup tags
	if ( in_array( 'schema_markup', $extra ) ) {
		$tags = array( 'meta', 'nav', 'ul', 'li', 'a', 'span' );

		foreach ( $tags as $tag ) {
			if ( isset( $allowed_tags[ $tag ] ) ) {
				$allowed_tags[ $tag ][ 'itemprop' ]  = true;
				$allowed_tags[ $tag ][ 'itemscope' ] = true;
				$allowed_tags[ $tag ][ 'itemtype' ]  = true;
			} else {
				$allowed_tags[ $tag ] = array(
					'itemprop'  => true,
					'itemscope' => true,
					'itemtype'  => true
				);
			}
		}
	}

	// Include iframe tags
	if ( in_array( 'iframe', $extra ) ) {
		$allowed_tags[ 'iframe' ] = array(
			'src'             => true,
			'height'          => true,
			'width'           => true,
			'frameborder'     => true,
			'allowfullscreen' => true
		);
	}

	// Include nonce tags
	if ( in_array( 'nonce', $extra ) ) {
		$allowed_tags[ 'input' ] = array(
			'type'  => true,
			'id'    => true,
			'name'  => true,
			'value' => true,
		);
	}

	// Include bdi tag
	if ( in_array( 'bdi', $extra ) ) {
		$allowed_tags[ 'bdi' ] = array(
			'class' => true,
			'id'    => true,
			'style' => true
		);
	}

	// Include forms tags.
	if ( in_array( 'forms', $extra ) ) {
		$tags = array( 'input', 'textarea' );

		foreach ( $tags as $tag ) {
			$allowed_tags[ $tag ] = array(
				'id'    => true,
				'class' => true,
				'style' => true,
				'name'  => true,
				'value' => true,
				'type'  => true,
				'placeholder' => true,
				'data'  => '*',
				'step'  => true,
				'min'   => true,
				'max'   => true,
				'selected' => true,
				'checked' => true,
				'onchange' => true
			);
		}
	}

	/**
	 * Filters the allowed tags.
	 *  
	 * @since 1.2.5
	 */
	return apply_filters( 'merchant_kses_allowed_tags', $allowed_tags );
}
