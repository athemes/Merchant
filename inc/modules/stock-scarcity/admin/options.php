<?php

/**
 * Stock Scarcity Options.
 *
 * @package Merchant
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

// Settings
Merchant_Admin_Options::create(array(
	'module' => Merchant_Stock_Scarcity::MODULE_ID,
	'title'  => esc_html__('Settings', 'merchant'),
	'fields' => array(

		array(
			'id'      => 'min_inventory',
			'type'    => 'number',
			'title'   => esc_html__('Show urgency box when variant inventory is below', 'merchant'),
			'default' => 50,
		),
		array(
			'id'      => 'display-pages',
			'type'    => 'checkbox_multiple',
			'title'   => esc_html__( 'Show on pages', 'merchant' ),
			'options' => array(
				'product'  => esc_html__( 'Product Page', 'merchant' ),
				'archive'   => esc_html__( 'Product Archive', 'merchant' ),
			),
			'default' => array( 'archive', 'product' ),
		),

		// Loading position/priority on single product.
		array(
			'id'      => 'hook-order-single-product',
			'type'    => 'hook_select',
			'title'   => esc_html__('Loading position and priority on single product', 'merchant'),
			'options' => array(
				'woocommerce_before_add_to_cart_button' => esc_html__('Before add to cart button', 'merchant'),
				'woocommerce_after_add_to_cart_button' => esc_html__('After add to cart button', 'merchant'),
				'woocommerce_before_add_to_cart_quantity' => esc_html__('Before add to cart quantity', 'merchant'),
				'woocommerce_after_add_to_cart_quantity' => esc_html__('After add to cart quantity', 'merchant'),
			),
			'min'     => -999,
			'max'     => 999,
			'step'    => 1,
			'unit'    => '',
			'order' => true,
			'default' => array(
				'hook_name'     => 'woocommerce_after_add_to_cart_button',
				'hook_priority' => 10,
			),
		),
		array(
			'type'    => 'warning',
			'content' => esc_html__('This is a developer level feature. The Stock Scarcity module is "hooked" into a specific location on the single product pages. Themes and other plugins might also add additional elements to the same location. By modifying the loading postiion and priority, you have the ability to customize the placement of this element on that particular location. A lower number = a higher priority, so the module will appear higher on the page.', 'merchant'),
		),

		// Loading position/priority on shop archive.
		array(
			'id'      => 'hook-order-shop-archive',
			'type'    => 'hook_select',
			'title'   => esc_html__('Loading position and priority on shop archive', 'merchant'),
			'options' => array(
				'woocommerce_before_shop_loop_item' => esc_html__('Before shop loop item', 'merchant'),
				'woocommerce_before_shop_loop_item_title' => esc_html__('Before shop loop item title', 'merchant'),
				'woocommerce_shop_loop_item_title' => esc_html__('Shop loop item title', 'merchant'),
				'woocommerce_after_shop_loop_item_title' => esc_html__('After shop loop item title', 'merchant'),
				'woocommerce_after_shop_loop_item' => esc_html__('After shop loop item', 'merchant'),
			),
			'min'     => -999,
			'max'     => 999,
			'step'    => 1,
			'unit'    => '',
			'order' => true,
			'default' => array(
				'hook_name'     => 'woocommerce_after_shop_loop_item',
				'hook_priority' => 10,
			),
		),
		array(
			'type'    => 'warning',
			'content' => esc_html__('This is a developer level feature. The Stock Scarcity module is "hooked" into a specific location on the shop archive pages. Themes and other plugins might also add additional elements to the same location. By modifying the loading postiion and priority, you have the ability to customize the placement of this element on that particular location. A lower number = a higher priority, so the module will appear higher on the page.', 'merchant'),
		),
	),
));


// Text Formatting Settings
Merchant_Admin_Options::create(array(
	'title'  => esc_html__('Text Formatting Settings', 'merchant'),
	'module' => Merchant_Stock_Scarcity::MODULE_ID,
	'fields' => array(

		array(
			'id'      => 'low_inventory_text',
			'type'    => 'text',
			'title'   => esc_html__('Text when inventory is low', 'merchant'),
			'default' => esc_html__('Hurry! Only {stock} unit left in stock!', 'merchant'),
		),

		array(
			'id'      => 'low_inventory_text_plural',
			'type'    => 'text',
			'title'   => esc_html__('Text when inventory is low (plural)', 'merchant'),
			'default' => esc_html__('Hurry! Only {stock} units left in stock!', 'merchant'),
		),

		array(
			'id'      => 'low_inventory_text_simple',
			'type'    => 'text',
			'title'   => esc_html__('Text when inventory is low (simple - used for product variation)', 'merchant'),
			'default' => esc_html__('Hurry, low stock.', 'merchant'),
		),
	),
));



// Style
Merchant_Admin_Options::create(array(
	'module' => Merchant_Stock_Scarcity::MODULE_ID,
	'title'  => esc_html__('Style', 'merchant'),
	'fields' => array(

		array(
			'id'      => 'gradient_start',
			'type'    => 'color',
			'title'   => esc_html__('Progress bar gradient start', 'merchant'),
			'default' => '#ffc108',
		),

		array(
			'id'      => 'gradient_end',
			'type'    => 'color',
			'title'   => esc_html__('Progress bar gradient end', 'merchant'),
			'default' => '#d61313',
		),

		array(
			'id'      => 'progress_bar_bg',
			'type'    => 'color',
			'title'   => esc_html__('Progress bar background color', 'merchant'),
			'default' => '#e1e1e1',
		),

		array(
			'id'      => 'text_font_weight',
			'type'    => 'select',
			'title'   => esc_html__('Text font weight', 'merchant'),
			'options' => array(
				'lighter' => esc_html__('Light', 'merchant'),
				'normal'  => esc_html__('Normal', 'merchant'),
				'bold' => esc_html__('Bold', 'merchant'),
			),
			'default' => 'normal',
		),

		array(
			'id'      => 'text_font_size',
			'type'    => 'range',
			'title'   => esc_html__('Text font size', 'merchant'),
			'min'     => 0,
			'max'     => 100,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 16,
		),

		array(
			'id'      => 'text_text_color',
			'type'    => 'color',
			'title'   => esc_html__('Text text color', 'merchant'),
			'default' => '#212121',
		),

	),
));

// Shortcode
$merchant_module_id = Merchant_Stock_Scarcity::MODULE_ID;
Merchant_Admin_Options::create(array(
	'module' => $merchant_module_id,
	'title'  => esc_html__('Use shortcode', 'merchant'),
	'fields' => array(
		array(
			'id'      => 'use_shortcode',
			'type'    => 'switcher',
			'title'   => __('Use shortcode', 'merchant'),
			'default' => 0,
		),
		array(
			'type'    => 'warning',
			'content' => esc_html__(
				'If you are using a page builder or a theme that supports shortcodes, then you can output the module using the shortcode above. This might be useful if, for example, you find that you want to control the position of the module output more precisely than with the module settings. Note that the shortcodes can only be used on single product pages.',
				'merchant'
			),
		),
		array(
			'id'        => 'shortcode_text',
			'type'      => 'text_readonly',
			'title'     => esc_html__('Shortcode text', 'merchant'),
			'default'   => '[merchant_module_' . str_replace('-', '_', $merchant_module_id) . ']',
			'condition' => array( 'use_shortcode', '==', '1' ),
		),
	),
));
