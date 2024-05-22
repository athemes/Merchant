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

if ( ! isset( $args['comments_open'] ) || ! $args['comments_open'] ) {
	return;
}

$product      = $args[ 'product' ];
$product_id   = $product->get_id();
$review_count = $product->get_review_count();
$average      = $product->get_average_rating();
$average      = floor( $average ) === ceil( $average ) ? intval( $average ) : number_format( $average, 1 );

// Title tag
$title_tag = $args[ 'title_tag' ] ?? 'h2';

// Dropdown sort
$default_sorting = $args[ 'default_sorting' ] ?? 'newest';
$sort_orderby    = isset( $_GET['orderby'] ) ? sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) : $default_sorting;  // phpcs:ignore WordPress.Security.NonceVerification.Recommended

// Get Comments args
$comments_args = array(
	'post_id' => $product_id,
	'number'  => get_option( 'page_comments' ) ? get_option( 'comments_per_page' ) : '',
);

// Pagination
$comment_pages = 0;
if ( get_option( 'page_comments' ) ) {
	$cpaged = get_query_var( 'cpage' );

	$comment_pages = count( get_comments( array(
		'post_id' => $product_id,
		'fields'  => 'ids',
		'status'  => 'approve',
	) ) );

	$comment_pages = ceil( $comment_pages / get_option( 'comments_per_page' ) );

	$comments_args['product_id'] = $product_id;
	$comments_args['cpage']      = empty( $cpaged ) ? 1 : $cpaged;
	$comments_args['total']      = $comment_pages;
}

// Orderby
switch ( $sort_orderby ) {
	case 'newest':
		$comments_args['order']   = 'DESC';
		$comments_args['orderby'] = 'comment_date_gmt';
		break;

	case 'oldest':
		$comments_args['order']   = 'ASC';
		$comments_args['orderby'] = 'comment_date_gmt';
		break;

	case 'top-rated':
		$comments_args['order']   = 'DESC';
		$comments_args['orderby'] = 'meta_value_num';
		// phpcs:disable
		$comments_args['meta_key'] = 'rating';
		// phpcs:enable
		break;

	case 'low-rated':
		$comments_args['order']   = 'ASC';
		$comments_args['orderby'] = 'meta_value_num';
		// phpcs:disable
		$comments_args['meta_key'] = 'rating';
		// phpcs:enable
		break;

	case 'photo-first':
		// phpcs:disable
		$comments_args['meta_query'] = array(
			'relation' => 'OR',
			array(
				'key'     => 'review_images',
				'compare' => 'EXISTS',
			),
			array(
				'key'     => 'review_images',
				'compare' => 'NOT EXISTS',
			),
		);
		$comments_args['orderby'] = array(
			'meta_value' => 'DESC',
			'comment_date'   => 'DESC',
		);
		// phpcs:enable
		break;
}

/**
 * Hook 'merchant_wc_reviews_advanced_sorting_args'
 *
 * @since 1.0
 */
$_comments = isset( $args['comments'] ) ? $args['comments'] : get_comments( apply_filters( 'merchant_wc_reviews_advanced_sorting_args', $comments_args ) );

$args['comments'] = ! isset( $args['comments'] ) ? $_comments : $args['comments'];

// Reviews bars rating
$bars_data     = $args['bars_data'] ?? array();
$ratings       = $bars_data['ratings'] ?? array();
$total_ratings = $bars_data['total'] ?? 0;

// Carousel images
$is_carousel_on       = (bool) ( $args['review_images_carousel'] ?? false );
$carousel_images_data = $args['carousel_images_data'] ?? array();

if ( $is_carousel_on && is_array( $carousel_images_data ) && ! empty( $carousel_images_data ) ) : ?>
    <?php
    $images_per_page = $args['review_images_carousel_per_page'] ?? 3;

	wp_enqueue_style( 'merchant-carousel' );
	wp_enqueue_script( 'merchant-carousel' );
    ?>
	<section class="merchant-adv-reviews-media-carousel">
		<?php if ( ! empty( $args['carousel_title'] ) ) : ?>
			<h3 class="section-title"><?php echo esc_html( Merchant_Translator::translate( $args['carousel_title'] ) ); ?></h3>
		<?php endif; ?>

		<div class="merchant-carousel <?php echo esc_attr( $images_per_page >= count( $carousel_images_data ) ? ' no-carousel' : '' ); ?>" data-per-page="<?php echo esc_attr( $images_per_page ); ?>">
            <div class="merchant-carousel-stage">
                <?php foreach( $carousel_images_data as $data ) :
                    ?>
                    <?php if ( ! empty( $data['image_id'] ) ) : ?>
                    <div class="item js-photo-slider-item" role="button" data-comment-id="<?php echo esc_attr( $data['comment_id'] ?? '' ); ?>">
                        <?php echo wp_get_attachment_image( $data['image_id'], 'full' ); ?>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
		</div>
	</section>
