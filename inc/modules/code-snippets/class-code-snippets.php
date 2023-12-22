<?php

/**
 * Code Snippets.
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Code Snippets Class.
 * 
 */
class Merchant_Code_Snippets extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'code-snippets';

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
		parent::__construct();

		// Module section.
		$this->module_section = 'improve-experience';

		// Module id.
		$this->module_id = self::MODULE_ID;

		// Module default settings.
		$this->module_default_settings = array();

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

		if ( ! Merchant_Modules::is_module_active( self::MODULE_ID ) ) {
			return;
		}

		// Return early if it's on admin but not in the respective module settings page.
		if ( is_admin() && ! parent::is_module_settings_page() ) {
			return; 
		}

		// Snippets on head.
		add_action( 'wp_head', array( $this, 'header_code_snippet' ) );

		// Snippets on body.
		add_action( 'wp_body_open', array( $this, 'body_code_snippet' ) );

		// Snippets on footer.
		add_action( 'wp_footer', array( $this, 'footer_code_snippet' ), 99 );
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
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
		}
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

		<div class="mrc-code-snippets-preview">
			<img src="<?php echo esc_url( MERCHANT_URI . 'inc/modules/' . self::MODULE_ID . '/admin/images/preview-code-snippets.png' ); ?>" />
		</div>

		<?php
	}

	/**
	 * Get header code snippet.
	 * 
	 * @return void
	 */
	function header_code_snippet() {
		$snippet = Merchant_Option::get( 'code-snippets', 'header_code_snippets', '' );
	
		if ( ! empty( $snippet ) ) {
			echo wp_kses( $snippet, merchant_kses_allowed_tags_for_code_snippets() );
		}
	}

	/**
	 * Get body code snippet.
	 * 
	 * @return void
	 */
	function body_code_snippet() {
		$snippet = Merchant_Option::get( 'code-snippets', 'body_code_snippets', '' );
	
		if ( ! empty( $snippet ) ) {
			echo wp_kses( $snippet, merchant_kses_allowed_tags_for_code_snippets() );
		}
	}

	/**
	 * Get footer code snippet.
	 * 
	 * @return void
	 */
	function footer_code_snippet() {
		$snippet = Merchant_Option::get( 'code-snippets', 'footer_code_snippets', '' );
	
		if ( ! empty( $snippet ) ) {
			echo wp_kses( $snippet, merchant_kses_allowed_tags_for_code_snippets() );
		}
	}
}

// Initialize the module.
add_action( 'init', function() {
	new Merchant_Code_Snippets();
} );
