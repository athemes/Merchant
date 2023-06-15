<?php

// Pre Orders
class Merchant_Pre_Orders{

	private $pre_order_products = [];

  public function __construct() {
    add_action( 'woocommerce_loaded', array( $this, 'pre_orders_init' ) );
  }

  public function pre_orders_init() {

    if ( ! Merchant_Modules::is_module_active( 'pre-orders' ) ) {
      return;
    }

		add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'allow_one_type_only' ), 99, 2 );

		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'manage_pre_orders' ), 10, 2 );
		add_filter( 'woocommerce_payment_complete_order_status', array( $this, 'set_pre_order_status'), 10, 3 );
		add_filter( 'woocommerce_billing_fields', array( $this, 'add_shipping_date_field' ) );

		// Cronjob.
		if ( !wp_next_scheduled( 'check_for_released_preorders' ) ) {
			wp_schedule_event( time(), 'twicedaily', 'check_for_released_preorders' );
		}

		add_action( 'check_for_released_preorders', array( $this, 'check_for_pre_orders' ) );
		
		// Variations tab
		add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'custom_variations_fields' ), 10, 3 );

		// Inventory tab
		add_action( 'woocommerce_product_options_stock_status', array( $this, 'custom_simple_fields' ) );

		add_action( 'woocommerce_save_product_variation', array( $this, 'custom_variations_fields_save' ), 10, 2 );
		add_action( 'woocommerce_process_product_meta', array( $this, 'custom_simple_fields_save' ), 10, 2 );

		add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'change_button_text' ), 10, 2 );
		add_filter( 'woocommerce_product_single_add_to_cart_text', array( $this, 'change_button_text' ), 10, 2 );
		add_filter( 'woocommerce_available_variation', array( $this, 'change_button_text_for_variable_products' ), 10, 3 );
		add_action( 'woocommerce_before_add_to_cart_form', array( $this, 'before_add_to_cart_btn' ), 10 );

		add_action( 'init', array( $this, 'register_pre_orders_order_status' ) );
		add_filter( 'wc_order_statuses', array( $this, 'add_pre_orders_order_statuses' ) );

	}                   

	public function register_pre_orders_order_status() {

		register_post_status( 'wc-pre-ordered', array(
			'label'                     => esc_html__( 'Pre Ordered', 'merchant' ),
			'public'                    => true,
			'show_in_admin_status_list' => true,
			'show_in_admin_all_list'    => true,
			'exclude_from_search'       => false,
			'label_count'               => _n_noop( 'Pre Ordered <span class="count">(%s)</span>', 'Pre Ordered <span class="count">(%s)</span>', 'merchant' )
		) );

	}

	public function add_pre_orders_order_statuses( $order_statuses ) {

		$order_statuses['wc-pre-ordered'] = 'Pre Ordered';

		return $order_statuses;

	}

	public function before_add_to_cart_btn() {

		global $post, $product;

		if ( $product !== null ) {
			if ( 'yes' == get_post_meta( $post->ID, '_is_pre_order', true ) && strtotime( get_post_meta( $post->ID, '_pre_order_date', true ) ) > time() ) {
				$time_format = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( $post->ID, '_pre_order_date', true ) ) );
				$text = $this->replaceDateTxt( 'Available on {date}', $time_format );
				echo sprintf( '<p>%s</p>', esc_html( $text ) );
			}
		}

	}

	public function change_button_text_for_variable_products( $data, $product, $variation ) {

		global $product;

		if ( get_post_meta( $variation->get_id(), '_is_pre_order', true ) == 'yes' && strtotime( get_post_meta( $variation->get_id(), '_pre_order_date', true ) ) > time() ) {
			$data['is_pre_order'] = true;
		}

		return $data;

	}

	public function replaceDateTxt( $string, $time_format ) {

		$from = array( "{date}" );
		$to   = array( $time_format );

		return str_replace( $from, $to, $string );

	}

	public function change_button_text( $text, $product ) {

		global $post;

		if ( 'yes' == get_post_meta( $post->ID, '_is_pre_order', true ) && strtotime( get_post_meta( $post->ID, '_pre_order_date', true ) ) > time() ) {
			$text = Merchant_Admin_Options::get( 'pre-orders', 'add_button_title', esc_html__( 'Pre Order Now!', 'merchant' ) );
		}

		return $text;

	}

	public function custom_variations_fields( $loop, $variation_data, $variation ) {

		echo '<div class="options_group form-row form-row-full">';

			woocommerce_wp_checkbox(
				[
					'id'    => '_is_pre_order_' . $variation->ID,
					'label' => '&nbsp;'. esc_html__( 'Pre Order Product - Set this product as pre-order', 'merchant' ),
					'value' => get_post_meta( $variation->ID, '_is_pre_order', true ),
				]
			);

			woocommerce_wp_text_input(
				[
					'type'  => 'date',
					'id'    => '_pre_order_date_' . $variation->ID,
					'label' => esc_html__( 'Pre Order Date', 'merchant' ),
					'value' => get_post_meta( $variation->ID, '_pre_order_date', true ),
					'custom_attributes' => array(
						'min' => date( 'Y-m-d' ),
					),
				]
			);

		echo '</div>';

	}

	public function custom_variations_fields_save( $post_id ) {

		$product = wc_get_product( $post_id );

		$is_pre_order_variation = isset( $_POST['_is_pre_order_' . $post_id] ) ? 'yes' : 'no';
		$product->update_meta_data( '_is_pre_order', $is_pre_order_variation );

		if ( $is_pre_order_variation == 'yes' ) {
			$pre_order_date_value = esc_html( $_POST['_pre_order_date_' . $post_id] );
			$product->update_meta_data( '_pre_order_date', esc_attr( $pre_order_date_value ) );
		}

		$product->save();

	}

	public function custom_simple_fields() {

		echo '<div class="options_group form-row form-row-full hide_if_variable">';

			woocommerce_wp_checkbox(
				[
					'id'          => '_is_pre_order',
					'label'       => esc_html__( 'Pre Order Product', 'merchant' ),
					'description' => esc_html__( 'Set this product as pre-order', 'merchant' ),
					'value'       => get_post_meta( get_the_ID(), '_is_pre_order', true ),
				]
			);

			woocommerce_wp_text_input(
				array(
					'type'  => 'date',
					'id'    => '_pre_order_date',
					'label' => esc_html__( 'Pre Order Date', 'merchant' ),
					'value' => get_post_meta( get_the_ID(), '_pre_order_date', true ),
					'custom_attributes' => array(
						'min' => date( 'Y-m-d' ),
					),
				)
			);

		echo '</div>';

	}

	public function custom_simple_fields_save( $post_id ) {
		$product      = wc_get_product( $post_id );
		$is_pre_order = isset( $_POST['_is_pre_order'] ) ? 'yes' : 'no';
		$product->update_meta_data( '_is_pre_order', $is_pre_order );

		if ( $is_pre_order == 'yes' ) {
			$pre_order_date_value = esc_html( $_POST['_pre_order_date'] );
			$product->update_meta_data( '_pre_order_date', esc_attr( $pre_order_date_value ) );
		} else {
			$product->update_meta_data( '_pre_order_date', '' );
		}

		$product->save();
	}

	public function check_for_pre_orders() {

		$args = [
			'status' => 'wc-pre-ordered',
		];

		$pre_ordered_orders = wc_get_orders( $args );

		foreach ( $pre_ordered_orders as $order ) {
			$pre_order_date = strtotime( $order->get_meta('_preorder_date')  );
			if ( $pre_order_date < time() ) {
				$parent_order_id = $order->get_parent_id();
				if ( $parent_order_id !== 0 ) {
					$parent_order = wc_get_order( $parent_order_id );
					if ( $parent_order->get_status() == 'completed' ) {
						$order->update_status( 'wc-completed', '[WooCommerce Pre Orders] ' );
					}
				} else {
					if ( $order->get_status() == 'wc-pre-ordered' && $order->payment_complete() ) {
						$order->update_status( 'wc-completed', '[WooCommerce Pre Orders] ' );
					}
				}
			}
		}
	}

	public function check_pre_order_products( $items ) {
		if ( isset( $items['line_items'] ) ) {
			$items = $items['line_items'];
		}

		$pre_order_products = @array_filter( $items, function ( $v ) {
			return 'yes' === get_post_meta( $v['product_id'], '_is_pre_order', true ) && new DateTime( get_post_meta( $v['product_id'], '_pre_order_date', true ) ) > new DateTime() || 'yes' === get_post_meta( $v['variation_id'], '_is_pre_order', true ) && new DateTime( get_post_meta( $v['variation_id'], '_pre_order_date', true ) ) > new DateTime();
		} );

		$this->set_pre_order_products( $pre_order_products );
	}

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

	public function get_pre_order_products() {
		return $this->pre_order_products;
	}

	public function set_pre_order_products( $pre_order_products ) {
		$this->pre_order_products = $pre_order_products;
		return $this;
	}

	public function allow_one_type_only( $passed, $product_id ) {

		$products = array_filter( WC()->cart->get_cart_contents() );
		$has_pre_orders = false;

		foreach ( $products as $product ) {
			$isPreOrder = $this->isPreOrder( $product['data']->get_id() );
			if ( $isPreOrder ) {
				$has_pre_orders = true;
			}
		}

		$variableId = ( isset( $_POST['variation_id'] ) ) ? sanitize_text_field( $_POST['variation_id'] ) : 0;
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

	public function isPreOrder( $product_id, $variableId = 0 ) {

		if ( 'yes' === get_post_meta( $product_id, '_is_pre_order', true ) && new DateTime( get_post_meta( $product_id, '_pre_order_date', true ) ) > new DateTime() ) {
			return true;
		} else if ( 'yes' === get_post_meta( $variableId, '_is_pre_order', true ) && new DateTime( get_post_meta( $variableId, '_pre_order_date', true ) ) > new DateTime() ) {
			return true;
		}

		return false;

	}

	public function set_pre_order_status( $status, $order_id, $order ) {

		$order = wc_get_order( $order_id );

		if ( $order->get_meta('_preorder_date') ) {
			return 'wc-pre-ordered';
		}

		return $status;

	}

	public function add_shipping_date_field( $fields ) {

		if ( ! is_checkout() && ! is_cart() ) {
			return $fields;
		}

		global $woocommerce;

		$this->check_pre_order_products( $woocommerce->cart->get_cart() );

		if ( count( $this->get_pre_order_products() ) > 0 ) {
			$fields['preorder_date'] = [
				'type'     => 'hidden',
				'class'    => array( 'merchant-hidden' ),
				'required' => true,
				'default'  => $this->get_oldest_date(),
			];
		}

		return $fields;

	}

	public function manage_pre_orders( $orderId, $data ) {

		$order = wc_get_order( $orderId );

		if ( isset( $data['preorder_date'] ) ) {
			$order->update_meta_data( '_preorder_date', esc_attr( $data['preorder_date'] ) );
			$order->save();
		}

	}

}

new Merchant_Pre_Orders();
