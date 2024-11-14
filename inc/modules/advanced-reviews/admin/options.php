<?php

/**
 * Advanced Reviews Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Settings
Merchant_Admin_Options::create(
	array(
		'title'  => __( 'Settings', 'merchant' ),
		'module' => Merchant_Advanced_Reviews::MODULE_ID,
		'fields' => array(

			// Title.
			array(
				'id'      => 'title',
				'type'    => 'text',
				'title'   => __( 'Title', 'merchant' ),
				'default' => __( 'What our customers are saying', 'merchant' ),
			),

			// Title HTML Tag.
			array(
				'id'      => 'title_tag',
				'type'    => 'select',
				'title'   => __( 'Title HTML tag', 'merchant' ),
				'options' => array(
					'h1'  => __( 'H1', 'merchant' ),
					'h2'  => __( 'H2', 'merchant' ),
					'h3'  => __( 'H3', 'merchant' ),
					'h4'  => __( 'H4', 'merchant' ),
					'h5'  => __( 'H5', 'merchant' ),
					'h6'  => __( 'H6', 'merchant' ),
					'div' => __( 'div', 'merchant' ),
				),
				'default' => 'h2',
			),

			// Hide Title.
			array(
				'id'      => 'hide_title',
				'type'    => 'switcher',
				'title'   => __( 'Hide title', 'merchant' ),
				'default' => 0,
			),

			// Description.
			array(
				'id'      => 'description',
				'type'    => 'textarea',
				'title'   => __( 'Description', 'merchant' ),
				'default' => '',
			),

			// Title and Description Alignment.
			array(
				'id'      => 'title_desc_align',
				'type'    => 'radio',
				'title'   => __( 'Title and description alignment', 'merchant' ),
				'options' => array(
					'left'   => __( 'Left', 'merchant' ),
					'center' => __( 'Center', 'merchant' ),
					'right'  => __( 'Right', 'merchant' ),
				),
				'default' => 'left',
			),

			// Default Reviews Sorting.
			array(
				'id'      => 'default_sorting',
				'type'    => 'select',
				'title'   => __( 'Default reviews sorting', 'merchant' ),
				'options' => array(
					'newest'      => __( 'Newest', 'merchant' ),
					'oldest'      => __( 'Oldest', 'merchant' ),
					'top-rated'   => __( 'Top rated', 'merchant' ),
					'low-rated'   => __( 'Low rated', 'merchant' ),
					'photo-first' => __( 'Photo first', 'merchant' ),
				),
				'default' => 'newest',
			),

			// Pagination Type.
			array(
				'id'      => 'pagination_type',
				'type'    => 'select',
				'title'   => __( 'Pagination type', 'merchant' ),
				'desc'    => sprintf(
				/* Translators: 1. Defualt WordPress discussion settings page. */
					__( 'This option works only if you have pagination for comments enabled. By default, WordPress doesn\'t have pagination enabled for comments/reviews. You can change it from: <a href="%1$s" target="_blank">Settings > Discusson</a>',
						'merchant' ),
					admin_url( 'options-discussion.php' )
				),
				'options' => array(
					'default'   => __( 'Default', 'merchant' ),
					'load-more' => __( 'Load more button', 'merchant' ),
				),
				'default' => 'load-more',
			),

			// Review options.
			array(
				'id'      => 'review_options',
				'type'    => 'radio',
				'title'   => __( 'Review options', 'merchant' ),
				'options' => array(
					'image_and_text' => __( 'Image and text', 'merchant' ),
					'image'          => __( 'Image only', 'merchant' ),
					'text'           => __( 'Text only', 'merchant' ),
				),
				'default' => 'image_and_text',
			),

			// Photos Upload limit.
			array(
				'id'      => 'photos_limit',
				'type'    => 'range',
				'title'   => __( 'Photos upload limit', 'merchant' ),
				'desc'    => __( 'Controls the photos upload limit for a review.', 'merchant' ),
				'min'     => 1,
				'max'     => '',
				'step'    => 1,
				'unit'    => '',
				'default' => 6,
			),

			// Review images carousel.
			array(
				'id'      => 'review_images_carousel',
				'type'    => 'switcher',
				'title'   => __( 'Review images carousel', 'merchant' ),
				'desc'    => __( 'Display the first image from each review in a carousel to engage shoppers and help their decision-making process.', 'merchant' ),
				'default' => 0,
			),

			// Number of Images per page.
			array(
				'id'        => 'review_images_carousel_total',
				'type'      => 'range',
				'title'     => __( 'Total images', 'merchant' ),
				'desc'      => __( 'Controls the total number of images to be displayed in the slider.', 'merchant' ),
				'min'       => 1,
				'step'      => 1,
				'unit'      => '',
				'default'   => 12,
				'condition' => array( 'review_images_carousel', '==', '1' ),
			),

			// Number of Columns.
			array(
				'id'        => 'review_images_carousel_per_page',
				'type'      => 'range',
				'title'     => __( 'Columns', 'merchant' ),
				'desc'      => __( 'Controls the number of images to be displayed per slide.', 'merchant' ),
				'min'       => 1,
				'max'       => 6,
				'step'      => 1,
				'unit'      => '',
				'default'   => 3,
				'condition' => array( 'review_images_carousel', '==', '1' ),
			),

			// Carousel Title.
			array(
				'id'      => 'carousel_title',
				'type'    => 'text',
				'title'   => __( 'Carousel Title', 'merchant' ),
				'default' => __( 'Reviews with Photos', 'merchant' ),
			),

			// Hook Order.
			array(
				'id'      => 'hook_order',
				'type'    => 'range',
				'title'   => __( 'Hook order', 'merchant' ),
				'desc'    => __( 'Controls the display position for the entire advanced reviews section. Lower values will move the section towards the top, while higher values will move the section towards the bottom.',
					'merchant' ),
				'min'     => 1,
				'max'     => 100,
				'step'    => 1,
				'unit'    => '',
				'default' => 10,
			),

			/**
			 * Styles
			 *
			 */

			// Title color.
			array(
				'id'      => 'title_color',
				'type'    => 'color',
				'title'   => __( 'Title color', 'merchant' ),
				'default' => '#212121',
			),

			// Description color.
			array(
				'id'      => 'description_color',
				'type'    => 'color',
				'title'   => __( 'Description color', 'merchant' ),
				'default' => '#777',
			),

			// Stars color.
			array(
				'id'      => 'stars_color',
				'type'    => 'color',
				'title'   => __( 'Stars color', 'merchant' ),
				'default' => '#FFA441',
			),

			// Stars background color.
			array(
				'id'      => 'stars_background_color',
				'type'    => 'color',
				'title'   => __( 'Stars background color', 'merchant' ),
				'default' => '#757575',
			),

			// Progress bar color.
			array(
				'id'      => 'progress_bar_color',
				'type'    => 'color',
				'title'   => __( 'Progress bar color', 'merchant' ),
				'default' => '#212121',
			),

			// Progress bar background color.
			array(
				'id'      => 'progress_bar_bg_color',
				'type'    => 'color',
				'title'   => __( 'Progress bar background color', 'merchant' ),
				'default' => '#F5F5F5',
			),

			// Dividers color.
			array(
				'id'      => 'dividers_color',
				'type'    => 'color',
				'title'   => __( 'Dividers color', 'merchant' ),
				'default' => '#e9e9e9',
			),

			// Button color.
			array(
				'id'      => 'button_color',
				'type'    => 'color',
				'title'   => __( 'Button color', 'merchant' ),
				'default' => '#FFF',
			),

			// Button color (hover).
			array(
				'id'      => 'button_color_hover',
				'type'    => 'color',
				'title'   => __( 'Button color (hover)', 'merchant' ),
				'default' => '#FFF',
			),

			// Button background color.
			array(
				'id'      => 'button_bg_color',
				'type'    => 'color',
				'title'   => __( 'Button background color', 'merchant' ),
				'default' => '#212121',
			),

			// Button background color (hover).
			array(
				'id'      => 'button_bg_color_hover',
				'type'    => 'color',
				'title'   => __( 'Button background color (hover)', 'merchant' ),
				'default' => '#757575',
			),

		),
	)
);


