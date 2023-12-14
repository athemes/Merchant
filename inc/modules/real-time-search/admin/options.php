<?php
/**
 * Merchant - Real Time Search
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'real-time-search',
	'fields' => array(

		array(
	  		'id'      => 'results_amounth_per_search',
			'type'    => 'range',
			'title'   => esc_html__( 'Results amount per search', 'merchant' ),
			'desc'    => esc_html__( 'Control the maximum amount of products to show in the search results.', 'merchant' ),
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'default' => 5,
		),

		array(
			'id'      => 'results_description',
			'type'    => 'select',
			'title'   => esc_html__( 'Results description', 'merchant' ),
			'options' => array(
				'product-post-content'      => esc_html__( 'Product Description', 'merchant' ),
				'product-short-description' => esc_html__( 'Product short description', 'merchant' ),
			),
	  		'default' => 'product-short-description',
		),
		
		array(
	  		'id'      => 'results_description_length',
			'type'    => 'range',
			'title'   => esc_html__( 'Results description length', 'merchant' ),
	  		'desc'    => esc_html__( 'The number of words to show in the description of the results.', 'merchant' ),
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'default' => 10,
		),

	array(
		'id'      => 'results_order_by',
		'type'    => 'select',
		'title'   => esc_html__( 'Results order by', 'merchant' ),
		'options' => array(
			'none'       => esc_html__( 'None', 'merchant' ),
			'title'      => esc_html__( 'Product name', 'merchant' ),
			'date'       => esc_html__( 'Published date', 'merchant' ),
			'modified' => esc_html__( 'Modified date', 'merchant' ),
			'rand'       => esc_html__( 'Random', 'merchant' ),
			'price'      => esc_html__( 'Product price', 'merchant' ),
		),
		'default' => 'title',
	),

	array(
		'id'      => 'results_order',
		'type'    => 'select',
		'title'   => esc_html__( 'Results order', 'merchant' ),
		'options' => array(
			'asc'   => esc_html__( 'Ascendant', 'merchant' ),
			'desc'  => esc_html__( 'Descendant', 'merchant' ),
		),
		'default' => 'asc',
	),

	array(
	  	'id'      => 'results_box_width',
		'type'    => 'range',
		'title'   => esc_html__( 'Results box width', 'merchant' ),
		'min'     => 1,
		'max'     => 1000,
		'step'    => 1,
		'default' => 500,
		'unit'    => 'px',
	),

	array(
		'id'    => 'display_categories',
		'type'  => 'checkbox',
		'label' => esc_html__( 'Display categories', 'merchant' ),
		'desc'  => esc_html__( 'Display product categories in the results if the searched term matches with category name.', 'merchant' ),
        'default' => false,
	),
	
	array(
	  	'id'    => 'enable_search_by_sku',
		'type'  => 'checkbox',
		'label' => esc_html__( 'Enable search by SKU', 'merchant' ),
		'desc'  => esc_html__( 'Return search results based on either product name or SKU.', 'merchant' ),
        'default' => false,
	),
	
    ),
) );
