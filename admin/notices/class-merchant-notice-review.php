<?php
/**
 * Review notice.
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Merchant_Notice_Review extends Merchant_Notice {
	
	/**
	 * Constructor.
	 * 
	 */
	public function __construct() {
		$this->id = 'merchant-review-notice';

		parent::__construct();

		add_action( 'admin_init', array( $this, 'set_plugin_installed_time' ), 0 );
		add_action( 'admin_init', array( $this, 'ignore_plugin_review_notice' ), 0 );
		add_action( 'admin_init', array( $this, 'ignore_plugin_review_notice_partially' ), 0 );
	}

	/**
	 * Set plugin installed time in database.
	 * 
	 * @return void
	 */
	public function set_plugin_installed_time() {
		if ( ! get_option( 'merchant_plugin_installed_time' ) ) {
			update_option( 'merchant_plugin_installed_time', time() );
		}
	}

	/**
	 * Disable review notice permanently
	 * 
	 * @return void
	 */
	public function ignore_plugin_review_notice() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['nag_' . $this->id] ) && '0' === $_GET['nag_' . $this->id] ) {
			add_user_meta( get_current_user_id(), 'nag_' . $this->id, 'true', true );
		}
	}

	/**
	 * Delay review notice
	 * 
	 * @return void
	 */
	public function ignore_plugin_review_notice_partially() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['delay_' . $this->id . '_partially'] ) && '0' === $_GET['delay_' . $this->id . '_partially'] ) {
			update_user_meta( get_current_user_id(), 'delay_' . $this->id . '_partially', time() );
		}
	}

	/**
	 * The notice markup/output.
	 * 
	 * @return void
	 */
	public function notice_markup() { 
		$user_id                  = get_current_user_id();
		$current_user             = wp_get_current_user();
		$ignored_notice           = get_user_meta( $user_id, $this->id, true );
		$ignored_notice_partially = get_user_meta( $user_id, 'delay_' . $this->id . '_partially', true );
		$already_rated            = get_user_meta( $user_id, 'nag_' . $this->id, true );

		if ( ( get_option( 'merchant_plugin_installed_time' ) > strtotime( '-14 day' ) ) || ( $ignored_notice_partially > strtotime( '-14 day' ) ) || ( $ignored_notice ) || $already_rated ) {
			return;
		}

		?>
		<div class="merchant-notice notice" style="position:relative;">
			<p>
				<?php
				printf(
				    /* Translators: %1$s current user display name. */
					esc_html__(
						'Hi %1$s, we hope you\'re enjoying using Merchant. We have a small request that would mean a lot to us. Could you please leave a review for Merchant on WordPress.org? Your support will not only motivate us but also help other users feel confident in choosing our theme. Thank you!', 
						'merchant'
					),
					'<strong>' . esc_html( $current_user->display_name ) . '</strong>'
				);
				?>
			</p>

			<a href="<?php echo esc_url( MERCHANT_REVIEW_URL ); ?>" class="merchant-btn merchant-btn-secondary" target="_blank"><?php esc_html_e( 'Ok, you deserve it', 'merchant' ); ?></a>
			<a href="?delay_<?php echo esc_attr( $this->id ); ?>_partially=0" class="merchant-btn merchant-btn-link"><?php esc_html_e( 'Nope, maybe later', 'merchant' ); ?></a>
			<a href="?nag_<?php echo esc_attr( $this->id ); ?>=0" class="merchant-btn merchant-btn-link"><?php esc_html_e( 'I already rated it', 'merchant' ); ?></a>

			<a class="notice-dismiss" href="?nag_<?php echo esc_attr( $this->id ); ?>=0" style="text-decoration:none;"></a>
		</div>

		<?php
	}
}

new Merchant_Notice_Review();
