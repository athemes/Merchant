<?php
/**
 * Merchant Pro upsell notice.
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Merchant_Notice_Upsell extends Merchant_Notice {
	
	/**
	 * Constructor.
	 * 
	 */
	public function __construct() {
		$this->id        = 'merchant-upsell-notice';
        $this->only_free = true;

		parent::__construct();

		add_action( 'admin_init', array( $this, 'set_plugin_installed_time' ), 0 );
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
     * Show HTML markup if conditions meet.
     * 
     * @return void
     */
    public function notice_markup() { 
		if ( ( get_option( 'merchant_plugin_installed_time' ) > strtotime( '-3 day' ) ) ) {
			return;
		}

		?>
        <div class="merchant-notice merchant-notice-with-thumbnail notice" style="position:relative;">
			<h3><?php echo esc_html__( 'Earn More with Your Store and Merchant Pro! 💰', 'merchant' ); ?></h3>

			<p>
				<?php
					echo esc_html__( 'Merchant Pro packs in all the must-have eCommerce features you need to run your store successfully. With 40+ powerful modules and more to come, Merchant Pro will level up your store and save you the hassle of managing multiple plugins.', 'merchant' );
				?>
			</p>

			<a href="https://athemes.com/merchant-upgrade?utm_source=plugin_notice&utm_medium=button&utm_campaign=Merchant" class="merchant-btn merchant-btn-secondary" target="_blank"><?php esc_html_e( 'Upgrade To Merchant Pro', 'merchant' ); ?></a>
			
			<a class="notice-dismiss" href="?<?php echo esc_attr( $this->id ); ?>_dismiss=1" style="text-decoration:none;"></a>
		</div>

        <?php
    }
}

new Merchant_Notice_Upsell();
