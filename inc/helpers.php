<?php

/**
 * Helper functions.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Allowed tags general.
 * Should be used to escape complex outputs like entire features html.
 *
 * @param array $extra
 * @param bool  $include_post_tags Whether to include post kses allowed tags or not. Default true.
 *
 * @return array $allowed_tags
 */
if ( ! function_exists( 'merchant_kses_allowed_tags' ) ) {
	function merchant_kses_allowed_tags( $extra = array(), $include_post_tags = true ) {
		// Default
		$allowed_tags = array_merge(
			array(
				// Meta
				'meta'     => array(
					'name'    => true,
					'content' => true,
				),

				// SVG Support
				'svg'      => array(
					'class'           => true,
					'xmlns'           => true,
					'width'           => true,
					'height'          => true,
					'viewbox'         => true,
					'aria-hidden'     => true,
					'role'            => true,
					'focusable'       => true,
					'fill'            => true,
					'stroke'          => true,
					'stroke-linecap'  => true,
					'stroke-linejoin' => true,
					'stroke-width'    => true,
				),
				'g'        => array(
					'id'        => true,
					'class'     => true,
					'clip-path' => true,
					'style'     => true,
					'transform' => true,
				),
				'path'     => array(
					'fill'            => true,
					'fill-rule'       => true,
					'd'               => true,
					'transform'       => true,
					'stroke'          => true,
					'stroke-width'    => true,
					'stroke-linejoin' => true,
					'clip-rule'       => true,
				),
				'polyline' => array(
					'points'          => true,
					'fill'            => true,
					'fill-rule'       => true,
					'd'               => true,
					'transform'       => true,
					'stroke'          => true,
					'stroke-width'    => true,
					'stroke-linejoin' => true,
				),
				'polygon'  => array(
					'fill'         => true,
					'fill-rule'    => true,
					'points'       => true,
					'transform'    => true,
					'focusable'    => true,
					'stroke'       => true,
					'stroke-width' => true,
				),
				'rect'     => array(
					'x'            => true,
					'y'            => true,
					'rx'           => true,
					'width'        => true,
					'height'       => true,
					'transform'    => true,
					'fill'         => true,
					'stroke'       => true,
					'stroke-width' => true,
				),
				'circle'   => array(
					'cx'           => true,
					'cy'           => true,
					'r'            => true,
					'width'        => true,
					'height'       => true,
					'transform'    => true,
					'fill'         => true,
					'stroke'       => true,
					'stroke-width' => true,
					'stroke-linecap'  => true,
					'stroke-linejoin' => true,
				),
				'line'   => array(
					'x1'           => true,
					'y1'           => true,
					'x2'           => true,
					'y2'           => true,
					'width'        => true,
					'height'       => true,
					'transform'    => true,
					'fill'         => true,
					'stroke'       => true,
					'stroke-width' => true,
					'stroke-linecap'  => true,
					'stroke-linejoin' => true,
				),
				'clipPath' => array(
					'id'    => true,
					'class' => true,
					'style' => true,
				),
				'defs'     => array(
					'id' => true,
				),
				'progress'     => array(
					'id'    => true,
					'class' => true,
					'value' => true,
					'max'   => true,
				),
			),
			$include_post_tags ? wp_kses_allowed_html( 'post' ) : array()
		);

		// Include schema markup tags
		if ( in_array( 'schema_markup', $extra, true ) || in_array( 'all', $extra, true ) ) {
			$tags = array( 'meta', 'nav', 'ul', 'li', 'a', 'span' );

			foreach ( $tags as $tag ) {
				if ( isset( $allowed_tags[ $tag ] ) ) {
					$allowed_tags[ $tag ]['itemprop']  = true;
					$allowed_tags[ $tag ]['itemscope'] = true;
					$allowed_tags[ $tag ]['itemtype']  = true;
				} else {
					$allowed_tags[ $tag ] = array(
						'itemprop'  => true,
						'itemscope' => true,
						'itemtype'  => true,
					);
				}
			}
		}

		// Include iframe tags
		if ( in_array( 'iframe', $extra, true ) || in_array( 'all', $extra, true ) ) {
			$allowed_tags['iframe'] = array(
				'src'             => true,
				'height'          => true,
				'width'           => true,
				'frameborder'     => true,
				'allowfullscreen' => true,
			);
		}

		if ( in_array( 'wc_email', $extra, true ) || in_array( 'all', $extra, true ) ) {
			$allowed_tags['body'] = array(
				'class' => true,
				'style' => true,
				'leftmargin' => true,
				'marginwidth' => true,
				'topmargin' => true,
				'marginheight' => true,
				'offset' => true,
			);

			$allowed_tags['html'] = array(
				'lang' => true,
			);

			$allowed_tags['style'] = array(
				'type' => true,
			);
			$allowed_tags['head'] = array();
			$allowed_tags['meta']['http-equiv'] = true;
		}

		// Include nonce tags
		if ( in_array( 'nonce', $extra, true ) || in_array( 'all', $extra, true ) ) {
			$allowed_tags['input'] = array(
				'type'      => true,
				'id'        => true,
				'name'      => true,
				'value'     => true,
				'data-name' => true,
			);
		}

		// Include bdi tag
		if ( in_array( 'bdi', $extra, true ) || in_array( 'all', $extra, true ) ) {
			$allowed_tags['bdi'] = array(
				'class' => true,
				'id'    => true,
				'style' => true,
			);
		}

		// Include select2
		if ( in_array( 'select2', $extra, true ) || in_array( 'all', $extra, true ) ) {
			$allowed_tags['select'] = array(
				'name'        => true,
				'class'       => true,
				'id'          => true,
				'style'       => true,
				'data-name'   => true,
				'data-source' => true,
				'multiple'    => true,
			);

			$allowed_tags['option'] = array(
				'value'    => true,
				'selected' => true,
			);

			$allowed_tags['optgroup'] = array(
				'label' => true,
			);
		}

		// Include dd, dt tags
		if ( in_array( 'dd', $extra, true ) || in_array( 'all', $extra, true ) ) {
			$allowed_tags['dd'] = array(
				'class' => true,
				'id'    => true,
				'style' => true,
			);

			$allowed_tags['dt'] = array(
				'class' => true,
				'id'    => true,
				'style' => true,
			);
		}

		// Include forms tags.
		if ( in_array( 'forms', $extra, true ) || in_array( 'all', $extra, true ) ) {
			$tags = array( 'form', 'input', 'select', 'option', 'textarea', 'a' );

			foreach ( $tags as $tag ) {
				$allowed_tags[ $tag ] = array(
					'id'                      => true,
					'class'                   => true,
					'style'                   => true,
					'name'                    => true,
					'href'                    => true,
					'target'                  => true,
					'value'                   => true,
					'type'                    => true,
					'placeholder'             => true,
					'data'                    => '*',
					'data-source'             => true,
					'data-product_id'         => true,
					'data-product_variations' => true,
					'data-attribute_name'     => true,
					'data-show_option_none'   => true,
					'data-name'               => true,
					'data-allowed-types'      => true,
					'step'                    => true,
					'min'                     => true,
					'max'                     => true,
					'selected'                => true,
					'checked'                 => true,
					'onchange'                => true,
					'autocomplete'            => true,
					'required'                => true,
					'action'                  => true,
					'method'                  => true,
					'enctype'                 => true,
					'size'                    => true,
					'role'                    => true,
					'inputmode'               => true,
					'aria-label'              => true,
					'multiple'                => true,
				);

				if ( $tag === 'a' ) {
					$allowed_tags[ $tag ]['href']   = true;
					$allowed_tags[ $tag ]['title']  = true;
					$allowed_tags[ $tag ]['target'] = true;
				}
			}
		}


		// Include script tag
		if ( in_array( 'script', $extra, true ) ) {
			$allowed_tags['script'] = array(
				'id'          => true,
				'src'         => true,
				'type'        => true,
				'async'       => true,
				'defer'       => true,
			);
		}

		if ( in_array( 'div', $extra, true ) ) {
			$tags = array( 'form', 'input', 'select', 'option', 'textarea', 'a', 'div' );

			foreach ( $tags as $tag ) {
				$allowed_tags[ $tag ] = array(
					'id'                      => true,
					'class'                   => true,
					'style'                   => true,
					'name'                    => true,
					'href'                    => true,
					'target'                  => true,
					'value'                   => true,
					'type'                    => true,
					'placeholder'             => true,
					'data'                    => '*',
					'data-source'             => true,
					'data-product_id'         => true,
					'data-product_variations' => true,
					'data-attribute_name'     => true,
					'data-show_option_none'   => true,
					'data-name'               => true,
					'data-allowed-types'      => true,
					'step'                    => true,
					'min'                     => true,
					'max'                     => true,
					'selected'                => true,
					'checked'                 => true,
					'onchange'                => true,
					'autocomplete'            => true,
					'required'                => true,
					'action'                  => true,
					'method'                  => true,
					'enctype'                 => true,
					'size'                    => true,
					'role'                    => true,
					'inputmode'               => true,
					'aria-label'              => true,
					'multiple'                => true,
					'title'                   => true,
					'data-id'                 => true,
					'data-product-id'         => true,
				);

				if ( $tag === 'a' ) {
					$allowed_tags[ $tag ]['href']   = true;
					$allowed_tags[ $tag ]['title']  = true;
					$allowed_tags[ $tag ]['target'] = true;
				}
			}
		}

		/**
		 * Filters the allowed tags.
		 *
		 * @since 1.2.5
		 */
		return apply_filters( 'merchant_kses_allowed_tags', $allowed_tags );
	}
}

