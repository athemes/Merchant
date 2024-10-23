<?php

/**
 * Real Time Search.
 *
 * @package Merchat_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Real Time Search class.
 *
 */
class Merchant_Real_Time_Search extends Merchant_Add_Module {

	/**
	 * Module ID.
	 * 
	 */
	const MODULE_ID = 'real-time-search';

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
	 */
	public function __construct() {

		// Module id.
		$this->module_id = self::MODULE_ID;

		// WooCommerce only.
		$this->wc_only = true;

		// Parent construct.
		parent::__construct();

		// Module section.
		$this->module_section = 'improve-experience';

		// Module default settings.
		$this->module_default_settings = array(
			'results_amounth_per_search' => 5,
			'results_description' => 'product-short-description',
			'results_description_length' => 10,
			'results_order_by' => 'title',
			'results_order' => 'asc',
			'results_box_width' => 500,
			'display_categories' => false,
			'enable_search_by_sku' => false,
		);

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

			// Enqueue admin scripts.
			// add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

			// Localize script.
			//add_filter( 'merchant_admin_localize_script', array( $this, 'localize_script' ) );

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

		// Enqueue styles.
		add_action( 'merchant_enqueue_before_main_css_js', array( $this, 'enqueue_css' ) );

		// Enqueue scripts.
		add_action( 'merchant_enqueue_after_main_css_js', array( $this, 'enqueue_scripts' ) );

		// Localize script.
		add_filter( 'merchant_localize_script', array( $this, 'localize_script' ) );
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
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/real-time-search.min.css', array(), MERCHANT_VERSION );
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
		wp_enqueue_script( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/real-time-search.min.js', array(), MERCHANT_VERSION, true );
		wp_enqueue_script( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/admin/preview.min.js', array(), MERCHANT_VERSION, true );
	}

	/**
	 * Enqueue CSS.
	 * 
	 * @return void
	 */
	public function enqueue_css() {

		// Specific module styles.
		wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/real-time-search.min.css', array(), MERCHANT_VERSION );
	}

	/**
	 * Enqueue scripts.
	 * 
	 * @return void
	 */
	public function enqueue_scripts() {

		// Register and enqueue the main module script.
		wp_enqueue_script( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/real-time-search.min.js', array(), MERCHANT_VERSION, true );
	}

	/**
	 * Localize script with module settings.
	 *
	 * @param array $setting The merchant global object setting parameter.
	 * @return array $setting The merchant global object setting parameter.
	 */
	public function localize_script( $setting ) {
		$module_settings = $this->get_module_settings();

		$setting['real_time_search'] = array(
			'ajax_search'                              => true,
            'ajax_search_results_amount_per_search'    => $module_settings['results_amounth_per_search'] ?? 15,
            'ajax_search_results_order_by'             => $module_settings['results_order_by'] ?? 'title',
            'ajax_search_results_order'                => $module_settings['results_order'] ?? 'asc',
            'ajax_search_results_display_categories'   => $module_settings['display_categories'] ?? 0,
            'ajax_search_results_enable_search_by_sku' => $module_settings['enable_search_by_sku'] ?? 0,
        );

		return $setting;
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

			$preview->set_css( 'results_box_width', '.merchant-ajax-search-wrapper', '--merchant-results-box-width', 'px' );
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
		<div class="woocommerce-product-search merchant-product-search merchant-ajax-search">
            <input type="search" class="search-field wc-search-field" placeholder="<?php echo esc_attr__( 'Search products...', 'merchant' ); ?>" value="" name="s" autocomplete="off">
            <button type="submit" class="search-submit" value="<?php echo esc_attr__( 'Search', 'merchant' ); ?>" title="<?php echo esc_attr__( 'Search for the product', 'merchant' ); ?>">
                <i class="ws-svg-icon">
                    <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10.875 3.75a7.125 7.125 0 100 14.25 7.125 7.125 0 000-14.25zM2.25 10.875a8.625 8.625 0 1117.25 0 8.625 8.625 0 01-17.25 0z"></path>
                        <path fill-rule="evenodd" d="M15.913 15.914a.75.75 0 011.06 0l4.557 4.556a.75.75 0 01-1.06 1.06l-4.557-4.556a.75.75 0 010-1.06z"></path>
                    </svg>
                </i>
            </button>
			<div>
				<div class="merchant-ajax-search-wrapper">
					<div class="merchant-ajax-search-heading-title"><?php echo esc_html__( 'Products', 'merchant' ) ?></div>
					<div class="merchant-ajax-search-divider"></div>
					<div class="merchant-ajax-search-products">
						<a class="merchant-ajax-search-item merchant-ajax-search-item-product" href="#">
							<div class="merchant-ajax-search-item-image"></div>
							<div class="merchant-ajax-search-item-info">
								<div class="merchant-ajax-search-item-title"><?php echo esc_html__( 'Ultra Facial Moisturizing Cream', 'merchant' ) ?></div>
								<p><?php echo esc_html__( 'Cras mattis consectetur purus sit amet fermentum. Lorem ipsum dolor…', 'merchant' ) ?></p></div>
							<div class="merchant-ajax-search-item-price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>21.00</span></div>
						</a>
						<a class="merchant-ajax-search-item merchant-ajax-search-item-product" href="#">
							<div class="merchant-ajax-search-item-image"></div>
							<div class="merchant-ajax-search-item-info">
								<div class="merchant-ajax-search-item-title"><?php echo esc_html__( 'Rare Earth Deep Pore Cleansing', 'merchant' ) ?></div>
								<p><?php echo esc_html__( 'Cras mattis consectetur purus sit amet fermentum. Lorem ipsum dolor…', 'merchant' ) ?></p></div>
							<div class="merchant-ajax-search-item-price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>14.00</span></div>
						</a></div>
				</div>
			</div>
		</div>
		<?php
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

		ob_start();
        ?>
        <form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="woocommerce-product-search merchant-product-search">
            <input type="search" class="search-field wc-search-field" placeholder="<?php echo esc_attr__( 'Search products...', 'merchant' ); ?>" value="" name="s" autocomplete="off">
            <button type="submit" class="search-submit" value="<?php echo esc_attr__( 'Search', 'merchant' ); ?>" title="<?php echo esc_attr__( 'Search for the product', 'merchant' ); ?>">
                <i class="ws-svg-icon">
                    <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10.875 3.75a7.125 7.125 0 100 14.25 7.125 7.125 0 000-14.25zM2.25 10.875a8.625 8.625 0 1117.25 0 8.625 8.625 0 01-17.25 0z"></path>
                        <path fill-rule="evenodd" d="M15.913 15.914a.75.75 0 011.06 0l4.557 4.556a.75.75 0 01-1.06 1.06l-4.557-4.556a.75.75 0 010-1.06z"></path>
                    </svg>
                </i>
            </button>
            <input type="hidden" name="post_type" value="product">
        </form>
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
	 * Ajax search callback.
	 * 
	 * @return void
	 */
	public static function ajax_search_callback() {
		check_ajax_referer( 'merchant-nonce', 'nonce' );
	
		$search_term          = isset( $_POST['search_term'] ) ? sanitize_text_field( wp_unslash( $_POST['search_term'] ) ) : '';
		$posts_per_page       = isset( $_POST['posts_per_page'] ) ? absint( $_POST['posts_per_page'] ) : 15;
		$order                = isset( $_POST['order'] ) ? sanitize_text_field( wp_unslash( $_POST['order'] ) ) : 'asc';
		$orderby              = isset( $_POST['orderby'] ) ? sanitize_text_field( wp_unslash( $_POST['orderby'] ) ) : 'title';
		$enable_search_by_sku = isset( $_POST['enable_search_by_sku'] ) && sanitize_text_field( wp_unslash( $_POST['enable_search_by_sku'] ) ) ? true : false;

		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => $posts_per_page,
			's'              => $search_term,
			'order'          => $order,
			'orderby'        => $orderby,
			'post_status'    => array( 'publish' ),
		);
	
		if ( 'price' === $orderby ) {
			// phpcs:disable
			$args['meta_key'] = '_price';
			// phpcs:enable
			$args['orderby']  = 'meta_value_num';
		}
	
		$output = '';
		$qry    = new WP_Query( $args );
	
		// Enable search by SKU
		if ( $enable_search_by_sku ) {
			$args = array(
				'post_type'      => 'product',
				'posts_per_page' => $posts_per_page,
				'order'          => $order,
				'orderby'        => $orderby,
				'post_status'    => array( 'publish' ),
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				'meta_query'     => array(
					'relation' => 'OR',
					array(
						'key'     => '_sku',
						'value'   => $search_term,
						'compare' => 'LIKE',
					),
				),
			);

			if ( 'price' === $orderby ) {
				// phpcs:disable
				$args['meta_key'] = '_price';
				// phpcs:enable
				$args['orderby']  = 'meta_value_num';
			}
	
			$qry_sku         = new WP_Query( $args );
			$qry->posts      = array_merge( $qry->posts, $qry_sku->posts );
			$qry->post_count = count( $qry->posts );
		}
	
		if ( $qry->have_posts() ) :
			$output .= '<div class="merchant-ajax-search-heading-title">' . esc_html__( 'Products', 'merchant' ) . '</div>';
			$output .= '<div class="merchant-ajax-search-divider"></div>';
			$output .= '<div class="merchant-ajax-search-products">';
	
			while ( $qry->have_posts() ) :
				$qry->the_post();
	
				$post = get_post();
	
				$args = array(
					'post_id' => $post->ID,
					'type'    => 'product',
				);
	
				$output .= self::get_ajax_search_item( $args );
	
			endwhile;
	
			$output .= '</div>';
		endif;
	
		$display_categories = isset( $_POST['display_categories'] ) ? absint( $_POST['display_categories'] ) : 0;
		if ( $display_categories ) {
			$args = array(
				'taxonomy'  => 'product_cat',
				'name-like' => $search_term,
			);
			$cats = get_terms( $args );
	
			if ( count( $cats ) > 0 && $search_term ) {
				$output .= '<div class="merchant-ajax-search-heading-title">' . esc_html__( 'Categories', 'merchant' ) . '</div>';
				$output .= '<div class="merchant-ajax-search-divider"></div>';
				$output .= '<div class="merchant-ajax-search-categories">';
	
				foreach ( $cats as $category ) {
					$args   = array(
						'term_id' => $category->term_id,
						'type'    => 'category',
					);
					$output .= self::get_ajax_search_item( $args );
				}
	
				$output .= '</div>';
			}
		}
	
		if ( $output ) {
			wp_send_json( array(
				'status' => 'success',
				'output' => wp_kses_post( $output ),
			) );
		} else {
			$output = '<p class="merchant-ajax-search-no-results">' . esc_html__( 'No products found.', 'merchant' ) . '</p>';
	
			wp_send_json( array(
				'status' => 'success',
				'type'   => 'no-results',
				'output' => wp_kses_post( $output ),
			) );
		}
	}

	/**
	 * Get ajax search item.
	 * 
	 * @param array $args
	 * @return string
	 */
	public static function get_ajax_search_item( $args ) {
		if ( 'product' === $args['type'] ) {
			$desc_type   = Merchant_Admin_Options::get( 'real-time-search', 'results_description', 'product-post-content' );
			$desc_length = Merchant_Admin_Options::get( 'real-time-search', 'results_description_length', 10 );
	
			$item_post_id   = $args['post_id'];
			$product        = wc_get_product( $item_post_id );
			$item_permalink = get_the_permalink( $item_post_id );
			$item_image     = wp_get_attachment_image( $product->get_image_id() );
			$item_title     = get_the_title( $item_post_id );
			$description    = wp_trim_words( 'product-post-content' === $desc_type ? $product->get_description() : $product->get_short_description(), $desc_length );
			$price          = $product->get_price_html();
		} else {
			$item_term_id   = $args['term_id'];
			$item_term      = get_term( $item_term_id );
			$item_permalink = get_term_link( $item_term_id );
			$item_image     = false;
			$item_title     = $item_term->name;
			$description    = false;
			$price          = false;
		}
	
		ob_start();
		?>
	
		<a class="merchant-ajax-search-item merchant-ajax-search-item-<?php echo esc_attr( $args['type'] ); ?>" href="<?php echo esc_url( $item_permalink ); ?>">
			<?php if ( $item_image ) : ?>
				<div class="merchant-ajax-search-item-image">
					<?php echo wp_kses_post( $item_image ); ?>
				</div>
			<?php endif; ?>
			<div class="merchant-ajax-search-item-info">
				<div class="merchant-ajax-search-item-title"><?php echo esc_html( $item_title ); ?></div>
				<?php if ( $description ) : ?>
					<p><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $price ) : ?>
				<div class="merchant-ajax-search-item-price">
					<?php echo wp_kses_post( $price ); ?>
				</div>
			<?php endif; ?>
		</a>
	
		<?php
	
		return ob_get_clean();
	}
}

// Initialize the module.
add_action( 'init', function() {
	new Merchant_Real_Time_Search();
} );

// Ajax handlers.
add_action( 'wp_ajax_ajax_search_callback', array( 'Merchant_Real_Time_Search', 'ajax_search_callback' ) );
add_action( 'wp_ajax_nopriv_ajax_search_callback', array( 'Merchant_Real_Time_Search', 'ajax_search_callback' ) );
