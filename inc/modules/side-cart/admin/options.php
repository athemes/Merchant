<?php

/**
 * Side Cart Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

Merchant_Admin_Options::create( array(
	'module' => Merchant_Side_Cart::MODULE_ID,
	'title'  => esc_html__( 'Display Settings', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'show_after_add_to_cart',
			'type'    => 'switcher',
			'title'   => __( 'Shop Page', 'merchant' ),
			'desc'   => __( 'Show side cart after adding product to the cart from shop archive page', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'      => 'show_after_add_to_cart_single_product',
			'type'    => 'switcher',
			'title'   => __( 'Product Page', 'merchant' ),
			'desc'   => __( 'Display side cart after adding product to the cart from product single page', 'merchant' ),
			'default' => 0,
		),
		array(
			'id'      => 'show_on_cart_url_click',
			'type'    => 'switcher',
			'title'   => __( 'Cart Icons', 'merchant' ),
			'desc'   => __( 'Show side cart when a user clicks on the cart URL or menu items', 'merchant' ),
			'default' => 1,
		),
	),
) );

Merchant_Admin_Options::create( array(
	'module' => Merchant_Side_Cart::MODULE_ID,
	'title'  => esc_html__( 'Upsell', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'use_upsells',
			'type'    => 'switcher',
			'title'   => __( 'Cart Upsells', 'merchant' ),
			'desc'    => __( 'Enable upsell products in the side cart', 'merchant' ),
			'default' => 0,
		),
		array(
			'id'         => 'upsells_type',
			'type'       => 'select',
			//'title'   => esc_html__( 'Placement', 'merchant' ),
			'options'    => array(
				'related_products' => esc_html__( 'Related Products', 'merchant' ),
				'custom_upsell'    => esc_html__( 'Custom Upsell', 'merchant' ),
			),
			'default'    => 'related_products',
			'conditions' => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'use_upsells', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),

		array(
			'id'           => 'custom_upsells',
			'type'         => 'flexible_content',
			'sorting'      => true,
			'accordion'    => true,
			'duplicate'    => false,
			'style'        => Merchant_Side_Cart::MODULE_ID . '-style default',
			'button_label' => esc_html__( 'Add New Upsell', 'merchant' ),
			'conditions'   => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'use_upsells', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'upsells_type', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => 'custom_upsell', // can be a single value or an array of string/number/int
					),
				),
			),
			'default'      => array(
				array(
					'layout'              => 'upsell-details',
					'upsell_based_on'     => 'products',
					'exclude_product_ids' => 0,
					'custom_upsell_type'  => 'products',
				),
			),
			'layouts'      => array(
				'upsell-details' => array(
					'title'       => esc_html__( 'Custom Upsell', 'merchant' ),
					'title-field' => 'offer-title', // text field ID to use as title for the layout
					'fields'      => array(
						array(
							'id'      => 'upsell_based_on',
							'type'    => 'select',
							'title'   => esc_html__( 'Trigger to add the upsell for', 'merchant' ),
							'options' => array(
								'all'        => esc_html__( 'All products', 'merchant' ),
								'products'   => esc_html__( 'Specific product', 'merchant' ),
								'categories' => esc_html__( 'Specific category', 'merchant' ),
							),
							'default' => 'products',
						),
						array(
							'id'            => 'product_ids',
							'type'          => 'products_selector',
							'title'         => esc_html__( 'Select product(s)', 'merchant' ),
							'multiple'      => true,
							'desc'          => esc_html__( 'Select the product(s) that you want to add the upsell for', 'merchant' ),
							'allowed_types' => array( 'simple', 'variable' ),
							'conditions'    => array(
								'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
								'terms'    => array(
									array(
										'field'    => 'upsell_based_on', // field ID
										'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
										'value'    => 'products', // can be a single value or an array of string/number/int
									),
								),
							),
						),
						array(
							'id'          => 'category_slugs',
							'type'        => 'select_ajax',
							'title'       => esc_html__( 'Select category(es)', 'merchant' ),
							'source'      => 'options',
							'multiple'    => true,
							'options'     => Merchant_Admin_Options::get_category_select2_choices(),
							'placeholder' => esc_html__( 'Search category(es)', 'merchant' ),
							'desc'        => esc_html__( 'Select the category(es) that you want to add the upsell for.', 'merchant' ),
							'conditions'  => array(
								'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
								'terms'    => array(
									array(
										'field'    => 'upsell_based_on', // field ID
										'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
										'value'    => 'categories', // can be a single value or an array of string/number/int
									),
								),
							),
						),
						array(
							'id'         => 'exclude_product_ids_toggle',
							'type'       => 'switcher',
							'title'      => __( 'Exclusion List', 'merchant' ),
							'desc'       => __( 'Select products that will not display upsells.', 'merchant' ),
							'default'    => 0,
							'conditions' => array(
								'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
								'terms'    => array(
									array(
										'field'    => 'upsell_based_on', // field ID
										'operator' => 'in', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
										'value'    => array( 'all', 'categories' ), // can be a single value or an array of string/number/int
									),
								),
							),
						),
						array(
							'id'            => 'excluded_product_ids',
							'type'          => 'products_selector',
							'title'         => esc_html__( 'Trigger', 'merchant' ),
							'multiple'      => true,
							'desc'          => esc_html__( 'Upsell will not be displayed for selected products.', 'merchant' ),
							'allowed_types' => array( 'simple', 'variable' ),
							'conditions'    => array(
								'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
								'terms'    => array(
									array(
										'field'    => 'upsell_based_on', // field ID
										'operator' => 'in', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
										'value'    => array( 'all', 'categories' ), // can be a single value or an array of string/number/int
									),
									array(
										'field'    => 'exclude_product_ids_toggle', // field ID
										'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
										'value'    => true, // can be a single value or an array of string/number/int
									),
								),
							),
						),
						array(
							'id'      => 'custom_upsell_type',
							'type'    => 'select',
							'title'   => esc_html__( 'Trigger upsells', 'merchant' ),
							'options' => array(
								'products'   => esc_html__( 'Specific product', 'merchant' ),
								'categories' => esc_html__( 'Specific category', 'merchant' ),
							),
							'default' => 'products',
						),
						array(
							'id'            => 'upsells_product_ids',
							'type'          => 'products_selector',
							'title'         => esc_html__( 'Select product(s)', 'merchant' ),
							'multiple'      => true,
							'desc'          => esc_html__( 'Select the product(s) that you want to add the upsell for', 'merchant' ),
							'allowed_types' => array( 'simple', 'variable' ),
							'conditions'    => array(
								'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
								'terms'    => array(
									array(
										'field'    => 'custom_upsell_type', // field ID
										'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
										'value'    => 'products', // can be a single value or an array of string/number/int
									),
								),
							),
						),
						array(
							'id'          => 'upsells_category_slugs',
							'type'        => 'select_ajax',
							'title'       => esc_html__( 'Select category(es)', 'merchant' ),
							'source'      => 'options',
							'multiple'    => true,
							'options'     => Merchant_Admin_Options::get_category_select2_choices(),
							'placeholder' => esc_html__( 'Search category(es)', 'merchant' ),
							'desc'        => esc_html__( 'Select the category(es) that you want to display the upsell products form.', 'merchant' ),
							'conditions'  => array(
								'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
								'terms'    => array(
									array(
										'field'    => 'custom_upsell_type', // field ID
										'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
										'value'    => 'categories', // can be a single value or an array of string/number/int
									),
								),
							),
						),
					),
				),
			),
		),


		array(
			'id'         => 'upsells_products_count_limitation_toggle',
			'label'      => __( 'Limit the number of upsells in the cart', 'merchant' ),
			'type'       => 'checkbox',
			'default'    => 0,
			'conditions' => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'use_upsells', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'         => 'upsells_products_count_limitation',
			'title'      => __( 'Maximum number of upsells to display', 'merchant' ),
			'type'       => 'number',
			'default'    => 10,
			'min'        => 1,
			'step'       => 1,
			'conditions' => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'use_upsells', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'upsells_products_count_limitation_toggle', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'         => 'upsells_title',
			'title'      => __( 'Upsell title', 'merchant' ),
			'type'       => 'text',
			'default'    => __( 'You might also like', 'merchant' ),
			'conditions' => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'use_upsells', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'         => 'upsells_add_to_cart_text',
			'title'      => __( 'Button text', 'merchant' ),
			'type'       => 'text',
			'default'    => __( 'Add', 'merchant' ),
			'conditions' => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'use_upsells', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'         => 'upsells_style',
			'type'       => 'select',
			'title'      => esc_html__( 'Upsell direction', 'merchant' ),
			'options'    => array(
				'carousel' => esc_html__( 'Carousel', 'merchant' ),
				'block'    => esc_html__( 'Block', 'merchant' ),
			),
			'default'    => 'block',
			'conditions' => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'use_upsells', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),
	),
) );

// Side Cart Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Look and Feel', 'merchant' ),
	'module' => 'floating-mini-cart',
	'fields' => array(

		array(
			'id'      => 'side-cart-width',
			'type'    => 'range',
			'title'   => esc_html__( 'Side cart width', 'merchant' ),
			'min'     => 0,
			'max'     => 2000,
			'step'    => 1,
			'default' => 380,
			'unit'    => 'px',
		),

		array(
			'id'      => 'side-cart-title-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Title color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'side-cart-title-icon-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Title icon color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'side-cart-title-background-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Title background color', 'merchant' ),
			'default' => '#cccccc',
		),

		array(
			'id'      => 'side-cart-content-text-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Content text color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'side-cart-content-background-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Content background color', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'      => 'side-cart-content-remove-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Content (x) color', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'      => 'side-cart-content-remove-background-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Content (x) background color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'side-cart-total-text-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Total text color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'side-cart-total-background-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Total background color', 'merchant' ),
			'default' => '#f5f5f5',
		),

		array(
			'id'      => 'side-cart-button-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Button color', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'      => 'side-cart-button-color-hover',
			'type'    => 'color',
			'title'   => esc_html__( 'Button color hover', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'      => 'side-cart-button-border-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Button border color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'side-cart-button-border-color-hover',
			'type'    => 'color',
			'title'   => esc_html__( 'Button border color hover', 'merchant' ),
			'default' => '#313131',
		),

		array(
			'id'      => 'side-cart-button-background-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Button background color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'side-cart-button-background-color-hover',
			'type'    => 'color',
			'title'   => esc_html__( 'Button background color hover', 'merchant' ),
			'default' => '#313131',
		),

	),
) );
