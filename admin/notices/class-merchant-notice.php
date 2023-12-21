<?php
/**
 * Notice main class.
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Merchant_Notice {

	/**
	 * The notice ID.
	 * 
	 */
	public $id = '';

	/**
	 * Display conditions.
	 * 
	 */
	public $display_conditions = array( 
		'woocommerce_page_wc-settings', 
		'index.php', 
		'plugins.php', 
		'edit.php', 
		'plugin-install.php', 
	);

	/**
	 * Control if notice is only for free version.
	 * 
	 */
	public $only_free = false;
		
	/**
	 * The single class instance.
	 * 
	 */
	private static $instance = null;
	
	/**
	 * Instance.
	 * 
	 * @return Merchant_Notice
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 * 
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_notices', array( $this, 'notice' ), 20 );
        add_action( 'admin_init', array( $this, 'dimiss_notice' ), 0 );
	}

	/**
	 * Enqueue admin scripts
	 * 
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'merchant-notices', MERCHANT_URI . '/assets/css/admin/notices.min.css', array(), MERCHANT_VERSION, 'all' );
	}

	/**
	 * Notice.
	 * 
	 * @return void
	 */
	public function notice() {
		$user_id                  = get_current_user_id();
		$dismissed_notice         = get_user_meta( $user_id, $this->id . '_dismiss', true ) ? true : false;

		if( $this->only_free && defined( 'MERCHANT_PRO_VERSION' ) ) {
			return;
		}

		if ( $dismissed_notice ) {
			return;
		}

		// Display Conditions
		global $hook_suffix;
		
		if( ! in_array( $hook_suffix, $this->display_conditions, true ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if( $hook_suffix === 'edit.php' && ! isset( $_GET[ 'post_type' ] ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if( $hook_suffix === 'edit.php' && ( isset( $_GET[ 'post_type' ] ) && $_GET[ 'post_type' ] !== 'product' ) ) {
			return;
		}

		$this->notice_markup();
	}

	/**
	 * Notice HTML markup.
	 * 
	 */
	public function notice_markup() {}

    /**
	 * Dismiss notice permanently
	 * 
	 * @return void
	 */
	public function dimiss_notice() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET[$this->id . '_dismiss'] ) && '1' === $_GET[$this->id . '_dismiss'] ) {
			add_user_meta( get_current_user_id(), $this->id . '_dismiss', 'true', true );
		}
	}
}