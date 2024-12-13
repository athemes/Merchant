<?php

/**
 * Wishlist Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// General Settings.
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'General Settings', 'merchant' ),
	'module' => 'wishlist',
	'fields' => array(

		// Display on single product pages.
		array(
			'id'      => 'display_on_shop_archive',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Display on shop archive', 'merchant' ),
			'desc'    => esc_html__( 'Display the wishlist button in the products grid from shop catalog pages.', 'merchant' ),
			'default' => 1,
		),

		// Display on single product pages.
		array(
			'id'      => 'display_on_single_product',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Display on single product', 'merchant' ),
			'desc'    => esc_html__( 'Display the wishlist button in the single product pages.', 'merchant' ),
			'default' => 1,
		),

		array(
			'id'      => 'display_on_cart_page',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Display on cart page', 'merchant' ),
			'desc'    => esc_html__( 'Display the wishlist products on the cart page.', 'merchant' ),
			'default' => 0,
		),

		array(
			'id'        => 'posts_per_page',
			'type'      => 'range',
			'title'     => esc_html__( 'Products', 'merchant' ),
			'desc'      => esc_html__( 'Controls the number of products to display in the wishlist grid.', 'merchant' ),
			'min'       => 1,
			'max'       => 30,
			'step'      => 1,
			'unit'      => '',
			'default'   => 6,
			'condition' => array( 'display_on_cart_page', '==', '1' ),
		),

		array(
			'id'        => 'cart_page_title',
			'type'      => 'text',
			'title'     => esc_html__( 'Title', 'merchant' ),
			'default'   => esc_html__( 'Your wishlist items', 'merchant' ),
			'condition' => array( 'display_on_cart_page', '==', '1' ),
		),

		array(
			'id'        => 'cart_page_title_tag',
			'type'      => 'select',
			'title'     => esc_html__( 'Title HTML tag', 'merchant' ),
			'options'   => array(
				'h1'  => esc_html__( 'H1', 'merchant' ),
				'h2'  => esc_html__( 'H2', 'merchant' ),
				'h3'  => esc_html__( 'H3', 'merchant' ),
				'h4'  => esc_html__( 'H4', 'merchant' ),
				'h5'  => esc_html__( 'H5', 'merchant' ),
				'h6'  => esc_html__( 'H6', 'merchant' ),
				'div' => esc_html__( 'div', 'merchant' ),
			),
			'default'   => 'h2',
			'condition' => array( 'display_on_cart_page', '==', '1' ),
		),
	),
) );

// Add To Wishlist Button Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Add To Wishlist Button Settings', 'merchant' ),
	'module' => 'wishlist',
	'fields' => array(

		// Button icon.
		array(
			'id'        => 'button_icon',
			'type'      => 'choices',
			'title'     => esc_html__( 'Select an icon', 'merchant' ),
			'options'   => array(
				'heart1'    => MERCHANT_URI . 'inc/modules/wishlist/admin/icons/heart1.svg',
				'heart2'    => MERCHANT_URI . 'inc/modules/wishlist/admin/icons/heart2.svg',
			),
			'default'   => 'heart1',
		),

		// Button position top.
		array(
			'id'      => 'button_position_top',
			'type'    => 'range',
			'title'   => esc_html__( 'Button vertical position', 'merchant' ),
			'min'     => 1,
			'max'     => 80,
			'step'    => 1,
			'default' => 20,
			'unit'    => 'px',
		),

		// Button position left.
		array(
			'id'      => 'button_position_left',
			'type'    => 'range',
			'title'   => esc_html__( 'Button horizontal position', 'merchant' ),
			'min'     => 1,
			'max'     => 80,
			'step'    => 1,
			'default' => 20,
			'unit'    => 'px',
		),

		// Tooltip.
		array(
			'id'      => 'tooltip',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Display tooltip', 'merchant' ),
			'default' => 1,
		),

		// Tooltip text.
		array(
			'id'        => 'tooltip_text',
			'type'      => 'text',
			'title'     => esc_html__( 'Tooltip text', 'merchant' ),
			'default'   => esc_html__( 'Add to wishlist', 'merchant' ),
			'condition' => array( 'tooltip', '==', '1' ),
		),
		array(
			'id'        => 'tooltip_text_after',
			'type'      => 'text',
			'title'     => esc_html__( 'Tooltip text after adding to wishlist', 'merchant' ),
			'default'   => esc_html__( 'Added to wishlist', 'merchant' ),
			'condition' => array( 'tooltip', '==', '1' ),
		),

		// Tooltip border radius.
		array(
			'id'      => 'tooltip_border_radius',
			'type'    => 'range',
			'title'   => esc_html__( 'Tooltip border radius', 'merchant' ),
			'min'     => 0,
			'max'     => 35,
			'step'    => 1,
			'default' => 4,
			'unit'    => 'px',
			'condition' => array( 'tooltip', '==', '1' ),
		),

		// Colors.

		// Icon stroke color.
		array(
			'id'      => 'icon_stroke_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Icon stroke color', 'merchant' ),
			'default' => '#212121',
		),

		// Icon stroke color (hover).
		array(
			'id'      => 'icon_stroke_color_hover',
			'type'    => 'color',
			'title'   => esc_html__( 'Icon stroke color (hover)', 'merchant' ),
			'default' => '#212121',
		),

		// Icon fill color.
		array(
			'id'      => 'icon_fill_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Icon fill color', 'merchant' ),
			'default' => 'transparent',
		),

		// Icon fill color (hover).
		array(
			'id'      => 'icon_fill_color_hover',
			'type'    => 'color',
			'title'   => esc_html__( 'Icon fill color (hover)', 'merchant' ),
			'default' => '#f04c4c',
		),

		// Tooltip text color.
		array(
			'id'      => 'tooltip_text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Tooltip text color', 'merchant' ),
			'default' => '#FFF',
		),

		// Tooltip background color.
		array(
			'id'      => 'tooltip_background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Tooltip background color', 'merchant' ),
			'default' => '#212121',
		),

	),
) );

// Wishlist Page Settings
Merchant_Admin_Options::create( array(
	'module'    => 'wishlist',
	'title'     => esc_html__( 'Wishlist Page Settings', 'merchant' ),
	'fields'    => array(

		// Create Wishlist Page.
		array(
			'id'              => 'create_page',
			'type'            => 'create_page',
			'title'           => esc_html__( 'Wishlist page', 'merchant' ),
			'page_title'      => esc_html__( 'My Wishlist', 'merchant' ),
			'page_meta_key'   => '_wp_page_template',
			'page_meta_value' => 'modules/wishlist/page-template-wishlist.php',
			'option_name'     => 'merchant_wishlist_page_id',
		),

		// Hide page title.
		array(
			'id'      => 'hide_page_title',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Hide page title', 'merchant' ),
			'default' => 0,
		),

		// Table heading background color.
		array(
			'id'      => 'table_heading_background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Table heading background color', 'merchant' ),
			'default' => '#FFF',
		),

		// Table body background color.
		array(
			'id'      => 'table_body_background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Table body background color', 'merchant' ),
			'default' => '#fdfdfd',
		),

		// Table text color.
		array(
			'id'      => 'table_text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Table text color', 'merchant' ),
			'default' => '#777',
		),

		// Table links color.
		array(
			'id'      => 'table_links_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Table links color', 'merchant' ),
			'default' => '#212121',
		),

		// Table links color (hover).
		array(
			'id'      => 'table_links_color_hover',
			'type'    => 'color',
			'title'   => esc_html__( 'Table links color (hover)', 'merchant' ),
			'default' => '#757575',
		),

		// Buttons color.
		array(
			'id'      => 'buttons_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Buttons color', 'merchant' ),
			'default' => '#FFF',
		),

		// Buttons color (hover).
		array(
			'id'      => 'buttons_color_hover',
			'type'    => 'color',
			'title'   => esc_html__( 'Buttons color (hover)', 'merchant' ),
			'default' => '#FFF',
		),

		// Buttons background color.
		array(
			'id'      => 'buttons_background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Buttons background color', 'merchant' ),
			'default' => '#212121',
		),

		// Buttons color (hover).
		array(
			'id'      => 'buttons_background_color_hover',
			'type'    => 'color',
			'title'   => esc_html__( 'Buttons background color (hover)', 'merchant' ),
			'default' => '#757575',
		),

	),
) );

// Wishlist Sharing Settings
Merchant_Admin_Options::create( array(
	'module' => Merchant_Wishlist::MODULE_ID,
	'title'  => esc_html__( 'Wishlist Sharing Settings', 'merchant' ),
	'fields' => array(

		// Enable.
		array(
			'id'      => 'enable_sharing',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Enable', 'merchant' ),
			'desc'    => esc_html__( 'Allow users to share the wishlist.', 'merchant' ),
			'default' => 1,
		),

		// Sharing Links.
		array(
			'id'           => 'sharing_links',
			'type'         => 'flexible_content',
			'sorting'      => true,
			'title'        => esc_html__( 'Social links', 'merchant' ),
			'desc'         => esc_html__( 'Add the available social links to share the wishlist.', 'merchant' ),
			'button_label' => esc_html__( 'Add new', 'merchant' ),
			'layouts'      => array(
				'social' => array(
					'title'  => esc_html__( 'Social link', 'merchant' ),
					'fields' => array(
						array(
							'id'      => 'social_network',
							'title'   => esc_html__( 'Social Network', 'merchant' ),
							'type'    => 'select',
							'options' => array(
								'facebook'          => esc_html__( 'Facebook', 'merchant' ),
								'twitter'           => esc_html__( 'Twitter', 'merchant' ),
								'linkedin'          => esc_html__( 'Linkedin', 'merchant' ),
								'pinterest'         => esc_html__( 'Pinterest', 'merchant' ),
								'whatsapp'          => esc_html__( 'Whatsapp', 'merchant' ),
								'telegram'          => esc_html__( 'Telegram', 'merchant' ),
								'vk'                => esc_html__( 'VK', 'merchant' ),
								'weibo'             => esc_html__( 'Weibo', 'merchant' ),
								'reddit'            => esc_html__( 'Reddit', 'merchant' ),
								'ok'                => esc_html__( 'Ok', 'merchant' ),
								'xing'              => esc_html__( 'Xing', 'merchant' ),
								'mail'              => esc_html__( 'Mail', 'merchant' ),
							),
							'default' => 'facebook',
						),
					),
				),
			),
			'default'      => array(
				array(
					'layout' => 'social',
					'social_network'    => 'facebook',
				),
				array(
					'layout' => 'social',
					'social_network'    => 'twitter',
				),
				array(
					'layout' => 'social',
					'social_network'    => 'linkedin',
				),
			),
		),

		// Dislay Copy To Clipboard.
		array(
			'id'      => 'display_copy_to_clipboard',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Display copy to clipboard', 'merchant' ),
			'desc'    => esc_html__( 'Displays a copy to clipboard field after the social links.', 'merchant' ),
			'default' => 1,
		),
	),
) );

// Shortcode
$merchant_module_id = Merchant_Wishlist::MODULE_ID;
Merchant_Admin_Options::create( array(
	'module' => $merchant_module_id,
	'title'  => esc_html__( 'Use shortcode', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'use_shortcode',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Use shortcode', 'merchant' ),
			'default' => 0,
		),
		array(
			'type'    => 'info',
			'id'      => 'shortcode_info',
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
