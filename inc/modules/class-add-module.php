<?php

class Merchant_Add_Module {

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

	}

	/**
	 * Is module settings page.
	 * 
	 * @return bool
	 */
	public function is_module_settings_page() {
		return isset( $_GET[ 'page' ] ) && 'merchant' === $_GET[ 'page' ] 
			&& isset( $_GET[ 'module' ] ) && $this->module_id === $_GET[ 'module' ];
	}

	/**
	 * Get module settings.
	 * 
	 */
	public function get_module_settings() {
		$settings = get_option( 'merchant' );

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
