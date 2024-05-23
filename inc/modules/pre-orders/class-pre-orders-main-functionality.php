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
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'pre-orders';

	/**
	 * @var string Date time format
	 */
	public const DATE_TIME_FORMAT = 'm-d-Y h:i A';

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
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_cart_item_data' ), 10, 4 );
		add_filter( 'woocommerce_hidden_order_itemmeta', array( $this, 'hidden_order_itemmeta' ) );
		add_action( 'woocommerce_add_order_item_meta', array( $this, 'add_order_item_meta' ), 10, 2 );

		// Cronjob.
		if ( ! wp_next_scheduled( 'check_for_released_preorders' ) ) {
			wp_schedule_event( time(), 'twicedaily', 'check_for_released_preorders' );
		}

		add_action( 'check_for_released_preorders', array( $this, 'check_for_pre_orders_and_maybe_update_status' ) );

		add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'change_button_text' ), 10, 2 );
		add_filter( 'woocommerce_product_single_add_to_cart_text', array( $this, 'change_button_text' ), 10, 2 );
		add_filter( 'woocommerce_available_variation', array( $this, 'change_button_text_for_variable_products' ), 10, 3 );
		add_action( 'woocommerce_before_add_to_cart_form', array( $this, 'additional_information_before_cart_form' ) );
		add_action( 'woocommerce_after_add_to_cart_form', array( $this, 'additional_information_after_cart_form' ) );

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

		add_filter( 'woocommerce_get_price_html', array( $this, 'dynamic_discount_price_html' ), 10, 2 );
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'dynamic_discount_cart_price' ) );
		add_action( 'woocommerce_checkout_order_created', array( $this, 'splitting_orders' ) );
		add_filter( 'manage_woocommerce_page_wc-orders_columns', array( $this, 'shop_order_column' ), 11 );
		add_filter( 'manage_edit-shop_order_columns', array( $this, 'shop_order_column' ), 11 );
		add_action( 'manage_woocommerce_page_wc-orders_custom_column', array( $this, 'shop_order_column_content' ), 10, 2 );
		add_action( 'manage_shop_order_posts_custom_column', array( $this, 'shop_order_column_content' ), 10, 2 );
	}

	/**
	 * Add shipping date column to the orders list.
	 *
	 * @param $columns array The columns.
	 *
	 * @return array The columns.
	 */
	public function shop_order_column( $columns ) {
		$columns['pre_order_shipping_date'] = esc_html__( 'Shipping Date', 'merchant' );

		return $columns;
	}

	/**
	 * Display the shipping date in the orders list.
	 *
	 * @param $column string The column.
	 * @param $order  WC_Order|int The order object in HPOS or order id.
	 *
	 * @return void
	 */
	public function shop_order_column_content( $column, $order ) {
		if ( $column === 'pre_order_shipping_date' ) {
			if ( is_numeric( $order ) ) {
				$order = wc_get_order( $order );
			}
			/**
			 * Filter the pre order shipping date.
			 *
			 * @param int      $shipping_date The shipping date.
			 * @param WC_Order $order         The order object.
			 *
			 * @since 1.9.9
			 */
			$shipping_date = apply_filters(
				'merchant_pre_order_shipping_date_column',
				$order->get_meta( '_merchant_order_pre_order_shipping_date' ),
				$order
			);
			if ( $shipping_date ) {
				echo '<strong>' . esc_html( $this->convert_timestamp_to_human_readable( $shipping_date ) ) . '</strong>';
			}
		}
	}

	/**
	 * Convert timestamp to human readable date.
	 *
	 * @param $timestamp int The timestamp.
	 *
	 * @return string The human readable date.
	 */
	private function convert_timestamp_to_human_readable( $timestamp ) {
		$timezone = new DateTimeZone( merchant_timezone() );
		$date     = new \DateTime( 'now', $timezone );
		$date->setTimestamp( $timestamp );

		return $date->format( self::DATE_TIME_FORMAT );
	}

	/**
	 * Add offer details to the product.
	 *
	 * @param $cart_item_data array The cart item data.
	 * @param $product_id     int The product ID.
	 * @param $variation_id   int The variation ID.
	 * @param $quantity       int The quantity.
	 *
	 * @return array The cart item data.
	 */
	public function add_cart_item_data( $cart_item_data, $product_id, $variation_id, $quantity ) {
		$product = wc_get_product( $product_id );
		$offer   = self::available_product_rule( $product_id );
		if ( empty( $offer ) && $product->is_type( 'variable' ) ) {
			$offer = self::available_product_rule( $variation_id );
		}
		if ( ! empty( $offer ) ) {
			$cart_item_data['_merchant_pre_order']               = $offer;
			$cart_item_data['_merchant_pre_order_shipping_date'] = $offer['shipping_timestamp'];
		}

		return $cart_item_data;
	}

	/**
	 * Add pre-order data to order item meta.
	 *
	 * @param $item_id int The item ID.
	 * @param $values  array The values.
	 *
	 * @return void
	 */
	public function add_order_item_meta( $item_id, $values ) {
		if ( isset( $values['_merchant_pre_order'] ) ) {
			wc_add_order_item_meta( $item_id, '_merchant_pre_order', $values['_merchant_pre_order'] );
		}

		if ( isset( $values['_merchant_pre_order_shipping_date'] ) ) {
			wc_add_order_item_meta( $item_id, '_merchant_pre_order_shipping_date', $values['_merchant_pre_order_shipping_date'] );
		}
	}

	/**
	 * Splitting orders.
	 *
	 * @param $order WC_Order The order object.
	 *
	 * @return void
	 */
	public function splitting_orders( $order ) {
		$mode = Merchant_Admin_Options::get( self::MODULE_ID, 'modes', 'unified_order' );
		if ( 'unified_order' === $mode || 'only_pre_orders' === $mode ) {
			$this->mark_whole_order_as_pre_order( $order );
		} elseif ( 'group_pre_order_into_one_order' === $mode ) {
			$this->group_pre_order_into_one_order( $order );
		} elseif ( 'separate_order_for_pre_orders' === $mode ) {
			$this->separate_order_for_pre_orders( $order );
		}
	}

	/**
	 * Mark whole order as pre-order if it contains pre-order products.
	 *
	 * @param $order WC_Order The order object.
	 *
	 * @return void
	 */
	public function mark_whole_order_as_pre_order( $order ) {
		$has_pre_order  = 0;
		$shipping_dates = array();
		foreach ( $order->get_items() as $item_id => $item ) {
			$product_id = $item->get_product_id();
			if ( $this->is_pre_order( $product_id ) ) {
				$shipping_dates[] = $item->get_meta( '_merchant_pre_order_shipping_date' );
				++ $has_pre_order;
				break;
			}
		}

		if ( $has_pre_order ) {
			$order->set_status( 'wc-pre-ordered' );
			$order->add_meta_data( '_is_pre_order', true );
			$order->add_meta_data( '_merchant_order_pre_order_shipping_date', max( $shipping_dates ) );
			$order->save();
		}
	}

	/**
	 * Group pre-order products into one order and keep the original order.
	 *
	 * @param WC_Order $order The order object.
	 *
	 * @return void
	 */
	public function group_pre_order_into_one_order( $order ) {
		// Get the original order
		$original_order = $order;

		// Store sub-orders IDs
		$sub_order_ids = $pre_order_products = array();

		// Loop through each product in the original order
		foreach ( $original_order->get_items() as $item_id => $item ) {
			if ( $this->is_pre_order( $item->get_product()->get_id() ) ) {
				$pre_order_products[] = $item;
			}
		}

		if ( ! empty( $pre_order_products ) ) {
			$shipping_dates = array();
			// Create a new order
			$new_order = wc_create_order(
				array(
					'parent' => $original_order->get_id(),
				)
			);

			// Copy order details from original order to new order.
			$this->copy_order_details( $original_order, $new_order );

			$new_order->add_meta_data( '_is_sub_order', true );
			$new_order->add_meta_data( '_is_pre_order', true );
			$new_order->set_status( 'wc-pre-ordered' );

			// Copy order meta data from original order
			foreach ( $original_order->get_meta_data() as $meta ) {
				$new_order->add_meta_data( $meta->key, $meta->value );
			}

			// add pre-order products to the new order
			foreach ( $pre_order_products as $item ) {
				$rule     = self::available_product_rule( $item->get_product()->get_id() );
				$new_item = $this->clone_order_item( $item );
				$new_item->add_meta_data( '_merchant_pre_order', $rule );
				$new_item->add_meta_data( '_merchant_is_pre_order_product', true );
				$new_item->add_meta_data( '_merchant_pre_order_shipping_date', $rule['shipping_timestamp'] );
				$new_order->add_item( $new_item );
				$shipping_dates[] = $rule['shipping_timestamp'];
			}

			// Calculate totals and set status
			$new_order->calculate_totals();
			$new_order->add_meta_data( '_merchant_order_pre_order_shipping_date', max( $shipping_dates ) );
			$new_order->save();

			$sub_order_ids[] = $new_order->get_id();

			// Save sub-order IDs to the original order
			$original_order->update_meta_data( '_sub_order_ids', $sub_order_ids );


			// Update original order totals after removing items
			$original_order->calculate_totals();
			$original_order->save();
		}
	}

	private function clone_order_item( $item ) {
		$new_item = new WC_Order_Item_Product();
		$new_item->set_product_id( $item->get_product_id() );
		$new_item->set_variation_id( $item->get_variation_id() );
		$new_item->set_name( $item->get_name() );
		$new_item->set_quantity( $item->get_quantity() );
		$new_item->set_subtotal( $item->get_subtotal() );
		$new_item->set_total( $item->get_total() );
		$new_item->set_taxes( $item->get_taxes() );
		$new_item->set_meta_data( $item->get_meta_data() );

		return $new_item;
	}

	/**
	 * Generate separate orders for each pre-order product.
	 *
	 * @param WC_Order $order The order object.
	 *
	 * @return void
	 */
	public function separate_order_for_pre_orders( $order ) {
		// Get the original order
		$original_order = $order;

		// Store sub-orders IDs
		$sub_order_ids = array();

		// Loop through each product in the original order
		foreach ( $original_order->get_items() as $item_id => $item ) {
			//check if the item is a product

			// Get product details
			$product_id = $item->get_product()->get_id();
			if ( ! $product_id ) {
				continue;
			}

			// check if the product is a pre-order
			if ( ! $this->is_pre_order( $product_id ) ) {
				continue;
			}

			// Create a new order
			$new_order = wc_create_order(
				array(
					'parent' => $original_order->get_id(),
				)
			);

			$new_item = $this->clone_order_item( $item );
			$rule     = self::available_product_rule( $product_id );
			$new_item->add_meta_data( '_merchant_pre_order', $rule );
			$new_item->add_meta_data( '_merchant_is_pre_order_product', true );
			$new_item->add_meta_data( '_merchant_pre_order_shipping_date', $rule['shipping_timestamp'] );
			$new_order->add_item( $new_item );

			// Copy order details from original order to new order.
			$this->copy_order_details( $original_order, $new_order );

			// Calculate totals and set status
			$new_order->calculate_totals();

			// Save new order and store its ID
			$new_order->add_meta_data( '_is_sub_order', true );
			$new_order->add_meta_data( '_is_pre_order', true );
			$new_order->add_meta_data( '_merchant_order_pre_order_shipping_date', $rule['shipping_timestamp'] );
			$new_order->set_status( 'wc-pre-ordered' );
			$new_order->save();
			$sub_order_ids[] = $new_order->get_id();
		}

		// Save sub-order IDs to the original order
		$original_order->update_meta_data( '_sub_order_ids', $sub_order_ids );

		// Update original order totals after removing items
		$original_order->calculate_totals();
		$original_order->save();
	}

	/**
	 * Hide order item meta that will be used for internal use only.
	 *
	 * @param $args array The hidden order item meta.
	 *
	 * @return array The hidden order item meta.
	 */
	function hidden_order_itemmeta( $args ) {
		$args[] = '_merchant_pre_order';
		$args[] = '_merchant_is_pre_order_product';
		$args[] = '_merchant_pre_order_shipping_date';

		return $args;
	}

	/**
	 * Copy order details from the original order to the new child order.
	 *
	 * @param $original_order WC_Order The original order.
	 * @param $new_order      WC_Order The new child order.
	 *
	 * @return void
	 */
	private function copy_order_details( $original_order, $new_order ) {
		$new_order->set_customer_id( $original_order->get_customer_id() );

		// set customer billing details
		$new_order->set_billing_first_name( $original_order->get_billing_first_name() );
		$new_order->set_billing_last_name( $original_order->get_billing_last_name() );
		$new_order->set_billing_phone( $original_order->get_billing_phone() );
		$new_order->set_billing_email( $original_order->get_billing_email() );
		$new_order->set_billing_address_1( $original_order->get_billing_address_1() );
		$new_order->set_billing_address_2( $original_order->get_billing_address_2() );
		$new_order->set_billing_city( $original_order->get_billing_city() );
		$new_order->set_billing_state( $original_order->get_billing_state() );
		$new_order->set_billing_postcode( $original_order->get_billing_postcode() );
		$new_order->set_billing_country( $original_order->get_billing_country() );

		// set customer shipping details
		$new_order->set_shipping_first_name( $original_order->get_shipping_first_name() );
		$new_order->set_shipping_last_name( $original_order->get_shipping_last_name() );
		$new_order->set_shipping_address_1( $original_order->get_shipping_address_1() );
		$new_order->set_shipping_address_2( $original_order->get_shipping_address_2() );
		$new_order->set_shipping_city( $original_order->get_shipping_city() );
		$new_order->set_shipping_state( $original_order->get_shipping_state() );
		$new_order->set_shipping_postcode( $original_order->get_shipping_postcode() );
		$new_order->set_shipping_country( $original_order->get_shipping_country() );

		$new_order->set_customer_note( $original_order->get_customer_note() );
		/**
		 * This action is documented in woocommerce/includes/class-wc-checkout.php
		 *
		 * @since 3.0.0 or earlier
		 */
		$new_order->set_customer_id( apply_filters( 'woocommerce_checkout_customer_id', get_current_user_id() ) );
		$new_order->set_customer_ip_address( WC_Geolocation::get_ip_address() );
		$new_order->set_customer_user_agent( wc_get_user_agent() );
		$new_order->set_currency( get_woocommerce_currency() );
		$new_order->set_created_via( 'checkout' );

		// Copy order meta data from original order
		foreach ( $original_order->get_meta_data() as $meta ) {
			$new_order->add_meta_data( $meta->key, $meta->value );
		}
	}

	/**
	 * Update the product price html based on the offer.
	 *
	 * @return string The price html.
	 */
	public function dynamic_discount_price_html( $html_price, $product ) {
		/**
		 * Disable the sale price change.
		 *
		 * @param bool       $disable_sale_price The sale price change status.
		 * @param string     $html_price         The price.
		 * @param WC_Product $product            The product object.
		 *
		 * @since 1.9.9
		 */
		if ( apply_filters( 'merchant_pre_order_disable_sale_price_html', false, $html_price, $product ) ) {
			return $html_price;
		}

		if ( ! is_admin() && in_array( $product->get_type(), $this->allowed_product_types(), true ) ) {
			if ( '' === $product->get_price() ) {
				return $html_price;
			}

			$offer = self::available_product_rule( $product->get_id() );
			if ( empty( $offer ) && $product->is_type( 'variation' ) ) {
				$offer = self::available_product_rule( $product->get_parent_id() );
			}

			if ( $product->is_type( 'variable' ) ) {
				$html_price = $this->variable_product_price_html( $product );
			} else {
				$html_price = $this->simple_product_price_html( $product, $offer, $html_price );
			}
		}

		return $html_price;
	}

	/**
	 * Variable product price html.
	 *
	 * @param WC_Product $product The product object.
	 *
	 * @return string The price html.
	 */
	private function variable_product_price_html( $product ) {
		$prices     = array();
		$variations = $product->get_children();
		foreach ( $variations as $variation_id ) {
			$variation       = wc_get_product( $variation_id );
			$variation_offer = self::available_product_rule( $variation_id );
			$regular_price   = $variation->get_regular_price();
			if ( empty( $variation_offer ) ) {
				if ( $variation->is_on_sale() ) {
					$sale_price = $variation->get_sale_price();
				} else {
					$sale_price = $regular_price;
				}
				$prices[] = $sale_price;
				continue;
			}
			$sale_price = $this->calculate_discounted_price( $regular_price, $variation_offer, $variation );
			if ( $sale_price <= 0 ) {
				// If the price is less than 0, set it to the regular/sale price
				if ( $variation->is_on_sale() ) {
					$sale_price = $variation->get_sale_price();
				} else {
					$sale_price = $regular_price;
				}
			}
			$prices[] = $sale_price;
		}

		$min_price = min( $prices );
		$max_price = max( $prices );
		if ( $min_price !== $max_price ) {
			return wc_format_price_range( $min_price, $max_price );
		}

		return wc_price( $min_price );
	}

	/**
	 * Simple product price html.
	 *
	 * @param WC_Product $product The product object.
	 * @param array      $offer   The offer details.
	 *
	 * @return string The price html.
	 */
	private function simple_product_price_html( $product, $offer, $html_price ) {
		$sale = self::get_rule_sale( $offer );
		if ( ! $sale ) {
			return $html_price;
		}
		$regular_price    = $product->get_regular_price();
		$discounted_price = $this->calculate_discounted_price( $regular_price, $offer, $product );

		return wc_format_sale_price( $regular_price, $discounted_price );
	}

	/**
	 * Calculate the offer discount for certain price.
	 *
	 * @param float      $price   The price.
	 * @param array      $offer   The offer.
	 * @param WC_Product $product The product object (only supplied to the filter).
	 *
	 * @return float The discounted price.
	 */
	public function calculate_discounted_price( $price, $offer, $product = null ) {
		$sale          = self::get_rule_sale( $offer );
		$discount_type = $discount_value = '';
		if ( $sale ) {
			$discount_type  = $offer['discount_type'];
			$discount_value = $offer['discount_amount'];
			if ( 'percentage' === $discount_type ) {
				$price = (float) $price - ( (float) $price * ( (float) $discount_value / 100 ) );
			} else {
				$price = (float) $price - (float) $discount_value;
			}
		}

		/**
		 * Filter the discounted price.
		 *
		 * @param float      $price          The price.
		 * @param array      $offer          The offer.
		 * @param string     $discount_type  The discount type.
		 * @param float      $discount_value The discount value.
		 * @param WC_Product $product        The product object.
		 *
		 * @since 1.9.5
		 */
		return apply_filters(
			'merchant_pre_order_sale_calculate_discounted_price',
			$price,
			$offer,
			$discount_type,
			$discount_value,
			$product
		);
	}

	/**
	 * Set the discounted cart price for the product.
	 *
	 * @param $cart_object array The cart object.
	 *
	 * @return void
	 */
	public function dynamic_discount_cart_price() {
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			/**
			 * Disable the cart price change.
			 *
			 * @param bool   $disable_cart_price The cart price change status.
			 * @param array  $cart_item          The cart item.
			 * @param string $cart_item_key      The cart item key.
			 *
			 * @since 1.9.7
			 */
			if ( apply_filters( 'merchant_pre_order_sale_disable_cart_price', false, $cart_item, $cart_item_key ) ) {
				continue;
			}
			$product_id = $cart_item['data']->get_id();
			$product    = wc_get_product( $product_id );
			if ( ! is_admin() && in_array( $product->get_type(), $this->allowed_product_types(), true ) ) {
				$offer = self::available_product_rule( $product->get_id() );
				if ( $product->is_type( 'variable' ) ) {
					$product_id = $cart_item['variation_id'];
					$product    = wc_get_product( $product_id );
					// check if the variation has an offer
					$offer = self::available_product_rule( $product_id );
				}
				if ( empty( $offer ) ) {
					continue;
				}

				$regular_price = $product->get_regular_price();
				$sale_price    = $this->calculate_discounted_price( $regular_price, $offer, $product );
				$cart_item['data']->set_price( $sale_price );
				$cart_item['data']->set_meta_data( array( '_merchant_pre_order_sale' => $offer ) );
			}
		}
	}

	/**
	 * Validation (one type only).
	 *
	 * @param boolean $passed
	 * @param integer $product_id
	 *
	 * @return boolean
	 */
	public function allow_one_type_only( $passed, $product_id ) {
		$products       = array_filter( WC()->cart->get_cart_contents() );
		$pre_orders     = 0;
		$has_pre_orders = false;
		foreach ( $products as $product ) {
			$is_pre_order = $this->is_pre_order( $product['data']->get_id() );
			if ( $is_pre_order ) {
				++ $pre_orders;
			}
		}
		if ( $pre_orders ) {
			$has_pre_orders = true;
		}
		$input_post_data = array(
			'variation_id' => filter_input( INPUT_POST, 'variation_id', FILTER_SANITIZE_NUMBER_INT ),
		);

		$variable_id               = ( isset( $input_post_data['variation_id'] ) ) ? sanitize_text_field( wp_unslash( $input_post_data['variation_id'] ) ) : 0;
		$is_variable_has_pre_order = $this->is_pre_order( $product_id ) || $this->is_pre_order( $variable_id );

		if ( empty( $products ) || ( $is_variable_has_pre_order && $has_pre_orders ) || ( false === $is_variable_has_pre_order && false === $has_pre_orders ) ) {
			$passed = true;
		} else {
			$mode = Merchant_Admin_Options::get( self::MODULE_ID, 'modes', 'unified_order' );
			if ( 'only_pre_orders' === $mode ) {
				$passed = false;
				if ( $is_variable_has_pre_order ) {
					$notice = esc_html__( 'We detected that you are trying to add a pre-order product in your cart. Please remove the rest of the products before proceeding.',
						'merchant' );
				} else {
					$notice = esc_html__( 'We detected that your cart has pre-order products. Please remove them before being able to add this product.', 'merchant' );
				}
				wc_add_notice( $notice, 'error' );
			}
		}

		return $passed;
	}

	/**
	 * Check if product is a pre order item by product ID.
	 *
	 * @param integer $product_id
	 *
	 * @return boolean
	 */
	public function is_pre_order( $product_id ) {
		$available_pre_order = self::available_product_rule( $product_id );

		return ! empty( $available_pre_order );
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
			$pre_order_date = $order->get_meta( '_merchant_order_pre_order_shipping_date' );

			if ( $pre_order_date < time() ) {
				$parent_order_id = $order->get_parent_id();

				if ( 0 !== $parent_order_id ) {
					$parent_order = wc_get_order( $parent_order_id );

					if ( $parent_order->get_status() === 'completed' ) {
						$order->update_status( 'wc-completed', '[Merchant Pre Orders] ' );
					}
				} elseif ( $order->get_status() === 'wc-pre-ordered' && $order->payment_complete() ) {
					$order->update_status( 'wc-completed', '[Merchant Pre Orders] ' );
				}
			}
		}
	}

	/**
	 * Change pre-order button text.
	 *
	 * @param string     $text
	 * @param WC_Product $product
	 *
	 * @return string
	 */
	public function change_button_text( $text, $product ) {
		if ( $product && $this->is_pre_order( $product->get_id() ) ) {
			$pre_order_rule = self::available_product_rule( $product->get_id() );
			$text           = $pre_order_rule['button_text'] ? Merchant_Translator::translate( $pre_order_rule['button_text'] ) : esc_html__( 'Pre Order Now!', 'merchant' );
		}

		return $text;
	}

	/**
	 * Change pre-order button text for variable products.
	 *
	 * @param array  $data
	 * @param object $product
	 * @param object $variation
	 *
	 * @return array
	 */
	public function change_button_text_for_variable_products( $data, $product, $variation ) {
		if ( $this->is_pre_order( $variation->get_id() ) ) {
			$pre_order_rule = self::available_product_rule( $variation->get_id() );
			$data['is_pre_order'] = true;

			$additional_text = $pre_order_rule['additional_text'] ? Merchant_Translator::translate( $pre_order_rule['additional_text'] )
				: esc_html__( 'Ships on {date}.', 'merchant' );
			$time_format     = date_i18n( get_option( 'date_format' ), $pre_order_rule['shipping_timestamp'] );
			$text            = $this->replace_date_text( $additional_text, $time_format );

			if ( ! empty( $text ) ) {
				$data['is_pre_order_date'] = $this->replace_date_text( $additional_text, $time_format );
			}
		}

		return $data;
	}

	/**
	 * Replace {date} markup with new text.
	 *
	 * @param string $string_contains_date_tag
	 * @param string $time_format
	 *
	 * @return string
	 */
	public function replace_date_text( $string_contains_date_tag, $time_format ) {
		$from = array( '{date}' );
		$to   = array( $time_format );

		return str_replace( $from, $to, $string_contains_date_tag );
	}

	/**
	 * Display pre order additional information before add to cart form.
	 *
	 * @return void
	 */
	public function additional_information_before_cart_form() {
		$input_post_data = array(
			'product_id' => filter_input( INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT ),
		);

		global $post, $product;

		// Do not override globals.
		$_post    = $post;
		$_product = $product;

		// In some cases the $post might be null. e.g inside quick view popup.
		if ( ! $_post && isset( $input_post_data['product_id'] ) ) {
			$_post    = get_post( absint( $input_post_data['product_id'] ) );
			$_product = wc_get_product( $_post->ID );
		}
		if ( $this->is_pre_order( $_post->ID ) ) {
			$pre_order_rule = self::available_product_rule( $_product->get_id() );
			if ( ! empty( $pre_order_rule ) && $pre_order_rule['placement'] === 'before' ) {
				$this->maybe_render_additional_information( $pre_order_rule );
			}
		}
	}

	/**
	 * Display pre order additional information after add to cart form.
	 *
	 * @return void
	 */
	public function additional_information_after_cart_form() {
		$input_post_data = array(
			'product_id' => filter_input( INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT ),
		);

		global $post, $product;

		// Do not override globals.
		$_post    = $post;
		$_product = $product;

		// In some cases the $post might be null. e.g inside quick view popup.
		if ( ! $_post && isset( $input_post_data['product_id'] ) ) {
			$_post    = get_post( absint( $input_post_data['product_id'] ) );
			$_product = wc_get_product( $_post->ID );
		}
		if ( $_product && $this->is_pre_order( $_post->ID ) ) {
			$pre_order_rule = self::available_product_rule( $_product->get_id() );
			if ( ! empty( $pre_order_rule ) && $pre_order_rule['placement'] === 'after' ) {
				$this->maybe_render_additional_information( $pre_order_rule );
			}
		}
	}

	/**
	 * Maybe render additional information.
	 *
	 * @return void
	 */
	public function maybe_render_additional_information( $rule ) {
		if ( ! empty( $rule ) ) {
			$additional_text = $rule['additional_text'] ? Merchant_Translator::translate( $rule['additional_text'] )
				: esc_html__( 'Ships on {date}.', 'merchant' );
			$time_format     = date_i18n( get_option( 'date_format' ), $rule['shipping_timestamp'] );
			$text            = $this->replace_date_text( $additional_text, $time_format );

			printf( '<div class="merchant-pre-orders-date">%s</div>', esc_html( $text ) );
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
	 * @param array $order_statuses
	 *
	 * @return array
	 */
	public function add_pre_orders_order_statuses( $order_statuses ) {
		$order_statuses['wc-pre-ordered'] = esc_html__( 'Pre Ordered', 'merchant' );

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
		if ( $cart_item['data']->is_type( 'variation' ) ) {
			$product_id = $cart_item['variation_id'];
		}
		if ( $this->is_pre_order( $product_id ) ) {
			$pre_order_rule = self::available_product_rule( $product_id );
			if ( $pre_order_rule ) {
				$label_text     = $pre_order_rule['cart_label_text'] ? Merchant_Translator::translate( $pre_order_rule['cart_label_text'] ) : esc_html__( 'Ships on', 'merchant' );
				$pre_order_date = date_i18n( get_option( 'date_format' ), $pre_order_rule['shipping_timestamp'] );

				$item_data[] = array(
					'key'     => $label_text,
					'value'   => $pre_order_date,
					'display' => '',
				);
			}
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
	 *
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
	 *
	 * @param array $context Default context.
	 *
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
	 *
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
	 *
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
	 *
	 * @return string
	 */
	private function get_pre_order_text( $product_id, $render_type = '' ) {
		if ( ! $this->is_pre_order( $product_id ) ) {
			return '';
		}

		$pre_order_rule = self::available_product_rule( $product_id );
		$label_text     = $pre_order_rule['cart_label_text'] ? Merchant_Translator::translate( $pre_order_rule['cart_label_text'] ) : esc_html__( 'Ships on', 'merchant' );
		$pre_order_date = date_i18n( get_option( 'date_format' ), $pre_order_rule['cart_label_text'] );
		if ( 'span' === $render_type ) {
			return sprintf( '<span class="merchant-pre-orders-note"><span class="merchant-pre-orders-label">%s:</span><span>%s</span></span>', esc_html( $label_text ),
				$pre_order_date );
		} elseif ( 'dl' === $render_type ) {
			return sprintf( '<dl class="merchant-pre-orders-note"><dt>%s:</dt><dd>%s</dd></dl>', esc_html( $label_text ), $pre_order_date );
		} else {
			return sprintf( '%s: %s', esc_html( $label_text ), $pre_order_date );
		}
	}

	/**
	 * Get the product types that are allowed to have storewide sale.
	 *
	 * @return array The allowed product types.
	 */
	public function allowed_product_types() {
		/**
		 * Filter the product types that are allowed to have storewide sale.
		 *
		 * @param array $product_types
		 *
		 * @since 1.9.5
		 */
		return apply_filters( 'merchant_pre_order_allowed_product_types', array(
			'simple',
			'variation',
			'variable',
		) );
	}

	/**
	 * Get the pre order rules.
	 *
	 * @param array $rule The rule to get.
	 *
	 * @return array|false The pre order rules or false if there are no rule sale.
	 */
	private static function get_rule_sale( $rule ) {
		$sale = false;
		if ( isset( $rule['discount_toggle'] ) && $rule['discount_toggle'] ) {
			$discount_type   = $rule['discount_type'];
			$discount_amount = $rule['discount_amount'];

			$sale = array(
				'discount_type'   => $discount_type,
				'discount_amount' => $discount_amount,
			);
		}

		/**
		 * Filter the pre order sale.
		 *
		 * @param array $sale The pre order sale.
		 * @param array $rule The pre order rule.
		 *
		 * @since 1.9.9
		 */
		return apply_filters( 'merchant_pre_order_rule_sale', $sale, $rule );
	}

	/**
	 * Get the pre order rules.
	 *
	 * @return array The pre order rules.
	 */
	private static function pre_order_rules() {
		return Merchant_Admin_Options::get( self::MODULE_ID, 'rules', array() );
	}

	/**
	 * Check if the rule is valid.
	 *
	 * @param array $rule The rule to check.
	 *
	 * @return boolean True if the rule is valid, false otherwise.
	 */
	private static function is_valid_rule( $rule ) {
		if ( ! isset( $rule['trigger_on'] ) ) {
			return false;
		}

		if ( 'product' === $rule['trigger_on'] && empty( $rule['product_ids'] ) ) {
			return false;
		}

		if ( 'category' === $rule['trigger_on'] && empty( $rule['category_slugs'] ) ) {
			return false;
		}

		if ( isset( $rule['discount_toggle'] ) && $rule['discount_toggle'] === true ) {
			if ( ! isset( $rule['discount_type'] ) ) {
				return false;
			}
			if ( ! isset( $rule['discount_amount'] ) ) {
				return false;
			}
		}

		if ( isset( $rule['partial_payment_toggle'] ) && $rule['partial_payment_toggle'] === true ) {
			if ( ! isset( $rule['partial_payment_type'] ) ) {
				return false;
			}
			if ( ! isset( $rule['partial_payment_amount'] ) ) {
				return false;
			}
		}

		if ( ! isset( $rule['user_condition'] ) ) {
			return false;
		}

		if ( ( 'customers' === $rule['user_condition'] ) && empty( $rule['user_condition_users'] ) ) {
			return false;
		}

		if ( ( 'roles' === $rule['user_condition'] ) && empty( $rule['user_condition_roles'] ) ) {
			return false;
		}

		if ( empty( $rule['shipping_date'] ) ) {
			return false;
		}

		if ( empty( $rule['button_text'] ) ) {
			return false;
		}

		if ( empty( $rule['placement'] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Prepare the rule fields.
	 *
	 * @param array $rule The rule to prepare.
	 *
	 * @return array The prepared rule.
	 */
	private static function prepare_rule( $rule ) {
		if ( 'product' === $rule['trigger_on'] ) {
			$rule['product_ids'] = array_map( 'intval', explode( ',', $rule['product_ids'] ) );
		}

		if ( ! empty( $rule['pre_order_start'] ) ) {
			$rule['pre_order_start'] = merchant_convert_date_to_timestamp( $rule['pre_order_start'], self::DATE_TIME_FORMAT );
		}

		if ( ! empty( $rule['pre_order_end'] ) ) {
			$rule['pre_order_end'] = merchant_convert_date_to_timestamp( $rule['pre_order_end'], self::DATE_TIME_FORMAT );
		}

		$rule['shipping_timestamp'] = merchant_convert_date_to_timestamp( $rule['shipping_date'], self::DATE_TIME_FORMAT );

		return $rule;
	}

	/**
	 * Get the available product rule.
	 *
	 * @param string $product_id The product ID.
	 *
	 * @return array The available product rule.
	 */
	public static function available_product_rule( $product_id ) {
		$available_rule = array();
		$rules          = self::pre_order_rules();
		$current_time   = merchant_get_current_timestamp();
		$current_user   = wp_get_current_user();
		foreach ( $rules as $rule ) {
			if ( self::is_valid_rule( $rule ) ) {
				$rule = self::prepare_rule( $rule );

				// check if pre-order start date is set and if it is not in the future
				if ( ! empty( $rule['pre_order_start'] ) && $rule['pre_order_start'] > $current_time ) {
					continue;
				}

				// check if pre-order end date is set and if it is in the past
				if ( ! empty( $rule['pre_order_end'] ) && $rule['pre_order_end'] < $current_time ) {
					continue;
				}

				if ( 'roles' === $rule['user_condition'] ) {
					$allowed_roles = $rule['user_condition_roles'];
					$user_roles    = $current_user->roles;
					$intersect     = array_intersect( $allowed_roles, $user_roles );
					if ( empty( $intersect ) ) {
						continue;
					}
				}

				if ( 'customers' === $rule['user_condition'] ) {
					$allowed_users   = $rule['user_condition_users'];
					$current_user_id = $current_user->ID;
					if ( ! in_array( $current_user_id, $allowed_users, true ) ) {
						continue;
					}
				}

				if ( 'product' === $rule['trigger_on'] && in_array( $product_id, $rule['product_ids'], true ) ) {
					$available_rule = $rule;
					break;
				}
				if ( 'category' === $rule['trigger_on'] ) {
					$terms = get_the_terms( $product_id, 'product_cat' );
					if ( ! empty( $terms ) ) {
						foreach ( $terms as $term ) {
							if ( in_array( $term->slug, $rule['category_slugs'], true ) ) {
								$available_rule = $rule;
								break;
							}
						}
					}
				}
			}
		}

		/**
		 * Filter the available product rule.
		 *
		 * @param array $available_rule The available product rule.
		 * @param int   $product_id     The product ID.
		 *
		 * @return array The available product rule.
		 *
		 * @since 1.9.9
		 */
		return apply_filters( 'merchant_pre_order_available_rule', $available_rule, $product_id );
	}

	/**
	 * Migrate old data to the new module storage.
	 *
	 * @return void
	 */
	public static function data_migration() {
		$args     = array(
			'post_type'      => array( 'product', 'product_variation' ),
			'posts_per_page' => - 1,
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => '_pre_order_date',
					'compare' => 'EXISTS',
				),
				array(
					'key'     => '_pre_order_date',
					'compare' => '!=',
					'value'   => '',
				),
				array(
					'key'     => '_is_pre_order',
					'compare' => 'EXISTS',
				),
				array(
					'key'     => '_is_pre_order',
					'compare' => 'in',
					'value'   => array( 'yes', '1' ),
				),
			),
		);
		$migrated = get_option( 'merchant_pre_orders_migrated' );
		if ( ! $migrated ) {
			$products = new WP_Query( $args );
			if ( $products->have_posts() ) {
				$rules = self::pre_order_rules();
				while ( $products->have_posts() ) {
					$products->the_post();
					$product_id     = get_the_ID();
					$pre_order_date = get_post_meta( $product_id, '_pre_order_date', true );
					$is_pre_order   = get_post_meta( $product_id, '_is_pre_order', true );
					$pre_order_date = merchant_convert_date_to_timestamp( $pre_order_date, 'yy-m-d' );
					$pre_order_date = date_i18n( self::DATE_TIME_FORMAT, $pre_order_date );
					if ( $is_pre_order ) {
						$rules[] = array(
							'offer-title'     => esc_html__( 'Custom Pre-order', 'merchant' ),
							'trigger_on'      => 'product',
							'product_ids'     => $product_id,
							'discount_toggle' => false,
							'user_condition'  => 'all',
							'shipping_date'   => $pre_order_date,
							'button_text'     => esc_html__( 'Pre Order Now!', 'merchant' ),
							'additional_text' => esc_html__( 'Ships on {date}.', 'merchant' ),
							'placement'       => 'before',
							'cart_label_text' => esc_html__( 'Ships on', 'merchant' ),
							'layout'          => 'rule-details',
						);
					}
				}
				Merchant_Admin_Options::set( self::MODULE_ID, 'rules', $rules );
			}
			update_option( 'merchant_pre_orders_migrated', true );
		}
	}
}

add_action( 'init', 'Merchant_Pre_Orders_Main_Functionality::data_migration' );