/**
 * Allowed tags for scripts.
 *
 * @return array $allowed_tags
 */
if ( ! function_exists( 'merchant_kses_allowed_tags_for_code_snippets' ) ) {
	function merchant_kses_allowed_tags_for_code_snippets() {
		return array(
			'script' => array(
				'type' => true,
			),
		);
	}
}

/**
 * Check if WooCommerce checkout page is being rendered by block.
 * Since WooCommerce 8.3.0 the checkout page is rendered by block.
 *
 * @return bool
 */
if ( ! function_exists( 'merchant_is_checkout_block_layout' ) ) {
	function merchant_is_checkout_block_layout() {
		$checkout_page = wc_get_page_id( 'checkout' );

		if ( empty( $checkout_page ) ) {
			return false;
		}

		if ( function_exists( 'has_blocks' ) && has_blocks( $checkout_page ) ) {
			$post   = get_post( $checkout_page );
			$blocks = parse_blocks( $post->post_content );

			foreach ( $blocks as $block ) {
				if ( 'woocommerce/checkout' === $block['blockName'] ) {
					return true;
				}
			}
		}

		return false;
	}
}

/**
 * Check if WooCommerce cart page is being rendered by block.
 * Since WooCommerce 8.3.0 the cart page is rendered by block.
 *
 * @return bool
 */
