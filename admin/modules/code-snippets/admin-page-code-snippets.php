<?php
/**
 * Merchant Code Snippets
 */

/**
 * CSS
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'CSS', 'merchant' ),
	'module' => 'code-snippets',
	'fields' => array(

		array(
			'id'    => 'custom_css',
			'type'  => 'textarea',
			'title' => esc_html__( 'Custom CSS', 'merchant' ),
			'class' => 'merchant-code-snippets-textarea',
		),

	),
) );

/**
 * Javascript
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Javascript', 'merchant' ),
	'module' => 'code-snippets',
	'fields' => array(

		array(
			'id'    => 'custom_js_first',
			'type'  => 'textarea',
			'title' => esc_html__( 'Custom JS First - runs at the beginning of Merchant', 'merchant' ),
			'class' => 'merchant-code-snippets-textarea',
		),

		array(
			'id'    => 'custom_js_last',
			'type'  => 'textarea',
			'title' => esc_html__( 'Custom JS Last - runs at the end of Merchant', 'merchant' ),
			'class' => 'merchant-code-snippets-textarea',
		),

		array(
			'id'    => 'custom_js',
			'type'  => 'textarea',
			'title' => esc_html__( 'Custom JS', 'merchant' ),
			'class' => 'merchant-code-snippets-textarea',
		),

	),
) );
