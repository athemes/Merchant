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
	'title'  => esc_html__( 'Floating Gift Widget', 'merchant' ),
	'module' => Merchant_Free_Gifts::MODULE_ID,
	'fields' => array(
		array(
			'id'           => 'offers',
			'type'         => 'flexible_content',
			'button_label' => esc_html__( 'Add New Offer', 'merchant' ),
			'style'        => Merchant_Free_Gifts::MODULE_ID . '-style default',
			'sorting'      => true,
			'accordion'    => true,
			'duplicate'    => true,
			'layouts'      => array(
				'spending' => array(
					'title'       => esc_html__( 'Spending Based', 'merchant' ),
					'title-field' => 'offer-title', // text field ID to use as title for the layout
					'fields'      => array(
						array(
							'id'      => 'offer-title',
							'type'    => 'text',
							'title'   => esc_html__( 'Offer name', 'merchant' ),
							'default' => esc_html__( 'Free Gift Campaign', 'merchant' ),
						),
						array(
							'id'      => 'rules_to_apply',
							'type'    => 'select',
							'title'   => esc_html__( 'Products that can be purchased to claim the gift', 'merchant' ),
							'options' => array(
								'all'        => esc_html__( 'All products', 'merchant' ),
								'product'    => esc_html__( 'Specific Products', 'merchant' ),
								'categories' => esc_html__( 'Specific Categories', 'merchant' ),
							),
							'default' => 'all',
						),
						array(
							'id'          => 'category_slugs',
							'type'        => 'select_ajax',
							'source'      => 'options',
							'multiple'    => true,
							'options'     => Merchant_Admin_Options::get_category_select2_choices(),
							'placeholder' => esc_html__( 'Select categories', 'merchant' ),
							'desc'        => esc_html__( 'Select the product categories that the spending goal will apply to.', 'merchant' ),
							'condition'   => array( 'rules_to_apply', '==', 'categories' ),
						),
						array(
							'id'            => 'product_to_purchase',
							'type'          => 'products_selector',
							'multiple'      => false,
							'desc'          => esc_html__( 'Select the product that the spending goal will apply to.', 'merchant' ),
							'condition'     => array( 'rules_to_apply', '==', 'product' ),
							'allowed_types' => array( 'simple', 'variable' ),
						),

						array(
							'id'        => 'excluded_products',
							'type'      => 'products_selector',
							'title'     => esc_html__( 'Exclude Products', 'merchant' ),
							'multiple'  => true,
							'desc'      => esc_html__( 'Exclude products from this offer.', 'merchant' ),
							'condition' => array( 'rules_to_apply', 'any', 'all|categories|tags' ),
						),

						array(
							'id'          => 'excluded_categories',
							'type'        => 'select_ajax',
							'title'       => esc_html__( 'Exclude Categories', 'merchant' ),
							'source'      => 'options',
							'multiple'    => true,
							'options'     => Merchant_Admin_Options::get_category_select2_choices(),
							'placeholder' => esc_html__( 'Select categories', 'merchant' ),
							'desc'        => esc_html__( 'Exclude categories from this campaign.', 'merchant' ),
							'condition'   => array( 'rules_to_apply', '==', 'all' ),
						),

						'amount' => array(
							'id'      => 'amount',
							'title'   => esc_html__( 'Spending goal', 'merchant' ),
							'type'    => 'number',
							'step'    => 0.01,
							'append'  => function_exists( 'get_woocommerce_currency_symbol' ) ? get_woocommerce_currency() : esc_html__( 'USD', 'merchant' ),
							'default' => 100,
						),

						array(
							'id'            => 'product',
							'type'          => 'products_selector',
							'title'         => esc_html__( 'Product rewarded as a gift', 'merchant' ),
							'multiple'      => false,
							'allowed_types' => array( 'simple', 'variable', 'variation' ),
						),

						array(
							'id'      => 'exclude_coupon',
							'type'    => 'switcher',
							'title'   => esc_html__( 'Exclude coupons', 'merchant' ),
							'desc'    => esc_html__( 'Coupon codes will not be applicable on top of this offer.', 'merchant' ),
							'default' => false,
						),

						array(
							'id'      => 'user_condition',
							'type'    => 'select',
							'title'   => esc_html__( 'User Condition', 'merchant' ),
							'options' => array(
								'all'       => esc_html__( 'All Users', 'merchant' ),
								'customers' => esc_html__( 'Selected Users', 'merchant' ),
								'roles'     => esc_html__( 'Selected Roles', 'merchant' ),
							),
							'default' => 'all',
						),

						array(
							'id'        => 'user_condition_roles',
							'type'      => 'select_ajax',
							'title'     => esc_html__( 'User Roles', 'merchant' ),
							'desc'      => esc_html__( 'This will limit the offer to users with these roles.', 'merchant' ),
							'source'    => 'options',
							'multiple'  => true,
							'classes'   => array( 'flex-grow' ),
							'options'   => Merchant_Admin_Options::get_user_roles_select2_choices(),
							'condition' => array( 'user_condition', '==', 'roles' ),
						),

						array(
							'id'        => 'user_condition_users',
							'type'      => 'select_ajax',
							'title'     => esc_html__( 'Users', 'merchant' ),
							'desc'      => esc_html__( 'This will limit the offer to the selected customers.', 'merchant' ),
							'source'    => 'user',
							'multiple'  => true,
							'classes'   => array( 'flex-grow' ),
							'condition' => array( 'user_condition', '==', 'customers' ),
						),

						array(
							'id'        => 'spending_goal_target',
							'type'      => 'content',
							'title'     => esc_html__( 'Spending goal target', 'merchant' ),
							'content'   => '',
							'desc'      => esc_html__( 'Configure the text for the gift offer across five phases. Personalize the text at each phase to maximize conversions.', 'merchant' ),
						),

						array(
							'id'      => 'spending_text_0',
							'type'    => 'text',
							'title'   => esc_html__( 'At 0%', 'merchant' ),
							'default' => sprintf(
								/* Translators: 1. goal amount */
								esc_html__( 'Spend %1$s to receive this gift!', 'merchant' ),
								'{goalAmount}' // existing one is {amount}
							),
						),

						array(
							'id'      => 'spending_text_1_to_99',
							'type'    => 'text',
							'title'   => esc_html__( 'Between 1 - 99%', 'merchant' ),
							'default' => sprintf(
								/* Translators: 1. more amount */
								esc_html__( 'Spend %1$s more to receive this gift!', 'merchant' ),
								'{amountMore}'
							),
						),

						array(
							'id'      => 'spending_text_100',
							'type'    => 'text',
							'title'   => esc_html__( 'At 100%', 'merchant' ),
							'default' => esc_html__( 'Congratulations! You are eligible to receive a free gift.', 'merchant' ),
						),

						array(
							'id'      => 'text_before_claim',
							'type'    => 'text',
							'title'   => esc_html__( 'Reminder to claim the gift on the cart page', 'merchant' ),
							'default' => esc_html__( 'Don\'t forget to claim your free gift before checking out!', 'merchant' ),
						),

						array(
							'id'      => 'text_after_claim',
							'type'    => 'text',
							'title'   => esc_html__( 'Gift claimed message', 'merchant' ),
							'default' => esc_html__( 'Your free gift has been added to your cart. Thank you for shopping with us!', 'merchant' ),
						),
					),
				),
				'coupon'   => array(
					'title'       => esc_html__( 'Coupon Based', 'merchant' ),
					'title-field' => 'offer-title', // text field ID to use as title for the layout
					'fields'      => array(
						array(
							'id'      => 'offer-title',
							'type'    => 'text',
							'title'   => esc_html__( 'Offer name', 'merchant' ),
							'default' => esc_html__( 'Campaign', 'merchant' ),
						),
						array(
							'id'            => 'product',
							'type'          => 'products_selector',
							'title'         => esc_html__( 'Product', 'merchant' ),
							'multiple'      => false,
							'desc'          => esc_html__( 'Select the product that the coupon will apply to.', 'merchant' ),
							'allowed_types' => array( 'simple', 'variable', 'variation' ),
						),
						'coupon' => array(
							'id'    => 'coupon',
							'title' => esc_html__( 'Select the coupon that will apply to this offer.', 'merchant' ),
							'type'  => 'wc_coupons',
						),
						array(
							'id'          => 'coupon_campaign_info',
							'type'        => 'info_block',
							'description' => esc_html__( 'The offer won’t appear in the Free-Gifts widget unless the associated coupon is entered.', 'merchant' ),
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
	'title'  => esc_html__( 'Cart Settings', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'total_type',
			'type'    => 'select',
			'title'   => esc_html__( 'Gift based on spending type', 'merchant' ),
			'desc'    => esc_html__( 'Select whether the spending goal for receiving a gift should be based on the ‘Cart Subtotal’ or the ‘Cart Total.’ The ‘Cart Subtotal’ reflects the total before any adjustments, whereas the ‘Cart Total’ includes all charges and fees.', 'merchant' ),
			'options' => array(
				'subtotal' => esc_html__( 'Cart subtotal', 'merchant' ),
				'total'    => esc_html__( 'Cart total', 'merchant' ),
			),
			'default' => 'subtotal',
		),

		array(
			'id'      => 'free_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Gift price label', 'merchant' ),
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

		array(
			'id'      => 'tiered_free_gifts',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Tiered Free Gifts', 'merchant' ),
			'desc'    => esc_html__( 'Customers can claim one free gift per order. Selecting a new gift automatically replaces any previously chosen gift.', 'merchant' ),
			'default' => 0,
		),
	),
) );

// Gift Widget Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Gift Widget', 'merchant' ),
	'module' => Merchant_Free_Gifts::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'icon',
			'type'    => 'choices',
			'title'   => esc_html__( 'Widget icon', 'merchant' ),
			'options' => array(
				'gifts-icon-1' => MERCHANT_URI . 'assets/images/icons/free-gifts/icon-1.svg',
				'gifts-icon-2' => MERCHANT_URI . 'assets/images/icons/free-gifts/icon-2.svg',
				'gifts-icon-3' => MERCHANT_URI . 'assets/images/icons/free-gifts/icon-3.svg',
				'gifts-icon-4' => MERCHANT_URI . 'assets/images/icons/free-gifts/icon-4.svg',
				'gifts-icon-5' => MERCHANT_URI . 'assets/images/icons/free-gifts/icon-5.svg',
			),
			'default' => 'gifts-icon-1',
		),

		array(
			'id'      => 'position',
			'type'    => 'select',
			'title'   => esc_html__( 'Position', 'merchant' ),
			'options' => array(
				'top_right'    => esc_html__( 'Top Right', 'merchant' ),
				'top_left'     => esc_html__( 'Top Left', 'merchant' ),
				'bottom_right' => esc_html__( 'Bottom Right', 'merchant' ),
				'bottom_left'  => esc_html__( 'Bottom Left', 'merchant' ),
			),
			'default' => 'top_right',
		),

		array(
			'id'      => 'distance',
			'type'    => 'range',
			'title'   => esc_html__( 'Distance', 'merchant' ),
			'min'     => 0,
			'max'     => 999,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 150,
		),

		array(
			'id'      => 'display_homepage',
			'type'    => 'checkbox',
			'title'   => esc_html__( 'Show on pages', 'merchant' ),
			'label'   => esc_html__( 'Homepage', 'merchant' ),
			'default' => 1,
		),

		array(
			'id'      => 'display_shop',
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Shop page', 'merchant' ),
			'default' => 1,
		),

		array(
			'id'      => 'display_product',
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Product page', 'merchant' ),
			'default' => 1,
		),

		array(
			'id'      => 'display_cart',
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Cart page', 'merchant' ),
			'default' => 1,
		),
	),
) );

// Style Settings
Merchant_Admin_Options::create( array(
		'module' => Merchant_Free_Gifts::MODULE_ID,
		'title'  => esc_html__( 'Look and Feel', 'merchant' ),
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
				'id'        => 'mobile_width',
				'type'      => 'checkbox',
				'label'     => esc_html__( 'Mobile width', 'merchant' ),
				'default'   => 0,
			),

			array(
				'id'        => 'content_width_mobile',
				'type'      => 'range',
				'title'     => esc_html__( 'Content width', 'merchant' ),
				'min'       => 0,
				'max'       => 600,
				'step'      => 1,
				'unit'      => 'px',
				'default'   => 300,
				'condition' => array( 'mobile_width', '==', '1' ),
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
				'title'   => esc_html__( 'Gift price label color', 'merchant' ),
				'default' => '#212121',
			),
		),
	)
);
