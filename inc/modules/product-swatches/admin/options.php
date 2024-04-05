<?php

/**
 * Product Swatches Options.
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
	'module' => Merchant_Product_Swatches::MODULE_ID,
	'fields' => array(

		// Enable shop catalog
		array(
			'id'      => 'on_shop_catalog',
			'type'    => 'switcher',
			'title'   => __( 'Enable on shop catalog', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['on_shop_catalog'],
		),

		// Enable mouseover
		array(
			'id'      => 'mouseover',
			'type'    => 'switcher',
			'title'   => __( 'Enable mouseover', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['mouseover'],
		),

		// Enable tooltip
		array(
			'id'      => 'tooltip',
			'type'    => 'switcher',
			'title'   => __( 'Enable tooltip', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['tooltip'],
		),

		// Variation name on product title
		array(
			'id'      => 'display_variation_name_on_product_title',
			'type'    => 'switcher',
			'title'   => __( 'Variation name on product title', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['display_variation_name_on_product_title'],
		),
	),
) );

/**
 * Select swatch settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( "'Select' Swatch Style", 'merchant' ),
	'module' => Merchant_Product_Swatches::MODULE_ID,
	'fields' => array(

		/**
		 *  Single product
		 */

		array(
			'type'  => 'divider',
			'title' => esc_html__( 'Single product', 'merchant' ),
		),

		// Text color.
		array(
			'id'      => 'select_text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Text color', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['select_text_color'],
		),

		// Border color
		array(
			'id'      => 'select_border_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Border color', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['select_border_color'],
		),

		// Background color
		array(
			'id'      => 'select_background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Background color', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['select_background_color'],
		),

		// Padding
		array(
			'id'      => 'select_padding',
			'type'    => 'responsive_dimensions',
			'title'   => esc_html__( 'Padding', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['select_padding'],
		),

		// Border radius
		array(
			'id'      => 'select_border_radius',
			'type'    => 'dimensions',
			'title'   => esc_html__( 'Border radius', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['select_border_radius'],
		),

		/**
		 * Shop archive
		 */

		array(
			'type'  => 'divider',
			'title' => esc_html__( 'Shop archive', 'merchant' ),
		),

		// Inherit style from single product
		array(
			'id'      => 'select_custom_style_shop_archive',
			'type'    => 'switcher',
			'title'   => __( 'Don\'t inherit style from single product ', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['select_custom_style_shop_archive'],
		),

		// Shop archive text color.
		array(
			'id'        => 'select_text_color_shop_archive',
			'type'      => 'color',
			'title'     => esc_html__( 'Text color', 'merchant' ),
			'condition' => array( 'select_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['select_text_color_shop_archive'],
		),

		// Shop archive border color
		array(
			'id'        => 'select_border_color_shop_archive',
			'type'      => 'color',
			'title'     => esc_html__( 'Border color', 'merchant' ),
			'condition' => array( 'select_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['select_border_color_shop_archive'],
		),

		// Shop archive background color
		array(
			'id'        => 'select_background_color_shop_archive',
			'type'      => 'color',
			'title'     => esc_html__( 'Background color', 'merchant' ),
			'condition' => array( 'select_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['select_background_color_shop_archive'],
		),

		// Shop archive padding
		array(
			'id'        => 'select_padding_shop_archive',
			'type'      => 'responsive_dimensions',
			'title'     => esc_html__( 'Padding', 'merchant' ),
			'condition' => array( 'select_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['select_padding_shop_archive'],
		),

		// Shop archive border radius
		array(
			'id'        => 'select_border_radius_shop_archive',
			'type'      => 'dimensions',
			'title'     => esc_html__( 'Border radius', 'merchant' ),
			'condition' => array( 'select_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['select_border_radius_shop_archive'],
		),

	),
) );

/**
 * Color swatch settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( "'Color' Swatch Style", 'merchant' ),
	'module' => Merchant_Product_Swatches::MODULE_ID,
	'fields' => array(

		/**
		 *  Single product
		 */

		array(
			'type'  => 'divider',
			'title' => esc_html__( 'Single product', 'merchant' ),
		),

		// Color width
		array(
			'id'      => 'color_width',
			'type'    => 'range',
			'title'   => __( 'Width', 'merchant' ),
			'min'     => 1,
			'max'     => 250,
			'step'    => 1,
			'unit'    => '',
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['color_width'],
		),

		// Color height
		array(
			'id'      => 'color_height',
			'type'    => 'range',
			'title'   => __( 'Height', 'merchant' ),
			'min'     => 1,
			'max'     => 250,
			'step'    => 1,
			'unit'    => '',
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['color_height'],
		),

		// Spacing
		array(
			'id'      => 'color_spacing',
			'type'    => 'range',
			'title'   => __( 'Spacing', 'merchant' ),
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'unit'    => '',
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['color_spacing'],
		),

		// Border color
		array(
			'id'      => 'color_border_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Border color', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['color_border_color'],
		),

		// Border hover color
		array(
			'id'      => 'color_border_hover_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Border Hover Color', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['color_border_hover_color'],
		),

		// Border width
		array(
			'id'      => 'color_border_width',
			'type'    => 'responsive_dimensions',
			'title'   => esc_html__( 'Border width', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['color_border_width'],
		),

		// Border radius
		array(
			'id'      => 'color_border_radius',
			'type'    => 'dimensions',
			'title'   => esc_html__( 'Border radius', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['color_border_radius'],
		),

		/**
		 * Shop archive
		 */

		array(
			'type'  => 'divider',
			'title' => esc_html__( 'Shop archive', 'merchant' ),
		),

		// Inherit style from single product
		array(
			'id'      => 'color_custom_style_shop_archive',
			'type'    => 'switcher',
			'title'   => __( 'Don\'t inherit style from single product ', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['color_custom_style_shop_archive'],
		),

		// Color width
		array(
			'id'        => 'color_width_shop_archive',
			'type'      => 'range',
			'title'     => __( 'Width', 'merchant' ),
			'min'       => 1,
			'max'       => 250,
			'step'      => 1,
			'unit'      => '',
			'condition' => array( 'color_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['color_width_shop_archive'],
		),

		// Color height
		array(
			'id'        => 'color_height_shop_archive',
			'type'      => 'range',
			'title'     => __( 'Height', 'merchant' ),
			'min'       => 1,
			'max'       => 250,
			'step'      => 1,
			'unit'      => '',
			'condition' => array( 'color_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['color_height_shop_archive'],
		),

		// Spacing
		array(
			'id'        => 'color_spacing_shop_archive',
			'type'      => 'range',
			'title'     => __( 'Spacing', 'merchant' ),
			'min'       => 1,
			'max'       => 100,
			'step'      => 1,
			'unit'      => '',
			'condition' => array( 'color_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['color_spacing_shop_archive'],
		),

		// Border color
		array(
			'id'        => 'color_border_color_shop_archive',
			'type'      => 'color',
			'title'     => esc_html__( 'Border color', 'merchant' ),
			'condition' => array( 'color_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['color_border_color_shop_archive'],
		),

		// Border hover color
		array(
			'id'        => 'color_border_hover_color_shop_archive',
			'type'      => 'color',
			'title'     => esc_html__( 'Border Hover Color', 'merchant' ),
			'condition' => array( 'color_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['color_border_hover_color_shop_archive'],
		),

		// Border radius
		array(
			'id'        => 'color_border_width_shop_archive',
			'type'      => 'responsive_dimensions',
			'title'     => esc_html__( 'Border width', 'merchant' ),
			'condition' => array( 'color_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['color_border_width_shop_archive'],
		),

		// Border radius
		array(
			'id'        => 'color_border_radius_shop_archive',
			'type'      => 'dimensions',
			'title'     => esc_html__( 'Border radius', 'merchant' ),
			'condition' => array( 'color_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['color_border_radius_shop_archive'],
		),
	),
) );

/**
 * Button swatch settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( "'Button' Swatch Style", 'merchant' ),
	'module' => Merchant_Product_Swatches::MODULE_ID,
	'fields' => array(

		/**
		 *  Single product
		 */

		array(
			'type'  => 'divider',
			'title' => esc_html__( 'Single product', 'merchant' ),
		),

		// Text color.
		array(
			'id'      => 'button_text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Text color', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['button_text_color'],
		),

		// Text hover color.
		array(
			'id'      => 'button_text_hover_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Text hover color', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['button_text_hover_color'],
		),

		// Border color
		array(
			'id'      => 'button_border_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Border color', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['button_border_color'],
		),

		// Border hover color
		array(
			'id'      => 'button_border_hover_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Border hover color', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['button_border_hover_color'],
		),

		// Background color
		array(
			'id'      => 'button_background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Background color', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['button_background_color'],
		),

		// Background color
		array(
			'id'      => 'button_background_hover_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Background hover color', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['button_background_hover_color'],
		),

		// Padding
		array(
			'id'      => 'button_padding',
			'type'    => 'responsive_dimensions',
			'title'   => esc_html__( 'Padding', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['button_padding'],
		),

		// Spacing
		array(
			'id'      => 'button_spacing',
			'type'    => 'range',
			'title'   => __( 'Spacing', 'merchant' ),
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'unit'    => '',
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['button_spacing'],
		),

		// Border width
		array(
			'id'      => 'button_border_width',
			'type'    => 'responsive_dimensions',
			'title'   => esc_html__( 'Border width', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['button_border_width'],
		),

		// Border radius
		array(
			'id'      => 'button_border_radius',
			'type'    => 'dimensions',
			'title'   => esc_html__( 'Border radius', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['button_border_radius'],
		),

		/**
		 * Shop archive
		 */

		array(
			'type'  => 'divider',
			'title' => esc_html__( 'Shop archive', 'merchant' ),
		),

		// Inherit style from single product
		array(
			'id'      => 'button_custom_style_shop_archive',
			'type'    => 'switcher',
			'title'   => __( 'Don\'t inherit style from single product ', 'merchant' ),
			'default' => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['button_custom_style_shop_archive'],
		),

		// Text color.
		array(
			'id'        => 'button_text_color_shop_archive',
			'type'      => 'color',
			'title'     => esc_html__( 'Text color', 'merchant' ),
			'condition' => array( 'button_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['button_text_color_shop_archive'],
		),

		// Text hover color.
		array(
			'id'        => 'button_text_hover_color_shop_archive',
			'type'      => 'color',
			'title'     => esc_html__( 'Text hover color', 'merchant' ),
			'condition' => array( 'button_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['button_text_hover_color_shop_archive'],
		),

		// Border color
		array(
			'id'        => 'button_border_color_shop_archive',
			'type'      => 'color',
			'title'     => esc_html__( 'Border color', 'merchant' ),
			'condition' => array( 'button_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['button_border_color_shop_archive'],
		),

		// Border hover color
		array(
			'id'        => 'button_border_hover_color_shop_archive',
			'type'      => 'color',
			'title'     => esc_html__( 'Border hover color', 'merchant' ),
			'condition' => array( 'button_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['button_border_hover_color_shop_archive'],
		),

		// Background color
		array(
			'id'        => 'button_background_color_shop_archive',
			'type'      => 'color',
			'title'     => esc_html__( 'Background color', 'merchant' ),
			'condition' => array( 'button_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['button_background_color_shop_archive'],
		),

		// Background color
		array(
			'id'        => 'button_background_hover_color_shop_archie',
			'type'      => 'color',
			'title'     => esc_html__( 'Background hover color', 'merchant' ),
			'condition' => array( 'button_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['button_background_hover_color_shop_archive'],
		),


		// Padding
		array(
			'id'        => 'button_padding_shop_archive',
			'type'      => 'responsive_dimensions',
			'title'     => esc_html__( 'Padding', 'merchant' ),
			'condition' => array( 'button_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['button_padding_shop_archive'],
		),

		// Spacing
		array(
			'id'        => 'button_spacing_shop_archive',
			'type'      => 'range',
			'title'     => __( 'Spacing', 'merchant' ),
			'min'       => 1,
			'max'       => 100,
			'step'      => 1,
			'unit'      => '',
			'condition' => array( 'button_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['button_spacing_shop_archive'],
		),

		// Border width
		array(
			'id'        => 'button_border_width_shop_archive',
			'type'      => 'responsive_dimensions',
			'title'     => esc_html__( 'Border width', 'merchant' ),
			'condition' => array( 'button_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['button_border_width_shop_archive'],
		),

		// Border radius
		array(
			'id'        => 'button_border_radius_shop_archive',
			'type'      => 'dimensions',
			'title'     => esc_html__( 'Border radius', 'merchant' ),
			'condition' => array( 'button_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['button_border_radius_shop_archive'],
		),
	),
) );


/**
 * Image swatch settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( "'Image' Swatch Style", 'merchant' ),
	'module' => Merchant_Product_Swatches::MODULE_ID,
	'fields' => array(

		/**
		 *  Single product
		 */

		array(
			'type'  => 'divider',
			'title' => esc_html__( 'Single product', 'merchant' ),
		),

		// Width
		array(
			'id'      => 'image_width',
			'type'    => 'range',
			'title'   => __( 'Width', 'merchant' ),
			'min'     => 1,
			'max'     => 250,
			'step'    => 1,
			'unit'    => '',
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['image_width'],
		),

		// Height
		array(
			'id'      => 'image_height',
			'type'    => 'range',
			'title'   => __( 'Height', 'merchant' ),
			'min'     => 1,
			'max'     => 250,
			'step'    => 1,
			'unit'    => '',
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['image_height'],
		),

		// Spacing
		array(
			'id'      => 'image_spacing',
			'type'    => 'range',
			'title'   => __( 'Spacing', 'merchant' ),
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'unit'    => '',
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['image_spacing'],
		),

		// Border color
		array(
			'id'      => 'image_border_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Border color', 'merchant' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['image_border_color'],
		),

		// Border hover color
		array(
			'id'      => 'image_border_hover_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Border hover color', 'merchant' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['image_border_hover_color'],
		),

		// Border width
		array(
			'id'    => 'image_border_width',
			'type'  => 'responsive_dimensions',
			'title' => esc_html__( 'Border width', 'merchant' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['image_border_width'],
		),

		// Border radius
		array(
			'id'    => 'image_border_radius',
			'type'  => 'dimensions',
			'title' => esc_html__( 'Border radius', 'merchant' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['image_border_radius'],
		),

		/**
		 * Shop archive
		 */

		array(
			'type'  => 'divider',
			'title' => esc_html__( 'Shop archive', 'merchant' ),
		),

		// Inherit style from single product
		array(
			'id'      => 'image_custom_style_shop_archive',
			'type'    => 'switcher',
			'title'   => __( 'Don\'t inherit style from single product ', 'merchant' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['image_custom_style_shop_archive'],
		),

		// Width
		array(
			'id'        => 'image_width_shop_archive',
			'type'      => 'range',
			'title'     => __( 'Width', 'merchant' ),
			'min'       => 1,
			'max'       => 250,
			'step'      => 1,
			'unit'      => '',
			'condition' => array( 'image_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['image_width_shop_archive'],
		),

		// Height
		array(
			'id'        => 'image_height_shop_archive',
			'type'      => 'range',
			'title'     => __( 'Height', 'merchant' ),
			'min'       => 1,
			'max'       => 250,
			'step'      => 1,
			'unit'      => '',
			'condition' => array( 'image_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['image_height_shop_archive'],
		),

		// Spacing
		array(
			'id'        => 'image_spacing_shop_archive',
			'type'      => 'range',
			'title'     => __( 'Spacing', 'merchant' ),
			'min'       => 1,
			'max'       => 100,
			'step'      => 1,
			'unit'      => '',
			'condition' => array( 'image_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['image_spacing_shop_archive'],
		),

		// Border color
		array(
			'id'        => 'image_border_color_shop_archive',
			'type'      => 'color',
			'title'     => esc_html__( 'Border color', 'merchant' ),
			'condition' => array( 'image_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['image_border_color_shop_archive'],
		),

		// Border hover color
		array(
			'id'        => 'image_border_hover_color_shop_archive',
			'type'      => 'color',
			'title'     => esc_html__( 'Border hover color', 'merchant' ),
			'condition' => array( 'image_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['image_border_hover_color_shop_archive'],
		),

		// Border width
		array(
			'id'        => 'image_border_width_shop_archive',
			'type'      => 'responsive_dimensions',
			'title'     => esc_html__( 'Border width', 'merchant' ),
			'condition' => array( 'image_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['image_border_width_shop_archive'],
		),

		// Border radius
		array(
			'id'        => 'image_border_radius_shop_archive',
			'type'      => 'dimensions',
			'title'     => esc_html__( 'Border radius', 'merchant' ),
			'condition' => array( 'image_custom_style_shop_archive', '==', '1' ),
			'default'   => Merchant_Product_Swatches::MODULE_DEFAULT_SETTINGS['image_border_radius_shop_archive'],
		),
	),
) );

