<?php
/**
 * Merchant - Product Labels
 */

/**
 * Settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'product-labels',
	'fields' => array(

		array(
			'id'      => 'label_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Label Text', 'merchant' ),
			'default' => esc_html__( 'Spring Special', 'merchant' ),
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
			'id'      => 'label_position',
			'type'    => 'select',
			'title'   => esc_html__( 'Position', 'merchant' ),
			'options' => array(
				'top-left'  => esc_html__( 'Top Left', 'merchant' ),
				'top-right' => esc_html__( 'Top Right', 'merchant' ),
			),
			'default' => 'left',
		),

		array(
			'id'      => 'label_shape',
			'type'    => 'select',
			'title'   => esc_html__( 'Shape', 'merchant' ),
			'options' => array(
				'square'  => esc_html__( 'Square', 'merchant' ),
				'rounded' => esc_html__( 'Rounded', 'merchant' ),
			),
			'default' => 'square',
		),

		array(
			'id'      => 'label_text_transform',
			'type'    => 'select',
			'title'   => esc_html__( 'Letter Case', 'merchant' ),
			'options' => array(
				'uppercase'  => esc_html__( 'Uppercase', 'merchant' ),
				'lowercase'  => esc_html__( 'Lowercase', 'merchant' ),
				'capitalize' => esc_html__( 'Capitalize', 'merchant' ),
				'none'       => esc_html__( 'None', 'merchant' ),
			),
			'default' => 'uppercase',
		),

		array(
			'id'      => 'padding',
			'type'    => 'range',
			'title'   => esc_html__( 'Padding', 'merchant' ),
			'min'     => 0,
			'max'     => 250,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 8,
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
			'id'      => 'background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Background Color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Text Color', 'merchant' ),
			'default' => '#ffffff',
		),

	),
) );
