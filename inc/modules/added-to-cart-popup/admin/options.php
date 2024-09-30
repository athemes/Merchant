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
			'id'      => 'layout',
			'type'    => 'image_picker',
			'title'   => esc_html__( 'Select layout', 'merchant' ),
			'options' => array(
				'layout-1' => array(
					'image' => MERCHANT_URI . 'assets/images/modules/added-to-cart-popup/admin/layout-1.png',
					'title' => esc_html__( 'Layout 1', 'merchant' ),
				),
				'layout-2' => array(
					'image' => MERCHANT_URI . 'assets/images/modules/added-to-cart-popup/admin/layout-2.png',
					'title' => esc_html__( 'Layout 2', 'merchant' ),
				),
				'layout-3' => array(
					'image' => MERCHANT_URI . 'assets/images/modules/added-to-cart-popup/admin/layout-3.png',
					'title' => esc_html__( 'Layout 3', 'merchant' ),
				),
			),
			'default' => 'layout-1',
		),
		array(
			'id'      => 'popup_size',
			'type'    => 'range',
			'min'     => '700',
			'max'     => '1300',
			'step'    => '1',
			'unit'    => 'PX',
			'default' => '1000',
			'title'   => esc_html__( 'Popup size', 'merchant' ),
		),
		array(
			'id'      => 'popup_message',
			'type'    => 'text',
			'default' => esc_html__( 'Added to cart', 'merchant' ),
			'title'   => esc_html__( 'Popup message', 'merchant' ),
			'desc'    => esc_html__( 'This message will be shown at the top of the popup', 'merchant' ),
		),
		array(
			'id'      => 'show_product_info',
			'type'    => 'checkbox_multiple',
			'title'   => esc_html__( 'Show product info', 'merchant' ),
			'options' => array(
				'thumbnail'       => esc_html__( 'Product thumbnail', 'merchant' ),
				'title_and_price' => esc_html__( 'Product title and price', 'merchant' ),
				'description'     => esc_html__( 'Product description', 'merchant' ),
			),
			'default' => array( 'title_and_price', 'description', 'thumbnail' ),
		),

		array(
			'id'      => 'description_type',
			'type'    => 'radio',
			'title'   => esc_html__( 'Description type', 'merchant' ),
			'options' => array(
				'full'  => esc_html__( 'Full description', 'merchant' ),
				'short' => esc_html__( 'Short description', 'merchant' ),
			),
			'default' => 'short',
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'show_product_info', // field ID
						'operator' => 'contains', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => 'description', // can be a single value or an array of string/number/int
					),
				),
			),
		),

		array(
			'id'         => 'description_length',
			'type'       => 'range',
			'min'        => '5',
			'max'        => '60',
			'step'       => '1',
			'unit'       => esc_html__( 'Words', 'merchant' ),
			'default'    => '15',
			'title'      => esc_html__( 'Maximum product description length', 'merchant' ),
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'show_product_info', // field ID
						'operator' => 'contains', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => 'description', // can be a single value or an array of string/number/int
					),
				),
			),
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
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
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
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
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
			'title'   => esc_html__( 'Show suggested products', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'         => 'suggested_products_module',
			'type'       => 'select',
			'options'    => array(
				'related_products'           => esc_html__( 'Related Products', 'merchant' ),
				'recently_viewed_products'   => esc_html__( 'Recently Viewed Products', 'merchant' ),
				'frequently_bought_together' => esc_html__( 'Frequently Bought Together', 'merchant' ),
				'buy_x_get_y'                => esc_html__( 'Buy X Get Y', 'merchant' ),
			),
			'default'    => 'related_products',
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'show_suggested_products', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'          => 'suggested_products_instructions_frequently_bought_together',
			'type'        => 'info_block',
			'description' => esc_html__( 'You can display suggested products by using the default Related Products or by enabling certain modules (Recently Viewed Products, Frequently Bought Together or Buy X Get Y) from Merchant.',
				'merchant' ),
			'button_text' => esc_html__( 'View Frequently Bought Together', 'merchant' ),
			'button_link' => esc_url( admin_url( 'admin.php?page=merchant&module=frequently-bought-together' ) ),
			'conditions'  => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'show_suggested_products', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'suggested_products_module', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => 'frequently_bought_together', // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'          => 'suggested_products_instructions_recently_viewed_products',
			'type'        => 'info_block',
			'description' => esc_html__( 'You can display suggested products by using the default Related Products or by enabling certain modules (Recently Viewed Products, Frequently Bought Together or Buy X Get Y) from Merchant.',
				'merchant' ),
			'button_text' => esc_html__( 'View Recently Viewed Products', 'merchant' ),
			'button_link' => esc_url( admin_url( 'admin.php?page=merchant&module=recently-viewed-products' ) ),
			'conditions'  => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'show_suggested_products', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'suggested_products_module', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => 'recently_viewed_products', // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'          => 'suggested_products_instructions_buy_x_get_y',
			'type'        => 'info_block',
			'description' => esc_html__( 'You can display suggested products by using the default Related Products or by enabling certain modules (Recently Viewed Products, Frequently Bought Together or Buy X Get Y) from Merchant.',
				'merchant' ),
			'button_text' => esc_html__( 'View Buy X Get Y', 'merchant' ),
			'button_link' => esc_url( admin_url( 'admin.php?page=merchant&module=buy-x-get-y' ) ),
			'conditions'  => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'show_suggested_products', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'suggested_products_module', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => 'buy_x_get_y', // can be a single value or an array of string/number/int
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
			'id'         => 'exclusion_type',
			'type'       => 'select',
			'title'      => esc_html__( 'Trigger', 'merchant' ),
			'options'    => array(
				'specific_products'   => esc_html__( 'Specific Products', 'merchant' ),
				'specific_categories' => esc_html__( 'Specific Categories', 'merchant' ),
			),
			'default'    => 'specific_products',
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'exclusion_list', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'            => 'exclude_specific_products',
			'type'          => 'products_selector',
			'multiple'      => true,
			'desc'          => esc_html__( 'Popup will not be displayed for selected products.', 'merchant' ),
			'allowed_types' => array( 'simple', 'variable' ),
			'conditions'    => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'exclusion_list', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'exclusion_type', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => 'specific_products', // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'          => 'exclude_specific_categories',
			'type'        => 'select_ajax',
			'title'       => esc_html__( 'Categories', 'merchant' ),
			'source'      => 'options',
			'multiple'    => true,
			'options'     => Merchant_Admin_Options::get_category_select2_choices(),
			'placeholder' => esc_html__( 'Select categories', 'merchant' ),
			'desc'        => esc_html__( 'Popup will not be displayed for selected categories.', 'merchant' ),
			'conditions'  => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'exclusion_list', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'exclusion_type', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => 'specific_categories', // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'      => 'show_pages',
			'type'    => 'checkbox_multiple',
			'title'   => esc_html__( 'Show on pages', 'merchant' ),
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
			'default' => '#fff',
		),
		array(
			'id'      => 'popup_corner_radius',
			'type'    => 'range',
			'min'     => '0',
			'max'     => '20',
			'step'    => '1',
			'unit'    => 'PX',
			'default' => '0',
			'title'   => esc_html__( 'Popup corner radius', 'merchant' ),
		),
		array(
			'id'      => 'popup_overlay_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Popup overlay color', 'merchant' ),
			'default' => 'rgba(0,0,0,0.5)',
		),
		array(
			'id'      => 'border_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Borders colors', 'merchant' ),
			'default' => '#d9d9d9',
		),
		// close link color
		array(
			'id'      => 'close_btn_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Close button color', 'merchant' ),
			'default' => '#000',
		),
		// message text color
		array(
			'id'      => 'popup_message_text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Message text color', 'merchant' ),
			'default' => '#000',
		),
		// product title color
		array(
			'id'      => 'product_title_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Product name color', 'merchant' ),
			'default' => '#000',
		),
		array(
			'id'      => 'product_description_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Product description color', 'merchant' ),
			'default' => '#000',
		),
		array(
			'id'      => 'product_price_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Product price color', 'merchant' ),
			'default' => '#000',
		),
		array(
			'id'      => 'product_price_font_size',
			'type'    => 'range',
			'min'     => '1',
			'max'     => '60',
			'step'    => '1',
			'unit'    => 'PX',
			'default' => '18',
			'title'   => esc_html__( 'Product price font size', 'merchant' ),
		),
		array(
			'id'      => 'cart_details_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Cart details color', 'merchant' ),
			'default' => '#000',
		),
		array(
			'id'      => 'buttons_corner_radius',
			'type'    => 'range',
			'min'     => '0',
			'max'     => '25',
			'step'    => '1',
			'unit'    => 'PX',
			'default' => '25',
			'title'   => esc_html__( 'Buttons corner radius', 'merchant' ),
		),
		array(
			'id'      => 'cart_main_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Buttons main color', 'merchant' ),
			'default' => '#3858e9',
		),
		array(
			'id'      => 'cart_alternate_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Buttons alternate color', 'merchant' ),
			'default' => '#fff',
		),
		array(
			'id'      => 'suggested_products_section_title_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Suggested products section title color', 'merchant' ),
			'default' => '#000',
		),

		array(
			'id'      => 'suggested_product_name_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Suggested products name color', 'merchant' ),
			'default' => '#000',
		),

		array(
			'id'      => 'suggested_product_price_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Suggested products price color', 'merchant' ),
			'default' => '#8e8e8e',
		),
	),
) );