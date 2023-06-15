<?php
/**
 * Merchant - Quick View
 */

/**
 * Settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'quick-view',
	'fields' => array(

		array(
			'id'      => 'button_title',
			'type'    => 'text',
			'title'   => esc_html__( 'Button Title', 'merchant' ),
			'default' => esc_html__( 'Quick View', 'merchant' ),
		),

		array(
			'id'       => 'button_type',
			'type'     => 'select',
			'title'    => esc_html__( 'Button Type', 'merchant' ),
			'options'  => array(
				'text'   => esc_html__( 'Text', 'merchant' ),
				'button' => esc_html__( 'Button', 'merchant' ),
			),
			'default'  => 'button',
		),

		array(
			'id'        => 'button_position',
			'type'      => 'select',
			'title'     => esc_html__( 'Button Position', 'merchant' ),
			'options'   => array(
				'before'  => esc_html__( 'Before - Add to Cart', 'merchant' ),
				'after'   => esc_html__( 'After - Add to Cart', 'merchant' ),
				'overlay' => esc_html__( 'Overlay', 'merchant' ),
			),
			'default'   => 'after',
		),

		array(
      'id'      => 'modal_width',
			'type'    => 'range',
			'title'   => esc_html__( 'Modal Width', 'merchant' ),
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'default' => 1000,
			'unit'    => 'px',
		),

		array(
      'id'      => 'modal_height',
			'type'    => 'range',
			'title'   => esc_html__( 'Modal Height', 'merchant' ),
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'default' => 500,
			'unit'    => 'px',
		),

		array(
			'id'      => 'modal_overlay_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Modal Overlay Color', 'merchant' ),
			'default' => 'rgba(0, 0, 0, 0.9)',
		),

		array(
			'id'      => 'visibility',
			'type'    => 'select',
			'title'   => esc_html__( 'Visibility', 'merchant' ),
			'options' => array(
				'all'          => esc_html__( 'Show on all devices', 'merchant' ),
				'desktop-only' => esc_html__( 'Desktop Only', 'merchant' ),
				'mobile-only'  => esc_html__( 'Mobile Only', 'merchant' ),
			),
		),

	),
) );
