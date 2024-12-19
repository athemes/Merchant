<?php
/**
 * Merchant Pro campaign notice.
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Merchant_Notice_Campaign extends Merchant_Notice {
	
	/**
	 * Constructor.
	 * 
	 */
	public function __construct() {
		$this->id        = 'merchant-campaign-notice-christmas-2024';
        $this->only_free = false;
        $this->end_date_target = '2025-01-01';
        $this->display_conditions = array( 'toplevel_page_merchant' );

		parent::__construct();

        add_action( 'admin_enqueue_scripts', array( $this, 'add_inline_style' ) );
	}

    /**
     * Add inline style.
     * 
     * @return void
     */
    public function add_inline_style() {
        $dismissed = $this->is_notice_dismissed();

        if ( $dismissed ) {
            return;
        }

        $css = "
            .toplevel_page_merchant #wpbody-content>.updated.merchant-campaign-notice, 
            .toplevel_page_merchant #wpbody-content>.notice.merchant-campaign-notice {
                display: block !important;
                margin: -1px -1px 0px -20px;
            }

            .merchant-campaign-notice {
                position: relative !important;
                background: url(". esc_url( MERCHANT_URI . 'assets/images/admin/christmas-background.jpg' ) .");
                background-size: cover;
                background-position: center;
                padding: 30px 30px 0px !important;
                border-left: 0;
            }

            @media(min-width: 1270px) {
                .merchant-campaign-notice {
                    padding: 45px 61px 40px !important;
                }
            }

            .merchant-campaign-notice h3 {
                color: #FFF;
                font-size: 42px;
                font-weight: 700;
                line-height: 1.1;
                margin-bottom: 40px;
            }

            @media(min-width: 576px) {
                .merchant-campaign-notice h3 {
                    min-width: 455px;
                    max-width: 25%;
                    line-height: 0.8;
                }   
            }

            .merchant-campaign-notice h3 span {
                position: relative;
                top: 12px;
                display: inline-flex;
                align-items: center;
                gap: 10px;
                color: #FFDB12;
            }

            .merchant-campaign-notice-thumbnail {
                max-width: 100%;
                height: auto;
                margin-top: 30px;
            }

            @media(min-width: 1270px) {
                .merchant-campaign-notice-thumbnail {
                    position: absolute;
                    right: 40px;
                    bottom: 0;
                    max-width: 553px;
                    margin-top: 0;
                }
            }

            @media(min-width: 1300px) {
                .merchant-campaign-notice-thumbnail {
                    max-width: 663px;
                }
            }

            .merchant-campaign-notice-percent {
                position: relative;
                max-width: 118px;
                top: -2px;
            }

            .merchant-campaign-notice .merchant-btn {
                font-size: 19px;
                padding: 19px 41px;
                border-radius: 7px;
            }

            .merchant-campaign-notice .notice-dismiss,
            .merchant-campaign-notice .notice-dismiss:before {
                color: #FFF;
            }

            .merchant-campaign-notice .notice-dismiss:active:before, 
            .merchant-campaign-notice .notice-dismiss:focus:before, 
            .merchant-campaign-notice .notice-dismiss:hover:before {
                color: #757575;
            }
        ";

        wp_add_inline_style( 'merchant-notices', $css );
    }

    /**
     * Show HTML markup if conditions meet.
     * 
     * @return void
     */
    public function notice_markup() {
		?>

        <div class="merchant-notice notice merchant-campaign-notice" style="position:relative;">
			<h3><?php echo wp_kses_post( sprintf(
                /* Translators: 1. Image url. */
                __( 'Merchant Christmas Sale: Up to <span><img src="%1$s" class="merchant-campaign-notice-percent" alt="Up to 30 Percent Off!" /> Off!</span>', 'merchant' ),
                MERCHANT_URI . 'assets/images/admin/30-percent.png'
            ) ); ?></h3>

            <a href="https://athemes.com/pricing/?utm_source=plugin_notice&utm_medium=button&utm_campaign=Merchant#merchant-pro" class="merchant-btn merchant-btn-primary" target="_blank"><?php esc_html_e( 'Give Me This Deal', 'merchant' ); ?></a>

            <img src="<?php echo esc_url( MERCHANT_URI . 'assets/images/admin/people-christmas.png' ); ?>" alt="<?php echo esc_attr__( 'Ready to join 130,000+ WordPress creators who\'ve found their perfect match?', 'merchant' ); ?>" class="merchant-campaign-notice-thumbnail" />

			<a class="notice-dismiss" href="?page=merchant&<?php echo esc_attr( $this->id ); ?>_dismiss=1" style="text-decoration:none;"></a>             
		</div>

        <?php
    }
}

new Merchant_Notice_Campaign();
