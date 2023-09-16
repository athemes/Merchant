<?php

/**
 * Wait List Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Form Settings', 'merchant' ),
	'module' => Merchant_Wait_List::MODULE_ID,
	'fields' => array(

		array(
			'id'      => 'form_title',
			'type'    => 'text',
			'title'   => esc_html__( 'Form title', 'merchant' ),
			'default' => __( 'New stock on its way! Email when stock available', 'merchant' ),
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
			'default' => __( 'You have been successfully added to our wait list. As soon as new stock become available, we will notify you via email.', 'merchant' ),
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
			'default' => __( 'Hello subscriber, Thank you for subscribing to {product}. We will email you once product back in stock.', 'merchant' ),
			'desc'    => esc_html__( 'The message that will be sent to new subscribers.', 'merchant' ),
		),

		array(
			'id'      => 'email_update',
			'type'    => 'textarea',
			'title'   => esc_html__( 'Email in stock update', 'merchant' ),
			'default' => __( 'Hello Subscriber, Thanks for your patience and finally the wait is over! Your Subscribed Product {product} is now back in stock! We only have a limited amount of stock, and this email is not a guarantee you\'ll get one, so hurry to be one of the lucky shoppers who do. Add this product {product} directly to your cart.',
				'merchant' ),
			'desc'    => esc_html__( 'The message that will be sent to subscribers when product is in stock.', 'merchant' ),
		),

	),
) );


