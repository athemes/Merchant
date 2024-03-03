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
			'id'    => 'display_on_backorders',
			'type'  => 'switcher',
			'title' => esc_html__( 'Display on backorders?', 'merchant' ),
		),

		array(
			'id'      => 'form_title',
			'type'    => 'text',
			'title'   => esc_html__( 'Form title', 'merchant' ),
			'default' => __( 'Email me when this item is back in stock.', 'merchant' ),
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
			'default' => __( 'You are now subscribed to our stock notification list for this product. When stock becomes available, we will let you know you via email.', 'merchant' ),
			'desc'    => esc_html__( 'The message that will show after form submission.', 'merchant' ),
		),
		array(
			'id'      => 'form_unsubscribe_message',
			'type'    => 'textarea',
			'title'   => esc_html__( 'Form unsubscribe message', 'merchant' ),
			'default' => __( 'You have been successfully unsubscribed from our stock waitlist.', 'merchant' ),
			'desc'    => esc_html__( 'The message that will show after clicking on unsubscribe link.', 'merchant' ),
		),
	),
) );

Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Email Settings', 'merchant' ),
	'module' => Merchant_Wait_List::MODULE_ID,
	'fields' => array(

		array(
			'id'      => 'use_automatic_emails',
			'type'    => 'switcher',
			'title'   => __( 'Send emails automatically', 'merchant' ),
			'default' => 1,
			'desc'    => esc_html__( 'When products are back in stock, send emails automatically to subscribers to let them know.', 'merchant' ),
		),

		array(
			'id'      => 'email_new_subscriber',
			'type'    => 'textarea_multiline',
			'title'   => esc_html__( 'Email new subscribers', 'merchant' ),
			'default' => __( 'Hello, thank you for joining the stock notification list for {product}. We will email you when the product is back in stock.', 'merchant' ),
			'desc'    => esc_html__( 'The message that will be sent to new subscribers.', 'merchant' ),
		),

		array(
			'type'    => 'info',
			'content' => sprintf(
			/* Translators: 1. docs link */
				__( 'Click <a href="%1$s" target="_blank">here</a> to preview the new subscriber email.', 'merchant' ),
				esc_url(
					add_query_arg(
						array(
							'action' => 'merchant_pro_preview_new_subscriber_email',
							'nonce'  => wp_create_nonce( 'merchant_pro_wait_list_mailer_preview' ),
						),
						admin_url( 'admin-post.php' )
					)
				)
			),
		),

		array(
			'id'      => 'email_update',
			'type'    => 'textarea_multiline',
			'title'   => esc_html__( 'Email in stock update', 'merchant' ),
			'default' => __( 'Hello, we’re pleased to let you know that {product} is now back in stock.',
				'merchant' ),
			'desc'    => esc_html__( 'The message that will be sent to subscribers when a product is back in stock.', 'merchant' ),
		),
		array(
			'type'    => 'info',
			'content' => sprintf(
			/* Translators: 1. docs link */
				__( 'Click <a href="%1$s" target="_blank">here</a> to preview the stock update email.', 'merchant' ),
				esc_url(
					add_query_arg(
						array(
							'action' => 'merchant_pro_preview_stock_update_email',
							'nonce'  => wp_create_nonce( 'merchant_pro_wait_list_mailer_preview' ),
						),
						admin_url( 'admin-post.php' )
					)
				)
			),
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