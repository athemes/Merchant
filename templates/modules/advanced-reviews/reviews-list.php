<?php

/**
 * Template for showing comments list
 */

global $comment; // Required to use global $comment in other places.

$_comments = $args['comments'] ?? array();
?>
<div id="comments">
	<?php if ( count( $_comments ) > 0 ) : ?>
        <div class="merchant-reviews-list-wrapper">
			<?php
			foreach ( $_comments as $comment ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				if ( '1' === $comment->comment_approved ) :
					// Get review images
					$review_images = get_comment_meta( $comment->comment_ID, 'review_images', true );
					?>
                    <div id="comment-<?php echo esc_attr( $comment->comment_ID ); ?>" class="merchant-reviews-list-item">
                        <div class="mrc-row mrc-columns-no-gutter">
                            <div class="mrc-col">
                                <div class="merchant-reviews-author-wrapper">
									<?php
									$comment_rating_value = (int) ( $args['comment_rating'] ?? get_comment_meta( $comment->comment_ID, 'rating', true ) );
                                    if ( $comment_rating_value && wc_review_ratings_enabled() ) : ?>
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
										echo esc_html( get_comment_author( $comment ) );

										/**
										 * Verified owner
										 */
										$verified = wc_review_is_from_verified_owner( $comment->comment_ID );
										if ( 'yes' === get_option( 'woocommerce_review_rating_verification_label' ) && $verified ) {
											echo '<em class="woocommerce-review__verified verified">'. esc_attr__( ' — verified owner', 'merchant' ) . '</em> ';
										}
										?>
                                    </strong>
                                </div>
                            </div>

                            <div class="mrc-col-3 merchant-review-date-wrapper">
                                <time class="merchant-review-date" datetime="<?php echo esc_attr( get_comment_date( 'c', $comment ) ); ?>"><?php echo esc_html( get_comment_date( 'F j, Y', $comment ) ); ?></time>
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
									do_action( 'woocommerce_review_before_comment_text', $comment ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Ensure compatibility with WooCommerce plugins

									if ( isset( $args['comment_text'] ) ) {
										echo wp_kses_post( $args['comment_text'] );
									} else {
										comment_text( $comment );
									}

									/**
									 * Hook 'woocommerce_review_after_comment_text'
									 *
									 * @since 1.0
									 */
									do_action( 'woocommerce_review_after_comment_text', $comment ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Ensure compatibility with WooCommerce plugins

									// Display review images
									if ( $review_images ) : ?>
                                        <div class="merchant-review-images">
											<?php foreach ( $review_images as $image_id ) : ?>
                                                <div class="merchant-review-image js-photo-slider-item" role="button" data-comment-id="<?php echo esc_attr( $comment->comment_ID ); ?>">
													<?php echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?>
                                                </div>
											<?php endforeach; ?>
                                        </div>
									<?php
									endif;
									?>
                                </div>
                            </div>
                        </div>
                    </div>

					<?php 

					// The method has to be checked here because it is also rendered in the preview of the plugin on admin dashboard.
					$comment_children = method_exists( $comment, 'get_children' ) ? $comment->get_children() : false;
					if ( $comment_children ) : ?>
						<?php foreach( $comment_children as $child_comment ) : ?>

							<div id="comment-<?php echo esc_attr( $child_comment->comment_ID ); ?>" class="merchant-reviews-list-item merchant-reviews-list-item-child">
								<div class="mrc-row mrc-columns-no-gutter">
									<div class="mrc-col">
										<div class="merchant-reviews-author-wrapper">
											<strong class="merchant-review-author">
												<?php echo esc_html( get_comment_author( $child_comment ) ); ?>
												<em class="owner-reply"><?php echo esc_html__( ' — Store Owner', 'merchant' ); ?></em>
											</strong>
										</div>
									</div>

									<div class="mrc-col-3 merchant-review-date-wrapper">
										<time class="merchant-review-date" datetime="<?php echo esc_attr( get_comment_date( 'c', $child_comment ) ); ?>"><?php echo esc_html( get_comment_date( 'F j, Y', $child_comment ) ); ?></time>
									</div>
								</div>

								<div class="mrc-row mrc-columns-no-gutter">
									<div class="mrc-col">
										<div class="merchant-review-content">
											<?php

											/**
											 * Hook 'merchant_review_before_child_comment_text'
											 *
											 * @since 1.10.1
											 */
											do_action( 'merchant_review_before_child_comment_text', $child_comment ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Ensure compatibility with WooCommerce plugins

											if ( isset( $args['comment_text'] ) ) {
												echo wp_kses_post( $args['comment_text'] );
											} else {
												comment_text( $child_comment );
											}

											/**
											 * Hook 'merchant_review_after_child_comment_text'
											 *
											 * @since 1.10.1
											 */
											do_action( 'merchant_review_after_child_comment_text', $child_comment ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Ensure compatibility with WooCommerce plugins ?>
										</div>
									</div>
								</div>
							</div>

						<?php endforeach; ?>
					<?php endif; ?>


				<?php elseif( isset( $_GET['unapproved'] ) && $comment->comment_ID === $_GET['unapproved'] ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
                    <div id="comment-<?php echo esc_attr( $comment->comment_ID ); ?>" class="merchant-reviews-list-item">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex align-items-center">
									<?php
									$comment_rating_value = get_comment_meta( $comment->comment_ID, 'rating', true ); ?>

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
										<?php echo esc_html( get_comment_author( $comment ) ); ?>

										<?php
										/**
										 * Verified owner
										 */
										$verified = wc_review_is_from_verified_owner( $comment->comment_ID );
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
