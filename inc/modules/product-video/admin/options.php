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

// Shortcode
$merchant_module_id = Merchant_Product_Video::MODULE_ID;
Merchant_Admin_Options::create( array(
	'module' => $merchant_module_id,
	'title'  => esc_html__( 'Use shortcode', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'use_shortcode',
			'type'    => 'switcher',
			'title'   => __( 'Use shortcode', 'merchant' ),
			'default' => 0,
		),
		array(
			'type'    => 'info',
			'id'      => 'shortcode_info',
			'content' => esc_html__( 'If you are using a page builder or a theme that supports shortcodes, then you can output the module using the shortcode above. This might be useful if, for example, you find that you want to control the position of the module output more precisely than with the module settings. Note that the shortcodes can only be used on single product pages.', 'merchant' ),
		),
		array(
			'id'        => 'shortcode_text',
			'type'      => 'text_readonly',
			'title'     => esc_html__( 'Shortcode text', 'merchant' ),
			'default'   => '[merchant_module_' . str_replace( '-', '_', $merchant_module_id ) . ']',
			'condition' => array( 'use_shortcode', '==', '1' ),
		),
	),
) );
