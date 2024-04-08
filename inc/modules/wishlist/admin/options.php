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
	'title'  => __( 'General Settings', 'merchant' ),
	'module' => 'wishlist',
	'fields' => array(

		// Display on single product pages.
		array(
			'id'      => 'display_on_shop_archive',
			'type'    => 'switcher',
			'title'   => __( 'Display on shop archive', 'merchant' ),
			'desc'    => __( 'Display the wishlist button in the products grid from shop catalog pages.', 'merchant' ),
			'default' => 1,
		),

		// Display on single product pages.
		array(
			'id'      => 'display_on_single_product',
			'type'    => 'switcher',
			'title'   => __( 'Display on single product', 'merchant' ),
			'desc'    => __( 'Display the wishlist button in the single product pages.', 'merchant' ),
			'default' => 1,
		),

	),
) );

// Add To Wishlist Button Settings
Merchant_Admin_Options::create( array(
	'title'  => __( 'Add To Wishlist Button Settings', 'merchant' ),
	'module' => 'wishlist',
	'fields' => array(

		// Button icon.
		array(
			'id'        => 'button_icon',
			'type'      => 'choices',
			'title'     => __( 'Select an icon', 'merchant' ),
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
			'title'   => __( 'Button vertical position', 'merchant' ),
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
			'title'   => __( 'Button horizontal position', 'merchant' ),
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
			'title'   => __( 'Display tooltip', 'merchant' ),
			'default' => 1,
		),

		// Tooltip text.
		array(
			'id'        => 'tooltip_text',
			'type'      => 'text',
			'title'     => __( 'Tooltip text', 'merchant' ),
			'default'   => __( 'Add to wishlist', 'merchant' ),
			'condition' => array( 'tooltip', '==', '1' ),
		),

		// Tooltip border radius.
		array(
			'id'      => 'tooltip_border_radius',
			'type'    => 'range',
			'title'   => __( 'Tooltip border radius', 'merchant' ),
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
			'title'   => __( 'Icon stroke color', 'merchant' ),
			'default' => '#212121',
		),

		// Icon stroke color (hover).
		array(
			'id'      => 'icon_stroke_color_hover',
			'type'    => 'color',
			'title'   => __( 'Icon stroke color (hover)', 'merchant' ),
			'default' => '#212121',
		),

		// Icon fill color.
		array(
			'id'      => 'icon_fill_color',
			'type'    => 'color',
			'title'   => __( 'Icon fill color', 'merchant' ),
			'default' => 'transparent',
		),

		// Icon fill color (hover).
		array(
			'id'      => 'icon_fill_color_hover',
			'type'    => 'color',
			'title'   => __( 'Icon fill color (hover)', 'merchant' ),
			'default' => '#f04c4c',
		),

		// Tooltip text color.
		array(
			'id'      => 'tooltip_text_color',
			'type'    => 'color',
			'title'   => __( 'Tooltip text color', 'merchant' ),
			'default' => '#FFF',
		),

		// Tooltip background color.
		array(
			'id'      => 'tooltip_background_color',
			'type'    => 'color',
			'title'   => __( 'Tooltip background color', 'merchant' ),
			'default' => '#212121',
		),

	),
) );

// Wishlist Page Settings
Merchant_Admin_Options::create( array(
	'module'    => 'wishlist',
	'title'     => __( 'Wishlist Page Settings', 'merchant' ),
	'fields'    => array(

		// Create Wishlist Page.
		array(
			'id'              => 'create_page',
			'type'            => 'create_page',
			'title'           => __( 'Wishlist page', 'merchant' ),
			'page_title'      => __( 'My Wishlist', 'merchant' ),
			'page_meta_key'   => '_wp_page_template',
			'page_meta_value' => 'modules/wishlist/page-template-wishlist.php',
			'option_name'     => 'merchant_wishlist_page_id',
		),

		// Hide page title.
		array(
			'id'      => 'hide_page_title',
			'type'    => 'switcher',
			'title'   => __( 'Hide page title', 'merchant' ),
			'default' => 0,
		),
		
		// Table heading background color.
		array(
			'id'      => 'table_heading_background_color',
			'type'    => 'color',
			'title'   => __( 'Table heading background color', 'merchant' ),
			'default' => '#FFF',
		),

		// Table body background color.
		array(
			'id'      => 'table_body_background_color',
			'type'    => 'color',
			'title'   => __( 'Table body background color', 'merchant' ),
			'default' => '#fdfdfd',
		),

		// Table text color.
		array(
			'id'      => 'table_text_color',
			'type'    => 'color',
			'title'   => __( 'Table text color', 'merchant' ),
			'default' => '#777',
		),

		// Table links color.
		array(
			'id'      => 'table_links_color',
			'type'    => 'color',
			'title'   => __( 'Table links color', 'merchant' ),
			'default' => '#212121',
		),

		// Table links color (hover).
		array(
			'id'      => 'table_links_color_hover',
			'type'    => 'color',
			'title'   => __( 'Table links color (hover)', 'merchant' ),
			'default' => '#757575',
		),

		// Buttons color.
		array(
			'id'      => 'buttons_color',
			'type'    => 'color',
			'title'   => __( 'Buttons color', 'merchant' ),
			'default' => '#FFF',
		),
		
		// Buttons color (hover).
		array(
			'id'      => 'buttons_color_hover',
			'type'    => 'color',
			'title'   => __( 'Buttons color (hover)', 'merchant' ),
			'default' => '#FFF',
		),

		// Buttons background color.
		array(
			'id'      => 'buttons_background_color',
			'type'    => 'color',
			'title'   => __( 'Buttons background color', 'merchant' ),
			'default' => '#212121',
		),
		
		// Buttons color (hover).
		array(
			'id'      => 'buttons_background_color_hover',
			'type'    => 'color',
			'title'   => __( 'Buttons background color (hover)', 'merchant' ),
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
			'title'   => __( 'Enable', 'merchant' ),
			'desc'    => __( 'Allow users to share the wishlist.', 'merchant' ),
			'default' => 1,
		),

		// Sharing Links.
		array(
			'id'           => 'sharing_links',
			'type'         => 'flexible_content',
			'sorting'      => true,
			'title'   => __( 'Social links', 'merchant' ),
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
			'title'   => __( 'Display copy to clipboard', 'merchant' ),
			'desc'    => __( 'Displays a copy to clipboard field after the social links.', 'merchant' ),
			'default' => 1,
		),
	),
) );