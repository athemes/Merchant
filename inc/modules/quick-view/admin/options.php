<?php
/**
 * Quick View
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Settings.
 * 
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'quick-view',
	'fields' => array(

		array(
			'id'      => 'button_type',
			'type'    => 'select',
			'title'   => esc_html__( 'Button type', 'merchant' ),
			'options' => array(
				'text'      => esc_html__( 'Text', 'merchant' ),
				'icon'      => esc_html__( 'Icon', 'merchant' ),
				'icon-text' => esc_html__( 'Icon + text', 'merchant' ),
			),
			'default' => 'text',
		),

		array(
			'id'        => 'button_text',
			'type'      => 'text',
			'title'     => esc_html__( 'Button text', 'merchant' ),
			'default'   => esc_html__( 'Quick view', 'merchant' ),
			'condition' => array( 'button_type', 'any', 'text|icon-text' ),
		),

		array(
			'id'        => 'button_icon',
			'type'      => 'choices',
			'title'     => esc_html__( 'Select an icon', 'merchant' ),
			'options'   => array(
				'eye'     => '%s/eye.svg',
				'cart'    => '%s/cart.svg',
			),
			'default'   => 'eye',
			'condition' => array( 'button_type', 'any', 'icon|icon-text' ),
		),

		array(
			'id'        => 'button_position',
			'type'      => 'select',
			'title'     => esc_html__( 'Button position', 'merchant' ),
			'options'   => array(
				'before'  => esc_html__( 'Before - Add to cart', 'merchant' ),
				'after'   => esc_html__( 'After - Add to cart', 'merchant' ),
				'overlay' => esc_html__( 'Overlay', 'merchant' ),
			),
			'default'   => 'overlay',
		),

		array(
			'id'      => 'button-position-top',
			'type'    => 'range',
			'title'   => esc_html__( 'Button position top', 'merchant' ),
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'default' => 50,
			'unit'    => '%',
			'condition' => array( 'button_position', '==', 'overlay' ),
		),

		array(
			'id'      => 'button-position-left',
			'type'    => 'range',
			'title'   => esc_html__( 'Button position left', 'merchant' ),
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'default' => 50,
			'unit'    => '%',
			'condition' => array( 'button_position', '==', 'overlay' ),
		),

		array(
			'id'        => 'icon-color',
			'type'      => 'color',
			'title'     => esc_html__( 'Icon color', 'merchant' ),
			'default'   => '#ffffff',
			'condition' => array( 'button_type', 'any', 'icon|icon-text' ),
		),

		array(
			'id'        => 'icon-hover-color',
			'type'      => 'color',
			'title'     => esc_html__( 'Icon color hover', 'merchant' ),
			'default'   => '#ffffff',
			'condition' => array( 'button_type', 'any', 'icon|icon-text' ),
		),

		array(
			'id'        => 'text-color',
			'type'      => 'color',
			'title'     => esc_html__( 'Button text color', 'merchant' ),
			'default'   => '#ffffff',
			'condition' => array( 'button_type', 'any', 'text|icon-text' ),
		),

		array(
			'id'        => 'text-hover-color',
			'type'      => 'color',
			'title'     => esc_html__( 'Button text color hover', 'merchant' ),
			'default'   => '#ffffff',
			'condition' => array( 'button_type', 'any', 'text|icon-text' ),
		),

		array(
			'id'    => 'border-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button border color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'    => 'border-hover-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button border color hover', 'merchant' ),
			'default' => '#414141',
		),

		array(
			'id'    => 'background-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button background color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'    => 'background-hover-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button background color hover', 'merchant' ),
			'default' => '#414141',
		),

	),
) );


/**
 * Modal.
 * 
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Modal', 'merchant' ),
	'module' => 'quick-view',
	'fields' => array(
		array(
			'id'      => 'modal_width',
			'type'    => 'range',
			'title'   => esc_html__( 'Modal width', 'merchant' ),
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'default' => 1000,
			'unit'    => 'px',
		),

		array(
			'id'      => 'modal_height',
			'type'    => 'range',
			'title'   => esc_html__( 'Modal height', 'merchant' ),
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'default' => 500,
			'unit'    => 'px',
		),

		array(
			'id'      => 'place_product_image',
			'type'    => 'radio',
			'title'   => esc_html__( 'Place product image', 'merchant' ),
			'options' => array(
				'thumbs-at-left'   => esc_html__( 'Thumbs at left', 'merchant' ),
				'thumbs-at-right'  => esc_html__( 'Thumbs at right', 'merchant' ),
				'thumbs-at-bottom' => esc_html__( 'Thumbs at bottom', 'merchant' ),
			),
			'default' => 'thumbs-at-left',
		),

		array(
			'id'      => 'zoom_effect',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Zoom effect on image', 'merchant' ),
			'default' => 1,
		),

		array(
			'id'       => 'place_product_description',
			'type'     => 'radio',
			'title'    => esc_html__( 'Place product description', 'merchant' ),
			'options'  => array(
				'top'    => esc_html__( 'Top', 'merchant' ),
				'bottom' => esc_html__( 'Bottom', 'merchant' ),
			),
			'default'  => 'top',
		),

		array(
			'id'      => 'description_style',
			'type'    => 'radio',
			'title'   => esc_html__( 'Description style', 'merchant' ),
			'options' => array(
				'full'  => esc_html__( 'Full description', 'merchant' ),
				'short' => esc_html__( 'Short description', 'merchant' ),
			),
			'default' => 'full',
		),

		array(
			'id'      => 'show_quantity',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Show quantity selector', 'merchant' ),
			'default' => 1,
		),

		array(
			'id'      => 'show_buy_now_button',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Show buy now button', 'merchant' ),
			'default' => 0,
		),

		array(
			'id'          => 'show_buy_now_button_instructions',
			'type'        => 'info_block',
			'description' => esc_html__( 'You can display a Buy Now button by using Merchant’s Buy Now module.', 'merchant' ),
			'button_text' => esc_html__( 'View Buy Now', 'merchant' ),
			'button_link' => esc_url( admin_url( 'admin.php?page=merchant&module=buy-now' ) ),
			'condition'   => array( 'show_buy_now_button', '==', '1' ),
		),

		array(
			'id'      => 'show_suggested_products',
			'pro'     => true, // Merchant Pro dependent
			'type'    => 'switcher',
			'title'   => esc_html__( 'Show suggested products', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'         => 'suggested_products_module',
			'pro'        => true,
			'type'       => 'select',
			'options'    => array(
				'bulk_discounts'             => esc_html__( 'Bulk Discounts', 'merchant' ),
				'buy_x_get_y'                => esc_html__( 'Buy X Get Y', 'merchant' ),
				'frequently_bought_together' => esc_html__( 'Frequently Bought Together', 'merchant' ),
			),
			'default'    => 'bulk_discounts',
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'show_suggested_products',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),
		array(
			'id'          => 'suggested_products_instructions_bulk_discounts',
			'pro'         => true,
			'type'        => 'info_block',
			'description' => esc_html__( 'You can display suggested products by enabling certain modules (Bulk Discounts, Buy X Get Y or Frequently Bought Together) from Merchant.', 'merchant' ),
			'button_text' => esc_html__( 'View Bulk Discounts', 'merchant' ),
			'button_link' => esc_url( admin_url( 'admin.php?page=merchant&module=volume-discounts' ) ),
			'conditions'  => array(
				'relation' => 'AND',
				'terms'    => array(
					array(
						'field'    => 'show_suggested_products',
						'operator' => '===',
						'value'    => true,
					),
					array(
						'field'    => 'suggested_products_module',
						'operator' => '===',
						'value'    => 'bulk_discounts',
					),
				),
			),
		),
		array(
			'id'          => 'suggested_products_instructions_frequently_bought_together',
			'pro'         => true,
			'type'        => 'info_block',
			'description' => esc_html__( 'You can display suggested products by enabling certain modules (Bulk Discounts, Buy X Get Y or Frequently Bought Together) from Merchant.', 'merchant' ),
			'button_text' => esc_html__( 'View Frequently Bought Together', 'merchant' ),
			'button_link' => esc_url( admin_url( 'admin.php?page=merchant&module=frequently-bought-together' ) ),
			'conditions'  => array(
				'relation' => 'AND',
				'terms'    => array(
					array(
						'field'    => 'show_suggested_products',
						'operator' => '===',
						'value'    => true,
					),
					array(
						'field'    => 'suggested_products_module',
						'operator' => '===',
						'value'    => 'frequently_bought_together',
					),
				),
			),
		),
		array(
			'id'          => 'suggested_products_instructions_buy_x_get_y',
			'pro'         => true,
			'type'        => 'info_block',
			'description' => esc_html__( 'You can display suggested products by enabling certain modules (Bulk Discounts, Buy X Get Y or Frequently Bought Together) from Merchant.', 'merchant' ),
			'button_text' => esc_html__( 'View Buy X Get Y', 'merchant' ),
			'button_link' => esc_url( admin_url( 'admin.php?page=merchant&module=buy-x-get-y' ) ),
			'conditions'  => array(
				'relation' => 'AND',
				'terms'    => array(
					array(
						'field'    => 'show_suggested_products',
						'operator' => '===',
						'value'    => true,
					),
					array(
						'field'    => 'suggested_products_module',
						'operator' => '===',
						'value'    => 'buy_x_get_y',
					),
				),
			),
		),
		array(
			'id'         => 'suggested_products_placement',
			'pro'        => true,
			'type'       => 'radio',
			'title'      => esc_html__( 'Suggested products placement', 'merchant' ),
			'options'    => array(
				'before_add_to_cart' => esc_html__( 'Before Add to Cart', 'merchant' ),
				'after_add_to_cart'  => esc_html__( 'After Add to Cart', 'merchant' ),
			),
			'default'    => 'after_add_to_cart',
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'show_suggested_products',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),

		array(
			'id'      => 'sale-price-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Sale price color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'regular-price-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Regular price color', 'merchant' ),
			'default' => '#999999',
		),

	),
) );
