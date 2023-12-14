<?php

/**
 * Cart reserved timer.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Countdown timer class.
 *
 */
class Merchant_Cart_Reserved_Timer extends Merchant_Add_Module {

	/**
	 * Module ID.
	 */
	const MODULE_ID = 'cart-reserved-timer';

	/**
	 * Module path.
	 */
	const MODULE_DIR = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID;

	/**
	 * Module template path.
	 */
	const MODULE_TEMPLATES = 'modules/' . self::MODULE_ID;

	/**
	 * Constructor.
	 *
	 */
	public function __construct() {
		// Module id.
		$this->module_id = self::MODULE_ID;

		// WooCommerce only.
		$this->wc_only = true;

		// Parent construct.
		parent::__construct();

		// Module section.
		$this->module_section = 'reduce-abandonment';

		// Module default settings.
		$this->module_default_settings = array(
			'time_expires'          => 'clear-cart',
			'duration'              => 10,
			'reserved_message'      => __( 'An item in your cart is in high demand.', 'merchant' ),
			'timer_message_minutes' => __( 'Your cart is saved for {timer} minutes!', 'merchant' ),
			'timer_message_seconds' => __( 'Your cart is saved for {timer} seconds!', 'merchant' ),
			'icon'                  => 'fire',
		);

		// Mount preview url.
		$preview_url = site_url( '/' );

		if ( function_exists( 'wc_get_page_id' ) ) {
			$preview_url = get_permalink( wc_get_page_id( 'cart' ) );
		}

		// Module data.
		$this->module_data                = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];
		$this->module_data['preview_url'] = $preview_url;

		// Module options path.
		$this->module_options_path = self::MODULE_DIR . "/admin/options.php";

		// Enqueue admin styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );

		// Add preview box
		add_filter( 'merchant_module_preview', array( $this, 'render_admin_preview' ), 10, 2 );

		// Only applies if module is active or module is not active but admin only
		if ( ! Merchant_Modules::is_module_active( self::MODULE_ID ) && is_admin() || Merchant_Modules::is_module_active( self::MODULE_ID ) ) {
			// Custom CSS.
			add_filter( 'merchant_custom_css', array( $this, 'custom_css' ), 10, 2 );
		}

		if ( Merchant_Modules::is_module_active( self::MODULE_ID ) && is_admin() ) {
			// Init translations.
			$this->init_translations();
		}
	}

	/**
	 * Init translations.
	 *
	 * @return void
	 */
	public function init_translations() {
		$settings = $this->get_module_settings();
		if ( ! empty( $settings['reserved_message'] ) ) {
			Merchant_Translator::register_string( $settings['reserved_message'], esc_html__( 'Cart Reserved Timer: Cart reserved message', 'merchant' ) );
		}
		if ( ! empty( $settings['timer_message_minutes'] ) ) {
			Merchant_Translator::register_string( $settings['timer_message_minutes'], esc_html__( 'Cart Reserved Timer: Timer message for > 1 min', 'merchant' ) );
		}
		if ( ! empty( $settings['timer_message_seconds'] ) ) {
			Merchant_Translator::register_string( $settings['timer_message_seconds'], esc_html__( 'Cart Reserved Timer: Timer message for < 1 min', 'merchant' ) );
		}
	}

	/**
	 * Enqueue admin styles
	 *
	 * @return void
	 */
	public function enqueue_admin_styles() {
		if ( $this->is_module_settings_page() ) {
			// Module styling.
			wp_enqueue_style(
				'merchant-' . self::MODULE_ID,
				MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/' . self::MODULE_ID . '.min.css',
				array(),
				MERCHANT_VERSION
			);

			// Preview-specific styling.
			wp_enqueue_style(
				'merchant-preview-' . self::MODULE_ID,
				MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css',
				array(),
				MERCHANT_VERSION
			);
		}
	}

	/**
	 * Get the icon.
	 *
	 * @param $icon
	 *
	 * @return string
	 */
	public function get_icon( $icon ) {
		$path = MERCHANT_URI . 'assets/images/icons/' . Merchant_Cart_Reserved_timer::MODULE_ID;

		return array(
			'none'       => $path . '/cancel.svg',
			'fire'       => $path . '/fire.svg',
			'clock'      => $path . '/clock.svg',
			'hour-glass' => $path . '/hour-glass.svg',
		)[ $icon ];
	}

	/**
	 * Filtered module settings.
	 *
	 * @return array
	 */
	public function get_settings() {
		$settings = $this->get_module_settings();

		// Replace {timer} with duration in these settings.
		foreach ( array( 'timer_message_minutes', 'timer_message_seconds' ) as $setting ) {
			$settings[ $setting ] = str_replace(
				'{timer}',
				'<span>' . $settings['duration'] . ':00</span>',
				$settings[ $setting ]
			);
		}

		// Get the icon attributes.
		$settings['icon'] = array(
			'src' => $this->get_icon( $settings['icon'] ),
			'alt' => __( 'Cart Reserved Timer Icon', 'merchant' ),
		);

		return $settings;
	}

	/**
	 * Render admin preview
	 *
	 * @param Merchant_Admin_Preview $preview
	 * @param string $module
	 *
	 * @return Merchant_Admin_Preview
	 */
	public function render_admin_preview( $preview, $module ) {
		if ( $module === self::MODULE_ID ) {
			// HTML.
			$preview->set_html( $this->admin_preview_content() );

			// Background Color.
			$preview->set_css( 'background_color', '.merchant-cart-reserved-timer', '--merchant-bg-color' );

			// Reserved Message Text.
			$preview->set_text( 'reserved_message', '.merchant-cart-reserved-timer-content-title' );

			// Timer Message Text.
			$preview->set_text( 'timer_message_minutes', '.merchant-cart-reserved-timer-content-desc', array(
				array(
					'{timer}',
				),
				array(
					array(
						'setting' => 'duration',
						'format'  => '<span>{string}:00</span>',
					),
				),
			) );

			// Select Icon.
			$preview->set_icon( 'icon', '.merchant-cart-reserved-timer-icon img' );

			// Trigger Update When Duration Is Changed.
			$preview->trigger_update( 'duration' );
		}

		return $preview;
	}

	/**
	 * Admin preview content.
	 *
	 * @return string
	 */
	public function admin_preview_content() {
		// Template arguments
		$args = array_merge( $this->get_settings(), array(
			'css' => '',
		) );

		// Preview template.
		return merchant_get_template_part( self::MODULE_TEMPLATES, 'cart', $args, true );
	}

	/**
	 * Custom CSS.
	 *
	 * @param string $css
	 * @param Merchant_Custom_CSS $custom_css
	 *
	 * @return string
	 */
	public function custom_css( $css, $custom_css ) {
		// Background Color.
		$css .= $custom_css->get_variable_css( $this->module_id, 'background_color', '#f4f6f8', '.merchant-cart-reserved-timer', '--merchant-bg-color' );

		return $css;
	}
}

// Initialize the module.
add_action( 'init', function () {
	Merchant_Modules::create_module( new Merchant_Cart_Reserved_Timer() );
} );
