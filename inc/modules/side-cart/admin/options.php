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
			'title'   => esc_html__( 'Shop Page', 'merchant' ),
			'desc'    => esc_html__( 'Show side cart after adding product to the cart from shop archive page', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'      => 'show_after_add_to_cart_single_product',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Product Page', 'merchant' ),
			'desc'    => esc_html__( 'Display side cart after adding product to the cart from product single page', 'merchant' ),
			'default' => 0,
		),
		array(
			'id'      => 'show_on_cart_url_click',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Cart Icons', 'merchant' ),
			'desc'    => esc_html__( 'Show side cart when a user clicks on the cart URL or menu items', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'      => 'slide_direction',
			'type'    => 'radio',
			'title'   => esc_html__( 'Cart position', 'merchant' ),
			'options' => array(
				'right' => esc_html__( 'Slide from right', 'merchant' ),
				'left'  => esc_html__( 'Slide from left', 'merchant' ),
			),
			'default' => 'right',
		),
		array(
			'id'      => 'use_discount_codes',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Show discount codes input', 'merchant' ),
			'default' => 0,
		),
		array(
			'id'      => 'show_checkout_btn',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Show checkout button', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'         => 'checkout_btn_text',
			'type'       => 'text',
			//          'title'   => esc_html__( 'Placement', 'merchant' ),
			'default'    => esc_html__( 'Checkout', 'merchant' ),
			'conditions' => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'show_checkout_btn', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'      => 'show_view_cart_btn',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Show view cart button', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'         => 'view_cart_btn_text',
			'type'       => 'text',
			//          'title'   => esc_html__( 'Placement', 'merchant' ),
			'default'    => esc_html__( 'View Cart', 'merchant' ),
			'conditions' => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'show_view_cart_btn', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'      => 'use_strikethrough_prices',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Show strikethrough prices', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'      => 'show_savings',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Show cart savings', 'merchant' ),
			'default' => 0,
		),
		array(
			'id'      => 'show_on_devices',
			'type'    => 'checkbox_multiple',
			'title'   => esc_html__( 'Show on devices', 'merchant' ),
			'options' => array(
				'desktop' => esc_html__( 'Desktop', 'merchant' ),
				'mobile'  => esc_html__( 'Mobile', 'merchant' ),
			),
			'default' => array( 'desktop', 'mobile' ),
		),
	),
) );