<?php endif; ?>

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
				<?php if ( $args['ratings_enabled'] && $total_ratings > 0 ) : ?>
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

				<?php if ( $args['ratings_enabled'] && $total_ratings > 0 ) : ?>
                    <div class="merchant-star-rating-bars">
                        <?php if ( is_array( $ratings ) && ! empty( $ratings ) ) : ?>
                            <?php foreach ( $ratings as $key => $rating ) : ?>
                                <div class="merchant-star-rating-bar-item" tabindex="0" role="button" data-rating="<?php echo esc_attr( substr( $key, 0, strpos( $key, '-' ) ) ); ?>">
                                    <div class="merchant-star-rating-bar-item-inner">
                                        <p class="item-rating"><?php echo esc_html( $rating['label'] ?? '' ); ?></p>
                                        <div class="item-bar">
                                            <div class="item-bar-inner" style="width: <?php echo esc_attr( $rating[ 'percent' ] ); ?>%;"></div>
                                        </div>
                                        <p class="item-qty">(<?php echo esc_html( $rating['value'] ?? '0' ); ?>)</p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
				<?php endif; ?>
			</div>

			<div class="mrc-col mrc-right-col">
                <?php
                $btn_text  = esc_html__( 'Write a Review', 'merchant' );
                $btn_link  = '#';
                $btn_attrs = '';
                $btn_class = 'merchant-adv-review-write-button';

                if ( get_option( 'comment_registration' ) && ! is_user_logged_in() ) {
	                $btn_text = esc_html__( 'Log in to write a Review', 'merchant' );
	                $btn_link = wp_login_url( get_permalink() );
	                $btn_attrs = 'rel="nofollow"';
                } else {
                    $btn_class .= ' js-merchant-adv-review-write-button';
                }
                ?>
				<a href="<?php echo esc_url( $btn_link ); ?>" class="<?php echo esc_attr( $btn_class ); ?>" <?php echo wp_kses_post( $btn_attrs ); ?>><?php echo esc_html( $btn_text ); ?></a>

				<?php if ( $review_count > 0 ) : ?>
                    <form class="merchant-reviews-orderby-form" method="get" action="<?php echo esc_url( get_the_permalink( $product_id ) ); ?>#reviews-stars">
                        <label for="orderby"><?php echo esc_html__( 'Sort by:', 'merchant' ); ?></label>
                        <select class="merchant-reviews-orderby" name="orderby" onChange="this.parentNode.submit();">
                            <option value="newest"<?php echo selected( $sort_orderby, 'newest' ); ?>><?php echo esc_html__( 'Newest', 'merchant' ); ?></option>
                            <option value="oldest"<?php echo selected( $sort_orderby, 'oldest' ); ?>><?php echo esc_html__( 'Oldest', 'merchant' ); ?></option>
                            <option value="top-rated"<?php echo selected( $sort_orderby, 'top-rated' ); ?>><?php echo esc_html__( 'Top rated', 'merchant' ); ?></option>
                            <option value="low-rated"<?php echo selected( $sort_orderby, 'low-rated' ); ?>><?php echo esc_html__( 'Low rated', 'merchant' ); ?></option>
                            <option value="photo-first"<?php echo selected( $sort_orderby, 'photo-first' ); ?>><?php echo esc_html__( 'Photo first', 'merchant' ); ?></option>
                        </select>
                    </form>
				<?php endif; ?>
			</div>
		</div>
	</div>

    <div class="merchant-adv-reviews-body">
	    <?php merchant_get_template_part( Merchant_Advanced_Reviews::MODULE_TEMPLATES_PATH, 'reviews-list', $args ); ?>
    </div>

    <div class="merchant-adv-reviews-footer">
        <?php
        if ( count( $_comments ) > 0 ) :
            /**
             * Hook 'merchant_after_shop_reviews_adv_pagination'
             *
             * @since 1.0
             */
            do_action( 'merchant_after_shop_reviews_adv_pagination', array_merge( $args, $comments_args ) );
        endif;
        ?>
</section>

<?php
/**
 * Hook 'merchant_after_adv_reviews_section'
 * 
 * @since 1.0
 */
do_action( 'merchant_after_adv_reviews_section' );
