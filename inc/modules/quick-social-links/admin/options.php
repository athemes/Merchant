<?php

/**
 * Quick Social Links Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Settings
Merchant_Admin_Options::create( array(
	'module' => Merchant_Quick_Social_Links::MODULE_ID,
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'layout',
			'type'    => 'choices',
			'title'   => esc_html__( 'Layout', 'merchant' ),
			'options' => array(
				'pos-bottom' => array(
					'image' => '%s/qlb.svg',
					'label' => esc_html__( 'Bottom', 'merchant' ),
				),
				'pos-left'   => array(
					'image' => '%s/qll.svg',
					'label' => esc_html__( 'Left', 'merchant' ),
				),
				'pos-right'  => array(
					'image' => '%s/qlr.svg',
					'label' => esc_html__( 'Right', 'merchant' ),
				),
			),
			'default' => 'pos-bottom',
		),
		array(
			'id'      => 'visibility',
			'type'    => 'select',
			'title'   => esc_html__( 'Visibility', 'merchant' ),
			'options' => array(
				'visibility-all'     => esc_html__( 'Show on all devices', 'merchant' ),
				'visibility-desktop' => esc_html__( 'Desktop only', 'merchant' ),
				'visibility-mobile'  => esc_html__( 'Mobile/Tablet only', 'merchant' ),
			),
			'default' => 'visibility-all',
		),
	),
) );

// Link settings
Merchant_Admin_Options::create( array(
		'module' => Merchant_Quick_Social_Links::MODULE_ID,
		'title'  => esc_html__( 'Style Settings', 'merchant' ),
		'fields' => array(
			array(
				'id'      => 'icon_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Icon color', 'merchant' ),
				'default' => '#212121',
			),

			array(
				'id'      => 'bg_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Background color', 'merchant' ),
				'default' => '#ffffff',
			),

			array(
				'id'      => 'border_radius',
				'type'    => 'range',
				'title'   => esc_html__( 'Border radius', 'merchant' ),
				'min'     => 1,
				'max'     => 500,
				'step'    => 1,
				'unit'    => 'px',
				'default' => 15,
			),
		),
	)
);

// Get the user roles
$user_roles = array();
$user_rules = get_editable_roles();

if ( ! empty( $user_rules ) ) {
	foreach ( $user_rules as $role_id => $role_data ) {
		$user_roles[] = array(
			'id'   => 'user_role_' . $role_id,
			'text' => $role_data['name'],
		);
	}
}


// Link settings
Merchant_Admin_Options::create( array(
	'module' => Merchant_Quick_Social_Links::MODULE_ID,
	'title'  => esc_html__( 'Display Conditions', 'merchant' ),
	'fields' => array(

		array(
			'id'           => 'condition_rules',
			'type'         => 'flexible_content',
			'style'        => 'table',
			'button_label' => esc_html__( 'Add new', 'merchant' ),
			'layouts'      => array(
				'display' => array(
					'title'  => esc_html__( 'Display condition', 'merchant' ),
					'fields' => array(
						array(
							'id'      => 'type',
							'title'   => esc_html__( 'Inclusion', 'merchant' ),
							'type'    => 'select',
							'options' => array(
								'include' => esc_html__( 'Include', 'merchant' ),
								'exclude' => esc_html__( 'Exclude', 'merchant' ),
							),
						),
						array(
							'id'       => 'condition',
							'type'     => 'select_ajax',
							'title'    => __( 'Condition', 'merchant' ),
							'source'   => 'options',
							'multiple' => false,
							'classes'  => array(
								'flex-grow',
							),
							'options'  => array(
								array(
									'id'   => 'all',
									'text' => esc_html__( 'Entire Site', 'merchant' ),
								),
								array(
									'id'      => 'basic',
									'text'    => esc_html__( 'Basic', 'merchant' ),
									'options' => array(
										array(
											'id'   => 'singular',
											'text' => esc_html__( 'Singulars', 'merchant' ),
										),
										array(
											'id'   => 'archive',
											'text' => esc_html__( 'Archives', 'merchant' ),
										),
									),
								),
								array(
									'id'      => 'posts',
									'text'    => esc_html__( 'Posts', 'merchant' ),
									'options' => array(
										array(
											'id'   => 'single-post',
											'text' => esc_html__( 'Single Post', 'merchant' ),
										),
										array(
											'id'   => 'post-archives',
											'text' => esc_html__( 'Post Archives', 'merchant' ),
										),
										array(
											'id'   => 'post-categories',
											'text' => esc_html__( 'Post Categories', 'merchant' ),
										),
										array(
											'id'   => 'post-tags',
											'text' => esc_html__( 'Post Tags', 'merchant' ),
										),
									),
								),
								array(
									'id'      => 'pages',
									'text'    => esc_html__( 'Pages', 'merchant' ),
									'options' => array(
										array(
											'id'   => 'single-page',
											'text' => esc_html__( 'Single Page', 'merchant' ),
										),
									),
								),
								array(
									'id'      => 'woocommerce',
									'text'    => esc_html__( 'WooCommerce', 'merchant' ),
									'options' => array(
										array(
											'id'   => 'cart-page',
											'text' => esc_html__( 'Cart', 'merchant' ),
										),
										array(
											'id'   => 'checkout-page',
											'text' => esc_html__( 'Checkout', 'merchant' ),
										),
										array(
											'id'   => 'single-product',
											'text' => esc_html__( 'Single Product', 'merchant' ),
										),
										array(
											'id'   => 'product-archives',
											'text' => esc_html__( 'Product Archives', 'merchant' ),
										),
										array(
											'id'   => 'product-categories',
											'text' => esc_html__( 'Product Categories', 'merchant' ),
										),
										array(
											'id'   => 'product-tags',
											'text' => esc_html__( 'Product Tags', 'merchant' ),
										),
										array(
											'id'   => 'account-page',
											'text' => esc_html__( 'My Account', 'merchant' ),
										),
										array(
											'id'   => 'edit-account-page',
											'text' => esc_html__( 'Edit Account', 'merchant' ),
										),
										array(
											'id'   => 'order-received-page',
											'text' => esc_html__( 'Order Received', 'merchant' ),
										),
										array(
											'id'   => 'view-order-page',
											'text' => esc_html__( 'View Order', 'merchant' ),
										),
										array(
											'id'   => 'lost-password-page',
											'text' => esc_html__( 'Lost Password', 'merchant' ),
										),
									),
								),
								array(
									'id'      => 'other',
									'text'    => esc_html__( 'Other', 'merchant' ),
									'options' => array(
										array(
											'id'   => 'front-page',
											'text' => esc_html__( 'Front Page', 'merchant' ),
										),
										array(
											'id'   => 'blog',
											'text' => esc_html__( 'Blog', 'merchant' ),
										),
										array(
											'id'   => 'search',
											'text' => esc_html__( 'Search', 'merchant' ),
										),
										array(
											'id'   => '404',
											'text' => esc_html__( '404', 'merchant' ),
										),
										array(
											'id'   => 'author',
											'text' => esc_html__( 'Author', 'merchant' ),
										),
										array(
											'id'   => 'privacy-policy-page',
											'text' => esc_html__( 'Privacy Policy Page', 'merchant' ),
										),
									),
								),
							),
						),
					),
				),
				'user'    => array(
					'title'  => esc_html__( 'User condition', 'merchant' ),
					'fields' => array(
						array(
							'id'      => 'type',
							'title'   => esc_html__( 'Inclusion', 'merchant' ),
							'type'    => 'select',
							'options' => array(
								'include' => esc_html__( 'Include', 'merchant' ),
								'exclude' => esc_html__( 'Exclude', 'merchant' ),
							),
						),
						array(
							'id'       => 'condition',
							'type'     => 'select_ajax',
							'title'    => __( 'Condition', 'merchant' ),
							'source'   => 'options',
							'multiple' => false,
							'classes'  => array(
								'flex-grow',
							),
							'options'  => array(
								array(
									'id'      => 'user-auth',
									'text'    => esc_html__( 'User Auth', 'merchant' ),
									'options' => array(
										array(
											'id'   => 'logged-in',
											'text' => esc_html__( 'User Logged In', 'merchant' ),
										),
										array(
											'id'   => 'logged-out',
											'text' => esc_html__( 'User Logged Out', 'merchant' ),
										),
									),
								),
								array(
									'id'      => 'user-roles',
									'text'    => esc_html__( 'User Roles', 'merchant' ),
									'options' => $user_roles,
								),
								array(
									'id'      => 'other',
									'text'    => esc_html__( 'Other', 'merchant' ),
									'options' => array(
										array(
											'id'   => 'author',
											'text' => esc_html__( 'Author', 'merchant' ),
											'ajax' => true,
										),
									),
								),
							),
						),
					),
				),
			),
			'default'      => array(
				array(
					'layout'    => 'display',
					'condition' => 'all',
					'type'      => 'include',
				),
			),
		),
	),
) );


// Create the social icon choices.
$social_icon_choices = array();

foreach ( Merchant_Quick_Social_Links::get_socials() as $key => $label ) {
	$social_icon_choices[ $key ] = array(
		'label' => $label,
		'svg'   => "icon-{$key}",
	);
}


// Link settings
Merchant_Admin_Options::create( array(
	'module' => Merchant_Quick_Social_Links::MODULE_ID,
	'title'  => esc_html__( 'Links', 'merchant' ),
	'fields' => array(

		array(
			'id'           => 'links',
			'type'         => 'flexible_content',
			'sorting'      => true,
			'desc'         => esc_html__( 'Add a social link with an associated icon, or insert a custom link and upload your preferred image or SVG', 'merchant' ),
			'button_label' => esc_html__( 'Add new', 'merchant' ),
			'layouts'      => array(
				'social' => array(
					'title'  => esc_html__( 'Social link', 'merchant' ),
					'fields' => array(
						array(
							'id'      => 'icon',
							'type'    => 'choices',
							'title'   => esc_html__( 'Icon', 'merchant' ),
							'options' => $social_icon_choices,
							'default' => 'cart-icon-1',
						),
						array(
							'id'      => 'url',
							'title'   => esc_html__( 'URL', 'merchant' ),
							'type'    => 'text',
							'default' => 'https://',
							'desc'    => esc_html__( 'After entering the complete URL, an associated icon will be automatically chosen.', 'merchant' ),
						),
					),
				),
				'custom' => array(
					'title'  => esc_html__( 'Custom link', 'merchant' ),
					'fields' => array(
						array(
							'id'    => "image",
							'type'  => 'upload',
							'title' => __( 'Select custom image or SVG', 'merchant' ),
						),
						array(
							'id'      => 'url',
							'title'   => esc_html__( 'URL', 'merchant' ),
							'type'    => 'text',
							'default' => 'https://',
						),
					),
				),
			),
			'default'      => array(
				array(
					'layout' => 'social',
					'icon'   => 'facebook',
					'url'    => esc_html__( 'https://www.facebook.com', 'merchant' ),
				),
				array(
					'layout' => 'social',
					'icon'   => 'instagram',
					'url'    => esc_html__( 'https://www.instagram.com', 'merchant' ),
				),
				array(
					'layout' => 'social',
					'icon'   => 'twitter',
					'url'    => esc_html__( 'https://www.twitter.com', 'merchant' ),
				),
			),
		),
	),
) );
