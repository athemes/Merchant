<?php

/**
 * Trust Badges.
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Trust Badges Class.
 * 
 */
class Merchant_Trust_Badges extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'trust-badges';

	/**
	 * Is module preview.
	 * 
	 */
	public static $is_module_preview = false;

	/**
	 * Whether the module has a shortcode or not.
	 *
	 * @var bool
	 */
	public $has_shortcode = true;

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
		$this->module_section = 'build-trust';

		// Module default settings.
		$this->module_default_settings = array(
			'badges' => '',
			'align' => 'center',
			'title' => __( 'Product Quality Guaranteed!', 'merchant' ),
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

			// Custom CSS.
			// The custom CSS should be added here as well due to ensure preview box works properly.
			add_filter( 'merchant_custom_css', array( $this, 'admin_custom_css' ) );

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

		// Add trust badges after add to cart form on single product pages.
		add_action( 'woocommerce_single_product_summary', array( $this, 'trust_badges_output' ), 30 );

		// Custom CSS.
		add_filter( 'merchant_custom_css', array( $this, 'frontend_custom_css' ) );
	}

	/**
	 * Print shortcode content.
	 *
	 * @return string
	 */
	public function shortcode_handler() {
		// Check if module is active.
		if ( ! Merchant_Modules::is_module_active( $this->module_id ) ) {
			return '';
		}

		// Check if shortcode is enabled.
		if ( ! $this->is_shortcode_enabled() ) {
			return '';
		}

		// Check if we are on a single product page.
		if ( ! is_singular( 'product' ) ) {
			// If user is admin, show error message.
			if ( current_user_can( 'manage_options' ) ) {
				return $this->shortcode_placement_error();
			}

			return '';
		}
		ob_start();

		$settings       = $this->get_module_settings();
		$is_placeholder = empty( $settings[ 'badges' ] ) ? true : false;
		$badges         = $this->get_badges( $settings[ 'badges' ] );
		?>
        <fieldset class="merchant-trust-badges">
			<?php if ( ! empty( $settings[ 'title' ] ) ) : ?>
                <legend class="merchant-trust-badges-title"><?php echo esc_html( Merchant_Translator::translate( $settings[ 'title' ] ) ); ?></legend>
			<?php endif; ?>
			<?php if ( ! $is_placeholder ) : ?>
                <div class="merchant-trust-badges-images">
					<?php foreach ( $badges as $image_id ) : ?>
						<?php $imagedata = wp_get_attachment_image_src( $image_id, 'full' ); ?>
						<?php if ( ! empty( $imagedata ) && ! empty( $imagedata[0] ) ) : ?>
							<?php printf( '<img src="%s" />', esc_url( $imagedata[0] ) ); ?>
						<?php endif; ?>
					<?php endforeach; ?>
                </div>
			<?php else : ?>
                <div class="merchant-trust-badges-images is-placeholder">
					<?php foreach ( $badges as $badge_src ) : ?>
                        <img src="<?php echo esc_url( $badge_src ); ?>" />
					<?php endforeach; ?>
                </div>
			<?php endif; ?>
        </fieldset>
		<?php
		$shortcode_content = ob_get_clean();

		/**
		 * Filter the shortcode html content.
		 *
		 * @param string $shortcode_content shortcode html content
		 * @param string $module_id         module id
		 * @param int    $post_id           product id
		 *
		 * @since 1.8
		 */
		return apply_filters( 'merchant_module_shortcode_content_html', $shortcode_content, $this->module_id, get_the_ID() );
	}

	/**
	 * Init translations.
	 *
	 * @return void
	 */
	public function init_translations() {
		$settings = $this->get_module_settings();
		if ( ! empty( $settings['title'] ) ) {
			Merchant_Translator::register_string( $settings['title'], esc_html__( 'Trust badges text above logos', 'merchant' ) );
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
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/trust-badges.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
		}
	}

	/**
	 * Enqueue CSS.
	 * 
	 * @return void
	 */
	public function enqueue_css() {
		if ( ! is_singular( 'product' ) && ! Merchant_Modules::is_module_active( 'quick-view' ) ) {
			return;
		}

		// Specific module styles.
		wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/trust-badges.min.css', array(), MERCHANT_VERSION );
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

			// Text Above the Logos.
			$preview->set_text( 'title', '.merchant-trust-badges-title' );

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
				<div class="mrc-preview-text-placeholder mrc-mw-30 mrc-hide-on-smaller-screens"></div>
				<div class="mrc-preview-addtocart-placeholder mrc-hide-on-smaller-screens"></div>
				<?php $this->trust_badges_output(); ?>
			</div>
		</div>

		<?php
	}

	/**
	 * Get trust badges.
	 * 
	 * @param string $badges
	 * @return array $badges Array of logos.
	 */
	public function get_badges( $badges ) {
		return ! empty( $badges ) 
			? explode( ',', $badges ) 
			: array(
				MERCHANT_URI . 'inc/modules/' . self::MODULE_ID . '/admin/images/badge1.svg',
				MERCHANT_URI . 'inc/modules/' . self::MODULE_ID . '/admin/images/badge2.svg',
				MERCHANT_URI . 'inc/modules/' . self::MODULE_ID . '/admin/images/badge3.svg',
			);
	}

	/**
	 * Get placeholder badges alternative descriptions.
	 * 
	 * @return array $logos_alt Array of badges alternative descriptions.
	 */
	public function get_placeholder_badges_alt_map() {
		return array(
			'badge1.svg' => __( 'Original', 'merchant' ),
			'badge2.svg' => __( '24/7 Support', 'merchant' ),
			'badge3.svg' => __( 'Satisfaction', 'merchant' ),
		);
	}

	/**
	 * Render trust badges.
	 * TODO: Render through template files.
	 * 
	 * @return void
	 */
	public function trust_badges_output() {
		if ( $this->is_shortcode_enabled() ) {
			return;
		}

		if ( is_archive() || is_page() ) {
			return;
		}
		
		$settings               = $this->get_module_settings();
		$is_placeholder         = empty( $settings[ 'badges' ] ) ? true : false;
		$badges                 = $this->get_badges( $settings[ 'badges' ] );
		$placeholder_badges_alt = $this->get_placeholder_badges_alt_map();

		?>

			<fieldset class="merchant-trust-badges">

				<?php if ( ! empty( $settings[ 'title' ] ) ) : ?>
					<legend class="merchant-trust-badges-title"><?php echo esc_html( Merchant_Translator::translate( $settings[ 'title' ] ) ); ?></legend>
				<?php endif; ?>

				<?php if ( ! $is_placeholder ) : ?>

					<div class="merchant-trust-badges-images">

						<?php foreach ( $badges as $image_id ) {
							echo wp_kses_post( wp_get_attachment_image( $image_id ) );
						} ?>

					</div>

					<?php else : ?>

						<div class="merchant-trust-badges-images is-placeholder">
							<?php foreach ( $badges as $badge_src ) : 
								$image_basename = basename( $badge_src );
								$image_alt      = isset( $placeholder_badges_alt[ $image_basename ] ) ? $placeholder_badges_alt[ $image_basename ] : '';
								?>
								<img src="<?php echo esc_url( $badge_src ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" />
							<?php endforeach; ?>
						</div>

					<?php endif; ?>

			</fieldset>
			
		<?php 
	}

	/**
	 * Custom CSS.
	 * 
	 * @return string
	 */
	public function get_module_custom_css() {
		$css = '';

		// Font Size.
		$css .= Merchant_Custom_CSS::get_variable_css( 'trust-badges', 'font-size', 15, '.merchant-trust-badges', '--mrc-tb-font-size', 'px' );

		// Text Color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'trust-badges', 'text-color', '#212121', '.merchant-trust-badges', '--mrc-tb-text-color' );

		// Border Color.
		$css .= Merchant_Custom_CSS::get_variable_css( 'trust-badges', 'border-color', '#e5e5e5', '.merchant-trust-badges', '--mrc-tb-border-color' );

		// Margin Top.
		$css .= Merchant_Custom_CSS::get_variable_css( 'trust-badges', 'margin-top', 20, '.merchant-trust-badges', '--mrc-tb-margin-top', 'px' );

		// Margin Bottom.
		$css .= Merchant_Custom_CSS::get_variable_css( 'trust-badges', 'margin-bottom', 20, '.merchant-trust-badges', '--mrc-tb-margin-bottom', 'px' );

		// Align.
		$css .= Merchant_Custom_CSS::get_variable_css( 'trust-badges', 'align', 'center', '.merchant-trust-badges', '--mrc-tb-align' );

		// Image Max Width.
		$css .= Merchant_Custom_CSS::get_variable_css( 'trust-badges', 'image-max-width', 70, '.merchant-trust-badges', '--mrc-tb-image-max-width', 'px' );

		// Image Max Height.
		$css .= Merchant_Custom_CSS::get_variable_css( 'trust-badges', 'image-max-height', 70, '.merchant-trust-badges', '--mrc-tb-image-max-height', 'px' );

		return $css;
	}

	/**
	 * Admin custom CSS.
	 * 
	 * @param string $css The custom CSS.
	 * @return string $css The custom CSS.
	 */
	public function admin_custom_css( $css ) {
		$css .= $this->get_module_custom_css(); 

		return $css;
	}

	/**
	 * Frontend custom CSS.
	 * 
	 * @param string $css The custom CSS.
	 * @return string $css The custom CSS.
	 */
	public function frontend_custom_css( $css ) {
		if ( ! is_singular( 'product' ) && ! Merchant_Modules::is_module_active( 'quick-view' ) ) {
			return $css;
		}
		
		$css .= $this->get_module_custom_css();

		return $css;
	}
}

// Initialize the module.
add_action( 'init', function() {
	new Merchant_Trust_Badges();
} );