if ( ! function_exists( 'merchant_is_cart_block_layout' ) ) {
	function merchant_is_cart_block_layout() {
		$cart_page = wc_get_page_id( 'cart' );

		if ( empty( $cart_page ) ) {
			return false;
		}

		if ( function_exists( 'has_blocks' ) && has_blocks( $cart_page ) ) {
			$post   = get_post( $cart_page );
			$blocks = parse_blocks( $post->post_content );

			foreach ( $blocks as $block ) {
				if ( 'woocommerce/cart' === $block['blockName'] ) {
					return true;
				}
			}
		}

		return false;
	}
}

/**
 * Get the product categories.
 */
if ( ! function_exists( 'merchant_get_product_categories' ) ) {
	function merchant_get_product_categories() {
		$args = array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
		);

		$categories = get_terms( $args );

		$categories_tree    = array();
		$indexed_categories = array();

		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			foreach ( $categories as $category ) {
				$indexed_categories[ $category->term_id ] = array(
					'id'       => $category->term_id,
					'slug'     => $category->slug,
					'name'     => $category->name,
					'parent'   => $category->parent,
					'children' => array(),
				);
			}

			foreach ( $indexed_categories as &$cat ) {
				if ( (int) $cat['parent'] === 0 ) {
					$categories_tree[] = &$cat;
				} else {
					$indexed_categories[ $cat['parent'] ]['children'][] = &$cat;
				}
			}
		}

		return $categories_tree;
	}
}

