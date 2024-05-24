<?php
/**
 * Merchant - Product Labels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$position       = Merchant_Admin_Options::get( Merchant_Product_Labels::MODULE_ID, 'label_position',  'top-left' );
$margin_y_label = ( $position === 'top-left' || $position === 'top-right' ) ? esc_html__( 'Margin Top', 'merchant' ) : esc_html__( 'Margin Bottom', 'merchant' );
$margin_x_label = ( $position === 'top-left' || $position === 'bottom-left' ) ? esc_html__( 'Margin Left', 'merchant' ) : esc_html__( 'Margin Right', 'merchant' );

$text_shapes  = array();
$image_shapes = array();

for ( $i = 1; $i <= 24; $i++ ) {
	if ( $i <= 8 ) {
		$text_shapes[ 'text-shape-' . $i ] = MERCHANT_URI . 'assets/images/icons/product-labels/text-shape-' . $i . '.svg';
	}

	$image_shapes[ 'image-shape-' . $i ] = MERCHANT_URI . 'assets/images/icons/product-labels/image-shape-' . $i . '.svg';
}

/**
 * Settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => Merchant_Product_Labels::MODULE_ID,
	'fields' => array(
		array(
			'id'           => 'labels',
			'type'         => 'flexible_content',
			'sorting'      => true,
			'accordion'    => true,
			'style'        => Merchant_Product_Labels::MODULE_ID . '-style default',
			'button_label' => esc_html__( 'Add New Label', 'merchant' ),
			'layouts'      => array(
				'single-label' => array(
					'title'       => esc_html__( 'Product label', 'merchant' ),
					'title-field' => 'label-title',
					'fields'      => array(
						array(
							'id'      => 'label-title',
							'title'   => esc_html__( 'Label name', 'merchant' ),
							'desc'    => esc_html__( 'Internal label name. This is not visible to customers.', 'merchant' ),
							'type'    => 'text',
							'default' => esc_html__( 'Product label', 'merchant' ),
						),

						array(
							'id'      => 'label_type',
							'type'    => 'buttons_content',
							'title'   => '',
							'desc'    => '',
							'options' => array(
								'text'    => array(
									'title' => esc_html__( 'Text', 'merchant' ),
									'desc'  => esc_html__( 'Add text label', 'merchant' ),
									'icon'  => MERCHANT_URI . 'assets/images/icons/product-labels/text-icon.svg',
								),
								'image' => array(
									'title' => esc_html__( 'Image', 'merchant' ),
									'desc'  => esc_html__( 'Add image label', 'merchant' ),
									'icon'  => MERCHANT_URI . 'assets/images/icons/product-labels/image-icon.svg',
								),
							),
							'default' => 'text',
						),

						array(
							'id'          => 'label',
							'title'       => esc_html__( 'Label content', 'merchant' ),
							'type'        => 'text',
							'default'     => esc_html__( 'SALE', 'merchant' ),
							'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
							'hidden_desc' => sprintf(
								/* Translators: %1$s: Discount percentage, %2$s: Discount amount, %3$s: In Stock, %4$s: Total quantity */
								__(
									'<strong>%1$s:</strong> to show discount percentage<br><strong>%2$s:</strong> to show discount amount<br><strong>%3$s:</strong> to show In Stock, Sold Out<br><strong>%4$s:</strong> to show total quantity in inventory',
									'merchant'
								),
								'{sale}',
								'{sale_amount}',
								'{inventory}',
								'{inventory_quantity}'
							),
							'condition'   => array( 'label_type', '==', 'text' ),
						),

						array(
							'id'        => 'label_text_shape',
							'type'      => 'choices',
							'title'     => esc_html__( 'Label shape', 'merchant' ),
							'options'   => $text_shapes,
							'default'   => 'text-shape-1',
							'condition' => array( 'label_type', '==', 'text' ),
						),

						array(
							'id'        => 'label_image_shape',
							'type'      => 'choices',
							'title'     => esc_html__( 'Image label', 'merchant' ),
							'options'   => $image_shapes,
							'default'   => 'image-shape-1',
							'condition' => array( 'label_type', '==', 'image' ),
						),

						array(
							'id'        => 'label_image_shape_custom',
							'type'      => 'upload',
							'drag_drop' => true,
							'title'     => esc_html__( 'Upload custom image label', 'merchant' ),
							'label'     => esc_html__( 'Click to upload or drag and drop', 'merchant' ),
							'condition' => array( 'label_type', '==', 'image' ),
						),

						array(
							'id'      => 'label_position',
							'type'    => 'select',
							'title'   => esc_html__( 'Position', 'merchant' ),
							'options' => array(
								'top-left'  => esc_html__( 'Top left', 'merchant' ),
								'top-right' => esc_html__( 'Top right', 'merchant' ),
							),
							'default' => 'top-left',
						),

						array(
							'id'      => 'margin_y',
							'type'    => 'range',
							'title'   => $margin_y_label,
							'min'     => 0,
							'max'     => 250,
							'step'    => 1,
							'default' => 10,
							'unit'    => 'px',
						),

						array(
							'id'      => 'margin_x',
							'type'    => 'range',
							'title'   => $margin_x_label,
							'min'     => 0,
							'max'     => 250,
							'step'    => 1,
							'default' => 10,
							'unit'    => 'px',
						),

						array(
							'id'        => 'label_width',
							'type'      => 'range',
							'title'     => esc_html__( 'Label width', 'merchant' ),
							'min'       => 1,
							'max'       => 1000,
							'step'      => 1,
							'default'   => 50,
							'unit'      => 'px',
						),
						array(
							'id'        => 'label_height',
							'type'      => 'range',
							'title'     => esc_html__( 'Label height', 'merchant' ),
							'min'       => 1,
							'max'       => 250,
							'step'      => 1,
							'default'   => 50,
							'unit'      => 'px',
						),

						array(
							'id'        => 'background_color',
							'type'      => 'color',
							'title'     => esc_html__( 'Background color', 'merchant' ),
							'default'   => '#212121',
							'condition' => array( 'label_type', '==', 'text' ),
						),

						array(
							'id'        => 'shape_radius',
							'type'      => 'range',
							'title'     => esc_html__( 'Shape radius', 'merchant' ),
							'min'       => 0,
							'max'       => 250,
							'step'      => 1,
							'unit'      => 'px',
							'default'   => 5,
							'condition' => array( 'label_type', '==', 'text' ),
						),

						array(
							'id'        => 'font_size',
							'type'      => 'range',
							'title'     => esc_html__( 'Font size', 'merchant' ),
							'min'       => 0,
							'max'       => 250,
							'step'      => 1,
							'unit'      => 'px',
							'default'   => 14,
							'condition' => array( 'label_type', '==', 'text' ),
						),

						array(
							'id'        => 'font_style',
							'type'      => 'select',
							'title'     => esc_html__( 'Font style', 'merchant' ),
							'options'   => array(
								'normal'      => esc_html__( 'Normal', 'merchant' ),
								'italic'      => esc_html__( 'Italic', 'merchant' ),
								'bold'        => esc_html__( 'Bold', 'merchant' ),
								'bold_italic' => esc_html__( 'Bold Italic', 'merchant' ),
							),
							'default'   => 'normal',
							'condition' => array( 'label_type', '==', 'text' ),
						),

						array(
							'id'        => 'text_color',
							'type'      => 'color',
							'title'     => esc_html__( 'Font color', 'merchant' ),
							'default'   => '#ffffff',
							'condition' => array( 'label_type', '==', 'text' ),
						),

						array(
							'id'      => 'display_rules',
							'type'    => 'select',
							'title'   => esc_html__( 'Product trigger', 'merchant' ),
							'options' => array(
								'featured_products' => esc_html__( 'Featured Products', 'merchant' ),
								'products_on_sale'  => esc_html__( 'Products on Sale', 'merchant' ),
								'new_products'      => esc_html__( 'New Products', 'merchant' ),
								'out_of_stock'      => esc_html__( 'Out of Stock Products', 'merchant' ),
								'all_products'      => esc_html__( 'All Products', 'merchant' ),
								'specific_products' => esc_html__( 'Specific Products', 'merchant' ),
								'by_category'       => esc_html__( 'Specific Categories', 'merchant' ),
							),
							'default' => 'products_on_sale',
						),

						array(
							'id'        => 'new_products_days',
							'type'      => 'number',
							'min'       => 0,
							'step'      => 1,
							'title'     => esc_html__( 'How long counts as new', 'merchant' ),
							'desc'      => esc_html__( 'Set the number of days the product will be marked as ‘New’ after it has been created', 'merchant' ),
							'default'   => 3,
							'condition' => array( 'display_rules', '==', 'new_products' ),
						),

						array(
							'id'          => 'product_cats',
							'type'        => 'select_ajax',
							'title'       => esc_html__( 'Categories', 'merchant' ),
							'source'      => 'options',
							'multiple'    => true,
							'options'     => Merchant_Admin_Options::get_category_select2_choices(),
							'placeholder' => esc_html__( 'Select categories', 'merchant' ),
							'desc'        => esc_html__( 'Select the product categories that will show the label.', 'merchant' ),
							'condition'   => array( 'display_rules', '==', 'by_category' ),
						),

						array(
							'id'            => 'product_ids',
							'type'          => 'products_selector',
							'multiple'      => true,
							'desc'          => esc_html__( 'Select the products that will show the layel.', 'merchant' ),
							'allowed_types' => array( 'simple', 'variable' ),
							'condition'     => array( 'display_rules', '==', 'specific_products' ),
						),

						array(
							'id'        => 'excluded_products',
							'type'      => 'products_selector',
							'title'     => esc_html__( 'Exclude Products', 'merchant' ),
							'multiple'  => true,
							'desc'      => esc_html__( 'Exclude products from this label.', 'merchant' ),
							'condition' => array( 'display_rules', 'any', 'all_products|by_category|featured_products|new_products|products_on_sale|out_of_stock' ),
						),

						array(
							'id'      => 'show_pages',
							'type'    => 'checkbox_multiple',
							'title'   => esc_html__( 'Show on pages', 'merchant' ),
							'options' => array(
								'homepage' => esc_html__( 'Homepage', 'merchant' ),
								'single'   => esc_html__( 'Product Single', 'merchant' ),
								'archive'  => esc_html__( 'Product Archive', 'merchant' ),
							),
							'default' => array( 'homepage', 'single', 'archive' ),
						),

						array(
							'id'      => 'show_devices',
							'type'    => 'checkbox_multiple',
							'title'   => esc_html__( 'Show on devices', 'merchant' ),
							'options' => array(
								'desktop' => esc_html__( 'Desktop', 'merchant' ),
								'mobile'  => esc_html__( 'Mobile', 'merchant' ),
							),
							'default' => array( 'desktop', 'mobile' ),
						),
					),
				),
			),
			'default'      => array(
				array(
					'layout'           => 'single-label',
					'label'            => esc_html__( 'SALE', 'merchant' ),
					'display_rules'    => 'featured_products',
				),
			),
		),
	),
) );
