<?php

/**
 * Photo slider on popup
 */

$comment_id = (int)( $args['comment_id'] ?? 0 );

if ( ! $comment_id ) {
    return;
}

$_comment      = get_comment( $comment_id );
$review_images = get_comment_meta( $comment_id, 'review_images', true );

$comment_rating_value = get_comment_meta( $comment_id, 'rating', true );

wp_enqueue_style( 'merchant-carousel' );
wp_enqueue_script( 'merchant-carousel' );
?>
<button class="merchant-adv-reviews-modal-next-prev merchant-adv-reviews-modal-prev" data-nav="prev">
    <svg width="18" height="18" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="stroke-based"><path d="M8.5 1.33301L1.83333 7.99967L8.5 14.6663" stroke="#fff" stroke-width="1.5"></path></svg>
</button>
<button class="merchant-adv-reviews-modal-next-prev merchant-adv-reviews-modal-next" data-nav="next">
    <svg width="18" height="18" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="stroke-based"><path d="M1.5 14.667L8.16667 8.00033L1.5 1.33366" stroke="#fff" stroke-width="1.5"></path></svg>
</button>
<div class="merchant-adv-reviews-modal-body">
        <a href="#" class="merchant-adv-reviews-modal-close" title="<?php echo esc_attr__( 'Close popup', 'merchant' ); ?>">
            <i class="ws-svg-icon icon-cancel">
				<?php echo wp_kses( Merchant_SVG_Icons::get_svg_icon( 'icon-cancel' ), merchant_kses_allowed_tags( array(), false ) ); ?>
            </i>
        </a>
		<div class="merchant-adv-reviews-modal-content">
            <div class="merchant-adv-reviews-modal-column">
                <div class="merchant-carousel" data-per-page="1" data-loop="0" data-gap="0">
                    <div class="merchant-carousel-stage">
                        <?php foreach( $review_images as $image_id ) : ?>
                            <div class="item" role="button" tabindex="0">
                                <?php echo wp_get_attachment_image( $image_id, 'full' ); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="merchant-adv-reviews-modal-column">
                <div class="merchant-adv-reviews-modal-column-review">
                    <h3 class="merchant-adv-reviews-modal-column-review-title"><?php echo esc_html( get_the_title( $_comment->comment_post_ID ) ); ?></h3>
	                <?php if ( wc_review_ratings_enabled() ) : ?>
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
		                <?php
		                echo esc_html( get_comment_author( $_comment ) );

		                /**
		                 * Verified owner
		                 */
		                $verified = wc_review_is_from_verified_owner( $_comment->comment_ID );
		                if ( 'yes' === get_option( 'woocommerce_review_rating_verification_label' ) && $verified ) {
			                echo '<em class="woocommerce-review__verified verified">'. esc_attr__( ' — verified owner', 'merchant' ) . '</em> ';
		                }
		                ?>
                    </strong>
                    <div class="merchant-review-content"><?php comment_text( $_comment ); ?></div>
                </div>
            </div>
		</div>
	</div>
