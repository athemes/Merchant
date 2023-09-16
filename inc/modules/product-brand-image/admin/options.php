<?php

/**
 * Product Brand Image Options.
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'product-brand-image',
	'fields' => array(

		array(
			'id'    => 'global-brand-image',
			'type'  => 'upload',
			'title' => esc_html__( 'Upload brand image', 'merchant' ),
			'desc'  => sprintf( esc_html__( 'Note: If you want to add a different brand image for each product, go to %sProducts%s, edit the desired product and upload a different image.', 'merchant' ), '<a href="'. esc_url( admin_url( 'edit.php?post_type=product' ) ).'">', '</a>' ),
		),

		array(
			'type' => 'divider',
		),

		array(
			'id'      => 'margin-top',
			'type'    => 'range',
			'title'   => esc_html__( 'Margin top', 'merchant' ),
			'min'     => 0,
			'max'     => 250,
			'step'    => 1,
			'default' => 15,
			'unit'    => 'px',
		),

		array(
			'id'      => 'margin-bottom',
			'type'    => 'range',
			'title'   => esc_html__( 'Margin bottom', 'merchant' ),
			'min'     => 0,
			'max'     => 250,
			'step'    => 1,
			'default' => 15,
			'unit'    => 'px',
		),

		array(
			'id'      => 'image-max-width',
			'type'    => 'range',
			'title'   => esc_html__( 'Image max width', 'merchant' ),
			'min'     => 1,
			'max'     => 500,
			'step'    => 1,
			'default' => 250,
			'unit'    => 'px',
		),

		array(
			'id'      => 'image-max-height',
			'type'    => 'range',
			'title'   => esc_html__( 'Image max height', 'merchant' ),
			'min'     => 1,
			'max'     => 500,
			'step'    => 1,
			'default' => 250,
			'unit'    => 'px',
		),

	)
) );
