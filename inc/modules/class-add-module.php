<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Merchant_Add_Module {

	/**
	 * WooCommerce only.
	 * 
	 */
	public $wc_only = false;

	/**
	 * Module section.
	 * 
	 */
	public $module_section = '';

	/**
	 * Module id.
	 * 
	 */
	public $module_id = '';

	/**
	 * Module default settings.
	 * 
	 */
	public $module_default_settings = array();

	/**
	 * Module data.
	 * 
	 */
	public $module_data = array();

	/**
	 * Module options.
	 * 
	 */
	public $module_options_path = '';

	/**
	 * Constructor.
	 * 
	 */
	public function __construct() {
		
		// Add and expose the module into the plugin dashboard.
		add_filter( 'merchant_modules', array( $this, 'add_module' ) );

		// Add module options.
		add_filter( 'merchant_module_file_path', array( $this, 'add_module_options' ), 10, 2 );

		// Add class to body to identify if module is active or not.
		add_filter( 'admin_body_class', array( $this, 'add_module_activation_status_class' ), 10, 2 );

		// Handle modules list item class.
		add_filter( "merchant_admin_module_{$this->module_id}_list_item_class", array( $this, 'modules_list_item_class' ) );
	}

	/**
	 * Active modules class handler.
	 * 
	 */
	public function add_module_activation_status_class( $classes ) {
		if ( ! $this->is_module_settings_page() ) {
			return $classes;
		}

		if ( Merchant_Modules::is_module_active( $this->module_id ) ) {
			$classes = $classes . ' merchant-module-enabled';
		} else {
			$classes = $classes . ' merchant-module-disabled';
		}

		return $classes;
	}

	/**
	 * Modules list item class.
	 * 
	 * @param string $class
	 * @return string
	 */
	public function modules_list_item_class( $class ) {
		if ( $this->wc_only && ! class_exists( 'Woocommerce' ) ) {
			$class = $class . ' merchant-module-wc-only';
		}

		return $class;
	}

	/**
	 * Is module settings page.
	 * 
	 * @return bool
	 */
	public function is_module_settings_page() {
		return isset( $_GET[ 'page' ] ) && 'merchant' === $_GET[ 'page' ] // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			&& isset( $_GET[ 'module' ] ) && $this->module_id === $_GET[ 'module' ]; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	/**
	 * Get module settings.
	 * 
	 */
	public function get_module_settings() {
		$settings = get_option( 'merchant' ) ? get_option( 'merchant' ) : array();

		// Default settings.
		$defaults = $this->module_default_settings;

		if ( empty( $settings[ $this->module_id ] ) ) {
			$settings[ $this->module_id ] = $defaults;
		}

		// Parse settings with defaults.
		$settings = wp_parse_args( $settings[ $this->module_id ], $defaults );

		return $settings;
	}

	/**
	 * Add module.
	 * 
	 */
	public function add_module( $modules ) {
		$modules[ $this->module_section ][ 'modules' ][ $this->module_id ] = $this->module_data;

		return $modules;
	}

	/**
	 * Add module options.
	 * 
	 */
	public function add_module_options( $module_path, $merchant_module ) {
		if ( $this->module_id === $merchant_module ) {
			return $this->module_options_path;
		}

		return $module_path;
	}
}