// Upsells
Merchant_Admin_Options::create( array(
	'module' => Merchant_Side_Cart::MODULE_ID,
	'title'  => esc_html__( 'Upsells', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'use_upsells',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Cart Upsells', 'merchant' ),
			'desc'    => esc_html__( 'Enable upsell products in the side cart', 'merchant' ),
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
					'title'       => esc_html__( 'Custom Upsells', 'merchant' ),
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
							'title'       => esc_html__( 'Select categories', 'merchant' ),
							'source'      => 'options',
							'multiple'    => true,
							'options'     => Merchant_Admin_Options::get_category_select2_choices(),
							'placeholder' => esc_html__( 'Search categories', 'merchant' ),
							'desc'        => esc_html__( 'Select the categories that you want to add the upsell for.', 'merchant' ),
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
							'id'         => 'exclusion_toggle',
							'type'       => 'switcher',
							'title'      => esc_html__( 'Exclusion List', 'merchant' ),
							'desc'       => esc_html__( 'Select products that will not display upsells.', 'merchant' ),
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
							'title'         => esc_html__( 'Exclude products', 'merchant' ),
							'multiple'      => true,
							'desc'          => esc_html__( 'Upsell will not be displayed for selected products.', 'merchant' ),
							'allowed_types' => array( 'simple', 'variable' ),
							'conditions'    => array(
								'relation' => 'OR', // AND/OR, If not provided, only first term will be considered
								'terms'    => array(
									array(
										'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
										'terms'    => array(
											array(
												'field'    => 'upsell_based_on', // field ID
												'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
												'value'    => 'all', // can be a single value or an array of string/number/int
											),
											array(
												'field'    => 'exclusion_toggle', // field ID
												'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
												'value'    => true, // can be a single value or an array of string/number/int
											),
										),
									),
									array(
										'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
										'terms'    => array(
											array(
												'field'    => 'upsell_based_on', // field ID
												'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
												'value'    => 'categories', // can be a single value or an array of string/number/int
											),
											array(
												'field'    => 'exclusion_toggle', // field ID
												'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
												'value'    => true, // can be a single value or an array of string/number/int
											),
										),
									),
								),
							),
						),
						array(
							'id'          => 'excluded_category_slugs',
							'type'        => 'select_ajax',
							'title'       => esc_html__( 'Exclude categories', 'merchant' ),
							'source'      => 'options',
							'multiple'    => true,
							'options'     => Merchant_Admin_Options::get_category_select2_choices(),
							'placeholder' => esc_html__( 'Search categories', 'merchant' ),
							'desc'        => esc_html__( 'Upsell will not be displayed for selected category products.', 'merchant' ),
							'conditions'  => array(
								'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
								'terms'    => array(
									array(
										'field'    => 'upsell_based_on', // field ID
										'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
										'value'    => 'all', // can be a single value or an array of string/number/int
									),
									array(
										'field'    => 'exclusion_toggle', // field ID
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
							'title'       => esc_html__( 'Select categories', 'merchant' ),
							'source'      => 'options',
							'multiple'    => true,
							'options'     => Merchant_Admin_Options::get_category_select2_choices(),
							'placeholder' => esc_html__( 'Search categories', 'merchant' ),
							'desc'        => esc_html__( 'Select the categories that you want to display the upsell products form.', 'merchant' ),
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
			'label'      => esc_html__( 'Limit the number of upsells in the cart', 'merchant' ),
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
			'title'      => esc_html__( 'Maximum number of upsells to display', 'merchant' ),
			'type'       => 'number',
			'default'    => 5,
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
			'title'      => esc_html__( 'Upsell title', 'merchant' ),
			'type'       => 'text',
			'default'    => esc_html__( 'You might also like', 'merchant' ),
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
			'title'      => esc_html__( 'Button text', 'merchant' ),
			'type'       => 'text',
			'default'    => esc_html__( 'Add', 'merchant' ),
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
			'title'      => esc_html__( 'Upsell layout', 'merchant' ),
			'options'    => array(
				'carousel' => esc_html__( 'Carousel', 'merchant' ),
				'block'    => esc_html__( 'List', 'merchant' ),
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

// Floating Mini Cart
Merchant_Admin_Options::create( array(
	'module' => Merchant_Side_Cart::MODULE_ID,
	'title'  => esc_html__( 'Floating Mini Cart', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'enable-floating-cart',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Floating cart', 'merchant' ),
			'desc'    => esc_html__( 'Enable floating cart icon', 'merchant' ),
			'default' => 0,
		),

		array(
			'id'        => 'icon-display',
			'type'      => 'select',
			'title'     => esc_html__( 'Display', 'merchant' ),
			'options'   => array(
				'cart-not-empty' => esc_html__( 'When cart is not empty', 'merchant' ),
				'always'         => esc_html__( 'Always', 'merchant' ),
			),
			'default'   => 'always',
			'condition' => array( 'enable-floating-cart', '==', '1' ),
		),

		array(
			'id'        => 'icon',
			'type'      => 'choices',
			'title'     => esc_html__( 'Icon', 'merchant' ),
			'options'   => array(
				'cart-icon-1' => MERCHANT_URI . 'assets/images/icons/side-cart/admin/cart-icon-1.svg',
				'cart-icon-2' => MERCHANT_URI . 'assets/images/icons/side-cart/admin/cart-icon-2.svg',
				'cart-icon-3' => MERCHANT_URI . 'assets/images/icons/side-cart/admin/cart-icon-3.svg',
				'cart-icon-4' => MERCHANT_URI . 'assets/images/icons/side-cart/admin/cart-icon-4.svg',
				'cart-icon-5' => MERCHANT_URI . 'assets/images/icons/side-cart/admin/cart-icon-5.svg',
			),
			'default'   => 'cart-icon-1',
			'condition' => array( 'enable-floating-cart', '==', '1' ),
		),

		array(
			'id'        => 'icon-position',
			'type'      => 'radio',
			'title'     => esc_html__( 'Position', 'merchant' ),
			'options'   => array(
				'left'  => esc_html__( 'Left', 'merchant' ),
				'right' => esc_html__( 'Right', 'merchant' ),
			),
			'default'   => 'right',
			'condition' => array( 'enable-floating-cart', '==', '1' ),
		),

		array(
			'id'        => 'icon-size',
			'type'      => 'range',
			'title'     => esc_html__( 'Icon size', 'merchant' ),
			'min'       => 0,
			'max'       => 250,
			'step'      => 1,
			'default'   => 25,
			'unit'      => 'px',
			'condition' => array( 'enable-floating-cart', '==', '1' ),
		),

		array(
			'id'        => 'icon-corner-offset',
			'type'      => 'range',
			'title'     => esc_html__( 'Corner offset', 'merchant' ),
			'min'       => 0,
			'max'       => 250,
			'step'      => 1,
			'default'   => 30,
			'unit'      => 'px',
			'condition' => array( 'enable-floating-cart', '==', '1' ),
		),

		array(
			'id'        => 'icon-border-radius',
			'type'      => 'range',
			'title'     => esc_html__( 'Border radius', 'merchant' ),
			'min'       => 0,
			'max'       => 35,
			'step'      => 1,
			'default'   => 35,
			'unit'      => 'px',
			'condition' => array( 'enable-floating-cart', '==', '1' ),
		),

		array(
			'id'        => 'icon-color',
			'type'      => 'color',
			'title'     => esc_html__( 'Icon color', 'merchant' ),
			'default'   => '#ffffff',
			'condition' => array( 'enable-floating-cart', '==', '1' ),
		),

		array(
			'id'        => 'icon-background-color',
			'type'      => 'color',
			'title'     => esc_html__( 'Background color', 'merchant' ),
			'default'   => '#212121',
			'condition' => array( 'enable-floating-cart', '==', '1' ),
		),

		array(
			'id'        => 'icon-counter-color',
			'type'      => 'color',
			'title'     => esc_html__( 'Counter color', 'merchant' ),
			'default'   => '#ffffff',
			'condition' => array( 'enable-floating-cart', '==', '1' ),
		),

		array(
			'id'        => 'icon-counter-background-color',
			'type'      => 'color',
			'title'     => esc_html__( 'Counter background color', 'merchant' ),
			'default'   => '#757575',
			'condition' => array( 'enable-floating-cart', '==', '1' ),
		),
	),
) );

// Side Cart Settings
Merchant_Admin_Options::create( array(
	'module' => Merchant_Side_Cart::MODULE_ID,
	'title'  => esc_html__( 'Look and Feel', 'merchant' ),
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