/**
 * Get the product tags.
 */
if ( ! function_exists( 'merchant_get_product_tags' ) ) {
	function merchant_get_product_tags() {
		$product_tags = get_terms( array(
			'taxonomy'   => 'product_tag',
			'hide_empty' => false,
		) );

		$tag_names = array();

		if ( ! empty( $product_tags ) && ! is_wp_error( $product_tags ) ) {
			foreach ( $product_tags as $tag ) {
				$tag_names[ $tag->slug ] = $tag->name;
			}
		}

		return $tag_names;
	}
}

/**
 * Convert array to css.
 */
if ( ! function_exists( 'merchant_array_to_css' ) ) {
	function merchant_array_to_css( $css_array ) {
		$css = '';
		if ( empty( $css_array ) ) {
			return $css;
		}
		foreach ( $css_array as $key => $value ) {
			$css .= $key . ':' . $value . ';';
		}

		return $css;
	}
}

/**
 * Get the share link data structure.
 * 
 * @return array 
 */
if ( ! function_exists( 'merchant_get_share_link_data' ) ) {
	function merchant_get_share_link_data() {
		return array(
			'facebook'  => array(
				'url'       => 'https://www.facebook.com/sharer.php?u={{url}}',
				'title'     => __( 'Facebook', 'merchant' ),
			),
			'twitter'   => array(
				'url'       => 'https://twitter.com/intent/tweet?url={{url}}&text={{title}}',
				'title'     => __( 'X', 'merchant' ),
			),
			'linkedin'  => array(
				'url'       => 'https://www.linkedin.com/sharing/share-offsite/?url={{url}}',
				'title'     => __( 'LinkedIn', 'merchant' ),
			),
			'reddit'    => array(
				'url'       => 'https://reddit.com/submit?url={{url}}&title={{title}}',
				'title'     => __( 'Reddit', 'merchant' ),
			),
			'whatsapp'  => array(
				'url'       => 'https://api.whatsapp.com/send/?text={{url}}',
				'title'     => __( 'WhatsApp', 'merchant' ),
			),
			'pinterest'     => array(
				'url'       => 'http://pinterest.com/pin/create/link/?url={{url}}',
				'title'     => __( 'Pinterest', 'merchant' ),
			),
			'telegram'  => array(
				'url'       => 'https://t.me/share/url?url={{url}}&text={{title}}',
				'title'     => __( 'Telegram', 'merchant' ),
			),
			'weibo'     => array(
				'url'       => 'http://service.weibo.com/share/share.php?url={{url}}&appkey=&title={{title}}&pic=&ralateUid=',
				'title'     => __( 'Weibo', 'merchant' ),
			),
			'vk'    => array(
				'url'       => 'http://vk.com/share.php?url={{url}}&title={{title}}&comment={text}',
				'title'     => __( 'VK', 'merchant' ),
			),
			'ok'    => array(
				'url'       => 'https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&st.shareUrl={{url}}',
				'title'     => __( 'OK', 'merchant' ),
			),      
			'xing'  => array(
				'url'       => 'https://www.xing.com/spi/shares/new?url={{url}}',
				'title'     => __( 'Xing', 'merchant' ),
			),      
			'mail'  => array(
				'url'       => 'mailto:?subject={{title}}&body={{url}}',
				'title'     => __( 'Mail', 'merchant' ),
			),
		);
	}
}

/**
 * Get the share link url.
 * 
 * @param string $social_network
 * 
 * @return string 
 */
