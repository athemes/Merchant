<?php
/**
 * Trust Badges
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Trust Trust
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Trust Badges', 'merchant' ),
	'module' => Merchant_Trust_Badges::MODULE_ID,
	'fields' => array(

		array(
			'id'    => 'badges',
			'type'  => 'gallery',
			'label' => esc_html__( 'Select badges', 'merchant' ),
		),

	),
) );

/**
 * Settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => Merchant_Trust_Badges::MODULE_ID,
	'fields' => array(

		array(
			'id'           => 'align',
			'type'         => 'select',
			'title'        => esc_html__( 'Align logos', 'merchant' ),
			'options'      => array(
				'flex-start' => esc_html__( 'Left', 'merchant' ),
				'center'     => esc_html__( 'Center', 'merchant' ),
				'flex-end'   => esc_html__( 'Right', 'merchant' ),
			),
			'default'      => 'center',
		),

		array(
			'id'    => 'title',
			'type'  => 'text',
			'title' => esc_html__( 'Text above the logos', 'merchant' ),
			'default' => esc_html__( 'Product Quality Guaranteed!', 'merchant' ),
		),

		array(
			'id'      => 'font-size',
			'type'    => 'range',
			'title'   => esc_html__( 'Font size', 'merchant' ),
			'min'     => 1,
			'max'     => 250,
			'step'    => 1,
			'default' => 15,
			'unit'    => 'px',
		),

		array(
			'id'      => 'text-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Text color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'border-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Border color', 'merchant' ),
			'default' => '#e5e5e5',
		),

		array(
			'id'      => 'margin-top',
			'type'    => 'range',
			'title'   => esc_html__( 'Margin top', 'merchant' ),
			'min'     => 1,
			'max'     => 250,
			'step'    => 1,
			'default' => 20,
			'unit'    => 'px',
		),

		array(
			'id'      => 'margin-bottom',
			'type'    => 'range',
			'title'   => esc_html__( 'Margin bottom', 'merchant' ),
			'min'     => 1,
			'max'     => 250,
			'step'    => 1,
			'default' => 20,
			'unit'    => 'px',
		),

		array(
			'id'      => 'image-max-width',
			'type'    => 'range',
			'title'   => esc_html__( 'Image max width', 'merchant' ),
			'min'     => 1,
			'max'     => 250,
			'step'    => 1,
			'default' => 70,
			'unit'    => 'px',
		),

		array(
			'id'      => 'image-max-height',
			'type'    => 'range',
			'title'   => esc_html__( 'Image max height', 'merchant' ),
			'min'     => 1,
			'max'     => 250,
			'step'    => 1,
			'default' => 70,
			'unit'    => 'px',
		),

	),
) );

// Shortcode
$merchant_module_id = Merchant_Trust_Badges::MODULE_ID;
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