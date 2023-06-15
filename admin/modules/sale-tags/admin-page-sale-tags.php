<?php
/**
 * Merchant - Sale Tags
 */

/**
 * General Settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'General Settings', 'merchant' ),
	'module' => 'sale-tags',
	'fields' => array(

		array(
			'id'      => 'badge_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Badge Text', 'merchant' ),
			'default' => esc_html__( 'Sale!', 'merchant' ),
		),

		array(
			'id'    => 'display_percentage',
			'type'  => 'checkbox',
			'label' => esc_html__( 'Display Sale Percentage', 'merchant' ),
		),

		array(
			'id'        => 'percentage_text',
			'type'      => 'text',
			'title'     => 'Sale Percentage Text',
			'desc'      => esc_html__( 'You may use the {value} tag. E.g. <strong>{value}% OFF!</strong>', 'merchant' ),
			'default'   => '-{value}%',
			'condition' => array( 'display_percentage', '==', '1' ),
		),

		array(
			'id'      => 'badge_position',
			'type'    => 'select',
			'title'   => esc_html__( 'Position', 'merchant' ),
			'options' => array(
				'left'  => esc_html__( 'Left', 'merchant' ),
				'right' => esc_html__( 'Right', 'merchant' ),
			),
			'default' => 'left',
		),

		array(
			'id'      => 'top-offset',
			'type'    => 'range',
			'title'   => esc_html__( 'Top Offset', 'merchant' ),
			'min'     => 0,
			'max'     => 250,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 10,
		),

		array(
			'id'      => 'side-offset',
			'type'    => 'range',
			'title'   => esc_html__( 'Side Offset', 'merchant' ),
			'min'     => 0,
			'max'     => 250,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 10,
		),

		array(
			'id'      => 'border-radius',
			'type'    => 'range',
			'title'   => esc_html__( 'Border Radius', 'merchant' ),
			'min'     => 0,
			'max'     => 250,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 0,
		),

		array(
			'id'      => 'font-size',
			'type'    => 'range',
			'title'   => esc_html__( 'Font Size', 'merchant' ),
			'min'     => 0,
			'max'     => 250,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 14,
		),

		array(
			'id'      => 'tb-spacing',
			'type'    => 'range',
			'title'   => esc_html__( 'Top/Bottom Spacing', 'merchant' ),
			'min'     => 0,
			'max'     => 250,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 5,
		),

		array(
			'id'      => 'lr-spacing',
			'type'    => 'range',
			'title'   => esc_html__( 'Left/Right Spacing', 'merchant' ),
			'min'     => 0,
			'max'     => 250,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 20,
		),

	),
) );

/**
 * Design Settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Design Settings', 'merchant' ),
	'module' => 'sale-tags',
	'fields' => array(

		array(
			'id'      => 'background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Badge Background Color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Badge Text Color', 'merchant' ),
			'default' => '#ffffff',
		),

	),
) );
