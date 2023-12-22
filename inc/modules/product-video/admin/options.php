<?php

/**
 * Product Video Options.
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'product-video',
	'fields' => array(

		// Autoplay.
		array(
			'id'      => 'autoplay',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Autoplay', 'merchant' ),
			'desc'    => esc_html__( 'Autoplay may cause usability issues for some users.', 'merchant' ),
			'default' => false,
		),

		// Aspect ratio.
		array(
			'id'      => 'aspect_ratio',
			'type'    => 'select',
			'title'   => esc_html__( 'Aspect ratio', 'merchant' ),
			'desc'    => esc_html__( 'The aspect ratio definition is the proportional relationship between the width and height of a video.', 'merchant' ),
			'options' => array(
				'16-9'  => '16:9',
				'9-16'  => '9:16',
				'4-3'   => '4:3',
				'3-2'   => '3:2',
				'1-1'   => '1:1',
				'auto'  => 'auto',
			),
			'default' => '16-9',
		),

	),
) );
