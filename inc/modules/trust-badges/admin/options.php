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
	'module' => 'trust-badges',
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
	'module' => 'trust-badges',
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
			'default' => esc_html__( 'Product Quality Guaranteed!', 'merchant' )
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
