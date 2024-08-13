<?php
/**
 * Clear Cart
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => Merchant_Clear_Cart::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'enable_auto_clear',
			'type'    => 'switcher',
			'title'   => __( 'Clear Cart After Inactivity', 'merchant' ),
			'desc'    => __( 'Show a prompt to clear the cart when there\'s no user activity for this amount of time.', 'merchant' ),
			'default' => 0,
		),

		array(
			'id'        => 'auto_clear_expiration_hours',
			'type'      => 'number',
			'title'     => esc_html__( 'Time of Cart Session Expiration (hours)', 'merchant' ),
			'desc'      => esc_html__( 'Cart will be automatically cleared after this time', 'merchant' ),
			'condition' => array( 'enable_auto_clear', '==', '1' ),
			'default'   => 24,
		),

		array(
			'id'        => 'auto_clear_popup_message',
			'type'      => 'text',
			'title'     => esc_html__( 'Confirmation Message', 'merchant' ),
			'desc'      => esc_html__( 'Clear cart confirmation dialog box', 'merchant' ),
			'default'   => esc_html__( 'It looks like you haven’t been active for a while. Would you like to empty your shopping cart?', 'merchant' ),
			'condition' => array( 'enable_auto_clear', '==', '1' ),
		),

		array(
			'id'        => 'redirect_link',
			'type'      => 'radio',
			'title'     => esc_html__( 'Redirect URL after Clearing the Cart', 'merchant' ),
			'options'   => array(
				''       => esc_html__( 'None', 'merchant' ),
				'home'   => esc_html__( 'Home', 'merchant' ),
				'shop'   => esc_html__( 'Shop', 'merchant' ),
				'custom' => esc_html__( 'Custom', 'merchant' ),
			),
			'default'   => '',
		),

		array(
			'id'        => 'redirect_link_custom',
			'type'      => 'text',
			'title'     => esc_html__( 'Custom Redirect URL', 'merchant' ),
			'desc'      => esc_html__( 'The URL pattern is /page-name, /category, /pages/contact, etc.', 'merchant' ),
			'default'   => '',
			'condition' => array( 'redirect_link', '==', 'custom' ),
		),
	),
) );

/**
 * Placement
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Placement', 'merchant' ),
	'module' => Merchant_Clear_Cart::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'enable_cart_page',
			'type'    => 'switcher',
			'title'   => __( 'Cart Page', 'merchant' ),
			'default' => 1,
		),

		array(
			'id'        => 'cart_page_position',
			'type'      => 'select',
			'title'     => esc_html__( 'Position', 'merchant' ),
			'options'   => array(
				'before_update_cart' => esc_html__( 'Before Update Cart Button', 'merchant' ),
				'after_update_cart'  => esc_html__( 'After Update Cart Button', 'merchant' ),
			),
			'condition' => array( 'enable_cart_page', '==', '1' ),
			'default'   => 'before_update_cart',
		),

		array(
			'id'      => 'enable_mini_cart',
			'type'    => 'switcher',
			'title'   => __( 'Mini Cart', 'merchant' ),
			'default' => 1,
		),

		array(
			'id'        => 'cart_page_position',
			'type'      => 'select',
			'title'     => esc_html__( 'Position', 'merchant' ),
			'options'   => array(
				'before_subtotal' => esc_html__( 'Before Subtotal', 'merchant' ),
				'after_subtotal'  => esc_html__( 'After Subtotal', 'merchant' ),
			),
			'condition' => array( 'enable_mini_cart', '==', '1' ),
			'default'   => 'after_subtotal',
		),

		array(
			'id'      => 'enable_side_cart',
			'type'    => 'switcher',
			'title'   => __( 'Side Cart - // Todo check if side cart enabled', 'merchant' ),
			'default' => 1,
		),

		array(
			'id'        => 'cart_page_position',
			'type'      => 'select',
			'title'     => esc_html__( 'Position', 'merchant' ),
			'options'   => array(
				'before_subtotal' => esc_html__( 'Before Subtotal', 'merchant' ),
				'after_subtotal'  => esc_html__( 'After Subtotal', 'merchant' ),
			),
			'condition' => array( 'enable_side_cart', '==', '1' ),
			'default'   => 'after_subtotal',
		),
	),
) );

/**
 * Style
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Look and Feel', 'merchant' ),
	'module' => Merchant_Clear_Cart::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'button_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Button Text', 'merchant' ),
			'default' => esc_html__( 'Clear Cart', 'merchant' ),
		),

		array(
			'id'      => 'button_style',
			'type'    => 'radio',
			'title'   => esc_html__( 'Style', 'merchant' ),
			'options' => array(
				'solid'   => esc_html__( 'Solid', 'merchant' ),
				'outline' => esc_html__( 'Outline', 'merchant' ),
				'text'    => esc_html__( 'Text', 'merchant' ),
			),
			'default' => 'solid',
		),

		array(
			'id'        => 'border_width',
			'type'      => 'range',
			'title'     => esc_html__( 'Outline', 'merchant' ),
			'min'       => 1,
			'max'       => 20,
			'step'      => 1,
			'unit'      => 'px',
			'default'   => 2,
			'condition' => array( 'button_style', '==', 'outline' ),
		),

		array(
			'id'        => 'border_radius',
			'type'      => 'range',
			'title'     => esc_html__( 'Border Radius', 'merchant' ),
			'min'       => 0,
			'max'       => 999,
			'step'      => 1,
			'unit'      => 'px',
			'default'   => 5,
			'condition' => array( 'button_style', 'any', 'solid|outline' ),
		),

		array(
			'id'        => 'padding_vertical',
			'type'      => 'range',
			'title'     => esc_html__( 'Padding Top/Bottom', 'merchant' ),
			'min'       => 0,
			'max'       => 100,
			'step'      => 1,
			'default'   => 10,
			'unit'      => 'px',
			'condition' => array( 'button_style', 'any', 'solid|outline' ),
		),

		array(
			'id'        => 'padding_horizontal',
			'type'      => 'range',
			'title'     => esc_html__( 'Padding Left/Right', 'merchant' ),
			'min'       => 0,
			'max'       => 100,
			'step'      => 1,
			'default'   => 15,
			'unit'      => 'px',
			'condition' => array( 'button_style', 'any', 'solid|outline' ),
		),

		array(
			'id'      => 'margin_vertical',
			'type'    => 'range',
			'title'   => esc_html__( 'Margin Top/Bottom', 'merchant' ),
			'min'     => 0,
			'max'     => 100,
			'step'    => 1,
			'default' => 10,
			'unit'    => 'px',
		),

		array(
			'id'        => 'margin_horizontal',
			'type'      => 'range',
			'title'     => esc_html__( 'Margin Left/Right', 'merchant' ),
			'min'       => 0,
			'max'       => 100,
			'step'      => 1,
			'default'   => 10,
			'unit'      => 'px',
		),

		array(
			'id'        => 'background_color',
			'type'      => 'color',
			'title'     => esc_html__( 'Button Background Color', 'merchant' ),
			'default'   => '#212121',
			'condition' => array( 'button_style', '==', 'solid' ),
		),

		array(
			'id'        => 'text_color',
			'type'      => 'color',
			'title'     => esc_html__( 'Button Text Color', 'merchant' ),
			'default'   => '#ffffff',
		),

		array(
			'id'        => 'border_color',
			'type'      => 'color',
			'title'     => esc_html__( 'Button Outline Color', 'merchant' ),
			'default'   => '#212121',
			'condition' => array( 'button_style', '==', 'outline' ),
		),
	),
) );

// Shortcode
$merchant_module_id = Merchant_Clear_Cart::MODULE_ID;
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
			'content' => esc_html__( 'If you are using a page builder or a theme that supports shortcodes, then you can output the module using the shortcode above. This might be useful if, for example, you find that you want to control the position of the module output more precisely than with the module settings.', 'merchant' ),
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
