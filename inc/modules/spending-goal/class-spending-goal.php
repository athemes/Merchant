<?php

/**
 * Spending Goal
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Spending Goal class.
 */
class Merchant_Spending_Goal extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'spending-goal';

	/**
	 * Module template path.
	 *
	 */
	const MODULE_TEMPLATES_PATH = 'modules/' . self::MODULE_ID;

	/**
	 * Is module preview.
	 *
	 */
	public static $is_module_preview = false;

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
		$this->module_section = 'boost-revenue';

		// Module default settings.
		$this->module_default_settings = array(
			'spending_goal'     => 150,
			'total_type'        => 'subtotal',
			'discount_type'     => 'percent',
			'discount_amount'   => 10,
			'discount_name'     => esc_html__( 'Spending goal', 'merchant' ),
			'user_condition'    => 'all',
			'text_goal_zero'    => esc_html__( 'Spend {spending_goal} to get a {discount_amount} discount!', 'merchant' ),
			'text_goal_started' => esc_html__( 'Spend {spending_goal} more to get a {discount_amount} discount!', 'merchant' ),
			'text_goal_reached' => esc_html__( 'Congratulations! You got a discount of {discount_amount} on this order!', 'merchant' ),
		);

		// Mount preview url.
		$preview_url = site_url( '/' );

		if ( function_exists( 'wc_get_products' ) ) {
			$products = wc_get_products( array( 'limit' => 1 ) );

			if ( ! empty( $products ) && ! empty( $products[0] ) ) {
				$preview_url = get_permalink( $products[0]->get_id() );
			}
		}

		// Module data.
		$this->module_data                = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];
		$this->module_data['preview_url'] = $preview_url;

		// Module options path.
		$this->module_options_path = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID . '/admin/options.php';

		// Is module preview page.
		if ( is_admin() && parent::is_module_settings_page() ) {
			self::$is_module_preview = true;

			// Enqueue admin styles.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_css' ) );

			// Enqueue admin scripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

			// Admin preview box.
			add_filter( 'merchant_module_preview', array( $this, 'render_admin_preview' ), 10, 2 );

			// Custom CSS.
			// The custom CSS should be added here as well due to ensure preview box works properly.
			add_filter( 'merchant_custom_css', array( $this, 'admin_custom_css' ) );
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
		if ( ! empty( $settings['discount_name'] ) ) {
			Merchant_Translator::register_string( $settings['discount_name'], esc_html__( 'Spending Discount Goal: Discount name', 'merchant' ) );
		}
		if ( ! empty( $settings['text_goal_zero'] ) ) {
			Merchant_Translator::register_string( $settings['text_goal_zero'], esc_html__( 'Spending Discount Goal: When the goal target is at 0%', 'merchant' ) );
		}
		if ( ! empty( $settings['text_goal_started'] ) ) {
			Merchant_Translator::register_string( $settings['text_goal_started'], esc_html__( 'Spending Discount Goal: When the goal target is between 1-99%', 'merchant' ) );
		}
		if ( ! empty( $settings['text_goal_reached'] ) ) {
			Merchant_Translator::register_string( $settings['text_goal_reached'], esc_html__( 'Spending Discount Goal: When the goal target is at 100%', 'merchant' ) );
		}
	}

	/**
	 * Admin enqueue CSS.
	 *
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( parent::is_module_settings_page() ) {
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/spending-goal.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
		}
	}

	/**
	 * Admin Enqueue scripts.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		// Register and enqueue the main module script.
		wp_enqueue_script( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/admin/preview.min.js', array(), MERCHANT_VERSION, true );
		wp_localize_script( 'merchant-admin-' . self::MODULE_ID, 'merchantSpendingGoal', array(
			'currencySymbol' => function_exists( 'get_woocommerce_currency_symbol' ) ? get_woocommerce_currency_symbol() : '$',
		) );
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
			ob_start();
			self::admin_preview_content();
			$content = ob_get_clean();

			// Get the template with dummy preview data.
			$preview->set_html( $content );

			$preview->set_css( 'gradient_start', '.merchant-spending-goal-widget-label', '--merchant-gradient-start' );
			$preview->set_css( 'gradient_end', '.merchant-spending-goal-widget-label', '--merchant-gradient-end' );
			$preview->set_css( 'progress_bar', '.merchant-spending-goal-widget-progress-bar-filled', '--merchant-progress-bar' );
			$preview->set_css( 'content_bg_color', '.merchant-spending-goal-widget', '--merchant-content-bg-color' );
			$preview->set_css( 'content_text_color', '.merchant-spending-goal-widget-text', '--merchant-content-text-color' );
			$preview->set_css( 'content_width', '.merchant-spending-goal-widget-content', '--merchant-content-width', 'px' );
			$preview->set_css( 'content_width', '.merchant-spending-goal-widget', '--merchant-content-width', 'px' );
			$preview->set_text( 'text_goal_zero', '.merchant-spending-goal-widget-text', array(
				array(
					'{spending_goal}',
					'{discount_amount}',
				),
				array(
					array(
						'setting' => 'spending_goal',
						'format'  => $preview->get_price_format(),
					),
					array(
						'setting'    => 'discount_type',
						'conditions' => array(
							'percent' => array(
								'setting' => 'discount_amount',
								'format'  => '<strong>{string}%</strong>',
							),
							'fixed'   => array(
								'setting' => 'discount_amount',
								'format'  => '<strong>' . $preview->get_price_format() . '</strong>',
							),
						),
					),
				),
			) );
			$preview->trigger_update( 'spending_goal' );
			$preview->trigger_update( 'discount_type' );
			$preview->trigger_update( 'discount_amount' );
		}

		return $preview;
	}

	/**
	 * Admin preview content.
	 *
	 * @return void
	 */
	public function admin_preview_content() {
		$settings = $this->get_module_settings();
		// Discount amount formatted with currency symbol or percentage based on discount type
		$discount_amount_formatted = $settings['discount_type'] === 'percent'
			? $settings['discount_amount'] . '%'
			: wc_price( $settings['discount_amount'] );
		?>

        <div class="mrc-preview-single-product-elements">
            <div class="mrc-preview-left-column">
                <div class="mrc-preview-product-image-wrapper">
                    <div class="mrc-preview-product-image"></div>
                    <div class="mrc-preview-product-image-thumbs">
                        <div class="mrc-preview-product-image-thumb"></div>
                        <div class="mrc-preview-product-image-thumb"></div>
                        <div class="mrc-preview-product-image-thumb"></div>
                    </div>
                </div>
            </div>
            <div class="mrc-preview-right-column">
                <div class="mrc-preview-text-placeholder"></div>
                <div class="mrc-preview-text-placeholder mrc-mw-70"></div>
                <div class="mrc-preview-text-placeholder mrc-mw-30"></div>
                <div class="mrc-preview-text-placeholder mrc-mw-40"></div>
                <div class="mrc-preview-addtocart-placeholder"></div>
            </div>
        </div>

		<?php merchant_get_template_part(
			self::MODULE_TEMPLATES_PATH,
			'widget',
			array(
				'spending' => 50,
				'content'  => str_replace(
					array(
						'{spending_goal}',
						'{discount_amount}',
					),
					array(
						wc_price( $settings['spending_goal'] ),
						'<strong>' . $discount_amount_formatted . '</strong>',
					),
					sanitize_text_field( $settings['text_goal_started'] )
				),
			)
		); ?>

		<?php
	}

	/**
	 * Custom CSS.
	 *
	 * @return string
	 */
	public function get_module_custom_css() {
		$css = '';

		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'gradient_start', '#5e5e5e', '.merchant-spending-goal-widget-label', '--merchant-gradient-start' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'gradient_end', '#212121', '.merchant-spending-goal-widget-label', '--merchant-gradient-end' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'progress_bar', '#d83a3b', '.merchant-spending-goal-widget-progress-bar-filled', '--merchant-progress-bar' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'content_bg_color', '#f9f9f9', '.merchant-spending-goal-widget', '--merchant-content-bg-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'content_text_color', 'inherit', '.merchant-spending-goal-widget-text', '--merchant-content-text-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'content_width', 300, '.merchant-spending-goal-widget-content', '--merchant-content-width', 'px' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'content_width', 300, '.merchant-spending-goal-widget', '--merchant-content-width', 'px' );

		return $css;
	}

	/**
	 * Admin custom CSS.
	 *
	 * @param string $css The custom CSS.
	 *
	 * @return string $css The custom CSS.
	 */
	public function admin_custom_css( $css ) {
		$css .= $this->get_module_custom_css();

		return $css;
	}
}

// Initialize the module.
add_action( 'init', function () {
	Merchant_Modules::create_module( new Merchant_Spending_Goal() );
} );
