<?php

/**
 * Template for advanced reviews modal.
 *
 * @var $args array module settings.
 *
 * @since 1.0
 */

global $product;
?>
<div class="merchant-adv-reviews-modal">
	<div class="merchant-adv-reviews-modal-body">
		<a href="#" class="merchant-adv-reviews-modal-close" title="<?php echo esc_attr__( 'Close popup', 'merchant' ); ?>">
			<i class="ws-svg-icon icon-cancel">
				<?php echo wp_kses( Merchant_SVG_Icons::get_svg_icon( 'icon-cancel' ), merchant_kses_allowed_tags( array(), false ) ); ?>
			</i>
		</a>
		<div class="merchant-adv-reviews-modal-content">
			<h4 class="modal-title"><?php echo esc_html__( 'You are reviewing', 'merchant' ); ?></h4>
			<div class="merchant-adv-reviews-modal-product">
				<?php echo get_the_post_thumbnail( $product->get_id(), 'woocommerce_thumbnail' ); ?>
				<div class="modal-product-info">
					<h5><?php echo esc_html( $product->get_name() ); ?></h5>
					<p><?php echo esc_html( $product->get_short_description() ); ?></p>
				</div>
			</div>
			<div class="merchant-adv-reviews-modal-rating">
				<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>
					<div id="review_form_wrapper">
						<div id="review_form">
							<?php
							$commenter    = wp_get_current_commenter();
							$comment_form = array(
								/* translators: %s is product title */
                                'title_reply'         => '',
								/* translators: %s is product title */
                                'title_reply_to'      => esc_html__( 'Leave a Reply to %s', 'merchant' ),
                                'title_reply_before'  => '<span id="reply-title" class="comment-reply-title">',
                                'title_reply_after'   => '</span>',
                                'comment_notes_after' => '',
                                'label_submit'        => esc_html__( 'Submit', 'merchant' ),
                                'logged_in_as'        => '',
                                'comment_field'       => '',
							);

							$name_email_required = (bool) get_option( 'require_name_email', 1 );

                            $fields = array(
                                'author' => array(
                                    'label'    => esc_html__( 'Name', 'merchant' ),
                                    'type'     => 'text',
                                    'value'    => $commenter['comment_author'],
                                    'required' => $name_email_required,
                                ),
                                'email'  => array(
                                    'label'    => esc_html__( 'Email', 'merchant' ),
                                    'type'     => 'email',
                                    'value'    => $commenter['comment_author_email'],
                                    'required' => $name_email_required,
                                ),
							);

							$comment_form['fields'] = array();

							foreach ( $fields as $key => $field ) {
								$field_html  = '<p class="comment-form-' . esc_attr( $key ) . '">';
								$field_html .= '<label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] );

								if ( $field['required'] ) {
									$field_html .= '&nbsp;<span class="required">*</span>';
								}

								$field_html .= '</label><input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $field['value'] ) . '" size="30" ' . ( $field['required'] ? 'required' : '' ) . '/></p>';

								$comment_form['fields'][ $key ] = $field_html;
							}

							$account_page_url = wc_get_page_permalink( 'myaccount' );
							if ( $account_page_url ) {
								/* translators: %s opening and closing link tags respectively */
								$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'merchant' ),
												'<a href="' . esc_url( $account_page_url ) . '">',
												'</a>' ) . '</p>';
							}

							if ( wc_review_ratings_enabled() ) {
								$comment_form['comment_field'] = '<div class="comment-form-rating"><label class="modal-title" for="rating">' . esc_html__( 'Rating',
												'merchant' ) . ( wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '' ) . '</label><select name="rating" id="rating" required>
									<option value="">' . esc_html__( 'Rate&hellip;', 'merchant' ) . '</option>
									<option value="5">' . esc_html__( 'Perfect', 'merchant' ) . '</option>
									<option value="4">' . esc_html__( 'Good', 'merchant' ) . '</option>
									<option value="3">' . esc_html__( 'Average', 'merchant' ) . '</option>
									<option value="2">' . esc_html__( 'Not that bad', 'merchant' ) . '</option>
									<option value="1">' . esc_html__( 'Very poor', 'merchant' ) . '</option>
								</select></div>';
							}

							if ( isset( $args['review_options'] ) ) {

								// Enable comment field for image and text or text only.
								if ( 'image_and_text' === $args['review_options'] || 'text' === $args['review_options'] ) {
									$comment_form['comment_field'] .= '<p class="comment-form-comment"><label class="modal-title" for="comment">' . esc_html__( 'Your review', 'merchant' ) . '&nbsp;<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="5" required></textarea></p>';
								}

								// Enable image upload field for image and text or image only.
								if ( 'image_and_text' === $args['review_options'] || 'image' === $args['review_options'] ) {
									$comment_form['comment_field'] .= '<div class="merchant-adv-reviews-media">';
									// translators: %s is the number of photos limit
									$comment_form['comment_field'] .= '<label class="modal-title" for="review_images">' . sprintf( esc_html__( 'Upload images (up to %s)', 'merchant' ), $args['photos_limit'] ) . '</label>';
                                    $comment_form['comment_field'] .= '<input type="file" id="merchant-adv-review-images" name="review_images[]" accept="image/*" multiple>';
									$comment_form['comment_field'] .= '<div id="merchant-adv-reviews-drop-area">';
                                    $comment_form['comment_field'] .= '<svg class="merchant-adv-reviews-upload-icon" width="36" height="36" viewBox="0 0 50 50" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><rect fill="none" height="50" width="50"/><path d="M32,35c0,0,8.312,0,9.098,0C45.463,35,49,31.463,49,27.099s-3.537-7.902-7.902-7.902c-0.02,0-0.038,0.003-0.058,0.003  c0.061-0.494,0.103-0.994,0.103-1.504c0-6.71-5.439-12.15-12.15-12.15c-5.229,0-9.672,3.309-11.386,7.941  c-1.087-1.089-2.591-1.764-4.251-1.764c-3.319,0-6.009,2.69-6.009,6.008c0,0.085,0.01,0.167,0.013,0.251  C3.695,18.995,1,22.344,1,26.331C1,31.119,4.881,35,9.67,35c0.827,0,8.33,0,8.33,0" fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="2"/><polyline fill="none" points="20,28 25,23 30,28" stroke="#000000" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/><line fill="none" stroke="#000000" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" x1="25" x2="25" y1="43" y2="23.333"/></svg>';
                                    $comment_form['comment_field'] .= '<p class="merchant-adv-reviews-upload-text">' . esc_html__( 'Drag and drop images here or click to upload', 'merchant' ) . '</p>';
									$comment_form['comment_field'] .= '<div class="merchant-adv-reviews-loader"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 512 512" aria-hidden="true" focusable="false"><path fill="#000" d="M288 39.056v16.659c0 10.804 7.281 20.159 17.686 23.066C383.204 100.434 440 171.518 440 256c0 101.689-82.295 184-184 184-101.689 0-184-82.295-184-184 0-84.47 56.786-155.564 134.312-177.219C216.719 75.874 224 66.517 224 55.712V39.064c0-15.709-14.834-27.153-30.046-23.234C86.603 43.482 7.394 141.206 8.003 257.332c.72 137.052 111.477 246.956 248.531 246.667C393.255 503.711 504 392.788 504 256c0-115.633-79.14-212.779-186.211-240.236C302.678 11.889 288 23.456 288 39.056z" /></svg>';
                                    $comment_form['comment_field'] .= '</div></div>';
                                    $comment_form['comment_field'] .= '<input id="merchant-adv-review-images-holder" type="hidden" name="review_images_ids" value="" />';
                                    $comment_form['comment_field'] .= '<div class="merchant-adv-reviews-upload-preview"></div>';
                                    $comment_form['comment_field'] .= '</div>';

									// Add nonce field for review images attachment.
									$comment_form['comment_field'] .= wp_nonce_field( 'merchant_adv_reviews_upload_images', 'reviews_images_nonce', true, false );
								}

							}

							comment_form(
							/**
							 * Hook: woocommerce_product_review_comment_form_args
							 *
							 * @since 1.0
							 *
							 */
								apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ),
								$product->get_id()
							);
							?>
						</div>
					</div>
				<?php else : ?>
					<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'merchant' ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>