<?php
/**
 * Merchant Code Snippets
 */

/**
 * Global Header and Footer
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Global Header and Footer', 'merchant' ),
	'module' => 'code-snippets',
	'fields' => array(

		array(
			'id'    => 'header_scripts',
			'type'  => 'textarea',
			'title' => esc_html__( 'Header', 'merchant' ),
			'desc'  => esc_html__( 'These scripts will be printed in the <head> section.', 'merchant' ),
			'class' => 'merchant-code-snippets-textarea',
		),

		array(
			'id'    => 'body_scripts',
			'type'  => 'textarea',
			'title' => esc_html__( 'Body', 'merchant' ),
			'desc'  => esc_html__( 'These scripts will be printed just below the opening <body> tag.', 'merchant' ),
			'class' => 'merchant-code-snippets-textarea',
		),

		array(
			'id'    => 'footer_scripts',
			'type'  => 'textarea',
			'title' => esc_html__( 'Footer', 'merchant' ),
			'desc'  => esc_html__( 'These scripts will be printed above the closing </body> tag.', 'merchant' ),
			'class' => 'merchant-code-snippets-textarea',
		),

	),
) );
