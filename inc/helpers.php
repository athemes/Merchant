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
				),
				'clipPath' => array(
					'id'    => true,
					'class' => true,
					'style' => true,
				),
				'defs'     => array(
					'id' => true,
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
			$tags = array( 'form', 'input', 'select', 'option', 'textarea' );

			foreach ( $tags as $tag ) {
				$allowed_tags[ $tag ] = array(
					'id'                      => true,
					'class'                   => true,
					'style'                   => true,
					'name'                    => true,
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
		$product_categories = get_terms( array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
		) );
		$category_names     = array();
		if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
			foreach ( $product_categories as $category ) {
				$category_names[ $category->slug ] = $category->name;
			}
		}

		return $category_names;
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
 * Generate category choices for select2
 */
if ( ! function_exists( 'merchant_get_categories_select2_choices' ) ) {
	function merchant_get_category_select2_choices() {
		$choices = array();
		$cats    = merchant_get_product_categories();

		if ( is_array( $cats ) && ! empty( $cats ) ) {
			foreach ( $cats as $slug => $name ) {
				$choices[] = array(
					'id'   => esc_attr( $slug ),
					'text' => esc_html( $name ),
				);
			}
		}

		return $choices;
	}
}
