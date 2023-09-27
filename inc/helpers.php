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
if ( ! function_exists( 'merchant_kses_allowed_tags' ) ) {
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
					'class'       	  => true,
					'xmlns'       	  => true,
					'width'       	  => true,
					'height'      	  => true,
					'viewbox'     	  => true,
					'aria-hidden' 	  => true,
					'role'        	  => true,
					'focusable'   	  => true,
					'fill'		  	  => true,
					'stroke'	  	  => true,
					'stroke-linecap'  => true,
					'stroke-linejoin' => true,
					'stroke-width' 	  => true
				),
				'g'		  => array(
					'id'		=> true,
					'class'		=> true,
					'clip-path' => true,
					'style'		=> true
				),
				'path'    => array(
					'fill'      => true,
					'fill-rule' => true,
					'd'         => true,
					'transform' => true,
					'stroke'	=> true,
					'stroke-width' => true,
					'stroke-linejoin' => true,
					'clip-rule' => true
				),
				'polyline'    => array(
					'points'    => true,
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
					'stroke'	=> true,
					'stroke-width' => true
				),
				'rect'    => array(
					'x'      => true,
					'y'      => true,
					'rx' 	 => true,
					'width'  => true,
					'height' => true,
					'transform' => true,
					'fill'		=> true,
					'stroke'	=> true,
					'stroke-width' => true
				),
				'circle'    => array(
					'cx'      => true,
					'cy'      => true,
					'r' 	 => true,
					'width'  => true,
					'height' => true,
					'transform' => true,
					'fill'		=> true,
					'stroke'	=> true,
					'stroke-width' => true
				),
				'clipPath'	 => array(
					'id' 	=> true,
					'class' => true,
					'style' => true
				),
				'defs'	 => array(
					'id' 	=> true
				),
			),
			$include_post_tags ? wp_kses_allowed_html( 'post' ) : array() 
		);

		// Include schema markup tags
		if ( in_array( array( 'all', 'schema_markup' ), $extra ) ) {
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
		if ( in_array( array( 'all', 'iframe' ), $extra ) ) {
			$allowed_tags[ 'iframe' ] = array(
				'src'             => true,
				'height'          => true,
				'width'           => true,
				'frameborder'     => true,
				'allowfullscreen' => true
			);
		}

		// Include nonce tags
		if ( in_array( array( 'all', 'nonce' ), $extra ) ) {
			$allowed_tags[ 'input' ] = array(
				'type'  => true,
				'id'    => true,
				'name'  => true,
				'value' => true,
			);
		}

		// Include bdi tag
		if ( in_array( array( 'all', 'bdi' ), $extra ) ) {
			$allowed_tags[ 'bdi' ] = array(
				'class' => true,
				'id'    => true,
				'style' => true
			);
		}

		// Include dd, dt tags
		if ( in_array( array( 'all', 'bdi' ), $extra ) ) {
			$allowed_tags[ 'dd' ] = array(
				'class' => true,
				'id'    => true,
				'style' => true
			);
		}

		// Include forms tags.
		if ( in_array( array( 'all', 'forms' ), $extra ) ) {
			$tags = array( 'form', 'input', 'textarea' );

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
					'onchange' => true,
					'autocomplete' => true,
					'required' => true,
					'action' => true,
					'method' => true
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
}
