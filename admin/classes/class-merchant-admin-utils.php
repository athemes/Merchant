<?php
/**
 * Merchant Admin Utils Functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * SVG - wp_kses.
 * Allows to svg in wp_kses.
 */
if ( ! function_exists( 'merchant_get_svg_args' ) ) {
	function merchant_get_svg_args() {

		return array(
			'svg'       => array(
				'fill'    => true,
				'class'   => true,
				'xmlns'   => true,
				'width'   => true,
				'height'  => true,
				'viewbox' => true,
			),
			'rect'      => array(
				'fill'    => true,
				'width'   => true,
				'height'  => true,
				'rx'      => true,
			),
			'title'     => array(
				'fill'    => true,
				'title'   => true,
			),
			'path'      => array(
				'fill'    => true,
				'd'       => true,
			),
		);
	}
}
