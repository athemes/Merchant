<?php

/**
 * Advanced Reviews Options.
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Settings
Merchant_Admin_Options::create( array(
	'title'  => __( 'Settings', 'merchant' ),
	'module' => Merchant_Advanced_Reviews::MODULE_ID,
	'fields' => array(

		// Title.
		array(
			'id'        => 'title',
			'type'    => 'text',
			'title'  => __( 'Title', 'merchant' ),
			'default'   => __( 'What our customers are saying', 'merchant' ),
		),

		// Title HTML Tag.
		array(
			'id'        => 'title_tag',
			'type'      => 'select',
			'title'     => __( 'Title HTML tag', 'merchant' ),
			'options'   => array(
				'h1'  => __( 'H1', 'merchant' ),
				'h2'  => __( 'H2', 'merchant' ),
				'h3'  => __( 'H3', 'merchant' ),
				'h4'  => __( 'H4', 'merchant' ),
				'h5'  => __( 'H5', 'merchant' ),
				'h6'  => __( 'H6', 'merchant' ),
				'div' => __( 'div', 'merchant' ),
			),
			'default'   => 'h2',
		),

		// Hide Title.
		array(
			'id'      => 'hide_title',
			'type'    => 'switcher',
			'title'   => __( 'Hide title', 'merchant' ),
			'default' => 0,
		),

		// Description.
		array(
			'id'        => 'description',
			'type'      => 'textarea',
			'title'     => __( 'Description', 'merchant' ),
			'default'   => '',
		),

		// Title and Description Alignment.
		array(
			'id'      => 'title_desc_align',
			'type'    => 'radio',
			'title'   => __( 'Title and description alignment', 'merchant' ),
			'options' => array(
				'left'   => __( 'Left', 'merchant' ),
				'center' => __( 'Center', 'merchant' ),
				'right'  => __( 'Right', 'merchant' ),
			),
			'default' => 'left',
		),
		
		// Default Reviews Sorting.
		array(
			'id'        => 'default_sorting',
			'type'      => 'select',
			'title'     => __( 'Default reviews sorting', 'merchant' ),
			'options'   => array(
				'newest'     => __( 'Newest', 'merchant' ),
				'oldest'     => __( 'Oldest', 'merchant' ),
				'top-rated'  => __( 'Top rated', 'merchant' ),
				'low-rated'  => __( 'Low rated', 'merchant' ),
			),
			'default'   => 'newest',
		),

		// Pagination Type.
		array(
			'id'        => 'pagination_type',
			'type'      => 'select',
			'title'     => __( 'Pagination type', 'merchant' ),
			'desc'      => sprintf( 

				/* Translators: 1. Defualt WordPress discussion settings page. */
				__( 'This option works only if you have pagination for comments enabled. By default, WordPress doesn\'t have pagination enabled for comments/reviews. You can change it from: <a href="%1$s" target="_blank">Settings > Discusson</a>', 'merchant' ),
				admin_url( 'options-discussion.php' ) 
			),
			'options'   => array(
				'default'    => __( 'Default', 'merchant' ),
				'load-more'  => __( 'Load more button', 'merchant' ),
			),
			'default'   => 'default',
		),

		// Hook Order.
		array(
			'id'        => 'hook_order',
			'type'      => 'range',
			'title'     => __( 'Hook order', 'merchant' ),
			'desc'      => __( 'Controls the display order from the entire advanced reviews section. Low values will move the section to top. High values will move the section to bottom.', 'merchant' ),
			'min'       => 1,
			'max'       => 100,
			'step'      => 1,
			'unit'      => '',
			'default'   => 10,
		),


		/**
		 * Styles
		 * 
		 */

		// Title color.
		array(
			'id'      => 'title_color',
			'type'    => 'color',
			'title'   => __( 'Title color', 'merchant' ),
			'default' => '#212121',
		),

		// Description color.
		array(
			'id'      => 'description_color',
			'type'    => 'color',
			'title'   => __( 'Description color', 'merchant' ),
			'default' => '#777',
		),

		// Stars color.
		array(
			'id'      => 'stars_color',
			'type'    => 'color',
			'title'   => __( 'Stars color', 'merchant' ),
			'default' => '#FFA441',
		),

		// Stars background color.
		array(
			'id'      => 'stars_background_color',
			'type'    => 'color',
			'title'   => __( 'Stars background color', 'merchant' ),
			'default' => '#757575',
		),

		// Progress bar color.
		array(
			'id'      => 'progress_bar_color',
			'type'    => 'color',
			'title'   => __( 'Progress bar color', 'merchant' ),
			'default' => '#212121',
		),

		// Progress bar background color.
		array(
			'id'      => 'progress_bar_bg_color',
			'type'    => 'color',
			'title'   => __( 'Progress bar background color', 'merchant' ),
			'default' => '#F5F5F5',
		),

		// Dividers color.
		array(
			'id'      => 'dividers_color',
			'type'    => 'color',
			'title'   => __( 'Dividers color', 'merchant' ),
			'default' => '#e9e9e9',
		),

		// Button color.
		array(
			'id'      => 'button_color',
			'type'    => 'color',
			'title'   => __( 'Button color', 'merchant' ),
			'default' => '#FFF',
		),

		// Button color (hover).
		array(
			'id'      => 'button_color_hover',
			'type'    => 'color',
			'title'   => __( 'Button color (hover)', 'merchant' ),
			'default' => '#FFF',
		),

		// Button background color.
		array(
			'id'      => 'button_bg_color',
			'type'    => 'color',
			'title'   => __( 'Button background color', 'merchant' ),
			'default' => '#212121',
		),

		// Button background color (hover).
		array(
			'id'      => 'button_bg_color_hover',
			'type'    => 'color',
			'title'   => __( 'Button background color (hover)', 'merchant' ),
			'default' => '#757575',
		),

	),
) );

