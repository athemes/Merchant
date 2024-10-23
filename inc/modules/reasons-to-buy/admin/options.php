<?php

/**
 * Reasons To Buy Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => Merchant_Reasons_To_Buy::MODULE_ID,
	'fields' => array(
		array(
			'id'           => 'reasons_to_buy',
			'type'         => 'flexible_content',
			'button_label' => esc_html__( 'Add New', 'merchant' ),
			'style'        => Merchant_Reasons_To_Buy::MODULE_ID . '-style default',
			'sorting'      => true,
			'accordion'    => true,
			'duplicate'    => true,
			'layouts'      => array(
				'single-reason' => array(
					'title'       => esc_html__( 'Reasons To Buy', 'merchant' ),
					'title-field' => 'title',
					'fields'      => array(
						array(
							'id'      => 'title',
							'type'    => 'text',
							'title'   => esc_html__( 'Title', 'merchant' ),
							'default' => esc_html__( 'Reasons to buy list', 'merchant' ),
							'desc'    => '',
						),

						array(
							'id'      => 'display_rules',
							'type'    => 'select',
							'title'   => esc_html__( 'Products that will display the items', 'merchant' ),
							'options' => array(
								'all'        => esc_html__( 'All products', 'merchant' ),
								'products'   => esc_html__( 'Specific products', 'merchant' ),
								'categories' => esc_html__( 'Specific categories', 'merchant' ),
								'tags'       => esc_html__( 'Specific tags', 'merchant' ),
							),
							'default' => 'all',
						),

						array(
							'id'            => 'product_ids',
							'type'          => 'products_selector',
							'multiple'      => true,
							'desc'          => esc_html__( 'Select the product(s) on which the items will appear', 'merchant' ),
							'condition'     => array( 'display_rules', '==', 'products' ),
							'allowed_types' => array( 'simple', 'variable' ),
						),

						array(
							'id'          => 'category_slugs',
							'type'        => 'select_ajax',
							'source'      => 'options',
							'multiple'    => true,
							'options'     => Merchant_Admin_Options::get_category_select2_choices(),
							'placeholder' => esc_html__( 'Select categories', 'merchant' ),
							'desc'        => esc_html__( 'Select the product categories where the items will appear', 'merchant' ),
							'condition'   => array( 'display_rules', '==', 'categories' ),
						),

						array(
							'id'          => 'tag_slugs',
							'type'        => 'select_ajax',
							'title'       => esc_html__( 'Tags', 'merchant' ),
							'source'      => 'options',
							'multiple'    => true,
							'options'     => Merchant_Admin_Options::get_tag_select2_choices(),
							'placeholder' => esc_html__( 'Select tags', 'merchant' ),
							'desc'        => esc_html__( 'Select the product tags where the items will appear', 'merchant' ),
							'condition'   => array( 'display_rules', '==', 'tags' ),
						),

						array(
							'id'         => 'exclusion_enabled',
							'type'       => 'switcher',
							'title'      => __( 'Exclusion List', 'merchant' ),
							'desc'       => __( 'Select products that will not display items.', 'merchant' ),
							'default'    => 0,
							'conditions' => array(
								'relation' => 'AND',
								'terms'    => array(
									array(
										'field'    => 'display_rules',
										'operator' => 'in',
										'value'    => array( 'all', 'categories', 'tags' ),
									),
								),
							),
						),

						array(
							'id'            => 'excluded_products',
							'type'          => 'products_selector',
							'title'         => esc_html__( 'Exclude Products', 'merchant' ),
							'multiple'      => true,
							'desc'          => esc_html__( 'Exclude products from these items', 'merchant' ),
							'allowed_types' => array( 'simple', 'variable' ),
							'conditions'    => array(
								'relation' => 'AND',
								'terms'    => array(
									array(
										'field'    => 'display_rules',
										'operator' => 'in',
										'value'    => array( 'all', 'categories', 'tags' ),
									),
									array(
										'field'    => 'exclusion_enabled',
										'operator' => '===',
										'value'    => true,
									),
								),
							),
						),

						array(
							'id'          => 'excluded_categories',
							'type'        => 'select_ajax',
							'title'       => esc_html__( 'Exclude Categories', 'merchant' ),
							'source'      => 'options',
							'multiple'    => true,
							'options'     => Merchant_Admin_Options::get_category_select2_choices(),
							'placeholder' => esc_html__( 'Select categories', 'merchant' ),
							'desc'        => esc_html__( 'Exclude categories from these items.', 'merchant' ),
							'conditions'    => array(
								'relation' => 'AND',
								'terms'    => array(
									array(
										'field'    => 'display_rules',
										'operator' => 'in',
										'value'    => array( 'all' ),
									),
									array(
										'field'    => 'exclusion_enabled',
										'operator' => '===',
										'value'    => true,
									),
								),
							),
						),

						array(
							'id'          => 'excluded_tags',
							'type'        => 'select_ajax',
							'title'       => esc_html__( 'Exclude Tags', 'merchant' ),
							'source'      => 'options',
							'multiple'    => true,
							'options'     => Merchant_Admin_Options::get_tag_select2_choices(),
							'placeholder' => esc_html__( 'Select tags', 'merchant' ),
							'desc'        => esc_html__( 'Exclude tags from these items.', 'merchant' ),
							'conditions'    => array(
								'relation' => 'AND',
								'terms'    => array(
									array(
										'field'    => 'display_rules',
										'operator' => 'in',
										'value'    => array( 'all' ),
									),
									array(
										'field'    => 'exclusion_enabled',
										'operator' => '===',
										'value'    => true,
									),
								),
							),
						),

						array(
							'id'           => 'items',
							'type'         => 'sortable_repeater',
							'sorting'      => true,
							'title'        => esc_html__( 'Items', 'merchant' ),
							'desc'         => '',
							'button_label' => esc_html__( 'Add new item', 'merchant' ),
							'default'      => array(
								esc_html__( '100% Polyester.', 'merchant' ),
								// esc_html__( 'Recycled Polyamid.', 'merchant' ),
								// esc_html__( 'GOTS-certified organic cotton.', 'merchant' ),
							),
						),

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
							'condition' => array( 'display_icon', '==', '1' ),
						),

						// List items Spacing.
						array(
							'id'      => 'spacing',
							'type'    => 'range',
							'title'   => esc_html__( 'List items spacing', 'merchant' ),
							'min'     => 0,
							'max'     => 80,
							'step'    => 1,
							'unit'    => 'px',
							'default' => 5,
						),

						// Title color.
						array(
							'id'      => 'title_color',
							'type'    => 'color',
							'title'   => esc_html__( 'Title color', 'merchant' ),
							'default' => '#212121',
						),

						// List items color.
						array(
							'id'      => 'items_color',
							'type'    => 'color',
							'title'   => esc_html__( 'List items color', 'merchant' ),
							'default' => '#777',
						),

						// List items Icon color.
						array(
							'id'      => 'icon_color',
							'type'    => 'color',
							'title'   => esc_html__( 'List items Icon color', 'merchant' ),
							'default' => '#212121',
						),
					),
				),
			),
			'default'      => array(
				array(
					'layout' => 'single-reason',
				),
			),
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
			'type'    => 'info',
			'id'      => 'shortcode_info',
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