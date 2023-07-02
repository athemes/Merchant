<?php

function merchant_content_ajax_search_item( $args ) {

	if( $args['type'] === 'product' ) {

		$desc_type   = Merchant_Admin_Options::get( 'real-time-search', 'results_description', 'product-post-content' );
		$desc_length = Merchant_Admin_Options::get( 'real-time-search', 'results_description_length', 10 );

		$item_post_id   = $args['post_id'];
		$product        = wc_get_product( $item_post_id );
		$item_permalink = get_the_permalink( $item_post_id );
		$item_image     = wp_get_attachment_image( $product->get_image_id() );
		$item_title     = get_the_title( $item_post_id );
		$description    = wp_trim_words( $desc_type === 'product-post-content' ? $product->get_description() : $product->get_short_description(), $desc_length );
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
		<?php if( $item_image ) : ?>
			<div class="merchant-ajax-search-item-image">
				<?php echo wp_kses_post( $item_image ); ?>
			</div>
		<?php endif; ?>
		<div class="merchant-ajax-search-item-info">
			<div class="merchant-ajax-search-item-title"><?php echo esc_html( $item_title ); ?></div>
			<?php if( $description ) : ?>
				<p><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>
		</div>
		<?php if( $price ) : ?>
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
	
	if( $orderby === 'price' ) {
		$args[ 'meta_key' ] = '_price';
		$args[ 'orderby' ]  = 'meta_value_num';
	}

	$output = '';
	$qry = new WP_Query( $args );

	// Enable search by SKU
	if( $enable_search_by_sku ) {
		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => $posts_per_page,
			'order'          => $order,
			'orderby'        => $orderby,
			'post_status'    => array( 'publish' ),
			'meta_query'     => array(
				'relation' => 'OR',
				array(
					'key' => '_sku',
					'value' => $search_term,
					'compare' => 'LIKE'
				)
			)
		);
		
		if( $orderby === 'price' ) {
			$args[ 'meta_key' ] = '_price';
			$args[ 'orderby' ]  = 'meta_value_num';
		}

		$qry_sku = new WP_Query( $args );
		$qry->posts = array_merge( $qry->posts, $qry_sku->posts );
		$qry->post_count = count( $qry->posts );
	}

	if( $qry->have_posts() ) :
		$output .= '<div class="merchant-ajax-search-heading-title">'. esc_html__( 'Products', 'merchant' ) .'</div>';
		$output .= '<div class="merchant-ajax-search-divider"></div>';
		$output .= '<div class="merchant-ajax-search-products">';

			while( $qry->have_posts() ) :
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
	if( $display_categories ) {
		$args = array(
			'taxonomy' => 'product_cat',
			'name-like' => $search_term
		);
		$cats = get_terms( $args );
	
		if( count( $cats ) > 0 && $search_term ) {
			$output .= '<div class="merchant-ajax-search-heading-title">'. esc_html__( 'Categories', 'merchant' ) .'</div>';
				$output .= '<div class="merchant-ajax-search-divider"></div>';
			$output .= '<div class="merchant-ajax-search-categories">';
	
				foreach( $cats as $category ) {
					$args = array(
						'term_id' => $category->term_id,
						'type'    => 'category'
					);
					$output .= merchant_content_ajax_search_item( $args );
				}
				
			$output .= '</div>';
		}
	}

	if( $output ) {
		wp_send_json( array(
			'status'  => 'success',
			'output'  => wp_kses_post( $output )
		) );
	} else {
		$output = '<p class="merchant-ajax-search-no-results">'. esc_html__( 'No products found.', 'merchant' ) .'</p>';

		wp_send_json( array(
			'status'  => 'success',
			'type'    => 'no-results',
			'output'  => wp_kses_post( $output )
		) );
	}
}
add_action('wp_ajax_merchant_ajax_search_callback', 'merchant_ajax_search_callback');
add_action('wp_ajax_nopriv_merchant_ajax_search_callback', 'merchant_ajax_search_callback');
