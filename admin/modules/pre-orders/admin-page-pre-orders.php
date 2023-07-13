<?php
/**
 * Merchant - Pre Orders
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Tag Pre-Orders
 */
$merchant_pre_orders_notice  = '';
$merchant_pre_orders_notice .= '<div class="merchant-tag-pre-orders">';
$merchant_pre_orders_notice .= '<i class="dashicons dashicons-info"></i>';
$merchant_pre_orders_notice .= '<p>';
$merchant_pre_orders_notice .= esc_html__( 'Pre-orders captured by Merchant are tagged with "MerchantPreOrder" and can be found in your WooCommerce Order Section.', 'merchant' );
$merchant_pre_orders_notice .= sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( admin_url( 'edit.php?post_type=shop_order' ) ), esc_html__( 'View Pre-Orders', 'merchant' ) );
$merchant_pre_orders_notice .= '</p>';
$merchant_pre_orders_notice .= '</div>';

Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Tag Pre-Orders', 'merchant' ),
	'module' => 'pre-orders',
	'fields' => array(

		array(
			'id'      => 'tag-pre-orders',
			'type'    => 'content',
			'content' => $merchant_pre_orders_notice,
		),

	),
) );

/**
 * Settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'pre-orders',
	'fields' => array(

		array(
			'id'      => 'button_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Button Text', 'merchant' ),
			'default' => esc_html__( 'Pre Order Now!', 'merchant' ),
		),

		array(
			'id'    => 'text-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button Text Color', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'    => 'text-hover-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button Text Color Hover', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'    => 'border-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button Border Color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'    => 'border-hover-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button Border Color Hover', 'merchant' ),
			'default' => '#414141',
		),

		array(
			'id'    => 'background-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button Background Color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'    => 'background-hover-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button Background Color Hover', 'merchant' ),
			'default' => '#414141',
		),

		array(
			'id'      => 'additional_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Additional Information', 'merchant' ),
			'default' => esc_html__( 'Ships on {date}.', 'merchant' ),
		),

	),
) );
