<?php

/**
 * Quick Social Links
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Quick Social Links Class.
 *
 */
class Merchant_Quick_Social_Links extends Merchant_Add_Module {

	/**
	 * Module ID.
	 */
	const MODULE_ID = 'quick-social-links';

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

		// Module default settings.
		$this->module_default_settings = array(
				'layout'          => 'pos-bottom',
				'visibility'      => 'visibility-all',
				'icon_color'      => '#212121',
				'bg_color'        => '#ffffff',
				'border_radius'   => 15,
				'condition_rules' => array(
						array(
								'layout'    => 'display',
								'condition' => 'all',
								'type'      => 'include',
						),
				),
				'links'           => array(
						array(
								'layout' => 'social',
								'icon'   => 'facebook',
								'url'    => esc_html__( 'https://www.facebook.com', 'merchant' ),
						),
						array(
								'layout' => 'social',
								'icon'   => 'instagram',
								'url'    => esc_html__( 'https://www.instagram.com', 'merchant' ),
						),
						array(
								'layout' => 'social',
								'icon'   => 'twitter',
								'url'    => esc_html__( 'https://www.twitter.com', 'merchant' ),
						),
				),
		);

		// Module data.
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];

		// Module section.
		$this->module_section = $this->module_data['section'];

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
			add_filter( 'merchant_custom_css', array( $this, 'admin_custom_css' ) );
		}
	}


	/**
	 * Admin enqueue CSS.
	 *
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( $this->is_module_settings_page() ) {
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/quick-social-links.min.css', array(), MERCHANT_VERSION );
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
			// HTML.
			$preview->set_html( array( $this, 'admin_preview_content' ), $this->get_module_settings() );

			// Layout position
			$preview->set_class( 'layout', '.merchant-quick-social-links', array(
					'pos-bottom',
					'pos-left',
					'pos-right',
			) );

			// Layout visibility
			$preview->set_class( 'layout', '.merchant-quick-social-links', array(
					'visibility-all',
					'pos-left',
					'pos-right',
			) );

			// Links
			$preview->set_flexible_content( 'links', '.merchant-quick-social-links-inner', array(
							'social' => array( '{url}' => 'url', '{icon}' => 'icon' ),
							'custom' => array( '{url}' => 'url', '{icon}' => 'image' ),
					)
			);

			// Border radius
			$preview->set_css( 'border_radius', '.merchant-quick-social-links-inner', '--merchant-border-radius', 'px' );
		}

		return $preview;
	}


	/**
	 * Admin preview content.
	 *
	 * @return void
	 */
	public function admin_preview_content( $settings ) {
		// Classes
		$classes = array(
				'merchant-quick-social-links',
				'merchant-quick-social-links__regular',
				$settings['layout'],
				$settings['visibility'],
		);
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
				<div class="mrc-preview-text-placeholder"></div>
				<div class="mrc-preview-text-placeholder mrc-mw-70"></div>
				<div class="mrc-preview-text-placeholder mrc-mw-30"></div>
				<div class="mrc-preview-text-placeholder"></div>
				<div class="mrc-preview-text-placeholder mrc-mw-70"></div>
				<div class="mrc-preview-text-placeholder mrc-mw-30"></div>
				<div class="mrc-preview-text-placeholder"></div>
				<div class="mrc-preview-text-placeholder mrc-mw-70"></div>
				<div class="mrc-preview-addtocart-placeholder"></div>
			</div>
		</div>

		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<div class="merchant-quick-social-links-container">
				<div class="merchant-quick-social-links-inner">
					<div class="merchant-quick-social-link flexible-content-template">
						<a href="{url}" target="_blank">
							<div class="ql-image">
								{icon}
							</div>
						</a>
					</div>
					<?php if ( isset( $settings['links'] ) && ! empty( $settings['links'] ) ) : ?>
						<?php foreach ( $settings['links'] as $link ): ?>
							<?php if ( ! empty( $link['url'] ) ) : ?>
								<?php if ( 'social' === $link['layout'] && ! empty( $link['icon'] ) ) : ?>
									<div class="merchant-quick-social-link">
										<a href="<?php echo esc_url( $link["url"] ); ?>" target="_blank">
											<div class="ql-image">
												<?php echo wp_kses( Merchant_SVG_Icons::get_svg_icon( "icon-{$link['icon'] }" ), merchant_kses_allowed_tags( array(), false ) ); ?>
											</div>
										</a>
									</div>
								<?php elseif ( 'custom' === $link['layout'] && ! empty( $link['image'] ) ) : ?>
									<div class="merchant-quick-social-link">
										<a href="<?php echo esc_url( $link["url"] ); ?>" target="_blank">
											<div class="ql-image">
												<img src="<?php echo esc_url( wp_get_attachment_url( $link["image"] ) ); ?>">
											</div>
										</a>
									</div>
								<?php endif ?>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif ?>
				</div>
			</div>
		</div>

		<?php
	}

	/**
	 * Get the socials.
	 *
	 * @return array
	 */
	public static function get_socials() {
		return array(
				'facebook'   => esc_html__( 'Facebook', 'merchant' ),
				'instagram'  => esc_html__( 'Instagram', 'merchant' ),
				'twitter'    => esc_html__( 'Twitter', 'merchant' ),
				'tiktok'     => esc_html__( 'TikTok', 'merchant' ),
				'pinterest'  => esc_html__( 'Pinterest', 'merchant' ),
				'whatsapp'   => esc_html__( 'WhatsApp', 'merchant' ),
				'telegram'   => esc_html__( 'Telegram', 'merchant' ),
				'flickr'     => esc_html__( 'Flickr', 'merchant' ),
				'linkedin'   => esc_html__( 'LinkedIn', 'merchant' ),
				'youtube'    => esc_html__( 'Youtube', 'merchant' ),
				'discord'    => esc_html__( 'Discord', 'merchant' ),
				'xing'       => esc_html__( 'Xing', 'merchant' ),
				'dribble'    => esc_html__( 'Dribble', 'merchant' ),
				'vk'         => esc_html__( 'VK', 'merchant' ),
				'weibo'      => esc_html__( 'Weibo', 'merchant' ),
				'vimeo'      => esc_html__( 'Vimeo', 'merchant' ),
				'mix'        => esc_html__( 'Mix', 'merchant' ),
				'behance'    => esc_html__( 'Behance', 'merchant' ),
				'spotify'    => esc_html__( 'Spotify', 'merchant' ),
				'soundcloud' => esc_html__( 'Sound Cloud', 'merchant' ),
				'twitch'     => esc_html__( 'Twitch', 'merchant' ),
				'etsy'       => esc_html__( 'Etsy', 'merchant' ),
				'bandcamp'   => esc_html__( 'Bandcamp', 'merchant' ),
				'reddit'     => esc_html__( 'Reddit', 'merchant' ),
				'github'     => esc_html__( 'Github', 'merchant' ),
		);
	}

	/**
	 * Custom CSS.
	 *
	 * @return string
	 */
	public static function get_module_custom_css() {
		// Icon color.
		$css = Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'icon_color', '#212121', '.merchant-quick-social-link', '--merchant-icon-color' );

		// Background color.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'bg_color', '#ffffff', '.merchant-quick-social-links-inner', '--merchant-bg-color' );

		// Border radius.
		$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'border_radius', 15, '.merchant-quick-social-links-inner', '--merchant-border-radius', 'px' );


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
		$css .= static::get_module_custom_css();

		return $css;
	}
}

// Initialize the module.
add_action( 'init', function () {
	Merchant_Modules::create_module( new Merchant_Quick_Social_Links() );
} );