Merchant_Admin_Options::create(
	array(
		'title'  => __( 'Collect Reviews', 'merchant' ),
		'module' => Merchant_Advanced_Reviews::MODULE_ID,
		'fields' => array(
			array(
				'id'      => 'auto_emails_toggle',
				'type'    => 'checkbox',
				'label'   => esc_html__( 'Send automated emails for review requests', 'merchant' ),
				'default' => false,
			),
			array(
				'id'      => 'days_after_order_complete',
				'type'    => 'number',
				'title'   => esc_html__( 'After the order is completed, send the review request after the following number of days', 'merchant' ),
				'desc'    => esc_html__( 'The emails will be sent for orders that have been completed.', 'merchant' ),
				'default' => 7,
			),
			array(
				'id'      => 'discount_toggle',
				'type'    => 'switcher',
				'title'   => __( 'Discount for reviews', 'merchant' ),
				'desc'    => __( 'After a customer leaves a review on the store, an email containing a discount coupon will be sent to the email address associated with their order.', 'merchant' ),
				'default' => 0,
			),
			array(
				'id'         => 'require_photo_for_discount',
				'type'       => 'checkbox',
				'label'      => esc_html__( 'Offer a discount specifically for leaving a photo review.', 'merchant' ),
				'default'    => false,
				'conditions' => array(
					'terms' => array(
						array(
							'field'    => 'discount_toggle', // field ID
							'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
							'value'    => true, // can be a single value or an array of string/number/int
						),
					),
				),
			),
			array(
				'id'         => 'discount_generation_method',
				'type'       => 'radio',
				'title'      => esc_html__( 'Discount generation method', 'merchant' ),
				'options'    => array(
					'auto'   => esc_html__( 'Auto-generated code (different each time)', 'merchant' ),
					'manual' => esc_html__( 'Single discount code', 'merchant' ),
				),
				'default'    => 'auto',
				'conditions' => array(
					'terms' => array(
						array(
							'field'    => 'discount_toggle', // field ID
							'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
							'value'    => true, // can be a single value or an array of string/number/int
						),
					),
				),
			),
			array(
				'id'         => 'discount_info',
				'type'       => 'info',
				'content'    => esc_html__( 'A unique, single-use coupon code will be generated for each customer who leaves a review. This code can only be redeemed by the customer associated with the review.',
					'merchant' ),
				'conditions' => array(
					'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
					'terms'    => array(
						array(
							'field'    => 'discount_toggle', // field ID
							'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
							'value'    => true, // can be a single value or an array of string/number/int
						),
						array(
							'field'    => 'discount_generation_method', // field ID
							'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
							'value'    => 'auto', // can be a single value or an array of string/number/int
						),
					),
				),
			),
			array(
				'id'         => 'discount_type',
				'type'       => 'radio',
				'title'      => esc_html__( 'Discount', 'merchant' ),
				'options'    => array(
					'percent'    => esc_html__( 'Percentage', 'merchant' ),
					'fixed_cart' => esc_html__( 'Fixed', 'merchant' ),
				),
				'default'    => 'percent',
				'conditions' => array(
					'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
					'terms'    => array(
						array(
							'field'    => 'discount_toggle', // field ID
							'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
							'value'    => true, // can be a single value or an array of string/number/int
						),
						array(
							'field'    => 'discount_generation_method', // field ID
							'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
							'value'    => 'auto', // can be a single value or an array of string/number/int
						),
					),
				),
			),
			array(
				'id'         => 'discount_amount',
				'type'       => 'number',
				'step'       => 0.01,
				'default'    => 10,
				'conditions' => array(
					'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
					'terms'    => array(
						array(
							'field'    => 'discount_toggle', // field ID
							'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
							'value'    => true, // can be a single value or an array of string/number/int
						),
						array(
							'field'    => 'discount_generation_method', // field ID
							'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
							'value'    => 'auto', // can be a single value or an array of string/number/int
						),
					),
				),
			),
			array(
				'id'         => 'discount_expire_after',
				'type'       => 'select',
				'title'      => esc_html__( 'Discount expires after', 'merchant' ),
				'options'    => array(
					'never' => esc_html__( 'Never', 'merchant' ),
					'7'     => esc_html__( '7 days', 'merchant' ),
					'14'    => esc_html__( '14 days', 'merchant' ),
					'21'    => esc_html__( '21 days', 'merchant' ),
					'30'    => esc_html__( '30 days', 'merchant' ),
					'60'    => esc_html__( '60 days', 'merchant' ),
					'90'    => esc_html__( '90 days', 'merchant' ),
					'180'   => esc_html__( '180 days', 'merchant' ),
					'365'   => esc_html__( '365 days', 'merchant' ),
				),
				'conditions' => array(
					'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
					'terms'    => array(
						array(
							'field'    => 'discount_toggle', // field ID
							'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
							'value'    => true, // can be a single value or an array of string/number/int
						),
						array(
							'field'    => 'discount_generation_method', // field ID
							'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
							'value'    => 'auto', // can be a single value or an array of string/number/int
						),
					),
				),
			),
			array(
				'id'          => 'coupon_campaign_info',
				'type'        => 'info_block',
				'description' => esc_html__( 'You need to create the discount code in WooCommerce by navigating to Marketing > Coupons. The same discount code will be sent to all customers.',
					'merchant' ),
				'button_text' => esc_html__( 'Create discount code', 'merchant' ),
				'button_link' => admin_url( 'edit.php?post_type=shop_coupon' ),
				'conditions'  => array(
					'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
					'terms'    => array(
						array(
							'field'    => 'discount_toggle', // field ID
							'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
							'value'    => true, // can be a single value or an array of string/number/int
						),
						array(
							'field'    => 'discount_generation_method', // field ID
							'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
							'value'    => 'manual', // can be a single value or an array of string/number/int
						),
					),
				),
			),
			array(
				'id'         => 'manual_coupon_code',
				'type'       => 'wc_coupons',
				'title'      => esc_html__( 'Select manually created discount code', 'merchant' ),
				//'desc'        => esc_html__( 'The discount code must be created in WooCommerce to work and add here to validate.', 'merchant' ),
				'conditions' => array(
					'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
					'terms'    => array(
						array(
							'field'    => 'discount_toggle', // field ID
							'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
							'value'    => true, // can be a single value or an array of string/number/int
						),
						array(
							'field'    => 'discount_generation_method', // field ID
							'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
							'value'    => 'manual', // can be a single value or an array of string/number/int
						),
					),
				),
			),
		),
	)
);

