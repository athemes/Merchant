<?php

/**
 * Pre Orders.
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Pre Orders Class.
 * 
 */
class Merchant_Pre_Orders_Main_Functionality {

	/**
	 * Pre order products.
	 * 
	 */
	private $pre_order_products = [];

	/**
	 * Init.
	 * 
	 * @return void
	 */
	public function init() {
		add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'allow_one_type_only' ), 99, 2 );

		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'manage_pre_orders' ), 10, 2 );
		add_filter( 'woocommerce_thankyou', array( $this, 'set_pre_order_status'), 10 );
		add_filter( 'woocommerce_billing_fields', array( $this, 'add_shipping_date_field' ) );

		// Cronjob.
		if ( ! wp_next_scheduled( 'check_for_released_preorders' ) ) {
			wp_schedule_event( time(), 'twicedaily', 'check_for_released_preorders' );
		}

		add_action( 'check_for_released_preorders', array( $this, 'check_for_pre_orders_and_maybe_update_status' ) );
		
		// Variations tab
		add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'custom_fields_for_variable_products' ), 10, 3 );

		// Inventory tab
		add_action( 'woocommerce_product_options_stock_status', array( $this, 'custom_fields_for_simple_products' ) );

		add_action( 'woocommerce_save_product_variation', array( $this, 'custom_fields_for_variable_products_save' ), 10, 2 );
		add_action( 'woocommerce_process_product_meta', array( $this, 'custom_fields_for_simple_products_save' ), 10, 2 );

		add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'change_button_text' ), 10, 2 );
		add_filter( 'woocommerce_product_single_add_to_cart_text', array( $this, 'change_button_text' ), 10, 2 );
		add_filter( 'woocommerce_available_variation', array( $this, 'change_button_text_for_variable_products' ), 10, 3 );
		add_action( 'woocommerce_before_add_to_cart_form', array( $this, 'maybe_render_additional_information' ), 10 );

		$this->register_pre_orders_order_status();
		add_filter( 'wc_order_statuses', array( $this, 'add_pre_orders_order_statuses' ) );
		add_filter( 'woocommerce_post_class', array( $this, 'pre_orders_post_class' ), 10, 3 );
	}

	/**
	 * Validation (one type only).
	 * 
	 * @param  boolean $passed
	 * @param  integer $product_id
	 * @return boolean
	 */
	public function allow_one_type_only( $passed, $product_id ) {
		$products       = array_filter( WC()->cart->get_cart_contents() );
		$has_pre_orders = false;

		foreach ( $products as $product ) {
			$isPreOrder = $this->isPreOrder( $product['data']->get_id() );
			if ( $isPreOrder ) {
				$has_pre_orders = true;
			}
		}

		$input_post_data = array(
			'variation_id' => filter_input(INPUT_POST, 'variation_id', FILTER_SANITIZE_NUMBER_INT)
		);

		$variableId				   = ( isset( $input_post_data['variation_id'] ) ) ? sanitize_text_field( wp_unslash( $input_post_data['variation_id'] ) ) : 0;
		$is_variable_has_pre_order = $this->isPreOrder( $product_id, $variableId );

		if ( empty( $products ) || ( $is_variable_has_pre_order && $has_pre_orders ) || ( false === $is_variable_has_pre_order && false === $has_pre_orders ) ) {
			$passed = true;
		} else {
			$passed = false;
			if ( $is_variable_has_pre_order ) {
				$notice = esc_html__( 'We detected that you are trying to add a pre-order product in your cart. Please remove the rest of the products before proceeding.', 'merchant' );
			} else {
				$notice = esc_html__( 'We detected that your cart has pre-order products. Please remove them before being able to add this product.', 'merchant' );
			}
			wc_add_notice( $notice, 'error' );
		}

		return $passed;
	}

	/**
	 * Check if is pre order.
	 * 
	 * @param  integer $product_id
	 * @param  integer $variableId
	 * @return boolean
	 */
	public function isPreOrder( $product_id, $variableId = 0 ) {
		if ( 'yes' === get_post_meta( $product_id, '_is_pre_order', true ) && new DateTime( get_post_meta( $product_id, '_pre_order_date', true ) ) > new DateTime() ) {
			return true;
		} elseif ( 'yes' === get_post_meta( $variableId, '_is_pre_order', true ) && new DateTime( get_post_meta( $variableId, '_pre_order_date', true ) ) > new DateTime() ) {
			return true;
		}

		return false;
	}

	/**
	 * Update pre order meta data (date).
	 * 
	 * @param  integer $orderId
	 * @param  array   $data
	 * @return void
	 */
	public function manage_pre_orders( $orderId, $data ) {
		$order = wc_get_order( $orderId );

		if ( isset( $data['preorder_date'] ) ) {
			$order->update_meta_data( '_preorder_date', esc_attr( $data['preorder_date'] ) );
			$order->save();
		}
	}

	/**
	 * Set pre order status.
	 * 
	 * @param  string  $status
	 * @param  integer $order_id
	 * @param  object  $order
	 * @return string
	 */
	public function set_pre_order_status( $order_id ) {
		if ( ! $order_id ) {
			return;
		}

		$order = wc_get_order($order_id);
		
		// Change the order status.
		if ( $order->get_meta( '_preorder_date' ) ) {
			$order->update_status( 'wc-pre-ordered' );
		}

		// Save.
		$order->save();
	}

	/**
	 * Add shipping date field.
	 * 
	 * @param  array $fields
	 * @return array
	 */
	public function add_shipping_date_field( $fields ) {
		if ( ! is_checkout() && ! is_cart() ) {
			return $fields;
		}

		global $woocommerce;

		$this->check_pre_order_products( $woocommerce->cart->get_cart() );

		if ( count( $this->get_pre_order_products() ) > 0 ) {
			$fields['preorder_date'] = [
				'type'     => 'text',
				'class'    => array( 'merchant-hidden' ),
				'required' => true,
				'default'  => $this->get_oldest_date(),
			];
		}

		return $fields;
	}

	/**
	 * Check pre order products in cart.
	 * 
	 * @param  array $items
	 * @return void
	 */
	public function check_pre_order_products( $items ) {
		if ( isset( $items['line_items'] ) ) {
			$items = $items['line_items'];
		}

		$pre_order_products = array_filter( $items, function ( $v ) {
			return 'yes' === get_post_meta( $v['product_id'], '_is_pre_order', true ) && new DateTime( get_post_meta( $v['product_id'], '_pre_order_date', true ) ) > new DateTime() || 'yes' === get_post_meta( $v['variation_id'], '_is_pre_order', true ) && new DateTime( get_post_meta( $v['variation_id'], '_pre_order_date', true ) ) > new DateTime();
		} );

		$this->set_pre_order_products( $pre_order_products );
	}

	/**
	 * Get the oldest date.
	 * 
	 * @return string
	 */
	public function get_oldest_date() {
		$product_with_oldest_date = array_reduce( $this->get_pre_order_products(), function ( $a, $b ) {
			if ( null === $a ) {
				return $b;
			}
			$aId = isset( $a['variation_id'] ) && 0 !== $a['variation_id'] ? $a['variation_id'] : $a['product_id'];
			$bId = isset( $b['variation_id'] ) && 0 !== $b['variation_id'] ? $b['variation_id'] : $b['product_id'];
			return $a ? ( strtotime( get_post_meta( $aId, '_pre_order_date', true ) ) > strtotime( get_post_meta( $bId, '_pre_order_date', true ) ) ? $a : $b ) : $b;
		} );

		$oldestId = isset( $product_with_oldest_date['variation_id'] ) && 0 !== $product_with_oldest_date['variation_id'] ? $product_with_oldest_date['variation_id'] : $product_with_oldest_date['product_id'];

		return get_post_meta( $oldestId, '_pre_order_date', true );
	}

	/**
	 * Get pre order products.
	 * 
	 * @return array
	 */
	public function get_pre_order_products() {
		return $this->pre_order_products;
	}

	/**
	 * Set pre order products.
	 * 
	 * @param array $pre_order_products
	 * @return $this
	 */
	public function set_pre_order_products( $pre_order_products ) {
		$this->pre_order_products = $pre_order_products;
		return $this;
	}

	/**
	 * Check for pre orders and maybe update the status.
	 * 
	 * @return void
	 */
	public function check_for_pre_orders_and_maybe_update_status() {
		$args = [
			'status' => 'wc-pre-ordered',
		];

		$pre_ordered_orders = wc_get_orders( $args );

		foreach ( $pre_ordered_orders as $order ) {
			$pre_order_date = strtotime( $order->get_meta('_preorder_date')  );

			if ( $pre_order_date < time() ) {
				$parent_order_id = $order->get_parent_id();

				if ( 0 !== $parent_order_id ) {
					$parent_order = wc_get_order( $parent_order_id );

					if ( $parent_order->get_status() === 'completed' ) {
						$order->update_status( 'wc-completed', '[WooCommerce Pre Orders] ' );
					}
				} else {
					if ( $order->get_status() === 'wc-pre-ordered' && $order->payment_complete() ) {
						$order->update_status( 'wc-completed', '[WooCommerce Pre Orders] ' );
					}
				}
			}
		}
	}

	/**
	 * Custom pre-order fields for variations.
	 * 
	 * @param  integer $loop
	 * @param  array   $variation_data
	 * @param  object  $variation
	 * @return void
	 */
	public function custom_fields_for_variable_products( $loop, $variation_data, $variation ) {
		echo '<div class="is_pre_order_meta_field form-row form-row-full" style="display: flex; align-items: center;">';
			woocommerce_wp_checkbox(
				[
					'id'    => '_is_pre_order_' . $variation->ID,
					'label' => '&nbsp;' . esc_html__( 'Pre-Order Product - Set this product as pre-order', 'merchant' ),
					'value' => get_post_meta( $variation->ID, '_is_pre_order', true ),
				]
			);

			echo wc_help_tip( __( 'Important: To pre-order out of stock products you must enable the \'Backorder\' stock option.', 'merchant' ) );
		echo '</div>';
		echo '<div class="form-row form-row-full">';
			woocommerce_wp_text_input(
				[
					'type'  => 'date',
					'id'    => '_pre_order_date_' . $variation->ID,
					'label' => esc_html__( 'Pre-Order Shipping Date', 'merchant' ),
					'value' => get_post_meta( $variation->ID, '_pre_order_date', true ),
				]
			);
		echo '</div>';
	}

	/**
	 * Custom pre-order fields for simple products.
	 * 
	 * @return void
	 */
	public function custom_fields_for_simple_products() {
		echo '<div class="is_pre_order_meta_field form-row form-row-full hide_if_variable" style="display: flex; align-items: center;">';
			woocommerce_wp_checkbox(
				[
					'id'          => '_is_pre_order',
					'label'       => esc_html__( 'Pre-Order Product', 'merchant' ),
					'description' => esc_html__( 'Set this product as pre-order', 'merchant' ),
					'value'       => get_post_meta( get_the_ID(), '_is_pre_order', true ),
				]
			);

			echo '<div style="margin-left: -20px">';
				echo wc_help_tip( __( 'Important: To pre-order out of stock products you must enable the \'Backorder\' stock option.', 'merchant' ) );
			echo '</div>';
		echo '</div>';
		echo '<div class="form-row form-row-full hide_if_variable">';
			woocommerce_wp_text_input(
				array(
					'type'  => 'date',
					'id'    => '_pre_order_date',
					'label' => esc_html__( 'Pre-Order Shipping Date', 'merchant' ),
					'value' => get_post_meta( get_the_ID(), '_pre_order_date', true ),
				)
			);
		echo '</div>';
		
	}

	/**
	 * Save custom fields for variable products.
	 * 
	 * @param  integer $post_id
	 * @return void
	 */
	public function custom_fields_for_variable_products_save( $post_id ) {
		$input_post_data = array(
			'_is_pre_order_by_post_id' => filter_input(INPUT_POST, '_is_pre_order_' . $post_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
			'_pre_order_data_by_post_id' => filter_input(INPUT_POST, '_pre_order_date_' . $post_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
		);
		
		$product = wc_get_product( $post_id );

		$is_pre_order_variation = isset( $input_post_data[ '_is_pre_order_by_post_id' ] ) ? 'yes' : 'no';
		$product->update_meta_data( '_is_pre_order', $is_pre_order_variation );

		if ( 'yes' === $is_pre_order_variation && isset( $input_post_data[ '_pre_order_data_by_post_id' ] ) ) {
			$pre_order_date_value = sanitize_text_field( wp_unslash( $input_post_data[ '_pre_order_data_by_post_id' ] ) );
			$product->update_meta_data( '_pre_order_date', $pre_order_date_value );
		}

		$product->save();
	}

	/**
	 * Save custom fields for simple products.
	 * 
	 * @param  integer $post_id
	 * @return void
	 */
	public function custom_fields_for_simple_products_save( $post_id ) {
		$input_post_data = array(
			'_is_pre_order' => filter_input(INPUT_POST, '_is_pre_order', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
			'_pre_order_date' => filter_input(INPUT_POST, '_pre_order_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
		);

		$product      = wc_get_product( $post_id );
		$is_pre_order = isset( $input_post_data['_is_pre_order'] ) ? 'yes' : 'no'; 
		$product->update_meta_data( '_is_pre_order', $is_pre_order );

		if ( 'yes' === $is_pre_order && isset( $input_post_data['_pre_order_date'] ) ) { 
			$pre_order_date_value = sanitize_text_field( wp_unslash( $input_post_data['_pre_order_date'] ) ); 
			$product->update_meta_data( '_pre_order_date', esc_attr( $pre_order_date_value ) );
		} else {
			$product->update_meta_data( '_pre_order_date', '' );
		}

		$product->save();
	}

	/**
	 * Change pre-order button text.
	 * 
	 * @param  string $text
	 * @return string
	 */
	public function change_button_text( $text ) {
		$input_post_data = array(
			'product_id' => filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT),
		);

		global $post;
		$_post = $post;

		// In some cases the $post might be null. e.g inside quick view popup.
		if ( ! $_post && isset( $input_post_data[ 'product_id' ] ) ) { 
			$_post = get_post( absint( $input_post_data[ 'product_id' ] ) ); 
		}

		if ( 'yes' === get_post_meta( $_post->ID, '_is_pre_order', true ) && strtotime( get_post_meta( $_post->ID, '_pre_order_date', true ) ) > time() ) {
			$text = Merchant_Admin_Options::get( 'pre-orders', 'button_text', esc_html__( 'Pre Order Now!', 'merchant' ) );
		}

		return $text;
	}

	/**
	 * Change pre-order button text for variable products.
	 * 
	 * @param  array   $data
	 * @param  object  $product
	 * @param  object  $variation
	 * @return array
	 */
	public function change_button_text_for_variable_products( $data, $product, $variation ) {
		global $product;

		if ( get_post_meta( $variation->get_id(), '_is_pre_order', true ) === 'yes' && strtotime( get_post_meta( $variation->get_id(), '_pre_order_date', true ) ) > time() ) {

			$data['is_pre_order'] = true;

			$additional_text = Merchant_Admin_Options::get( 'pre-orders', 'additional_text', esc_html__( 'Ships on {date}.', 'merchant' ) );
			$time_format     = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( $variation->get_id(), '_pre_order_date', true ) ) );
			$text            = $this->replaceDateTxt( $additional_text, $time_format );

			if ( ! empty( $text ) ) {
				$data['is_pre_order_date'] = $this->replaceDateTxt( $additional_text, $time_format );
			}

		}

		return $data;
	}

	/**
	 * Replace {date} markup with new text.
	 * 
	 * @param  string $string
	 * @param  string $time_format
	 * @return string
	 */
	public function replaceDateTxt( $string, $time_format ) {
		$from = array( '{date}' );
		$to   = array( $time_format );

		return str_replace( $from, $to, $string );
	}

	/**
	 * Maybe render additional information.
	 * 
	 * @return void
	 */
	public function maybe_render_additional_information() {
		$input_post_data = array(
			'product_id' => filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT),
		);

		global $post, $product;
		
		// Do not override globals.
		$_post = $post;
		$_product = $product;

		// In some cases the $post might be null. e.g inside quick view popup.
		if ( ! $_post && isset( $input_post_data[ 'product_id' ] ) ) {
			$_post 	  = get_post( absint( $input_post_data[ 'product_id' ] ) );
			$_product = wc_get_product( $_post->ID );
		}

		if ( null !== $_product ) {
			if ( 'yes' === get_post_meta( $_post->ID, '_is_pre_order', true ) && strtotime( get_post_meta( $_post->ID, '_pre_order_date', true ) ) > time() ) {
				$additional_text = Merchant_Admin_Options::get( 'pre-orders', 'additional_text', esc_html__( 'Ships on {date}.', 'merchant' ) );
				$time_format     = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( $_post->ID, '_pre_order_date', true ) ) );
				$text            = $this->replaceDateTxt( $additional_text, $time_format );

				echo sprintf( '<div class="merchant-pre-orders-date">%s</div>', esc_html( $text ) );
			}
		}
	}

	/**
	 * Register pre orders status.
	 * 
	 * @return void
	 */
	public function register_pre_orders_order_status() {
		register_post_status( 'wc-pre-ordered', array(
			'label'                     => esc_html__( 'Pre Ordered', 'merchant' ),
			'public'                    => true,
			'show_in_admin_status_list' => true,
			'show_in_admin_all_list'    => true,
			'exclude_from_search'       => false,
			/* translators: %s: pre ordered product count */
			'label_count'               => _n_noop( 'Pre Ordered <span class="count">(%s)</span>', 'Pre Ordered <span class="count">(%s)</span>', 'merchant' )
		) );
	}

	/**
	 * Add pre orders status to order statuses.
	 * 
	 * @param  array $order_statuses
	 * @return array
	 */
	public function add_pre_orders_order_statuses( $order_statuses ) {
		$order_statuses['wc-pre-ordered'] = 'Pre Ordered';

		return $order_statuses;
	}

	/**
	 * Add pre orders class identifies to the body tag.
	 * 
	 */
	public function pre_orders_post_class( $classes, $product ) {
		if ( 'yes' === get_post_meta( $product->get_id(), '_is_pre_order', true ) && strtotime( get_post_meta( $product->get_id(), '_pre_order_date', true ) ) > time() ) {
			$classes[] = 'merchant-pre-ordered-product';
		}

		return $classes;
	}

}