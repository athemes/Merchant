<?php

/**
 * Free Gifts Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Offers', 'merchant' ),
	'module' => Merchant_Free_Gifts::MODULE_ID,
	'fields' => array(
		array(
			'id'           => 'offers',
			'type'         => 'flexible_content',
			'button_label' => esc_html__( 'Add New Offer', 'merchant' ),
			'style'        => Merchant_Free_Gifts::MODULE_ID . '-style default',
			'sorting'      => true,
			'accordion'    => false,
			'layouts'      => array(
				'spending' => array(
					'title'  => esc_html__( 'Spending Based', 'merchant' ),
					'title-field' => 'offer-title', // text field ID to use as title for the layout
					'fields'      => array(
						array(
							'id'      => 'offer-title',
							'type'    => 'text',
							'title'   => esc_html__( 'Offer name', 'merchant' ),
							'default' => esc_html__( 'Campaign', 'merchant' ),
						),
						array(
							'id'       => 'product',
							'type'     => 'products_selector',
							'title'    => esc_html__( 'Product', 'merchant' ),
							'multiple' => false,
							'desc'     => esc_html__( 'Select the products that will contain the bundle.',
								'merchant' ),
						),
						'amount' => array(
							'id'     => 'amount',
							'title'  => esc_html__( 'Spending Goal To Receive This Product For Free', 'merchant' ),
							'type'   => 'number',
							'append' => function_exists( 'get_woocommerce_currency_symbol' ) ? get_woocommerce_currency() : esc_html__( 'USD', 'merchant' ),
						),
					),
				),
				'coupon'   => array(
					'title'  => esc_html__( 'Coupon Based', 'merchant' ),
					'title-field' => 'offer-title', // text field ID to use as title for the layout
					'fields'      => array(
						array(
							'id'      => 'offer-title',
							'type'    => 'text',
							'title'   => esc_html__( 'Offer name', 'merchant' ),
							'default' => esc_html__( 'Campaign', 'merchant' ),
						),
						array(
							'id'       => 'product',
							'type'     => 'products_selector',
							'title'    => esc_html__( 'Product', 'merchant' ),
							'multiple' => false,
							'desc'     => esc_html__( 'Select the products that will contain the bundle.',
								'merchant' ),
						),
						'coupon' => array(
							'id'    => 'coupon',
							'title' => esc_html__( 'Use Coupon To Receive This Product For Free', 'merchant' ),
							'type'  => 'wc_coupons',
						),
					),
				),
			),
			'default'      => array(
				array(
					'layout'        => 'spending',
					'min_quantity'  => 2,
					'discount'      => 10,
					'discount_type' => 'percentage_discount',
				),
			),
		),
	),
) );

// Settings
Merchant_Admin_Options::create( array(
	'module' => Merchant_Free_Gifts::MODULE_ID,
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'total_type',
			'type'    => 'select',
			'title'   => esc_html__( 'Gift based on spending type', 'merchant' ),
			'desc'    => esc_html__( 'To which the spending goal should be based on to receive gifts. "Cart Subtotal" does not include additional calculated discounts, whereas the "Cart Total" does.',
				'merchant' ),
			'options' => array(
				'subtotal' => esc_html__( 'Cart subtotal', 'merchant' ),
				'total'    => esc_html__( 'Cart total', 'merchant' ),
			),
			'default' => 'subtotal',
		),
	),
) );

// Display Settings
Merchant_Admin_Options::create( array(
	'module' => Merchant_Free_Gifts::MODULE_ID,
	'title'  => esc_html__( 'Display Settings', 'merchant' ),
	'fields' => array(

		array(
			'id'      => 'display_homepage',
			'type'    => 'checkbox',
			'title'   => __( 'Show on homepage', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'      => 'display_shop',
			'type'    => 'checkbox',
			'title'   => __( 'Show on shop page', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'      => 'display_product',
			'type'    => 'checkbox',
			'title'   => __( 'Show on product page', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'      => 'display_cart',
			'type'    => 'checkbox',
			'title'   => __( 'Show on cart page', 'merchant' ),
			'default' => 1,
		),
	),
) );

// Text Formatting Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Text Formatting Settings', 'merchant' ),
	'module' => Merchant_Free_Gifts::MODULE_ID,
	'fields' => array(

		array(
			'id'      => 'spending_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Spending text', 'merchant' ),
			'default' => esc_html__( 'Spend {amount} more to receive this free gift!', 'merchant' ),
		),

		array(
			'id'      => 'free_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Free text', 'merchant' ),
			'default' => esc_html__( 'Free', 'merchant' ),
		),

		array(
			'id'      => 'cart_title_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Cart item title text', 'merchant' ),
			'default' => esc_html__( 'Free Gift', 'merchant' ),
			'desc'    => esc_html__( 'This is displayed on the cart page.', 'merchant' ),
		),

		array(
			'id'      => 'cart_description_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Cart item description text', 'merchant' ),
			'default' => esc_html__( 'This item was added as a free gift', 'merchant' ),
			'desc'    => esc_html__( 'This is displayed on the cart page.', 'merchant' ),
		),
	),
) );


// Style Settings
Merchant_Admin_Options::create( array(
		'module' => Merchant_Free_Gifts::MODULE_ID,
		'title'  => esc_html__( 'Style Settings', 'merchant' ),
		'fields' => array(

			array(
				'id'      => 'count_bg_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Count background color', 'merchant' ),
				'default' => '#000',
			),

			array(
				'id'      => 'count_text_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Count text color', 'merchant' ),
				'default' => '#fff',
			),

			array(
				'id'      => 'button_bg_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Gift button background color', 'merchant' ),
				'default' => '#362e94',
			),

			array(
				'id'      => 'button_hover_bg_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Gift button Hover background color', 'merchant' ),
				'default' => '#7167e1',
			),

			array(
				'id'      => 'button_text_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Gift button Icon color', 'merchant' ),
				'default' => '#fff',
			),

			array(
				'id'      => 'content_width',
				'type'    => 'range',
				'title'   => esc_html__( 'Content width', 'merchant' ),
				'min'     => 0,
				'max'     => 600,
				'step'    => 1,
				'unit'    => 'px',
				'default' => 300,
			),

			array(
				'id'      => 'content_bg_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Content background color', 'merchant' ),
				'default' => '#fff',
			),

			array(
				'id'      => 'label_bg_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Label background color', 'merchant' ),
				'default' => '#f5f5f5',
			),

			array(
				'id'      => 'label_text_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Label text color', 'merchant' ),
				'default' => '#212121',
			),

			array(
				'id'      => 'product_text_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Product text color', 'merchant' ),
				'default' => '#212121',
			),

			array(
				'id'      => 'product_text_hover_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Product hover text color', 'merchant' ),
				'default' => '#757575',
			),

			array(
				'id'      => 'product_price_text_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Product price text color', 'merchant' ),
				'default' => '#999999',
			),

			array(
				'id'      => 'free_text_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Free text color', 'merchant' ),
				'default' => '#212121',
			),
		),
	)
);