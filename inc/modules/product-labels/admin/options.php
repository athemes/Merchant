<?php
/**
 * Merchant - Product Labels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

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
			'title'   => esc_html__( 'Label text', 'merchant' ),
			'desc'    => esc_html__( 'Note: This label will be shown only on sales products.', 'merchant' ),
			'default' => esc_html__( 'Spring Special', 'merchant' ),
		),

		array(
			'id'    => 'display_percentage',
			'type'  => 'checkbox',
			'label' => esc_html__( 'Display Sale Percentage', 'merchant' ),
			'default' => 0
		),

		array(
			'id'        => 'percentage_text',
			'type'      => 'text',
			'title'     => esc_html__( 'Sale percentage text', 'merchant' ),
			'desc'      => esc_html__( 'You may use the {value} tag. E.g. <strong>{value}% OFF!</strong>', 'merchant' ),
			'default'   => '-{value}%',
			'condition' => array( 'display_percentage', '==', '1' ),
		),

		array(
			'id'      => 'label_position',
			'type'    => 'select',
			'title'   => esc_html__( 'Position', 'merchant' ),
			'options' => array(
				'top-left'  => esc_html__( 'Top left', 'merchant' ),
				'top-right' => esc_html__( 'Top right', 'merchant' ),
			),
			'default' => 'left',
		),

		array(
			'id'      => 'label_shape',
			'type'    => 'range',
			'title'   => esc_html__( 'Shape radius', 'merchant' ),
			'min'     => 0,
			'max'     => 35,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 0
		),

		array(
			'id'      => 'label_text_transform',
			'type'    => 'select',
			'title'   => esc_html__( 'Letter case', 'merchant' ),
			'options' => array(
				'uppercase'  => esc_html__( 'Uppercase', 'merchant' ),
				'lowercase'  => esc_html__( 'Lowercase', 'merchant' ),
				'capitalize' => esc_html__( 'Capitalize', 'merchant' ),
				'none'       => esc_html__( 'None', 'merchant' ),
			),
			'default' => 'uppercase'
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
			'title'   => esc_html__( 'Font size', 'merchant' ),
			'min'     => 0,
			'max'     => 250,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 14,
		),

		array(
			'id'      => 'background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Background color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Text color', 'merchant' ),
			'default' => '#ffffff',
		),

	),
) );