// Modal Settings
Merchant_Admin_Options::create( array(
	'title'  => __( 'Modal Settings', 'merchant' ),
	'module' => Merchant_Advanced_Reviews::MODULE_ID,
	'fields' => array(

		/**
		 * Styles
		 * 
		 */

		// Modal Close icon color.
		array(
			'id'      => 'modal_close_icon_color',
			'type'    => 'color',
			'title'   => __( 'Close icon color', 'merchant' ),
			'default' => '#757575',
		),

		// Modal Close icon color (hover).
		array(
			'id'      => 'modal_close_icon_color_hover',
			'type'    => 'color',
			'title'   => __( 'Close icon color (hover)', 'merchant' ),
			'default' => '#212121',
		),

		// Modal Title color.
		array(
			'id'      => 'modal_title_color',
			'type'    => 'color',
			'title'   => __( 'Title color', 'merchant' ),
			'default' => '#212121',
		),

		// Modal Description color.
		array(
			'id'      => 'modal_description_color',
			'type'    => 'color',
			'title'   => __( 'Description color', 'merchant' ),
			'default' => '#777',
		),

		// Modal Textarea color.
		array(
			'id'      => 'modal_textarea_color',
			'type'    => 'color',
			'title'   => __( 'Textarea color', 'merchant' ),
			'default' => '#777',
		),

		// Modal Textarea background color.
		array(
			'id'      => 'modal_textarea_background_color',
			'type'    => 'color',
			'title'   => __( 'Textarea background color', 'merchant' ),
			'default' => '#FFF',
		),

		// Modal background color.
		array(
			'id'      => 'modal_background_color',
			'type'    => 'color',
			'title'   => __( 'Modal background color', 'merchant' ),
			'default' => '#F5F5F5',
		),

	),
) );

// Shortcode
$merchant_module_id = Merchant_Advanced_Reviews::MODULE_ID;
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