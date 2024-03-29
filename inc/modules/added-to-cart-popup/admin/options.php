<?php

/**
 * Added To Cart Popup Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Settings
Merchant_Admin_Options::create( array(
	'module' => Merchant_Added_To_Cart_Popup::MODULE_ID,
	'title'  => esc_html__( 'Popup Settings', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'popup_width',
			'type'    => 'range',
			'min'     => '0',
			'max'     => '100',
			'step'    => '1',
			'unit'    => '%',
			'default' => '70',
			'title'   => esc_html__( 'Popup width', 'merchant' ),
		),
		array(
			'id'      => 'popup_height',
			'type'    => 'range',
			'min'     => '0',
			'max'     => '100',
			'step'    => '1',
			'unit'    => '%',
			'default' => '70',
			'title'   => esc_html__( 'Popup height', 'merchant' ),
		),
		array(
			'id'    => 'mobile_sizes',
			'type'  => 'switcher',
			'title' => esc_html__( 'Set different size on mobile', 'merchant' ),
		),
		array(
			'id'         => 'mobile_popup_width',
			'type'       => 'range',
			'min'        => '0',
			'max'        => '100',
			'step'       => '1',
			'unit'       => '%',
			'title'      => esc_html__( 'Mobile popup width', 'merchant' ),
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'mobile_sizes', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'         => 'mobile_popup_height',
			'type'       => 'range',
			'min'        => '0',
			'max'        => '100',
			'step'       => '1',
			'unit'       => '%',
			'title'      => esc_html__( 'Mobile popup height', 'merchant' ),
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'mobile_sizes', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'      => 'popup_message',
			'type'    => 'text',
			'default' => esc_html__( 'Product has been successfully added to your cart', 'merchant' ),
			'title'   => esc_html__( 'Popup message', 'merchant' ),
		),
		array(
			'id'      => 'show_product_thumb',
			'type'    => 'checkbox',
			'title'   => esc_html__( 'Show thumbnail', 'merchant' ),
			'label'   => esc_html__( 'Choose to show the thumbnail in the cart popup', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'         => 'thumbnail_size',
			'type'       => 'range',
			'min'        => '0',
			'max'        => '100',
			'step'       => '1',
			'unit'       => 'PX',
			'title'      => esc_html__( 'Thumbnail size', 'merchant' ),
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'show_product_thumb', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),

		array(
			'id'      => 'show_product_info',
			'type'    => 'checkbox_multiple',
			'title'   => esc_html__( 'Show product info', 'merchant' ),
			'options' => array(
				'title_and_price' => esc_html__( 'Product title and price', 'merchant' ),
				'description'     => esc_html__( 'Product description', 'merchant' ),
			),
			'default' => array( 'title_and_price', 'description' ),
		),

		array(
			'id'      => 'show_cart_details',
			'type'    => 'checkbox_multiple',
			'title'   => esc_html__( 'Show cart details', 'merchant' ),
			'options' => array(
				'cart_total'    => esc_html__( 'Cart total', 'merchant' ),
				'shipping_cost' => esc_html__( 'Shipping cost', 'merchant' ),
				'tax_amount'    => esc_html__( 'Tax amount', 'merchant' ),
			),
			'default' => array( 'cart_total', 'shipping_cost', 'tax_amount' ),
		),

		array(
			'id'      => 'show_view_cart_button',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Show view cart button', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'         => 'view_cart_button_label',
			'type'       => 'text',
			'default'    => esc_html__( 'View Cart', 'merchant' ),
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'show_view_cart_button', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'      => 'show_view_continue_shopping_button',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Show continue shopping button', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'         => 'view_continue_shopping_button_label',
			'type'       => 'text',
			'default'    => esc_html__( 'Continue Shopping', 'merchant' ),
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'show_view_continue_shopping_button', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'      => 'show_checkout_button',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Show checkout button', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'      => 'show_suggested_products',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Show suggested product', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'         => 'suggested_products_module',
			'type'       => 'select',
			'options'    => array(
				'recently_viewed_products'   => esc_html__( 'Recently Viewed Products', 'merchant' ),
				'frequently_bought_together' => esc_html__( 'Frequently Bought Together', 'merchant' ),
				'buy_x_get_y'                => esc_html__( 'Buy X Get Y', 'merchant' ),
				'bulk_discounts'             => esc_html__( 'Bulk Discounts', 'merchant' ),
			),
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'show_suggested_products', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'          => 'suggested_products_instructions_frequently_bought_together',
			'type'        => 'info_block',
			'description' => esc_html__( 'You can display offers like Frequently Bought Together, Buy X Get Y, Bulk Discount, Recently Viewed Product enabling Merchant’s module.',
				'merchant' ),
			'button_text' => esc_html__( 'View Frequently Bought Together', 'merchant' ),
			'button_link' => esc_url( admin_url( 'admin.php?page=merchant&module=frequently-bought-together' ) ),
			'conditions'  => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'show_suggested_products', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'suggested_products_module', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in
						'value'    => 'frequently_bought_together', // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'          => 'suggested_products_instructions_recently_viewed_products',
			'type'        => 'info_block',
			'description' => esc_html__( 'You can display offers like Frequently Bought Together, Buy X Get Y, Bulk Discount, Recently Viewed Product enabling Merchant’s module.',
				'merchant' ),
			'button_text' => esc_html__( 'View Recently Viewed Products', 'merchant' ),
			'button_link' => esc_url( admin_url( 'admin.php?page=merchant&module=recently-viewed-products' ) ),
			'conditions'  => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'show_suggested_products', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'suggested_products_module', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in
						'value'    => 'recently_viewed_products', // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'          => 'suggested_products_instructions_buy_x_get_y',
			'type'        => 'info_block',
			'description' => esc_html__( 'You can display offers like Frequently Bought Together, Buy X Get Y, Bulk Discount, Recently Viewed Product enabling Merchant’s module.',
				'merchant' ),
			'button_text' => esc_html__( 'View Buy X Get Y', 'merchant' ),
			'button_link' => esc_url( admin_url( 'admin.php?page=merchant&module=buy-x-get-y' ) ),
			'conditions'  => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'show_suggested_products', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'suggested_products_module', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in
						'value'    => 'buy_x_get_y', // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'          => 'suggested_products_instructions_bulk_discounts',
			'type'        => 'info_block',
			'description' => esc_html__( 'You can display offers like Frequently Bought Together, Buy X Get Y, Bulk Discount, Recently Viewed Product enabling Merchant’s module.',
				'merchant' ),
			'button_text' => esc_html__( 'View Bulk Discounts', 'merchant' ),
			'button_link' => esc_url( admin_url( 'admin.php?page=merchant&module=volume-discounts' ) ),
			'conditions'  => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'show_suggested_products', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'suggested_products_module', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in
						'value'    => 'bulk_discounts', // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'      => 'exclusion_list',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Exclusion List', 'merchant' ),
			'default' => false,
		),
		array(
			'id'         => 'suggested_products_type',
			'type'       => 'select',
			'title'      => esc_html__( 'Trigger', 'merchant' ),
			'options'    => array(
				'specific_products'   => esc_html__( 'Specific Products', 'merchant' ),
				'specific_categories' => esc_html__( 'Specific Categories', 'merchant' ),
			),
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'exclusion_list', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'            => 'products_to_display',
			'type'          => 'products_selector',
			'multiple'      => true,
			'desc'          => esc_html__( 'Popup will not be displayed for selected products.', 'merchant' ),
			'allowed_types' => array( 'simple', 'variable' ),
			'conditions'    => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'exclusion_list', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'suggested_products_type', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in
						'value'    => 'specific_products', // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'          => 'category_slugs',
			'type'        => 'select_ajax',
			'title'       => esc_html__( 'Categories', 'merchant' ),
			'source'      => 'options',
			'multiple'    => true,
			'options'     => Merchant_Admin_Options::get_category_select2_choices(),
			'placeholder' => esc_html__( 'Select categories', 'merchant' ),
			'desc'        => esc_html__( 'Select the product categories that will show the offer.', 'merchant' ),
			'conditions'  => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'exclusion_list', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'suggested_products_type', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in
						'value'    => 'specific_categories', // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'      => 'show_pages',
			'type'    => 'checkbox_multiple',
			'title'   => esc_html__( 'Show on devices', 'merchant' ),
			'options' => array(
				'homepage'        => esc_html__( 'Homepage', 'merchant' ),
				'product_single'  => esc_html__( 'Product Single', 'merchant' ),
				'product_archive' => esc_html__( 'Product Archive', 'merchant' ),
			),
			'default' => array( 'homepage', 'product_single', 'product_archive' ),
		),

		array(
			'id'      => 'show_devices',
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

Merchant_Admin_Options::create( array(
	'module' => Merchant_Added_To_Cart_Popup::MODULE_ID,
	'title'  => esc_html__( 'Look and Feel', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'popup_background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Popup background color', 'merchant' ),
			'default' => '#3858E9',
		),
		// close link color
		array(
			'id'      => 'close_link_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Close link color', 'merchant' ),
			'default' => '#1D2327',
		),
		// message text color
		array(
			'id'      => 'popup_message_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Message text color', 'merchant' ),
			'default' => '#ffffff',
		),
		// message text bg color
		array(
			'id'      => 'popup_message_bg_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Message background color', 'merchant' ),
			'default' => '#3858E9',
		),
		// product title color
		array(
			'id'      => 'product_title_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Product name color', 'merchant' ),
			'default' => '#1D2327',
		),
		// select cart items color
		array(
			'id'      => 'cart_items_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Select cart items color', 'merchant' ),
			'default' => '#1D2327',
		),
		// button bg color
		array(
			'id'      => 'button_bg_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Button background color', 'merchant' ),
			'default' => '#3858E9',
		),
		// button text color
		array(
			'id'      => 'button_text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Button text color', 'merchant' ),
			'default' => '#ffffff',
		),
		// suggested title color
		array(
			'id'      => 'suggested_title_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Suggested title color', 'merchant' ),
			'default' => '#1D2327',
		),
	),
) );