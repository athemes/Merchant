<?php

/**
 * Advanced Reviews.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Merchant_Advanced_Reviews' ) ) {
	/**
	 * Advanced Reviews class.
	 *
	 */
	class Merchant_Advanced_Reviews extends Merchant_Add_Module {

		/**
		 * Module ID.
		 *
		 */
		const MODULE_ID = 'advanced-reviews';

		/**
		 * Module template path.
		 */
		const MODULE_TEMPLATES_PATH = 'modules/' . self::MODULE_ID;

		/**
		 * Is module preview.
		 *
		 */
		public static $is_module_preview = false;

		/**
		 * Dummy product.
		 *
		 */
		public $dummy_product;

		/**
		 * Set the module as having analytics.
		 *
		 * @var bool
		 */
		protected $has_analytics = true;

		/**
		 * Constructor.
		 *
		 */
		public function __construct( Merchant_Product_Dummy $dummy_product ) {
			// Module id.
			$this->module_id = self::MODULE_ID;

			// WooCommerce only.
			$this->wc_only = true;

			// Parent construct.
			parent::__construct();

			// Dummy product.
			$this->dummy_product = $dummy_product;

			// Module section.
			$this->module_section = 'build-trust';

			// Module default settings.
			$this->module_default_settings = array(
				'title'                           => esc_html__( 'What our customers are saying', 'merchant' ),
				'title_tag'                       => 'h2',
				'hide_title'                      => 0,
				'description'                     => '',
				'title_desc_align'                => 'left',
				'default_sorting'                 => 'newest',
				'pagination_type'                 => 'load-more',
				'photos_limit'                    => 6,
				'review_options'                  => 'image_and_text',
				'review_images_carousel'          => 0,
				'carousel_title'                  => esc_html__( 'Review images', 'merchant' ),
				'hook_order'                      => 10,
				'review_images_carousel_total'    => 12,
				'review_images_carousel_per_page' => 3,
			);

			// Module data.
			$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];

			// Module options path.
			$this->module_options_path = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID . '/admin/options.php';

			if ( Merchant_Modules::is_module_active( self::MODULE_ID ) && is_admin() ) {
				// Init translations.
				$this->init_translations();
			}

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

				add_action( 'merchant_admin_before_include_modules_options', array( $this, 'help_banner' ) );

                // This hook is deactivated until we decide a better place for the analytics in module pages
				//add_action( 'merchant_admin_after_module_page_page_header', array( $this, 'module_analytics' ), 1 );
			}
		}

		/**
         * Render module analytics section.
         * This method is deactivated until we decide a better place for the analytics in module pages
         *
		 * @return void
		 */
		public function module_analytics() {
			if ( ! $this->has_analytics() ) {
				return;
			}
			$reports           = new Merchant_Advanced_Reviews_Analytics();
			$date_ranges       = $reports->get_last_and_previous_7_days_ranges();
			$collected_reviews = $reports->get_collected_reviews_card_report( $date_ranges['last_period'], $date_ranges['recent_period'] );
			$sent_emails       = $reports->get_sent_emails_card_report( $date_ranges['last_period'], $date_ranges['recent_period'] );
			$scheduled_emails  = $reports->get_scheduled_emails_card_report( $date_ranges['last_period'], $date_ranges['recent_period'] );
			$open_rate         = $reports->get_opened_emails_rate_report( $date_ranges['last_period'], $date_ranges['recent_period'] );
			$overview_data     = array(
				'section_title' => __( 'Module Overview', 'merchant' ), // Raw string
				'date_ranges'   => $date_ranges,
				'action'        => 'merchant_get_adv_reviews_analytics_cards_data',
				'cards'         => array(
					'reviews-collected' => array(
						'title'   => __( 'Reviews collected', 'merchant' ), // Raw string
						'value'   => $collected_reviews['second_period'], // Raw value
						'change'  => array(
							'value' => wc_format_decimal( $collected_reviews['change'][0], 2 ) . '%', // Raw value
							'class' => $collected_reviews['change'][1], // Raw value
						),
						'tooltip' => __( 'Revenue added by Merchant.', 'merchant' ), // Raw string
					),
					'scheduled-emails'  => array(
						'title'   => __( 'Scheduled emails', 'merchant' ), // Raw string
						'value'   => $scheduled_emails['second_period'], // Raw value
						'change'  => array(
							'value' => wc_format_decimal( $scheduled_emails['change'][0], 2 ) . '%', // Raw value
							'class' => $scheduled_emails['change'][1], // Raw value
						),
						'tooltip' => __( 'Average order value for Merchant orders.', 'merchant' ), // Raw string
					),
					'sent-emails'       => array(
						'title'   => __( 'Sent emails', 'merchant' ), // Raw string
						'value'   => $sent_emails['second_period'], // Raw value
						'change'  => array(
							'value' => wc_format_decimal( $sent_emails['change'][0], 2 ) . '%', // Raw value
							'class' => $sent_emails['change'][1], // Raw value
						),
						'tooltip' => __( 'Total number of orders involving Merchant.', 'merchant' ), // Raw string
					),
					'open-rate'         => array(
						'title'   => __( 'Open rate', 'merchant' ), // Raw string
						'value'   => wc_format_decimal( $open_rate['second_period'], 2 ) . '%', // Raw value
						'change'  => array(
							'value' => wc_format_decimal( $open_rate['change'][0], 2 ) . '%', // Raw value
							'class' => $open_rate['change'][1], // Raw value
						),
						'tooltip' => __( 'The percentage of Merchant offer viewers who made a purchase.', 'merchant' ), // Raw string
					),
				),
			);

			require_once MERCHANT_DIR . 'admin/components/analytics-overview.php';
		}

		/**
		 * Init translations.
		 *
		 * @return void
		 */
		public function init_translations() {
			$settings = $this->get_module_settings();
			if ( ! empty( $settings['title'] ) ) {
				Merchant_Translator::register_string( $settings['title'], esc_html__( 'Advanced reviews title', 'merchant' ) );
			}

			if ( ! empty( $settings['description'] ) ) {
				Merchant_Translator::register_string( $settings['description'], esc_html__( 'Advanced reviews description', 'merchant' ) );
			}

			if ( ! empty( $settings['review_request_reminder_email']['subject'] ) ) {
				Merchant_Translator::register_string( $settings['review_request_reminder_email']['subject'], esc_html__( 'Advanced reviews review request reminder email subject',
					'merchant' ) );
			}

			if ( ! empty( $settings['review_request_reminder_email']['body'] ) ) {
				Merchant_Translator::register_string( $settings['review_request_reminder_email']['body'], esc_html__( 'Advanced reviews review request reminder email body', 'merchant' ) );
			}

			if ( ! empty( $settings['review_request_email']['subject'] ) ) {
				Merchant_Translator::register_string( $settings['review_request_email']['subject'], esc_html__( 'Advanced reviews review request email subject', 'merchant' ) );
			}

			if ( ! empty( $settings['review_request_email']['body'] ) ) {
				Merchant_Translator::register_string( $settings['review_request_email']['body'], esc_html__( 'Advanced reviews review request email body', 'merchant' ) );
			}

			if ( ! empty( $settings['discount_for_review_email']['subject'] ) ) {
				Merchant_Translator::register_string( $settings['discount_for_review_email']['subject'], esc_html__( 'Advanced reviews discount email subject', 'merchant' ) );
			}

			if ( ! empty( $settings['discount_for_review_email']['body'] ) ) {
				Merchant_Translator::register_string( $settings['discount_for_review_email']['body'], esc_html__( 'Advanced reviews discount email body', 'merchant' ) );
			}

			if ( ! empty( $settings['discount_for_review_reminder_email']['subject'] ) ) {
				Merchant_Translator::register_string( $settings['discount_for_review_reminder_email']['subject'], esc_html__( 'Advanced reviews discount reminder email subject',
					'merchant' ) );
			}

			if ( ! empty( $settings['discount_for_review_reminder_email']['body'] ) ) {
				Merchant_Translator::register_string( $settings['discount_for_review_reminder_email']['body'], esc_html__( 'Advanced reviews discount reminder email body', 'merchant' ) );
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
				wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/advanced-reviews.min.css', array(), MERCHANT_VERSION );
				wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );

				wp_enqueue_style( 'merchant-grid', MERCHANT_URI . 'assets/css/grid.min.css', array(), MERCHANT_VERSION, 'all' );
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
				// HTML.
				$preview->set_html( $this->admin_preview_content() );

				// TItle.
				$preview->set_text( 'title', '.merchant-adv-reviews-title' );

				// Hide Title.
				$preview->set_class( 'hide_title', '.merchant-adv-reviews', array(), 'hide-title' );

				// Description.
				$preview->set_text( 'description', '.merchant-adv-reviews-desc' );
			}

			return $preview;
		}

		/**
		 * Get settings data with product object.
		 *
		 * @return array $product_data The product data. It will be dummy content if it's on admin preview.
		 */
		public function get_settings_with_product_object() {
			global $product;

			$settings = array_merge( $this->get_module_settings(), array( 'product' => $product ) );

			if ( self::$is_module_preview ) {
				$product = $this->dummy_product;

				$settings = array_merge(
					$settings,
					array(
						'product' => $product,
					)
				);

				// Don't allow 'h2' value for tag_title because it conflicts with WordPress notifications.
				if ( 'h2' === $settings['title_tag'] ) {
					$settings['title_tag'] = 'h3';
				}
			}

			return $settings;
		}

		/**
		 * Admin preview content.
		 *
		 * @return string
		 */
		public function admin_preview_content() {
			// Module settings
			$settings = $this->get_settings_with_product_object();

			// Template arguments
			$args = array_merge(
				$settings,
				array(
					'bars_data'       => array(
						'ratings' => array(
							'5-star' => array(
								'label'   => esc_html__( '5 Stars', 'merchant' ),
								'value'   => 5,
								'percent' => 50,
							),
							'4-star' => array(
								'label'   => esc_html__( '4 Stars', 'merchant' ),
								'value'   => 4,
								'percent' => 40,
							),
							'3-star' => array(
								'label'   => esc_html__( '3 Stars', 'merchant' ),
								'value'   => 3,
								'percent' => 30,
							),
							'2-star' => array(
								'label'   => esc_html__( '2 Stars', 'merchant' ),
								'value'   => 2,
								'percent' => 20,
							),
							'1-star' => array(
								'label'   => esc_html__( '1 Star', 'merchant' ),
								'value'   => 1,
								'percent' => 10,
							),
						),
						'total'   => 15,
					),
					'ratings_enabled' => true,
					'comments_open'   => true,
					'comments'        => array(
						(object) array(
							'comment_approved'     => '1',
							'comment_ID'           => 1,
							'comment_post_ID'      => 1,
							'comment_author'       => 'Kendall Grey',
							'comment_author_email' => 'johndoe@athemes.com',
							'comment_author_url'   => 'https://athemes.com',
							'comment_date'         => gmdate( 'Y-m-d H:i:s', strtotime( '-1 day' ) ),
						),
					),
					'comment_rating'  => 3,
					'comment_text'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam euismod, elit quis sagittis commodo, nisl elit ultricies diam, at',
				)
			);

			return merchant_get_template_part( 'modules/' . self::MODULE_ID, 'reviews', $args, true );
		}

		/**
		 * Custom CSS.
		 *
		 * @return string $css The module custom CSS.
		 */
		public function get_module_custom_css() {
			$css = '';

			// Title and Description Alignment.
			$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'title_desc_align', 'left', '.merchant-adv-reviews', '--mrc-adv-reviews-title-desc-align' );

			// Title Color.
			$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'title_color', '#212121', '.merchant-adv-reviews', '--mrc-adv-reviews-title-color' );

			// Description Color.
			$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'description_color', '#777', '.merchant-adv-reviews', '--mrc-adv-reviews-desc-color' );

			// Stars Color.
			$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'stars_color', '#FFA441', '.merchant-adv-reviews, .merchant-adv-reviews-modal', '--mrc-adv-reviews-stars-color' );

			// Stars Background Color.
			$css .= Merchant_Custom_CSS::get_variable_css(
				self::MODULE_ID,
				'stars_background_color',
				'#757575',
				'.merchant-adv-reviews, .merchant-adv-reviews-modal',
				'--mrc-adv-reviews-stars-bg-color'
			);

			// Progress Bar Color.
			$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'progress_bar_color', '#212121', '.merchant-adv-reviews', '--mrc-adv-reviews-bar-color' );

			// Progress Bar Background Color.
			$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'progress_bar_bg_color', '#F5F5F5', '.merchant-adv-reviews', '--mrc-adv-reviews-bar-bg-color' );

			// Dividers Color.
			$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'dividers_color', '#757575', '.merchant-adv-reviews', '--mrc-adv-reviews-dividers-color' );

			// Button Color.
			$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'button_color', '#FFF', '.merchant-adv-reviews, .merchant-adv-reviews-modal', '--mrc-adv-reviews-button-color' );

			// Button Color (hover).
			$css .= Merchant_Custom_CSS::get_variable_css(
				self::MODULE_ID,
				'button_color_hover',
				'#FFF',
				'.merchant-adv-reviews, .merchant-adv-reviews-modal',
				'--mrc-adv-reviews-button-color-hover'
			);

			// Button Background Color.
			$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'button_bg_color', '#212121', '.merchant-adv-reviews, .merchant-adv-reviews-modal', '--mrc-adv-reviews-button-bg-color' );

			// Button Background Color (hover).
			$css .= Merchant_Custom_CSS::get_variable_css(
				self::MODULE_ID,
				'button_bg_color_hover',
				'#757575',
				'.merchant-adv-reviews, .merchant-adv-reviews-modal',
				'--mrc-adv-reviews-button-bg-color-hover'
			);

			// Modal Close Icon Color.
			$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'modal_close_icon_color', '#212121', '.merchant-adv-reviews-modal', '--mrc-adv-reviews-modal-close-icon-color' );

			// Modal Close Icon Color (hover).
			$css .= Merchant_Custom_CSS::get_variable_css(
				self::MODULE_ID,
				'modal_close_icon_color_hover',
				'#757575',
				'.merchant-adv-reviews-modal',
				'--mrc-adv-reviews-modal-close-icon-color-hover'
			);

			// Modal Title Color.
			$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'modal_title_color', '#212121', '.merchant-adv-reviews-modal', '--mrc-adv-reviews-modal-title-color' );

			// Modal Description Color.
			$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'modal_description_color', '#777', '.merchant-adv-reviews-modal', '--mrc-adv-reviews-modal-desc-color' );

			// Modal Textarea Color.
			$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'modal_textarea_color', '#777', '.merchant-adv-reviews-modal', '--mrc-adv-reviews-modal-textarea-color' );

			// Modal Textarea Background Color.
			$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'modal_textarea_background_color', '#FFF', '.merchant-adv-reviews-modal', '--mrc-adv-reviews-modal-textarea-bg-color' );

			// Modal Background Color.
			$css .= Merchant_Custom_CSS::get_variable_css( self::MODULE_ID, 'modal_background_color', '#F5F5F5', '.merchant-adv-reviews-modal', '--mrc-adv-reviews-modal-bg-color' );

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

		/**
		 * Help banner.
		 *
		 * @return void
		 */
		public function help_banner( $module_id ) {
			if ( $module_id === 'advanced-reviews' ) {
				?>
				<div class="merchant-module-page-setting-fields">
					<div class="merchant-module-page-setting-field merchant-module-page-setting-field-content">
						<div class="merchant-module-page-setting-field-inner">
							<div class="merchant-tag-pre-orders">
								<i class="dashicons dashicons-info"></i>
								<p>
									<?php
									echo esc_html__(
										'Check all your reviews from Products > Reviews menu in the left sidebar of your WordPress admin area or clicking the \'View Reviews\' option below.',
										'merchant'
									);
									printf(
										'<a href="%1s" target="_blank">%2s</a>',
										esc_url( admin_url( 'edit.php?post_type=product&page=product-reviews' ) ),
										esc_html__( 'View Reviews', 'merchant' )
									);
									?></p>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
		}
	}

	// Dummy content.
	require MERCHANT_DIR . 'inc/modules/advanced-reviews/class-product-dummy-data.php';
	require MERCHANT_DIR . 'inc/modules/advanced-reviews/class-advanced-reviews-analytics.php';

	// Reviews List Table
	// require_once MERCHANT_DIR . 'inc/modules/advanced-reviews/admin/class-reviews-table.php';

	// Initialize the module.
	add_action(
		'init',
		function () {
			Merchant_Modules::create_module( new Merchant_Advanced_Reviews( new Merchant_Product_Dummy() ) );
		}
	);
}
