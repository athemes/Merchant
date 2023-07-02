<?php
/**
 * Merchant - Pre Orders
 */

/**
 * Tag Pre-Orders
 */

$content  = '';
$content .= '<div class="merchant-tag-pre-orders">';
$content .= '<i class="dashicons dashicons-info"></i>';
$content .= '<p>';
$content .= esc_html__( 'Pre-orders captured by Merchant are tagged with "MerchantPreOrder" and can be found in your WooCommerce Order Section.', 'merchant' );
$content .= sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( admin_url( 'edit.php?post_type=shop_order' ) ), esc_html__( 'View Pre-Orders', 'merchant' ) );
$content .= '</p>';
$content .= '</div>';

Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Tag Pre-Orders', 'merchant' ),
	'module' => 'pre-orders',
	'fields' => array(

		array(
			'id'      => 'tag-pre-orders',
			'type'    => 'content',
			'content' => $content,
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
			'default' => esc_html__( 'Ships in {date}.', 'merchant' ),
		),

	),
) );
