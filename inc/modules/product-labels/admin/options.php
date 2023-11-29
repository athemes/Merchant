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
	'module' => Merchant_Product_Labels::MODULE_ID,
	'fields' => array(
		array(
			'id'           => 'labels',
			'type'         => 'flexible_content',
			'sorting'      => true,
			'style'        => Merchant_Product_Labels::MODULE_ID . '-style default',
			'button_label' => esc_html__( 'Add', 'merchant' ),
			'layouts'      => array(
				'single-label' => array(
					'title'  => esc_html__( 'Products Label', 'merchant' ),
					'fields' => array(
						array(
							'id'      => 'label',
							'title'   => esc_html__( 'Label', 'merchant' ),
							'type'    => 'text',
							'default' => esc_html__( 'Spring Special', 'merchant' ),
						),
						array(
							'id'      => 'pages_to_display',
							'type'    => 'radio',
							'title'   => esc_html__( 'Pages to display', 'merchant' ),
							'options' => array(
								'both'    => esc_html__( 'Both', 'merchant' ),
								'archive' => esc_html__( 'Archive', 'merchant' ),
								'single'  => esc_html__( 'Single', 'merchant' ),
							),
							'default' => 'both',
						),
						array(
							'id'      => 'display_rules',
							'type'    => 'select',
							'title'   => esc_html__( 'Display Rules', 'merchant' ),
							'options' => array(
								'featured_products' => esc_html__( 'Featured Products', 'merchant' ),
								'products_on_sale'  => esc_html__( 'Products on Sale', 'merchant' ),
								'new_products'      => esc_html__( 'New Products', 'merchant' ),
								'out_of_stock'      => esc_html__( 'Out of Stock', 'merchant' ),
								'by_category'       => esc_html__( 'By Product Category', 'merchant' ),
							),
							'default' => 'featured_products',
						),
						array(
							'id'        => 'new_products_days',
							'type'      => 'number',
							'min'       => 0,
							'step'      => 1,
							'title'     => esc_html__( 'How long counts as new', 'merchant' ),
							'desc'      => esc_html__( 'Set the number of days the product will be marked as ‘New’ after it has been created', 'merchant' ),
							'default'   => 3,
							'condition' => array( 'display_rules', '==', 'new_products' ),
						),
						array(
							'id'        => 'percentage_text',
							'type'      => 'text',
							'title'     => esc_html__( 'Sale Percentage', 'merchant' ),
							'default'   => '-{value}%',
							'desc'      => esc_html__( 'You may use the {value} tag. E.g. {value}% OFF!', 'merchant' ),
							'condition' => array( 'display_rules', '==', 'products_on_sale' ),
						),
						array(
							'id'        => 'product_cats',
							'type'      => 'select',
							'title'     => esc_html__( 'Product Categories', 'merchant' ),
							'options'   => merchant_get_product_categories(),
							'condition' => array( 'display_rules', '==', 'by_category' ),
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

				),
			),
			'default'      => array(
				array(
					'layout'           => 'single-label',
					'label'            => Merchant_Admin_Options::get( Merchant_Product_Labels::MODULE_ID, 'label_text', esc_html__( 'Spring Special', 'merchant' ) ),
					'pages_to_display' => 'both',
					'display_rules'    => 'products_on_sale',
					'percentage_text'  => Merchant_Admin_Options::get( Merchant_Product_Labels::MODULE_ID, 'percentage_text', '-{value}%' ),
					'background_color' => Merchant_Admin_Options::get( Merchant_Product_Labels::MODULE_ID, 'background_color', '#212121' ),
					'text_color'       => Merchant_Admin_Options::get( Merchant_Product_Labels::MODULE_ID, 'text_color', '#ffffff' ),
				),
			),
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
			'default' => 0,
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
			'title'   => esc_html__( 'Font size', 'merchant' ),
			'min'     => 0,
			'max'     => 250,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 14,
		),
	),
) );
