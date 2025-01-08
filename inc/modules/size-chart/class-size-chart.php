<?php

/**
 * Size Chart
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Size Chart class.
 *
 */
class Merchant_Size_Chart extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'size-chart';

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
		$this->module_section = 'improve-experience';

		// Module default settings.
		$this->module_default_settings = array(
			'global_size_chart' => '',
			'text'              => __( 'Size Chart', 'merchant' ),
			'icon'              => 'icon-size-chart',
		);

		// Module data.
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];

		// Module options path.
		$this->module_options_path = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID . '/admin/options.php';

		// Is module preview page.
		if ( is_admin() && parent::is_module_settings_page() ) {
			self::$is_module_preview = true;

			// Enqueue admin styles.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_css' ) );

			// Enqueue admin scripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

			// Render module essencial instructions before the module page body content.
			add_action( 'merchant_admin_after_module_page_page_header', array( $this, 'admin_module_essencial_instructions' ) );

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
		if ( ! empty( $settings['text'] ) ) {
			Merchant_Translator::register_string( $settings['text'], esc_html__( 'Size chart: label text', 'merchant' ) );
		}
	}

	/**
	 * Admin enqueue CSS.
	 *
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( parent::is_module_settings_page()  ) {
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/size-chart.min.css', array(), MERCHANT_VERSION );
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
		wp_enqueue_script( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/size-chart.min.js', array(), MERCHANT_VERSION, true );
	}

	/**
	 * Render module essencial instructions.
	 *
	 * @return void
	 */
	public function admin_module_essencial_instructions() { ?>
        <div class="merchant-module-page-settings">
            <div class="merchant-module-page-setting-box merchant-module-page-setting-box-style-2">
                <div class="merchant-module-page-setting-fields">
                    <div class="merchant-module-page-setting-field merchant-module-page-setting-field-content">
                        <div class="merchant-module-page-setting-field-inner">
                            <div class="merchant-tag-pre-orders">
                                <i class="dashicons dashicons-info"></i>
                                <p><?php echo esc_html__( 'You can have as many size charts you want and either specify in which product you want to add them or simply enable one globally to be displayed in all products. But if you want to display different size charts for each product, that\'s possible from admin product edit page.',
										'merchant' ); ?><?php printf( '<a href="%s" target="_blank">%s</a>',
										esc_url( admin_url( 'edit.php?post_type=product' ) ),
										esc_html__( 'View All Products', 'merchant' ) ); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

		<?php
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
		if ( self::MODULE_ID === $module ) {
			ob_start();
			self::admin_preview_content();
			$content = ob_get_clean();

			// HTML.
			$preview->set_html( $content );

			// Label Text.
			$preview->set_text( 'text', '.merchant-product-size-chart-button span' );

			// Icon.
			$preview->set_svg_icon( 'icon', '.size-chart-icon' );
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
                <div class="merchant-product-size-chart">
                    <div class="merchant-product-size-chart-button">
                        <a href="#">
                            <div class="size-chart-icon">
								<?php echo wp_kses( Merchant_SVG_Icons::get_svg_icon( $settings['icon'] ), merchant_kses_allowed_tags( array(), false ) ); ?>
                            </div>
                            <span><?php echo esc_html( $settings['text'] ); ?></span>
                        </a>
                    </div>
                    <div class="merchant-product-size-chart-modal">
                        <div class="merchant-product-size-chart-modal-inner">
                            <div class="merchant-product-size-chart-modal-close">
								<?php echo wp_kses( Merchant_SVG_Icons::get_svg_icon( 'icon-close' ), merchant_kses_allowed_tags( array(), false ) ); ?>
                            </div>
                            <div class="merchant-product-size-chart-modal-title">
								<?php echo esc_html__( 'Size Chart Modal Title', 'merchant' ); ?>
                            </div>
                            <div class="merchant-product-size-chart-modal-tables">
                                <table class="merchant-product-size-chart-modal-table active">
                                    <thead>
                                    <tr>
                                        <th><?php echo esc_html__( 'US', 'merchant' ); ?></th>
                                        <th><?php echo esc_html__( 'EU', 'merchant' ); ?></th>
                                        <th><?php echo esc_html__( 'UK', 'merchant' ); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>3</td>
                                        <td>35</td>
                                        <td>5</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>36</td>
                                        <td>6</td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>37</td>
                                        <td>7</td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td>38</td>
                                        <td>8</td>
                                    </tr>
                                    <tr>
                                        <td>7</td>
                                        <td>39</td>
                                        <td>9</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="merchant-product-size-chart-modal-content">
                                <p>Lorem ipsum dolor sit a met, dont dolor sit a la quat. </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mrc-preview-addtocart-placeholder"></div>
            </div>
        </div>

		<?php
	}

	/**
	 * Custom CSS.
	 *
	 * @return string
	 */
	public function get_module_custom_css() {
		$css = '';

		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'icon-size', 24, '.merchant-product-size-chart', '--mrc-sc-icon-size', 'px' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'title-text-color', '#212121', '.merchant-product-size-chart', '--mrc-sc-title-text-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'title-text-color-hover', '#757575', '.merchant-product-size-chart', '--mrc-sc-title-text-color-hover' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'icon-color', '#212121', '.merchant-product-size-chart', '--mrc-sc-icon-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'icon-color-hover', '#757575', '.merchant-product-size-chart', '--mrc-sc-icon-color-hover' );

		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'popup-width', 750, '.merchant-product-size-chart', '--mrc-sc-popup-width', 'px' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'background-color', '#f2f2f2', '.merchant-product-size-chart', '--mrc-sc-background-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'close-icon-color', '#212121', '.merchant-product-size-chart', '--mrc-sc-close-icon-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'close-icon-color-hover', '#757575', '.merchant-product-size-chart', '--mrc-sc-close-icon-color-hover' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'title-color', '#212121', '.merchant-product-size-chart', '--mrc-sc-title-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'tabs-color', '#757575', '.merchant-product-size-chart', '--mrc-sc-tabs-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'tabs-color-active', '#212121', '.merchant-product-size-chart', '--mrc-sc-tabs-color-active' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'table-headings-background-color', '#212121', '.merchant-product-size-chart', '--mrc-sc-table-headings-background-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'table-headings-text-color', '#ffffff', '.merchant-product-size-chart', '--mrc-sc-table-headings-text-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'table-body-background-color', '#ffffff', '.merchant-product-size-chart', '--mrc-sc-table-body-background-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'table-body-text-color', '#212121', '.merchant-product-size-chart', '--mrc-sc-table-body-text-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'description-text-color', '#212121', '.merchant-product-size-chart', '--mrc-sc-description-text-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'description-link-color', '#212121', '.merchant-product-size-chart', '--mrc-sc-description-link-color' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'description-link-color-hover', '#757575', '.merchant-product-size-chart', '--mrc-sc-description-link-color-hover' );

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
	Merchant_Modules::create_module( new Merchant_Size_Chart() );
} );
