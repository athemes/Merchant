<?php

/**
 * Countdown timer.
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
class Merchant_Countdown_Timer extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'countdown-timer';

	/**
	 * Module template path.
	 */
	const MODULE_TEMPLATES = 'modules/' . self::MODULE_ID;

	/**
	 * Is module preview.
	 *
	 */
	public static $is_module_preview = false;

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Module id.
		$this->module_id = self::MODULE_ID;

		// WooCommerce only.
		$this->wc_only = true;

		// Parent construct.
		parent::__construct();

		// Module section.
		$this->module_section = 'convert-more';

		// Module default settings.
		$this->module_default_settings = array(
			'discount_products_only'  => true,
			'sale_ending_text'        => esc_html__( 'Sale ends in', 'merchant' ),
			'end_date'                => 'evergreen',
			'cool_off_period'         => 15,
			'min_expiration_deadline' => 2,
			'max_expiration_deadline' => 26,
			'sale_ending_alignment'   => 'left',
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

			// Localize Script.
			add_filter( 'merchant_admin_localize_script', array( $this, 'localize_script' ) );

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
		if ( ! empty( $settings['sale_ending_text'] ) ) {
			Merchant_Translator::register_string( $settings['sale_ending_text'], esc_html__( 'Countdown Timer: Sale ending message', 'merchant' ) );
		}
	}

	/**
	 * Admin enqueue CSS.
	 *
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( $this->is_module_settings_page() ) {
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/countdown-timer.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
		}
	}

	/**
	 * Admin Enqueue scripts.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_script( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/countdown-timer.min.js', array(), MERCHANT_VERSION, true );
		wp_enqueue_script( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/admin/preview.min.js', array(), MERCHANT_VERSION, true );
	}

	/**
	 * Localize Script.
	 */
	public function localize_script( $data ) {
		$data['is_admin'] = is_admin();

		return $data;
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

			// Font size
			$preview->set_css( 'digits_font_size', '.merchant-countdown-timer-countdown', '--merchant-digits-font-size', 'px' );
			$preview->set_css( 'labels_font_size', '.merchant-countdown-timer-countdown', '--merchant-labels-font-size', 'px' );

			// Sale Ending Color
			$preview->set_css( 'sale_ending_color', '.merchant-countdown-timer-text', '--merchant-sale-ending-color' );

			// Digits Color
			$preview->set_css( 'digits_color', '.merchant-countdown-timer-countdown', '--merchant-digits-color' );

			// Digits Background Color
			$preview->set_css( 'digits_background', '.merchant-countdown-timer-countdown', '--merchant-digits-background' );

			// Progress Color
			$preview->set_css( 'progress_color', '.merchant-countdown-timer-countdown', '--merchant-progress-color' );

			// Labels Color
			$preview->set_css( 'labels_color', '.merchant-countdown-timer-countdown', '--merchant-labels-color' );

			// Border Color
			$preview->set_css( 'digits_border', '.merchant-countdown-timer-countdown', '--merchant-digits-border' );

			// Digits width & height
			$preview->set_css( 'digits_width', '.merchant-countdown-timer-countdown', '--merchant-digits-width', 'px' );
			$preview->set_css( 'digits_height', '.merchant-countdown-timer-countdown', '--merchant-digits-height', 'px' );

			// Icon Color.
			$preview->set_css( 'icon_color', '.merchant-countdown-timer svg', '--merchant-icon-color' );

			// Sale Ending Text.
			$preview->set_text( 'sale_ending_text', '.merchant-countdown-timer-text' );
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
                </div>
            </div>
            <div class="mrc-preview-right-column">
                <h3 style="margin-top: 0;"><?php echo esc_html__( 'Your Product Name', 'merchant' ); ?></h3>
                <div class="mrc-preview-rating">
                    <div class="star-rating merchant-star-rating-style2" role="img" aria-label="Rated 3.00 out of 5">
                        <span style="width: 80%"></span>
                    </div>
                    <span style="color: #969696;"><?php echo esc_html__( 'reviews', 'merchant' ); ?></span>
                </div>
                <h3><?php echo esc_html__( '$49', 'merchant' ); ?></h3>
                <p><?php echo esc_html__( "An amazing product people can't refuse. What’s the next moment of value-realization when using your product? Tell the biggest use case. Briefly expand your product benefits on how this will help customers.", 'merchant' ); ?></p>
				<?php echo wp_kses( merchant_get_template_part( self::MODULE_TEMPLATES, 'single-product', $settings, true ), merchant_kses_allowed_tags() ); ?>

                <div class="merchant-preview-add-to-cart-inner">
                    <div class="merchant-preview-qty">
                        <button><?php echo esc_html( '+' ); ?></button>
                        <input type="text" value="<?php echo esc_attr( '1' ); ?>">
                        <button><?php echo esc_html( '-' ); ?></button>
                    </div>
                    <div class="merchant-preview-add-to-cart"><?php echo esc_html__( 'Add to cart', 'merchant' ); ?></div>
                </div>
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

		// Font sizes
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'digits_font_size', 16, '.merchant-countdown-timer-countdown', '--merchant-digits-font-size', 'px' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'labels_font_size', 16, '.merchant-countdown-timer-countdown', '--merchant-labels-font-size', 'px' );

		// Sale Ending Color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'sale_ending_color', '#626262', '.merchant-countdown-timer-text', '--merchant-sale-ending-color' );

		// Digits Color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'digits_color', '#444444', '.merchant-countdown-timer-countdown', '--merchant-digits-color' );

		// Digits Background Color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'digits_background', '#fff', '.merchant-countdown-timer-countdown', '--merchant-digits-background' );

		// Progress Color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'progress_color', '#3858E9', 'body', '--merchant-progress-color' );

		// Labels Color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'labels_color', '#444444', '.merchant-countdown-timer-countdown', '--merchant-labels-color' );

		// Border Color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'digits_border', '#444444', '.merchant-countdown-timer-countdown', '--merchant-digits-border' );

		// Digits width & height
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'digits_width', 80, '.merchant-countdown-timer-countdown', '--merchant-digits-width', 'px' );
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'digits_height', 80, '.merchant-countdown-timer-countdown', '--merchant-digits-height', 'px' );

		// Icon Color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'icon_color', '#626262', '.merchant-countdown-timer svg', '--merchant-icon-color' );

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
	Merchant_Modules::create_module( new Merchant_Countdown_Timer() );
} );