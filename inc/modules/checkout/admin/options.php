<?php

/**
 * Checkout Options.
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'checkout',
	'fields' => array(
		array(
			'id'      => 'layout',
			'type'    => 'radio',
			'title'   => esc_html__( 'Layout', 'merchant' ),
			'options' => array(
				'layout-shopify' => esc_html__( 'Shopify multi step', 'merchant' ),
				'layout-one-step' => esc_html__( 'One step', 'merchant' ),
				'layout-multi-step' => esc_html__( 'Multi step', 'merchant' )
			),
			'default' => 'layout-shopify',
		),

		array(
			'id'        => 'sticky_totals_box',
			'type'      => 'switcher',
			'title'     => esc_html__( 'Sticky Totals Box', 'merchant' ),
			'condition' => array( 'layout', 'any', 'layout-shopify|layout-one-step' ),
		),

	)
) );
