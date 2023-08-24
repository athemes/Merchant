<?php
/**
 * Agree to Terms Checkout
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
	'module' => 'agree-to-terms-checkbox',
	'fields' => array(

		array(
			'id'      => 'label',
			'type'    => 'text',
			'title'   => esc_html__( 'Label', 'merchant' ),
			'default' => esc_html__( 'I agree with the', 'merchant' ),
		),

		array(
			'id'      => 'text',
			'type'    => 'text',
			'title'   => esc_html__( 'Terms and conditions text', 'merchant' ),
			'default' => esc_html__( 'Terms & Conditions', 'merchant' ),
		),

		array(
			'id'      => 'link',
			'type'    => 'text',
			'title'   => esc_html__( 'Terms and conditions link', 'merchant' ),
			'default' => get_privacy_policy_url(),
		),

		array(
			'id'      => 'warning_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Checkbox warning text', 'merchant' ),
			'default' => esc_html__( 'You must read and accept the terms and conditions to complete checkout.', 'merchant' ),
			'desc'    => esc_html__( 'The error will be shown if the user tries to go to checkout without accepting the terms & conditions.', 'merchant' ),
		),

	),
) );