if ( ! function_exists( 'merchant_get_share_link_url' ) ) {
	function merchant_get_share_link_url( $social_network, $url_to_share, $title_to_share = '' ) {
		$share_link_data = merchant_get_share_link_data();

		if ( ! isset( $share_link_data[ $social_network ] ) ) {
			return '';
		}

		$share_link_url = str_replace( 
			array( '{{url}}', '{{title}}' ),
			array( $url_to_share, $title_to_share ),
			$share_link_data[ $social_network ]['url']
		);

		return $share_link_url;
	}
}

/**
 * Get the share link title.
 * 
 * @param string $social_network
 * 
 * @return string
 */
if ( ! function_exists( 'merchant_get_share_link_title' ) ) {
	function merchant_get_share_link_title( $social_network ) {
		$share_link_data = merchant_get_share_link_data();

		return isset( $share_link_data[ $social_network ] ) ? $share_link_data[ $social_network ]['title'] : '';
	}
}

if ( ! function_exists( 'merchant_timezone' ) ) {
	/**
	 * Get the WP timezone.
	 *
	 * @return string
	 */
	function merchant_timezone() {
		/**
		 * Filter the storewide sale timezone.
		 *
		 * @param string $timezone
		 *
		 * @since 1.9.9
		 */
		return apply_filters(
			'merchant_storewide_sale_timezone',
			wp_timezone_string()
		);
	}
}

if ( ! function_exists( 'merchant_get_current_timestamp' ) ) {
	/**
	 * Get the current timestamp.
	 *
	 * @return int|string
	 */
	function merchant_get_current_timestamp() {
		$timezone = new DateTimeZone( merchant_timezone() );

		// Get the timestamp
		return ( new DateTime( 'now', $timezone ) )->getTimestamp();
	}
}

if ( ! function_exists( 'merchant_convert_date_to_timestamp' ) ) {
	/**
	 * Convert date to timestamp.
	 *
	 * @param string $date The date to convert
	 * @param string $format The format of the date
	 *
	 * @return int The timestamp
	 */
	function merchant_convert_date_to_timestamp( $date, $format = 'm-d-Y h:i A' ) {
		$timezone    = new DateTimeZone( merchant_timezone() );
		$date_object = DateTime::createFromFormat( $format, $date, $timezone ); // Create DateTime object with specified format and timezone
		if ( false === $date_object ) {
			return 0;
		}

		return $date_object->getTimestamp(); // Output the timestamp
	}
}

/**
 * Parses a list of product IDs
 * @return array
 */
if ( ! function_exists( 'merchant_parse_product_ids' ) ) {
	function merchant_parse_product_ids( $product_ids ) {
		$parsed_ids = $product_ids ?? array();

		// Convert to array if it's not already an array
		$parsed_ids = ! is_array( $parsed_ids ) ? explode( ',', $parsed_ids ) : $parsed_ids;

		// Convert all elements to integers
		$parsed_ids = array_map( 'intval', $parsed_ids );

		return $parsed_ids;
	}
}

/**
 * Check if the user condition is passed.
 *
 * @param $args
 *
 * @return bool
 */
if ( ! function_exists( 'merchant_is_user_condition_passed' ) ) {
	function merchant_is_user_condition_passed( $args = array() ) {
		$passed = false;

		$is_logged_in = is_user_logged_in();
		$current_user = $is_logged_in ? wp_get_current_user() : null;

		$condition = $args['user_condition'] ?? 'all';

		switch ( $condition ) {
			case 'all':
			case '':
				$passed = true;
				break;

			case 'logged-in':
				if ( $is_logged_in ) {
					$passed = true;
				}
				break;

			case 'roles':
				$roles = $args['user_condition_roles'] ?? array();
				$role  = $current_user->roles[0] ?? '';

				if ( in_array( $role, $roles, true ) ) {
					$passed = true;
				}
				break;

			case 'customers':
				$customers_id = $args['user_condition_users'] ?? array();
				$customers_id = array_map( 'intval', $customers_id );
				$customer_id  = (int) ( $current_user->ID ?? 0 );

				if ( in_array( $customer_id, $customers_id, true ) ) {
					$passed = true;
				}
				break;
		}

		return $passed;
	}
}

