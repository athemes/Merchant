<?php

/**
 * Cookie Banner.
 *
 * @package Merchat_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Cookie Banner class.
 *
 */
class Merchant_Cookie_Banner extends Merchant_Add_Module {

	/**
	 * Module ID.
	 * 
	 */
	const MODULE_ID = 'cookie-banner';

	/**
	 * Is module preview.
	 * 
	 */
	public static $is_module_preview = false;

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();

		// Module section.
		$this->module_section = 'protect-your-store';

		// Module id.
		$this->module_id = self::MODULE_ID;

		// Module default settings.
		$this->module_default_settings = array(
			'theme' => 'merchant-cookie-banner-floating',
			'bar_text' => esc_html__( '🍪 We\'re using cookies to give you the best experience on our site.', 'merchant' ),
			'privacy_policy_text' => esc_html__( 'Learn More', 'merchant' ),
			'privacy_policy_url' => get_privacy_policy_url(),
			'button_text' => esc_html__( 'I understand', 'merchant' ),
			'cookie_duration' => '365',
			'close_button' => 1,
		);

		// Mount preview url.
		$preview_url = site_url( '/' );

		// Module data.
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];
		$this->module_data[ 'preview_url' ] = $preview_url;

		// Mount preview url.
		$preview_url = site_url( '/' );

		if ( function_exists( 'wc_get_page_id' ) ) {
			$preview_url = get_permalink( wc_get_page_id( 'shop' ) );
		}

		// Module data.
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];
		$this->module_data[ 'preview_url' ] = $preview_url;

		// Module options path.
		$this->module_options_path = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID . '/admin/options.php';

		// Is module preview page.
		if ( is_admin() && parent::is_module_settings_page() ) {
			self::$is_module_preview = true;

			// Enqueue admin styles.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_css' ) );

			// Admin preview box.
			add_filter( 'merchant_module_preview', array( $this, 'render_admin_preview' ), 10, 2 );

		}

		if ( Merchant_Modules::is_module_active( self::MODULE_ID ) && is_admin() ) {
			// Init translations.
			$this->init_translations();
		}

		if ( ! Merchant_Modules::is_module_active( self::MODULE_ID ) ) {
			return;
		}

		// Return early if it's on admin but not in the respective module settings page.
		if ( is_admin() && ! parent::is_module_settings_page() ) {
			return; 
		}

		// Enqueue styles.
		add_action( 'merchant_enqueue_before_main_css_js', array( $this, 'enqueue_css' ) );

		// Enqueue scripts.
		add_action( 'merchant_enqueue_after_main_css_js', array( $this, 'enqueue_scripts' ) );

		// Render cookie banner on footer.
		add_action( 'wp_footer', array( $this, 'cookie_banner' ) );
	}

	/**
	 * Init translations.
	 *
	 * @return void
	 */
	public function init_translations() {
		$settings = $this->get_module_settings();
		if ( ! empty( $settings['bar_text'] ) ) {
			Merchant_Translator::register_string( $settings['bar_text'], esc_html__( 'Cookie banner bar text', 'merchant' ) );
		}
		if ( ! empty( $settings['privacy_policy_text'] ) ) {
			Merchant_Translator::register_string( $settings['privacy_policy_text'], esc_html__( 'Pre orders privacy policy text', 'merchant' ) );
		}
		if ( ! empty( $settings['privacy_policy_url'] ) ) {
			Merchant_Translator::register_string( $settings['privacy_policy_url'], esc_html__( 'Pre orders privacy policy URL', 'merchant' ) );
		}
		if ( ! empty( $settings['button_text'] ) ) {
			Merchant_Translator::register_string( $settings['button_text'], esc_html__( 'Cookie banner button text', 'merchant' ) );
		}
	}

	/**
	 * Admin enqueue CSS.
	 * 
	 * @return void
	 */
	public function admin_enqueue_css() {
		$page   = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$module = ( ! empty( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( 'merchant' === $page && self::MODULE_ID === $module ) {
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/cookie-banner.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
		}
	}

	/**
	 * Enqueue CSS.
	 * 
	 * @return void
	 */
	public function enqueue_css() {

		// Specific module styles.
		wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/cookie-banner.min.css', array(), MERCHANT_VERSION );
	}

	/**
	 * Enqueue scripts.
	 * 
	 * @return void
	 */
	public function enqueue_scripts() {

		// Register and enqueue the main module script.
		wp_enqueue_script( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/cookie-banner.min.js', array(), MERCHANT_VERSION, true );
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

			$preview->set_html( $content );

			$preview->set_class( 'theme', '.merchant-cookie-banner', array( 'merchant-cookie-banner-floating', 'merchant-cookie-banner-fixed-bottom' ) );
			$preview->set_text( 'bar_text', '.merchant-cookie-banner-text' );
			$preview->set_text( 'button_text', '.merchant-cookie-banner-button' );
			$preview->set_css( 'background_color', '.merchant-cookie-banner-inner', '--merchant-background' );
			$preview->set_css( 'text_color', '.merchant-cookie-banner-inner', '--merchant-text-color' );
			$preview->set_css( 'button_background_color', '.merchant-cookie-banner-button', '--merchant-button-background' );
			$preview->set_css( 'button_text_color', '.merchant-cookie-banner-button', '--merchant-button-text-color' );
			$preview->set_css( 'modal_height', '.merchant-cookie-banner-inner', '--merchant-modal-height', 'px' );
		}

		return $preview;
	}

	/**
	 * Admin preview content.
	 * 
	 * @return void
	 */
	public function admin_preview_content() {
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

		<?php $this->cookie_banner(); ?>

		<?php
	}

	/**
	 * Get cookie banner.
	 * 
	 */
	public function get_cookie_banner() {
		$settings = $this->get_module_settings();

		ob_start();
		?>
	
		<div class="merchant-cookie-banner <?php echo esc_attr( $settings[ 'theme' ] ); ?>">
			<div class="merchant-cookie-banner-inner">
				<?php if ( ! empty( $settings[ 'close_button' ] ) ) : ?>
					<div class="merchant-cookie-close-button">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
							<path d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z"></path>
						</svg>
					</div>
				<?php endif; ?>
				<div class="merchant-cookie-banner-content">
					<div class="merchant-cookie-banner-text">
						<?php echo esc_html( Merchant_Translator::translate( $settings[ 'bar_text' ] ) ); ?>
						<?php if ( ! empty( $settings[ 'privacy_policy_url' ] ) ) : ?>
							<a href="<?php echo esc_url( Merchant_Translator::translate( $settings[ 'privacy_policy_url' ] ) ); ?>"><?php echo esc_html( Merchant_Translator::translate( $settings[ 'privacy_policy_text' ] ) ); ?></a>
						<?php endif; ?>
					</div>
					<div class="merchant-cookie-banner-button"><?php echo esc_html( Merchant_Translator::translate( $settings[ 'button_text' ] ) ); ?></div>
				</div>
			</div>
		</div>
	
		<?php
		return ob_get_clean();
	}

	/**
	 * Cookie banner.
	 * 
	 */
	public function cookie_banner() {
		echo wp_kses( $this->get_cookie_banner(), merchant_kses_allowed_tags() );
	}
}

// Initialize the module.
add_action( 'init', function() {
	new Merchant_Cookie_Banner();
} );
