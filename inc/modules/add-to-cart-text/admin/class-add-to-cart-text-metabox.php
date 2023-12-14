<?php

/**
 * Add To Cart Text metabox.
 *
 * @package Merchant_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( class_exists( 'Merchant_Metabox' ) ) {
	class Merchant_Add_To_Cart_Text_Metabox extends Merchant_Metabox {

		public function __construct() {
			parent::__construct();

			if ( ! Merchant_Modules::is_module_active( Merchant_Add_To_Cart_Text::MODULE_ID ) ) {
				return;
			}

			add_action( 'merchant_metabox_options', array( $this, 'add_metabox_options' ) );
		}

		public function add_metabox_options() {
			$this->add_section( Merchant_Add_To_Cart_Text::MODULE_ID, array(
				'post_type' => array( 'product' ),
				'title'     => esc_html__( 'Add To Cart Text', 'merchant' ),
			) );

			$this->add_field( '_merchant_add_to_cart_text_single_label', array(
				'section'   => Merchant_Add_To_Cart_Text::MODULE_ID,
				'type'      => 'text',
				'title'     => esc_html__( 'Label on single product page', 'merchant' ),
				'default' => '',
				'desc' =>  esc_html__( 'Leave empty to use global settings.', 'merchant' ),
				'separator' => 'none',
			) );

			$this->add_field( '_merchant_add_to_cart_text_shop_label', array(
				'section'   => Merchant_Add_To_Cart_Text::MODULE_ID,
				'type'      => 'text',
				'title'     => esc_html__( 'Label on shop pages', 'merchant' ),
				'default' => '',
				'desc' =>  esc_html__( 'Leave empty to use global settings.', 'merchant' ),
				'separator' => 'none',
			) );
		}
	}

	// Init.
	add_action( 'init', function () {
		new Merchant_Add_To_Cart_Text_Metabox();
	} );
}
