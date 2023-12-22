<?php

/**
 * Template for advanced reviews items.
 * 
 * $args module settings.
 * 
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! isset( $args[ 'product' ] ) ) {
	return;
}

$product        = $args[ 'product' ];
$product_id     = $product->get_id();
$review_count   = $product->get_review_count();
$average        = $product->get_average_rating();

// Title tag
$title_tag = $args[ 'title_tag' ];

// Dropdown sort
$default_sorting    = $args[ 'default_sorting' ];
$sort_orderby       = isset( $_GET['orderby'] ) ? sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) : $default_sorting;  // phpcs:ignore WordPress.Security.NonceVerification.Recommended

// Reviews bars rating
$bars_data = $args['bars_data']; ?>

<section id="reviews" class="merchant-adv-reviews products<?php echo ( $args[ 'hide_title' ] ) ? ' hide-title' : ''; ?>">
	<?php
	if ( ! $args[ 'hide_title' ] ) :
	
		echo wp_kses_post(
			sprintf(
				'<%1$s id="reviews-stars" class="merchant-adv-reviews-title">%2$s</%1$s>',
				$title_tag,
				esc_html( Merchant_Translator::translate( $args[ 'title' ] ) )
			)
		);

		?>

		<p class="merchant-adv-reviews-desc">
			<?php echo esc_html( Merchant_Translator::translate( $args[ 'description' ] ) ); ?>
		</p>

	<?php endif; ?>
	
	<div class="merchant-adv-reviews-header">
		<div class="mrc-row mrc-columns-no-gutter">
			<div class="mrc-col mrc-left-col">

				<?php if ( $args['ratings_enabled'] && $bars_data['total'] > 0 ) : ?>
				<div class="merchant-adv-reviews-rating-wrapper">
					<strong class="merchant-adv-reviews-rating"><?php echo esc_html( $average ); ?></strong>
					<div class="star-rating merchant-star-rating-style2" role="img" aria-label="Rated <?php echo esc_attr( $average ); ?> out of 5">
						<span style="width: <?php echo esc_attr( ( ( $average / 5 ) * 100 ) ); ?>%;">
							<?php
							/* translators: %s is average rating value */
							$rating_text = sprintf( __( 'Rated %s out of 5 based on customer ratings.', 'merchant' ), $average );
							echo esc_html( $rating_text ); 
							?>
						</span>
					</div>
				</div>
				<?php endif; ?>
				
				<p class="merchant-adv-reviews-total">
					<?php 
					if ( $review_count > 0 ) {
						/* translators: %s is review count */
						$review_count_text = sprintf( _nx( '%s Review', '%s Reviews', $review_count, 'review count', 'merchant' ), number_format_i18n( $review_count ) );
						echo esc_html( $review_count_text );
					} else {
						echo esc_html__( 'Be the first to leave a review.', 'merchant' );
					} 
					?>
				</p>

				<?php if ( $args['ratings_enabled'] && $bars_data[ 'total' ] > 0 ) : ?>
				<div class="merchant-star-rating-bars">
					<div class="merchant-star-rating-bar-item">
						<p class="item-rating"><?php echo esc_html__( '5 Stars', 'merchant' ); ?></p>
						<div class="item-bar">
							<div class="item-bar-inner" style="width: <?php echo esc_attr( $bars_data[ '5-stars-percent' ] ); ?>%;"></div>
						</div>
						<p class="item-qty">
							<?php 
							/* translators: %s is stars quantity */
							$five_star_text = sprintf( esc_html__( '(%s)', 'merchant' ), $bars_data[ '5-stars' ] );
							echo esc_html( $five_star_text ); 
							?>
						</p>
					</div>
					<div class="merchant-star-rating-bar-item">
						<p class="item-rating"><?php echo esc_html__( '4 Stars', 'merchant' ); ?></p>
						<div class="item-bar">
							<div class="item-bar-inner" style="width: <?php echo esc_attr( $bars_data[ '4-stars-percent' ] ); ?>%;"></div>
						</div>
						<p class="item-qty">
							<?php 
							/* translators: %s is stars quantity */
							$four_star_text = sprintf( esc_html__( '(%s)', 'merchant' ), $bars_data[ '4-stars' ] );
							echo esc_html( $four_star_text ); 
							?>
						</p>  
					</div>
					<div class="merchant-star-rating-bar-item">
						<p class="item-rating"><?php echo esc_html__( '3 Stars', 'merchant' ); ?></p>
						<div class="item-bar">
							<div class="item-bar-inner" style="width: <?php echo esc_attr( $bars_data[ '3-stars-percent' ] ); ?>%;"></div>
						</div>
						<p class="item-qty">
							<?php 
							/* translators: %s is stars quantity */
							$three_star_text = sprintf( esc_html__( '(%s)', 'merchant' ), $bars_data[ '3-stars' ] );
							echo esc_html( $three_star_text ); 
							?>
						</p>  
					</div>
					<div class="merchant-star-rating-bar-item">
						<p class="item-rating"><?php echo esc_html__( '2 Stars', 'merchant' ); ?></p>
						<div class="item-bar">
							<div class="item-bar-inner" style="width: <?php echo esc_attr( $bars_data[ '2-stars-percent' ] ); ?>%;"></div>
						</div>
						<p class="item-qty">
							<?php 
							/* translators: %s is stars quantity */
							$two_star_text = sprintf( esc_html__( '(%s)', 'merchant' ), $bars_data[ '2-stars' ] );
							echo esc_html( $two_star_text ); 
							?>
						</p>  
					</div>
					<div class="merchant-star-rating-bar-item">
						<p class="item-rating"><?php echo esc_html__( '1 Star', 'merchant' ); ?></p>
						<div class="item-bar">
							<div class="item-bar-inner" style="width: <?php echo esc_attr( $bars_data[ '1-stars-percent' ] ); ?>%;"></div>
						</div>
						<p class="item-qty">
							<?php 
							/* translators: %s is stars quantity */
							$one_star_text = sprintf( esc_html__( '(%s)', 'merchant' ), $bars_data[ '1-stars' ] );
							echo esc_html( $one_star_text ); 
							?>
						</p>  
					</div>
				</div>
				<?php endif; ?>

			</div>
			<div class="mrc-col mrc-right-col">
				<a href="#" class="merchant-adv-review-write-button"><?php echo esc_html__( 'Write a Review', 'merchant' ); ?></a>

				<?php if ( $review_count > 0 ) : ?>
				<form class="merchant-reviews-orderby-form" method="get" action="<?php echo esc_url( get_the_permalink( $product_id ) ); ?>#reviews-stars">
					<label for="orderby"><?php echo esc_html__( 'Sort by:', 'merchant' ); ?></label>
					<select class="merchant-reviews-orderby" name="orderby" onChange="this.parentNode.submit();">
						<option value="newest"<?php echo selected( $sort_orderby, 'newest' ); ?>><?php echo esc_html__( 'Newest', 'merchant' ); ?></option>
						<option value="oldest"<?php echo selected( $sort_orderby, 'oldest' ); ?>><?php echo esc_html__( 'Oldest', 'merchant' ); ?></option>
						<option value="top-rated"<?php echo selected( $sort_orderby, 'top-rated' ); ?>><?php echo esc_html__( 'Top rated', 'merchant' ); ?></option>
						<option value="low-rated"<?php echo selected( $sort_orderby, 'low-rated' ); ?>><?php echo esc_html__( 'Low rated', 'merchant' ); ?></option>
					</select>
				</form>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="merchant-adv-reviews-body">

		<?php
		if ( $args['comments_open'] ) :
			// Get comments args
			$comments_args = array(
				'post_id'  => $product_id,
				'number'   => get_option( 'page_comments' ) ? get_option( 'comments_per_page' ) : '',
			);

			// Pagination?
			$cpages = 0;
			if ( get_option( 'page_comments' ) ) {
				$cpaged = get_query_var( 'cpage' );

				$cpages = count( get_comments( array(
					'post_id' => $product_id,
					'fields' => 'ids',
				) ) );

				$cpages = $cpages / get_option( 'comments_per_page' );

				$comments_args[ 'paged' ] = empty( $cpaged ) ? 1 : $cpaged;
			}

			// Orderby
			switch ( $sort_orderby ) {
				case 'newest':
					$comments_args[ 'order' ]   = 'DESC';
					$comments_args[ 'orderby' ] = 'comment_date_gmt';
					break;

				case 'oldest':
					$comments_args[ 'order' ]   = 'ASC';
					$comments_args[ 'orderby' ] = 'comment_date_gmt';
					break;

				case 'top-rated':
					$comments_args[ 'order' ]   = 'DESC';
					$comments_args[ 'orderby' ]  = 'meta_value_num';
					// phpcs:disable
					$comments_args[ 'meta_key' ] = 'rating';
					// phpcs:enable
					break;
				
				case 'low-rated':
					$comments_args[ 'order' ]   = 'ASC';
					$comments_args[ 'orderby' ]  = 'meta_value_num';
					// phpcs:disable
					$comments_args[ 'meta_key' ] = 'rating';
					// phpcs:enable
					break;
			}

			/**
			 * Hook 'merchant_wc_reviews_advanced_sorting_args'
			 * 
			 * @since 1.0
			 */
			$_comments = isset($args['comments']) ? $args['comments'] : get_comments( apply_filters( 'merchant_wc_reviews_advanced_sorting_args',$comments_args ) ); ?>

			<div id="comments">
				<?php if ( count( $_comments ) > 0 ) : ?>
					<div class="merchant-reviews-list-wrapper">
						
						<?php 
						foreach ( $_comments as $_comment ) :
							if ( '1' === $_comment->comment_approved ) : 
							?>

								<div id="comment-<?php echo esc_attr( $_comment->comment_ID ); ?>" class="merchant-reviews-list-item">
									<div class="mrc-row mrc-columns-no-gutter">
										<div class="mrc-col">
											<div class="merchant-reviews-author-wrapper">

												<?php
												$comment_rating_value = isset( $args['comment_rating'] ) ? $args['comment_rating'] : get_comment_meta( $_comment->comment_ID, 'rating', true ); ?>

												<?php if( wc_review_ratings_enabled() ) : ?>
													<div class="star-rating merchant-star-rating-style2" role="img" aria-label="Rated <?php echo esc_attr( $comment_rating_value ); ?>.00 out of 5">
														<span style="width: <?php echo esc_attr( ( ( $comment_rating_value / 5 ) * 100 ) ); ?>%;">
															<?php 
															/* translators: %s is average rating value */
															$comment_rating_text = sprintf( __( 'Rated %s out of 5 based on customer ratings.', 'merchant' ), $comment_rating_value );
															echo esc_html( $comment_rating_text ); ?>
														</span>
													</div>
												<?php endif; ?>
												
												<strong class="merchant-review-author">
													<?php echo esc_html( get_comment_author( $_comment ) ); ?>

													<?php
													/**
													 * Verified owner
													*/
													$verified = wc_review_is_from_verified_owner( $_comment->comment_ID );
													if ( 'yes' === get_option( 'woocommerce_review_rating_verification_label' ) && $verified ) {
														echo '<em class="woocommerce-review__verified verified">'. esc_attr__( ' — verified owner', 'merchant' ) . '</em> ';
													} ?>
												</strong>
											</div>
										</div>
										<div class="mrc-col-3 merchant-review-date-wrapper">
											<time class="merchant-review-date" datetime="<?php echo esc_attr( get_comment_date( 'c', $_comment ) ); ?>"><?php echo esc_html( get_comment_date( 'F j, Y', $_comment ) ); ?></time>
										</div>
									</div>
									<div class="mrc-row mrc-columns-no-gutter">
										<div class="mrc-col">
											<div class="merchant-review-content">
												<?php
												
												/**
												 * Hook 'woocommerce_review_before_comment_text'
												 * 
												 * @since 1.0
												 */
												do_action( 'woocommerce_review_before_comment_text', $_comment ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Ensure compatibility with WooCommerce plugins

												if ( isset( $args['comment_text'] ) ) {
													echo wp_kses_post( $args['comment_text'] );
												} else {
													comment_text( $_comment );
												}

												/**
												 * Hook 'woocommerce_review_after_comment_text'
												 * 
												 * @since 1.0
												 */
												do_action( 'woocommerce_review_after_comment_text', $_comment ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Ensure compatibility with WooCommerce plugins
												?>
											</div>
										</div>
									</div>
								</div>

							<?php elseif( isset( $_GET['unapproved'] ) && $_comment->comment_ID === $_GET['unapproved'] ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>

								<div id="comment-<?php echo esc_attr( $_comment->comment_ID ); ?>" class="merchant-reviews-list-item">
									<div class="row">
										<div class="col-12">
											<div class="d-flex align-items-center">

												<?php 
												$comment_rating_value = get_comment_meta( $_comment->comment_ID, 'rating', true ); ?>

												<?php if( wc_review_ratings_enabled() ) : ?>
													<div class="star-rating merchant-star-rating-style2" role="img" aria-label="Rated <?php echo esc_attr( $comment_rating_value ); ?>.00 out of 5">
														<span style="width: <?php echo esc_attr( ( ( $comment_rating_value / 5 ) * 100 ) ); ?>%;">
															<?php 
															/* translators: %s is average rating value */
															$comment_rating_text = sprintf( __( 'Rated %s out of 5 based on customer ratings.', 'merchant' ), $comment_rating_value );
															echo esc_html( $comment_rating_text ); ?>
														</span>
													</div>
												<?php endif; ?>

												<strong class="merchant-review-author">
													<?php echo esc_html( get_comment_author( $_comment ) ); ?>

													<?php
													/**
													 * Verified owner
													*/
													$verified = wc_review_is_from_verified_owner( $_comment->comment_ID );
													if ( 'yes' === get_option( 'woocommerce_review_rating_verification_label' ) && $verified ) {
														echo '<em class="woocommerce-review__verified verified">'. esc_attr__( ' — verified owner', 'merchant' ) . '</em> ';
													} ?>
												</strong>
											</div>
											<br>
											<em><?php echo esc_html__( 'Your review is awaiting approval.', 'merchant' ); ?></em>
										</div>
									</div>
								</div>

							<?php endif;
						endforeach; ?>

					</div>

				<?php else : ?>
					<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'merchant' ); ?></p>
				<?php endif; ?>
			</div>

		<?php endif; ?>

	</div>
	<?php if ( isset( $comments ) && count( $comments ) > 0 ) {
		echo '<div class="merchant-adv-reviews-footer">';

			if ( $cpages > 1 && get_option( 'page_comments' ) ) {
				echo '<nav class="woocommerce-pagination merchant-pagination merchant-adv-reviews-pagination">';

					merchant_get_template_part( 'modules/advanced-reviews', 'pagination-links', array_merge(
						$args,
						array(
							'pagination_args' => array(
								'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
								'next_text' => is_rtl() ? '&larr;' : '&rarr;',
								'type'      => 'list',
							),
							'cpages'     => $cpages,
							'product_id' => $product_id,
						)
					) );

				echo '</nav>';
				
				/**
				 * Hook 'merchant_after_shop_reviews_adv_pagination'
				 * 
				 * @since 1.0
				 */
				do_action( 'merchant_after_shop_reviews_adv_pagination' );
			}

		echo '</div>';
	} ?>
</section>

<?php 
/**
 * Hook 'merchant_after_adv_reviews_section'
 * 
 * @since 1.0
 */
do_action( 'merchant_after_adv_reviews_section' ); ?>
