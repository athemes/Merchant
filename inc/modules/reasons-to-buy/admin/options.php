<?php

/**
 * Reasons To Buy Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


// Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => Merchant_Reasons_To_Buy::MODULE_ID,
	'fields' => array(

		// Placement
		array(
			'id'      => 'placement',
			'type'    => 'select',
			'title'   => esc_html__( 'Placement on product page', 'merchant' ),
			'options' => array(
				'after-short-description' => esc_html__( 'After short description', 'merchant' ),
				'before-cart-form'        => esc_html__( 'Before add to cart form', 'merchant' ),
				'after-cart-form'         => esc_html__( 'After add to cart form', 'merchant' ),
				'bottom-product-summary'  => esc_html__( 'Bottom of product summary', 'merchant' ),
			),
			'default' => 'bottom-product-summary',
		),

		// Title.
		array(
			'id'      => 'title',
			'type'    => 'text',
			'title'   => esc_html__( 'Title', 'merchant' ),
			'default' => __( 'Reasons to buy list', 'merchant' ),
		),

		// Reasons.
		array(
			'id'           => 'reasons',
			'type'         => 'sortable_repeater',
			'sorting'      => false,
			'title'        => esc_html__( 'Reasons to buy', 'merchant' ),
			/* Translators: 1. Link open tag 2. Link close tag */
			'desc'         => sprintf( esc_html__( 'Note: If you want to add a different reasons for each product, go to %1$sProducts%2$s, edit the desired product, set the title and reasons inside the metabox.',
				'merchant' ),
				'<a href="' . esc_url( admin_url( 'edit.php?post_type=product' ) ) . '">',
				'</a>' ),
			'button_label' => esc_html__( 'Add new', 'merchant' ),
			'default'      => array(
				esc_html__( '100% Polyester.', 'merchant' ),
				esc_html__( 'Recycled Polyamid.', 'merchant' ),
				esc_html__( 'GOTS-certified organic cotton.', 'merchant' ),
			),
		),

		// Display Icon.
		array(
			'id'      => 'display_icon',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Display icon', 'merchant' ),
			'default' => 1,
		),

		// List items Icon.
		array(
			'id'        => 'icon',
			'type'      => 'choices',
			'title'     => esc_html__( 'Select an icon', 'merchant' ),
			'options'   => array(
				'check2' => MERCHANT_URI . 'inc/modules/reasons-to-buy/admin/icons/check2.svg',
				'check3' => MERCHANT_URI . 'inc/modules/reasons-to-buy/admin/icons/check3.svg',
			),
			'default'   => 'check2',
			'condition' => array( 'display_icon', '==', true ),
		),

		// List items Spacing.
		array(
			'id'      => 'list_items_spacing',
			'type'    => 'range',
			'title'   => esc_html__( 'List items spacing', 'merchant' ),
			'min'     => 0,
			'max'     => 80,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 5,
		),

	),
) );

// Style Settings
Merchant_Admin_Options::create( array(
	'module' => Merchant_Reasons_To_Buy::MODULE_ID,
	'title'  => esc_html__( 'Style', 'merchant' ),
	'fields' => array(

		// Title color.
		array(
			'id'      => 'title_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Title color', 'merchant' ),
			'default' => '#212121',
		),

		// List items color.
		array(
			'id'      => 'list_items_color',
			'type'    => 'color',
			'title'   => esc_html__( 'List items color', 'merchant' ),
			'default' => '#777',
		),

		// List items Icon color.
		array(
			'id'      => 'list_items_icon_color',
			'type'    => 'color',
			'title'   => esc_html__( 'List items Icon color', 'merchant' ),
			'default' => '#212121',
		),

	),
) );

// Shortcode
$merchant_module_id = Merchant_Reasons_To_Buy::MODULE_ID;
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