/**
 * Check if a product is excluded based on given arguments.
 *
 * @param $product_id
 * @param $args
 *
 * @return bool
 */
if ( ! function_exists( 'merchant_is_product_excluded' ) ) {
	function merchant_is_product_excluded( $product_id, $args = array() ) {
		if ( empty( $args['exclusion_enabled'] ) ) {
			return false;
		}

		$display_rule = $args['rules_to_display'] ?? $args['display_rules'] ?? $args['rules_to_apply'] ?? $args['trigger_on'] ?? 'products';

		$rules = array(
			'all',
			'all_products',
			'categories',
			'category',
			'by_category',
			'tags',
			'by_tags',
			'featured_products',
			'products_on_sale',
			'new_products',
			'out_of_stock',
			'pre-order',
		);

		$product     = wc_get_product( $product_id );
		$_product_id = $product && $product->is_type( 'variation' ) ? $product->get_parent_id() : $product_id;

		// Exclude products
		if ( in_array( $display_rule, $rules, true ) ) {
			$excluded_product_ids = $args['excluded_products'] ?? array();
			$excluded_product_ids = merchant_parse_product_ids( $excluded_product_ids );

			if ( in_array( (int) $product_id, $excluded_product_ids, true ) || in_array( (int) $_product_id, $excluded_product_ids, true ) ) {
				return true;
			}
		}

		// Exclude categories
		if ( in_array( $display_rule, array( 'all', 'all_products' ), true ) ) {
			$excluded_categories_slugs = $args['excluded_categories'] ?? array();

			if ( ! empty( $excluded_categories_slugs ) && has_term( $excluded_categories_slugs, 'product_cat', $_product_id ) ) {
				return true;
			}
		}

		// Exclude tags
		if ( in_array( $display_rule, array( 'all', 'all_products' ), true ) ) {
			$excluded_tags_slugs = $args['excluded_tags'] ?? array();

			if ( ! empty( $excluded_tags_slugs ) && has_term( $excluded_tags_slugs, 'product_tag', $_product_id ) ) {
				return true;
			}
		}

		return false;
	}
}

/**
 * Check if a product or any of its variations is excluded based on the given offer.
 *
 * This method determines if a product (simple or variable) or any of its variations
 * satisfies the merchant exclusion rules.
 *
 * @param int   $product_id The ID of the product to check.
 * @param array $offer      The offer data to evaluate against exclusion rules.
 *
 * @return bool True if the product or any of its variations is excluded, false otherwise.
 */
if ( ! function_exists( 'merchant_is_product_or_variation_excluded' ) ) {
	function merchant_is_product_or_variation_excluded( $product_id, $offer = array() ) {
		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			return false;
		}

		// Get IDs to check for exclusion
		$product_ids = $product->is_type( 'variable' ) ? $product->get_children() : array( $product_id );

		foreach ( $product_ids as $id ) {
			if ( merchant_is_product_excluded( $id, $offer ) ) {
				return true; // Excluded if any ID matches
			}
		}

		return false;
	}
}

/**
 * Get the label of the first active payment gateway in WooCommerce.
 *
 * @return string|null The label of the first active payment gateway, or null if none are found.
 */
if ( ! function_exists( 'merchant_get_first_active_payment_gateway_label' ) ) {
	function merchant_get_first_active_payment_gateway_label() {
		// Get the available payment gateways
		$available_gateways = WC()->payment_gateways->get_available_payment_gateways();

		// Check if there are any active gateways
		if ( ! empty( $available_gateways ) ) {
			// Get the first active gateway
			return reset( $available_gateways )->get_title();
		}

		// Return null if no active gateways are found
		return null;
	}
}

/**
 * Get the review count of a product.
 *
 * @param int $product_id The ID of the product to get the review count for.
 *
 * @return int The review count of the product.
 */
if ( ! function_exists( 'merchant_get_product_reviews_count' ) ) {
	function merchant_get_product_reviews_count( $product_id ) {
		$product = wc_get_product( $product_id );

		return $product ? $product->get_review_count() : 0;
	}
}