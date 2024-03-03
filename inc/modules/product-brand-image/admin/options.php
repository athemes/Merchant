<?php

/**
 * Product Brand Image Options.
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => Merchant_Product_Brand_Image::MODULE_ID,
	'fields' => array(

		array(
			'id'    => 'global-brand-image',
			'type'  => 'upload',
			'title' => esc_html__( 'Upload brand image', 'merchant' ),
			/* Translators: 1. Link open tag 2. Link close tag */
			'desc'  => sprintf( esc_html__( 'Note: If you want to add a different brand image for each product, go to %1$sProducts%2$s, edit the desired product and upload a different image.', 'merchant' ), '<a href="'. esc_url( admin_url( 'edit.php?post_type=product' ) ).'">', '</a>' ),
		),

		array(
			'type' => 'divider',
		),

		array(
			'id'      => 'margin-top',
			'type'    => 'range',
			'title'   => esc_html__( 'Margin top', 'merchant' ),
			'min'     => 0,
			'max'     => 250,
			'step'    => 1,
			'default' => 15,
			'unit'    => 'px',
		),

		array(
			'id'      => 'margin-bottom',
			'type'    => 'range',
			'title'   => esc_html__( 'Margin bottom', 'merchant' ),
			'min'     => 0,
			'max'     => 250,
			'step'    => 1,
			'default' => 15,
			'unit'    => 'px',
		),

		array(
			'id'      => 'image-max-width',
			'type'    => 'range',
			'title'   => esc_html__( 'Image max width', 'merchant' ),
			'min'     => 1,
			'max'     => 500,
			'step'    => 1,
			'default' => 250,
			'unit'    => 'px',
		),

		array(
			'id'      => 'image-max-height',
			'type'    => 'range',
			'title'   => esc_html__( 'Image max height', 'merchant' ),
			'min'     => 1,
			'max'     => 500,
			'step'    => 1,
			'default' => 250,
			'unit'    => 'px',
		),

	),
) );

// Shortcode
$merchant_module_id = Merchant_Product_Brand_Image::MODULE_ID;
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
			'type'    => 'warning',
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