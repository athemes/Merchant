<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function merchant_content_ajax_search_item( $args ) {
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

function merchant_ajax_search_callback() {
	if ( ! Merchant_Modules::is_module_active( 'real-time-search' ) ) {
		return;
	}

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
		'post_status'    => array( 'publish' )
	);

	if ( 'price' === $orderby ) {
		$args['meta_key'] = '_price';
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
			'meta_query'     => array(
				'relation' => 'OR',
				array(
					'key'     => '_sku',
					'value'   => $search_term,
					'compare' => 'LIKE'
				)
			)
		);

		if ( 'price' === $orderby ) {
			$args['meta_key'] = '_price';
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
				'type'    => 'product'
			);

			$output .= merchant_content_ajax_search_item( $args );

		endwhile;

		$output .= '</div>';
	endif;

	$display_categories = isset( $_POST['display_categories'] ) ? absint( $_POST['display_categories'] ) : 0;
	if ( $display_categories ) {
		$args = array(
			'taxonomy'  => 'product_cat',
			'name-like' => $search_term
		);
		$cats = get_terms( $args );

		if ( count( $cats ) > 0 && $search_term ) {
			$output .= '<div class="merchant-ajax-search-heading-title">' . esc_html__( 'Categories', 'merchant' ) . '</div>';
			$output .= '<div class="merchant-ajax-search-divider"></div>';
			$output .= '<div class="merchant-ajax-search-categories">';

			foreach ( $cats as $category ) {
				$args   = array(
					'term_id' => $category->term_id,
					'type'    => 'category'
				);
				$output .= merchant_content_ajax_search_item( $args );
			}

			$output .= '</div>';
		}
	}

	if ( $output ) {
		wp_send_json( array(
			'status' => 'success',
			'output' => wp_kses_post( $output )
		) );
	} else {
		$output = '<p class="merchant-ajax-search-no-results">' . esc_html__( 'No products found.', 'merchant' ) . '</p>';

		wp_send_json( array(
			'status' => 'success',
			'type'   => 'no-results',
			'output' => wp_kses_post( $output )
		) );
	}
}

add_action( 'wp_ajax_merchant_ajax_search_callback', 'merchant_ajax_search_callback' );
add_action( 'wp_ajax_nopriv_merchant_ajax_search_callback', 'merchant_ajax_search_callback' );


if ( ! function_exists( 'merchant_ajax_search_admin_scripts' ) ) {
	add_action( 'admin_enqueue_scripts', 'merchant_ajax_search_admin_scripts' );

	function merchant_ajax_search_admin_scripts() {
		$page   = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
		$module = ( ! empty( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : '';

		if ( $page === 'merchant' && $module === 'real-time-search' ) {
			wp_enqueue_style( 'merchant', MERCHANT_URI . 'assets/css/merchant.min.css', array(), MERCHANT_VERSION );
		}
	}
}

if ( ! function_exists( 'merchant_ajax_search_preview' ) ) {
	add_filter( 'merchant_module_preview', 'merchant_ajax_search_preview', 10, 2 );

	/**
	 * Render admin preview
	 *
	 * @param Merchant_Admin_Preview $preview
	 * @param string $module
	 *
	 * @return Merchant_Admin_Preview
	 */
	function merchant_ajax_search_preview( $preview, $module ) {
		if ( $module === 'real-time-search' ) {
			ob_start();
			?>
            <div class="woocommerce-product-search merchant-ajax-search">
                <input type="search" id="woocommerce-product-search-field-search-form-1" class="search-field wc-search-field" placeholder="Search products…" value="" name="s"
                       autocomplete="off">
                <button type="submit" class="search-submit" value="Search" title="Search for the product">
                    <i class="ws-svg-icon">
                        <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10.875 3.75a7.125 7.125 0 100 14.25 7.125 7.125 0 000-14.25zM2.25 10.875a8.625 8.625 0 1117.25 0 8.625 8.625 0 01-17.25 0z"></path>
                            <path fill-rule="evenodd" d="M15.913 15.914a.75.75 0 011.06 0l4.557 4.556a.75.75 0 01-1.06 1.06l-4.557-4.556a.75.75 0 010-1.06z"></path>
                        </svg>
                    </i>
                </button>
                <input type="hidden" name="post_type" value="product">
               <div>
                   <div class="merchant-ajax-search-wrapper">
                       <div class="merchant-ajax-search-heading-title"><?php echo esc_html__( 'Products', 'merchant' ) ?></div>
                       <div class="merchant-ajax-search-divider"></div>
                       <div class="merchant-ajax-search-products">
                           <a class="merchant-ajax-search-item merchant-ajax-search-item-product" href="https://wp-merchant.ddev.site/product/deep-sweep-2-bha-pore-cleaning-toner-with-moringa/">
                               <div class="merchant-ajax-search-item-image"></div>
                               <div class="merchant-ajax-search-item-info">
                                   <div class="merchant-ajax-search-item-title"><?php echo __( 'Ultra Facial Moisturizing Cream', 'merchant' ) ?></div>
                                   <p><?php echo __( 'Cras mattis consectetur purus sit amet fermentum. Lorem ipsum dolor…', 'merchant' ) ?></p></div>
                               <div class="merchant-ajax-search-item-price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>21.00</span></div>
                           </a>
                           <a class="merchant-ajax-search-item merchant-ajax-search-item-product" href="https://wp-merchant.ddev.site/product/rare-earth-deep-pore-minimizing-cleansing-clay-mask/">
                               <div class="merchant-ajax-search-item-image"></div>
                               <div class="merchant-ajax-search-item-info">
                                   <div class="merchant-ajax-search-item-title"><?php echo __( 'Rare Earth Deep Pore Cleansing', 'merchant' ) ?></div>
                                   <p><?php echo __( 'Cras mattis consectetur purus sit amet fermentum. Lorem ipsum dolor…', 'merchant' ) ?></p></div>
                               <div class="merchant-ajax-search-item-price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>14.00</span></div>
                           </a></div>
                   </div>
               </div>
            </div>
			<?php
			$html = ob_get_clean();
			$preview->set_html( $html );
			$preview->set_css( 'results_box_width', '.merchant-ajax-search-wrapper', '--merchant-results-box-width', 'px' );
		}

		return $preview;
	}
}


add_filter( 'merchant_custom_css', function ( $css, $instance ) {
	$page   = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
	$module = ( ! empty( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : '';

	if ( is_admin() && $page === 'merchant' && $module === 'real-time-search' ) {
		$css .= '
        .merchant-ajax-search-item-image {
            width: 60px;
            height: 60px;
            background: #e5e5e5;
        }
		.woocommerce-product-search.merchant-ajax-search { 
            max-width: 500px; 
            width: 100%; 
            margin: 0 auto;
            display: flex;
        }
        .merchant-module-page-preview-browser-inner {
            padding: 20px;
        }
        input#woocommerce-product-search-field-search-form-1 {
            border: 1px solid #212121;
            border-radius: 0;
             flex-grow: 1;
        }
         button.search-submit {
            background: #212121;
            color: white;
            border: 0;
            margin-left: 10px;
            padding: 9px 15px;
        }
        
        button.search-submit svg {
            fill: white;
        }

        ';
	}

	return $css;
}, 10, 2 );