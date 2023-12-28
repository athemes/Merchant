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
	private $pre_order_products = array();

	/**
	 * Flag if the_title filter is added or not.
	 *
	 * @var bool
	 */
	private $is_pre_order_filter_on = false;

	/**
	 * Init.
	 * 
	 * @return void
	 */
	public function init() {
		add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'allow_one_type_only' ), 99, 2 );

		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'manage_pre_orders' ), 10, 2 );
		add_filter( 'woocommerce_thankyou', array( $this, 'set_pre_order_status' ), 10 );
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

		// Cart
		add_filter( 'woocommerce_get_item_data', array( $this, 'cart_message_handler' ), 10, 2 );

		add_action( 'woocommerce_order_item_meta_end', array( $this, 'order_item_meta_end' ), 10, 4 );
		add_action( 'woocommerce_shop_loop_item_title', array( $this, 'shop_loop_item_title' ) );

		// Products block.
		add_filter( 'render_block_context', array( $this, 'add_block_title_filter' ), 10, 1 );
		add_filter( 'woocommerce_blocks_product_grid_item_html', array( $this, 'override_product_grid_block' ), PHP_INT_MAX, 3 );

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
			$is_pre_order = $this->is_pre_order( $product['data']->get_id() );
			if ( $is_pre_order ) {
				$has_pre_orders = true;
			}
		}

		$input_post_data = array(
			'variation_id' => filter_input(INPUT_POST, 'variation_id', FILTER_SANITIZE_NUMBER_INT),
		);

		$variable_id               = ( isset( $input_post_data['variation_id'] ) ) ? sanitize_text_field( wp_unslash( $input_post_data['variation_id'] ) ) : 0;
		$is_variable_has_pre_order = $this->is_pre_order( $product_id ) || $this->is_pre_order( $variable_id );

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
	 * Check if product is a pre order item by product ID.
	 * 
	 * @param  integer $product_id
	 * @return boolean
	 */
	public function is_pre_order( $product_id ) {
		return 'yes' === get_post_meta( $product_id, '_is_pre_order', true ) && strtotime( get_post_meta( $product_id, '_pre_order_date', true ) ) > time();
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
			$fields['preorder_date'] = array(
				'type'     => 'text',
				'class'    => array( 'merchant-hidden' ),
				'required' => true,
				'default'  => $this->get_oldest_date(),
			);
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
			return $this->is_pre_order( $v['product_id'] ) || $this->is_pre_order( $v['variation_id'] );
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
		$args = array(
			'status' => 'wc-pre-ordered',
		);

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
				} elseif ( $order->get_status() === 'wc-pre-ordered' && $order->payment_complete() ) {
						$order->update_status( 'wc-completed', '[WooCommerce Pre Orders] ' );
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
				array(
					'id'    => '_is_pre_order_' . $variation->ID,
					'label' => '&nbsp;' . esc_html__( 'Pre-Order Product - Set this product as pre-order', 'merchant' ),
					'value' => get_post_meta( $variation->ID, '_is_pre_order', true ),
				)
			);

			echo wc_help_tip( __( 'Important: To pre-order out of stock products you must enable the \'Backorder\' stock option.', 'merchant' ) );
		echo '</div>';
		echo '<div class="form-row form-row-full">';
			woocommerce_wp_text_input(
				array(
					'type'  => 'date',
					'id'    => '_pre_order_date_' . $variation->ID,
					'label' => esc_html__( 'Pre-Order Shipping Date', 'merchant' ),
					'value' => get_post_meta( $variation->ID, '_pre_order_date', true ),
				)
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
				array(
					'id'          => '_is_pre_order',
					'label'       => esc_html__( 'Pre-Order Product', 'merchant' ),
					'description' => esc_html__( 'Set this product as pre-order', 'merchant' ),
					'value'       => get_post_meta( get_the_ID(), '_is_pre_order', true ),
				)
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
	 * @param string     $text
	 * @param WC_Product $product
	 * @return string
	 */
	public function change_button_text( $text, $product ) {
		if ( $product && $this->is_pre_order( $product->get_id() ) ) {
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

		if ( $this->is_pre_order( $variation->get_id() ) ) {

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
	 * @param  string $string_contains_date_tag
	 * @param  string $time_format
	 *
	 * @return string
	 */
	public function replaceDateTxt( $string_contains_date_tag, $time_format ) {
		$from = array( '{date}' );
		$to   = array( $time_format );

		return str_replace( $from, $to, $string_contains_date_tag );
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
			$_post    = get_post( absint( $input_post_data[ 'product_id' ] ) );
			$_product = wc_get_product( $_post->ID );
		}

		if ( null !== $_product ) {
			if ( $this->is_pre_order( $_post->ID ) ) {
				$additional_text = Merchant_Admin_Options::get( 'pre-orders', 'additional_text', esc_html__( 'Ships on {date}.', 'merchant' ) );
				$time_format     = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( $_post->ID, '_pre_order_date', true ) ) );
				$text            = $this->replaceDateTxt( $additional_text, $time_format );

				printf( '<div class="merchant-pre-orders-date">%s</div>', esc_html( $text ) );
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
			'label_count'               => _n_noop( 'Pre Ordered <span class="count">(%s)</span>', 'Pre Ordered <span class="count">(%s)</span>', 'merchant' ),
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
		if ( $this->is_pre_order( $product->get_id() ) ) {
			$classes[] = 'merchant-pre-ordered-product';
		}

		return $classes;
	}

	/**
	 * Filters cart item data to display cart.
	 *
	 * @param array $item_data Cart item data.
	 * @param array $cart_item Cart item array.
	 *
	 * @return array
	 *
	 * @see wc_get_formatted_cart_item_data for filter usage.
	 */
	public function cart_message_handler( $item_data, $cart_item ) {
		$product_id = $cart_item['product_id'];
		if ( $this->is_pre_order( $product_id ) ) {
			$label_text     = Merchant_Admin_Options::get( 'pre-orders', 'cart_label_text', esc_html__( 'Ships on', 'merchant' ) );
			$pre_order_date = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( $product_id, '_pre_order_date', true ) ) );

			$item_data[] = array(
				'key'     => $label_text,
				'value'   => $pre_order_date,
				'display' => '',
			);
		}

		return $item_data;
	}

	/**
	 * filter name: woocommerce_order_item_meta_end
	 * Adds pre order additional text to the order item name
	 *
	 * @param int                    $item_id    Product ID.
	 * @param \WC_Order_Item_Product $item       Item array data.
	 * @param \WC_Order              $order      Order data.
	 * @param bool                   $plain_text Is plain text or not.
	 * @return string
	 */
	public function order_item_meta_end( $item_id, $item, $order, $plain_text ) {
		echo $this->get_pre_order_text( $item->get_product()->get_id(), $plain_text ? '' : 'dl' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render pre order text.
	 */
	public function shop_loop_item_title() {
		echo $this->get_pre_order_text( get_the_ID(), 'span' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Add the_title filter for block rendering.
	 * @param array $context      Default context.
	 * @return array
	 */
	public function add_block_title_filter( $context ) {
		if ( ! empty( $context['postType'] ) && 'product' === $context['postType'] && ! $this->is_pre_order_filter_on ) {
			$this->is_pre_order_filter_on = true;
			add_filter( 'the_title', array( $this, 'block_add_the_title_filter' ), 10, 2 );
			add_filter( 'render_block', array( $this, 'block_remove_the_title_filter' ), 10, 3 );
		}
		return $context;
	}

	/**
	 * Add pre order info to product grid block.
	 *
	 * @param string      $html    Product grid item HTML.
	 * @param object      $data    Product data passed to the template.
	 * @param \WC_Product $product Product object.
	 * @return string     Updated product grid item HTML.
	 */
	public function override_product_grid_block( $html, $data, $product ) {
		$pre_order_text = $this->get_pre_order_text( $product->get_id(), 'span' );
		if ( $pre_order_text ) {
			$html = str_replace( $data->title, $data->title . $pre_order_text, $html );
		}
		return $html;
	}

	/**
	 * Adds pre order info to post_title.
	 *
	 * @param string $title   Title.
	 * @param int    $post_id Post ID.
	 * @return string
	 */
	public function block_add_the_title_filter( $title, $post_id ) {
		return $title . $this->get_pre_order_text( $post_id, 'span' );
	}

	/**
	 * Remove the_title filter when block rendering finished.
	 *
	 * @param string   $block_content The block content.
	 * @param array    $parsed_block  The full block, including name and attributes.
	 * @param WP_Block $block         The block instance.
	 *
	 * @return string
	 */
	public function block_remove_the_title_filter( $block_content, $parsed_block, $block ) {
		if ( $this->is_pre_order_filter_on ) {
			remove_filter( 'the_title', array( $this, 'block_add_the_title_filter' ), 10 );
			$this->is_pre_order_filter_on = false;
		}
		return $block_content;
	}

	/**
	 * Get pre order text by product ID.
	 *
	 * @param int    $product_id
	 * @param string $render_type Render template type string. 'span', 'dl', ''. Default ''.
	 * @return string
	 */
	private function get_pre_order_text( $product_id, $render_type = '' ) {
		if ( ! $this->is_pre_order( $product_id ) ) {
			return '';
		}

		$label_text     = Merchant_Admin_Options::get( 'pre-orders', 'cart_label_text', __( 'Ships on', 'merchant' ) );
		$pre_order_date = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( $product_id, '_pre_order_date', true ) ) );
		if ( 'span' === $render_type ) {
			return sprintf( '<span class="merchant-pre-orders-note"><span class="merchant-pre-orders-label">%s:</span><span>%s</span></span>', esc_html( $label_text ), $pre_order_date );
		} elseif ( 'dl' === $render_type ) {
			return sprintf( '<dl class="merchant-pre-orders-note"><dt>%s:</dt><dd>%s</dd></dl>', esc_html( $label_text ), $pre_order_date );
		} else {
			return sprintf( '%s: %s', esc_html( $label_text ), $pre_order_date );
		}
	}
}