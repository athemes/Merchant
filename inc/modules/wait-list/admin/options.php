<?php

/**
 * Wait List Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Form Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Form Settings', 'merchant' ),
	'module' => Merchant_Wait_List::MODULE_ID,
	'fields' => array(

		array(
			'id'        => 'display_on_backorders',
			'type'      => 'switcher',
			'title'     => esc_html__( 'Display on backorders?', 'merchant' ),
		),

		array(
			'id'      => 'form_title',
			'type'    => 'text',
			'title'   => esc_html__( 'Form title', 'merchant' ),
			'default' => __( 'New stock is coming! Email me when this item is back in stock', 'merchant' ),
		),

		array(
			'id'      => 'form_email_label',
			'type'    => 'text',
			'title'   => esc_html__( 'Form email label', 'merchant' ),
			'default' => __( 'Your Email Address', 'merchant' ),
		),

		array(
			'id'      => 'form_button_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Form button text', 'merchant' ),
			'default' => __( 'Notify Me', 'merchant' ),
		),

		array(
			'id'      => 'form_success_message',
			'type'    => 'textarea',
			'title'   => esc_html__( 'Form success message', 'merchant' ),
			'default' => __( 'You have been successfully added to our stock waitlist. As soon as new stock becomes available, we will notify you via email.', 'merchant' ),
			'desc'    => esc_html__( 'The message that will show after form submission.', 'merchant' ),
		),
	),
) );

Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Email Settings', 'merchant' ),
	'module' => Merchant_Wait_List::MODULE_ID,
	'fields' => array(

		array(
			'id'      => 'email_new_subscriber',
			'type'    => 'textarea',
			'title'   => esc_html__( 'Email new subscribers', 'merchant' ),
			'default' => __( 'Hello, thank you for subscribing to the stock waitlist for {product}. We will email you once the product back in stock.', 'merchant' ),
			'desc'    => esc_html__( 'The message that will be sent to new subscribers.', 'merchant' ),
		),

		array(
			'id'      => 'email_update',
			'type'    => 'textarea',
			'title'   => esc_html__( 'Email in stock update', 'merchant' ),
			'default' => __( 'Hello, thanks for your patience and finally the wait is over! Your {product} is now back in stock! We only have a limited amount of stock, and this email is not a guarantee you’ll get one. Add this {product} directly to your cart.',
				'merchant' ),
			'desc'    => esc_html__( 'The message that will be sent to subscribers when product is in stock.', 'merchant' ),
		),

	),
) );

// Shortcode
$merchant_module_id = Merchant_Wait_List::MODULE_ID;
Merchant_Admin_Options::create( array(
	'module' => $merchant_module_id,
	'title'  => esc_html__( 'Use shortcode', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'use_shortcode',
			'type'    => 'switcher',
			'title'   => __( 'Use shortcode', 'merchant' ),
			'default' => 0,
			'desc'      => esc_html__( 'If you are using a page builder or a theme that supports shortcodes, then you can output the module using the shortcode above. This might be useful if, for example, you find that you want to control the position of the module output more precisely than with the module settings. Note that the shortcodes can only be used on single product pages.',
				'merchant' ),
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