Merchant_Admin_Options::create(
	array(
		'title'  => __( 'Collect Reviews', 'merchant' ),
		'module' => Merchant_Advanced_Reviews::MODULE_ID,
		'fields' => array(
			array(
				'id'             => 'review_request_email',
				'type'           => 'fields_group',
				'title'          => esc_html__( 'Review request', 'merchant' ),
				'sub-desc'       => esc_html__( 'Encourage your customers to leave a review with automated emails.', 'merchant' ),
				'state'          => 'open',
				'default'        => 'inactive',
				'accordion'      => true,
				'display_status' => true,
				'fields'         => array(
					array(
						'id'          => 'subject',
						'type'        => 'text',
						'title'       => __( 'Subject', 'merchant' ),
						'default'     => __( 'How did we do?', 'merchant' ),
						'desc'        => __( 'Be concise, avoid CAPS and !s, and use emojis sparingly.', 'merchant' ),
						'hidden_desc' => sprintf(
						/* Translators: %1$s: Discount percentage, %2$s: Discount amount, %3$s: In Stock, %4$s: Total quantity */
							__(
								'<strong>%1$s:</strong> displays the customer’s first name<br><strong>%2$s:</strong> displays the customer’s last name<br><strong>%3$s:</strong> displays the customer’s order id<br><strong>%4$s</strong> and <strong>%5$s:</strong> These tags are used together to create a clickable link that directs the customer to their order page, where they can submit a review.<br><br><strong>Example usage:</strong><br>Please click {order_url_open}here{order_url_close} to leave a review for your recent order.',
								'merchant'
							),
							'{first_name}',
							'{last_name}',
							'{order_id}',
							'{order_url_open}',
							'{order_url_close}'
						),
					),
					array(
						'id'          => 'body',
						'type'        => 'textarea_multiline',
						'title'       => __( 'Body', 'merchant' ),
						'default'     => __( 'Hello {first_name},

We hope you’re enjoying your recent purchase from us. 

We would be grateful if you could take a moment to share your experience by leaving a review.

Your feedback helps us improve and provides valuable insights for other customers. Please click {order_url_open}here{order_url_close} to submit your review.

Best regards,', 'merchant' ),
						'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
						'hidden_desc' => sprintf(
						/* Translators: %1$s: Discount percentage, %2$s: Discount amount, %3$s: In Stock, %4$s: Total quantity */
							__(
								'<strong>%1$s:</strong> displays the customer’s first name<br><strong>%2$s:</strong> displays the customer’s last name<br><strong>%3$s:</strong> displays the customer’s order id<br><strong>%4$s</strong> and <strong>%5$s:</strong> These tags are used together to create a clickable link that directs the customer to their order page, where they can submit a review.<br><br><strong>Example usage:</strong><br>Please click {order_url_open}here{order_url_close} to leave a review for your recent order.',
								'merchant'
							),
							'{first_name}',
							'{last_name}',
							'{order_id}',
							'{order_url_open}',
							'{order_url_close}'
						),
					),
					//                  array(
					//                      'id'      => 'review_btn_type',
					//                      'type'    => 'select',
					//                      'title'   => esc_html__( 'Review request button type', 'merchant' ),
					//                      'options' => array(
					//                          'rating_stars'  => esc_html__( 'Rating stars', 'merchant' ),
					//                          'custom_button' => esc_html__( 'Custom Button', 'merchant' ),
					//                      ),
					//                      'default' => 'custom_button',
					//                  ),
					//                  array(
					//                      'id'         => 'review_btn_text',
					//                      'type'       => 'text',
					//                      'title'      => __( 'Button text', 'merchant' ),
					//                      'default'    => __( 'Write a review', 'merchant' ),
					//                      'conditions' => array(
					//                          'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
					//                          'terms'    => array(
					//                              array(
					//                                  'field'    => 'review_btn_type', // field ID
					//                                  'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
					//                                  'value'    => 'custom_button', // can be a single value or an array of string/number/int
					//                              ),
					//                          ),
					//                      ),
					//                  ),
					array(
						'id'      => 'preview-info',
						'type'    => 'info',
						'content' => sprintf(
						/* Translators: 1. docs link */
							__( 'Click <a href="%1$s" target="_blank">here</a> to preview the email and see how it will look.', 'merchant' ),
							esc_url(
								add_query_arg(
									array(
										'action' => 'merchant_pro_preview_request_review_email',
										'nonce'  => wp_create_nonce( 'merchant_pro_advanced_reviews_mailer_preview' ),
									),
									admin_url( 'admin-post.php' )
								)
							)
						),
					),

				),
			),
			array(
				'id'             => 'review_request_reminder_email',
				'type'           => 'fields_group',
				'title'          => esc_html__( 'Review request reminder', 'merchant' ),
				'sub-desc'       => esc_html__( 'Send a second review request to customers who did not submit a review yet.', 'merchant' ),
				'state'          => 'closed',
				'default'        => 'inactive',
				'accordion'      => true,
				'display_status' => true,
				'fields'         => array(
					array(
						'id'      => 'days_after_first_email',
						'type'    => 'number',
						'title'   => esc_html__( 'After the first email, send the review request reminder after the following number of days', 'merchant' ),
						'default' => 7,
					),
					array(
						'id'          => 'subject',
						'type'        => 'text',
						'title'       => __( 'Subject', 'merchant' ),
						'default'     => __( 'We value your feedback', 'merchant' ),
						'desc'        => __( 'Be concise, avoid CAPS and !s, and use emojis sparingly.', 'merchant' ),
						'hidden_desc' => sprintf(
						/* Translators: %1$s: Discount percentage, %2$s: Discount amount, %3$s: In Stock, %4$s: Total quantity */
							__(
								'<strong>%1$s:</strong> displays the customer’s first name<br><strong>%2$s:</strong> displays the customer’s last name<br><strong>%3$s:</strong> displays the customer’s order id<br><strong>%4$s</strong> and <strong>%5$s:</strong> These tags are used together to create a clickable link that directs the customer to their order page, where they can submit a review.<br><br><strong>Example usage:</strong><br>Please click {order_url_open}here{order_url_close} to leave a review for your recent order.',
								'merchant'
							),
							'{first_name}',
							'{last_name}',
							'{order_id}',
							'{order_url_open}',
							'{order_url_close}'
						),
					),
					array(
						'id'          => 'body',
						'type'        => 'textarea_multiline',
						'title'       => __( 'Body', 'merchant' ),
						'default'     => __( 'Hello {first_name},

We hope you’re enjoying your recent purchase from us. 

We would be grateful if you could take a moment to share your experience by leaving a review.

Your feedback helps us improve and provides valuable insights for other customers. Please click {order_url_open}here{order_url_close} to submit your review.

Best regards,', 'merchant' ),
						'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
						'hidden_desc' => sprintf(
						/* Translators: %1$s: Discount percentage, %2$s: Discount amount, %3$s: In Stock, %4$s: Total quantity */
							__(
								'<strong>%1$s:</strong> displays the customer’s first name<br><strong>%2$s:</strong> displays the customer’s last name<br><strong>%3$s:</strong> displays the customer’s order id<br><strong>%4$s</strong> and <strong>%5$s:</strong> These tags are used together to create a clickable link that directs the customer to their order page, where they can submit a review.<br><br><strong>Example usage:</strong><br>Please click {order_url_open}here{order_url_close} to leave a review for your recent order.',
								'merchant'
							),
							'{first_name}',
							'{last_name}',
							'{order_id}',
							'{order_url_open}',
							'{order_url_close}'
						),
					),
					//                  array(
					//                      'id'      => 'review_btn_type',
					//                      'type'    => 'select',
					//                      'title'   => esc_html__( 'Review request button type', 'merchant' ),
					//                      'options' => array(
					//                          'rating_stars'  => esc_html__( 'Rating stars', 'merchant' ),
					//                          'custom_button' => esc_html__( 'Custom Button', 'merchant' ),
					//                      ),
					//                      'default' => 'custom_button',
					//                  ),
					//                  array(
					//                      'id'         => 'review_btn_text',
					//                      'type'       => 'text',
					//                      'title'      => __( 'Button text', 'merchant' ),
					//                      'default'    => __( 'Write a review', 'merchant' ),
					//                      'conditions' => array(
					//                          'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
					//                          'terms'    => array(
					//                              array(
					//                                  'field'    => 'review_btn_type', // field ID
					//                                  'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
					//                                  'value'    => 'custom_button', // can be a single value or an array of string/number/int
					//                              ),
					//                          ),
					//                      ),
					//                  ),
					array(
						'id'      => 'preview-info',
						'type'    => 'info',
						'content' => sprintf(
						/* Translators: 1. docs link */
							__( 'Click <a href="%1$s" target="_blank">here</a> to preview the email and see how it will look.', 'merchant' ),
							esc_url(
								add_query_arg(
									array(
										'action' => 'merchant_pro_preview_request_review_reminder_email',
										'nonce'  => wp_create_nonce( 'merchant_pro_advanced_reviews_mailer_preview' ),
									),
									admin_url( 'admin-post.php' )
								)
							)
						),
					),

				),
			),
			array(
				'id'             => 'discount_for_review_email',
				'type'           => 'fields_group',
				'title'          => esc_html__( 'Discount for review', 'merchant' ),
				'sub-desc'       => esc_html__( 'Send customers a next-purchase code after submitting a review.', 'merchant' ),
				'state'          => 'closed',
				'default'        => 'inactive',
				'accordion'      => true,
				'display_status' => true,
				'fields'         => array(
					array(
						'id'          => 'subject',
						'type'        => 'text',
						'title'       => __( 'Subject', 'merchant' ),
						'default'     => __( 'Your discount code is inside', 'merchant' ),
						'desc'        => __( 'Be concise, avoid CAPS and !s, and use emojis sparingly.', 'merchant' ),
						'hidden_desc' => sprintf(
						/* Translators: %1$s: Discount percentage, %2$s: Discount amount, %3$s: In Stock, %4$s: Total quantity */
							__(
								'<strong>%1$s:</strong> displays the customer’s first name<br><strong>%2$s:</strong> displays the customer’s last name<br><strong>%3$s:</strong> displays discount code',
								'merchant'
							),
							'{first_name}',
							'{last_name}',
							'{discount_code}'
						),
					),
					array(
						'id'          => 'body',
						'type'        => 'textarea_multiline',
						'title'       => __( 'Body', 'merchant' ),
						'default'     => __( 'Hello {first_name}, We would like to let you know that you are eligible to receive a discount on your next purchase. Here is your discount code: {discount_code}.', 'merchant' ),
						'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
						'hidden_desc' => sprintf(
						/* Translators: %1$s: Discount percentage, %2$s: Discount amount, %3$s: In Stock, %4$s: Total quantity */
							__(
								'<strong>%1$s:</strong> displays the customer’s first name<br><strong>%2$s:</strong> displays the customer’s last name<br><strong>%3$s:</strong> displays discount code',
								'merchant'
							),
							'{first_name}',
							'{last_name}',
							'{discount_code}'
						),
					),
					array(
						'id'      => 'preview-info',
						'type'    => 'info',
						'content' => sprintf(
						/* Translators: 1. docs link */
							__( 'Click <a href="%1$s" target="_blank">here</a> to preview the email and see how it will look.', 'merchant' ),
							esc_url(
								add_query_arg(
									array(
										'action' => 'merchant_pro_preview_discount_for_review_email',
										'nonce'  => wp_create_nonce( 'merchant_pro_advanced_reviews_mailer_preview' ),
									),
									admin_url( 'admin-post.php' )
								)
							)
						),
					),

				),
			),
			array(
				'id'             => 'discount_for_review_reminder_email',
				'type'           => 'fields_group',
				'title'          => esc_html__( 'Review discount reminder', 'merchant' ),
				'sub-desc'       => esc_html__( 'Remind your customers to use their next-purchase discount if they haven\'t used it yet.', 'merchant' ),
				'state'          => 'closed',
				'default'        => 'inactive',
				'accordion'      => true,
				'display_status' => true,
				'fields'         => array(
					array(
						'id'      => 'days_after_first_email',
						'type'    => 'number',
						'title'   => esc_html__( 'After the first email, send the discount reminder after the following number of days', 'merchant' ),
						'default' => 7,
					),
					array(
						'id'          => 'subject',
						'type'        => 'text',
						'title'       => __( 'Subject', 'merchant' ),
						'default'     => __( 'A reminder to to use your discount', 'merchant' ),
						'desc'        => __( 'Be concise, avoid CAPS and !s, and use emojis sparingly.', 'merchant' ),
						'hidden_desc' => sprintf(
						/* Translators: %1$s: Discount percentage, %2$s: Discount amount, %3$s: In Stock, %4$s: Total quantity */
							__(
								'<strong>%1$s:</strong> displays the customer’s first name<br><strong>%2$s:</strong> displays the customer’s last name<br><strong>%3$s:</strong> displays discount code',
								'merchant'
							),
							'{first_name}',
							'{last_name}',
							'{discount_code}'
						),
					),
					array(
						'id'          => 'body',
						'type'        => 'textarea_multiline',
						'title'       => __( 'Body', 'merchant' ),
						'default'     => __( 'Hello {first_name},
This is a reminder to let you know that you are eligible to receive a discount on your next purchase. Here is your discount code: {discount_code}', 'merchant' ),
						'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
						'hidden_desc' => sprintf(
						/* Translators: %1$s: Discount percentage, %2$s: Discount amount, %3$s: In Stock, %4$s: Total quantity */
							__(
								'<strong>%1$s:</strong> to show customer first name<br><strong>%2$s:</strong> to show customer last name<br><strong>%3$s:</strong> to show the discount code',
								'merchant'
							),
							'{first_name}',
							'{last_name}',
							'{discount_code}'
						),
					),
					array(
						'id'      => 'preview-info',
						'type'    => 'info',
						'content' => sprintf(
						/* Translators: 1. docs link */
							__( 'Click <a href="%1$s" target="_blank">here</a> to preview the email and see how it will look.', 'merchant' ),
							esc_url(
								add_query_arg(
									array(
										'action' => 'merchant_pro_preview_discount_for_review_reminder_email',
										'nonce'  => wp_create_nonce( 'merchant_pro_advanced_reviews_mailer_preview' ),
									),
									admin_url( 'admin-post.php' )
								)
							)
						),
					),

				),
			),
			array(
				'id'          => 'sender_name',
				'type'        => 'text',
				'title'       => esc_html__( 'Sender name', 'merchant' ),
				'desc'        => esc_html__( 'Will be displayed in the inbox, in the "From" field.', 'merchant' ),
				'placeholder' => get_bloginfo( 'name' ),
				'default'     => get_bloginfo( 'name' ),
			),
			array(
				'id'          => 'sender_email',
				'type'        => 'text',
				'title'       => esc_html__( 'Sender email', 'merchant' ),
				'desc'        => esc_html__( 'We recommend using your customer support email so replies from customers are sent to that address.', 'merchant' ),
				'placeholder' => get_bloginfo( 'admin_email' ),
				'default'     => get_bloginfo( 'admin_email' ),
			),
		),
	)
);

// Modal Settings
Merchant_Admin_Options::create(
	array(
		'title'  => __( 'Modal Settings', 'merchant' ),
		'module' => Merchant_Advanced_Reviews::MODULE_ID,
		'fields' => array(

			/**
			 * Styles
			 *
			 */

			// Modal Close icon color.
			array(
				'id'      => 'modal_close_icon_color',
				'type'    => 'color',
				'title'   => __( 'Close icon color', 'merchant' ),
				'default' => '#757575',
			),

			// Modal Close icon color (hover).
			array(
				'id'      => 'modal_close_icon_color_hover',
				'type'    => 'color',
				'title'   => __( 'Close icon color (hover)', 'merchant' ),
				'default' => '#212121',
			),

			// Modal Title color.
			array(
				'id'      => 'modal_title_color',
				'type'    => 'color',
				'title'   => __( 'Title color', 'merchant' ),
				'default' => '#212121',
			),

			// Modal Description color.
			array(
				'id'      => 'modal_description_color',
				'type'    => 'color',
				'title'   => __( 'Description color', 'merchant' ),
				'default' => '#777',
			),

			// Modal Textarea color.
			array(
				'id'      => 'modal_textarea_color',
				'type'    => 'color',
				'title'   => __( 'Textarea color', 'merchant' ),
				'default' => '#777',
			),

			// Modal Textarea background color.
			array(
				'id'      => 'modal_textarea_background_color',
				'type'    => 'color',
				'title'   => __( 'Textarea background color', 'merchant' ),
				'default' => '#FFF',
			),

			// Modal background color.
			array(
				'id'      => 'modal_background_color',
				'type'    => 'color',
				'title'   => __( 'Modal background color', 'merchant' ),
				'default' => '#F5F5F5',
			),

		),
	)
);

// Shortcode
$merchant_module_id = Merchant_Advanced_Reviews::MODULE_ID;
Merchant_Admin_Options::create(
	array(
		'module' => $merchant_module_id,
		'title'  => esc_html__( 'Use shortcode', 'merchant' ),
		'fields' => array(
			array(
				'id'      => 'use_shortcode',
				'type'    => 'switcher',
				'title'   => __( 'Use shortcode', 'merchant' ),
				'default' => 0,
			),
			array(
				'type'    => 'info',
				'id'      => 'shortcode_info',
				'content' => esc_html__( 'If you are using a page builder or a theme that supports shortcodes, then you can output the module using the shortcode above. This might be useful if, for example, you find that you want to control the position of the module output more precisely than with the module settings. Note that the shortcodes can only be used on single product pages.',
					'merchant' ),
			),
			array(
				'id'        => 'shortcode_text',
				'type'      => 'text_readonly',
				'title'     => esc_html__( 'Shortcode text', 'merchant' ),
				'default'   => '[merchant_module_' . str_replace( '-', '_', $merchant_module_id ) . ']',
				'condition' => array( 'use_shortcode', '==', '1' ),
			),
		),
	)
);
