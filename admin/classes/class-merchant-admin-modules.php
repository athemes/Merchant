<?php
/**
 * Merchant_Admin_Modules Class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Merchant_Admin_Modules' ) ) {

	class Merchant_Admin_Modules {

		/**
		 * The single class instance.
		 */
		private static $instance = null;

		/**
		 * Instance.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor.
		 */
		public function __construct() {}

		/**
		 * Get modules.
		 */
		public static function get_modules() {

			// Set specific preview URLs
			$default_url  = site_url( '/' );
			$shop_url     = $default_url;
			$checkout_url = $default_url;
			$product_url  = $default_url;

			// Set shop url
			if ( function_exists( 'wc_get_page_id' ) ) {
				$shop_url = get_permalink( wc_get_page_id( 'shop' ) );
			} 

			// Set checkout url
			if ( function_exists( 'wc_get_page_id' ) ) {
				$checkout_url = get_permalink( wc_get_page_id( 'checkout' ) );
			}

			// Set single product url
			if ( function_exists( 'wc_get_products' ) ) {
				$products = wc_get_products( array( 'limit' => 1 ) );
				if ( ! empty( $products ) && ! empty( $products[0] ) ) {
					$product_url = get_permalink( $products[0]->get_id() );
				}
			}

			$modules = array(

				'convert-more' => array(
					'title' => esc_html__( 'Convert More', 'merchant' ),
					'modules' => array(

						'quick-view' => array(
							'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="16" viewBox="0 0 20 16"><path d="M12.5 8c0 1.654-1.122 3-2.5 3S7.5 9.654 7.5 8 8.622 5 10 5s2.5 1.346 2.5 3Zm7.5-.449S16.457 16 10.012 16C4.03 16 0 7.551 0 7.551S3.705 0 10.012 0C16.424 0 20 7.551 20 7.551ZM14.167 8c0-2.757-1.87-5-4.167-5-2.298 0-4.167 2.243-4.167 5s1.87 5 4.167 5c2.297 0 4.167-2.243 4.167-5Z"/></svg>',
							'title' => esc_html__( 'Quick View', 'merchant' ),
							'desc' => esc_html__( 'Let customers instantly preview product details without leaving the page they are on.', 'merchant' ),
							'placeholder' => MERCHANT_URI . 'assets/images/modules/quick-view.png',
							'tutorial_url' => 'https://docs.athemes.com/article/quick-view/',
							'preview_url' => $shop_url,
						),

						'product-labels' => array(
							'icon'  => '<svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" clip-rule="evenodd" d="M11.114 0h7.775c.295 0 .577.111.786.31.208.197.325.465.325.745v7.387c0 .139-.027.276-.082.405a1.052 1.052 0 0 1-.24.344l-.322.306h-2.688c-1.768 0-3.463.667-4.713 1.854-1.25 1.188-1.952 2.798-1.952 4.477v2.554l-.322.306a1.115 1.115 0 0 1-.361.23 1.161 1.161 0 0 1-1.216-.23L.329 11.3a1.054 1.054 0 0 1-.244-.343A1.01 1.01 0 0 1 .33 9.803L10.325.306c.104-.098.227-.175.363-.228.135-.052.28-.079.426-.078Zm4.073 5.01c.274.173.596.266.925.266.442 0 .866-.167 1.179-.464.312-.296.488-.699.488-1.119 0-.313-.098-.619-.281-.88a1.65 1.65 0 0 0-.748-.582 1.748 1.748 0 0 0-.963-.09c-.323.06-.62.212-.853.433a1.56 1.56 0 0 0-.456.81 1.51 1.51 0 0 0 .095.915c.126.29.34.536.614.71Z"/><path fill-rule="evenodd" clip-rule="evenodd" d="M17.193 11.75c0-.414-.393-.75-.877-.75-.485 0-.877.336-.877.75v.128c-1.023.309-1.755 1.142-1.755 2.122 0 .76.316 1.327.9 1.702.473.304 1.065.43 1.461.513l.058.013c.483.103.732.168.893.27.074.048.197.137.197.502 0 .41-.385.743-.863.75a1.623 1.623 0 0 1-.285-.055 2.306 2.306 0 0 1-.863-.475.986.986 0 0 0-1.24 0 .676.676 0 0 0 0 1.06 4.132 4.132 0 0 0 1.497.823v.147c0 .414.392.75.877.75.484 0 .877-.336.877-.75v-.128c1.022-.309 1.754-1.142 1.754-2.122 0-.76-.315-1.327-.899-1.702-.474-.303-1.065-.43-1.461-.513l-.058-.013c-.483-.103-.733-.168-.893-.27-.074-.048-.197-.137-.197-.502 0-.41.384-.743.862-.75l.042.004c.05.006.134.02.244.051.215.062.532.192.863.475a.985.985 0 0 0 1.24 0 .676.676 0 0 0 0-1.06 4.131 4.131 0 0 0-1.497-.823v-.147Z"/></svg>',
							'title' => esc_html__( 'Product Labels', 'merchant' ),
							'desc'  => esc_html__( 'Add customizable labels on top of your product images to promote special deals or discounts, highlight new products, or anything else you want.', 'merchant' ),
							'placeholder' => MERCHANT_URI . 'assets/images/modules/product-labels.png',
							'tutorial_url' => 'https://docs.athemes.com/article/product-labels/',
							'preview_url' => $shop_url,
						),

					),
				),

				'build-trust' => array(
					'title' => esc_html__( 'Build Trust', 'merchant' ),
					'modules' => array(

						'payment-logos' => array(
							'icon' => '<svg width="20" height="14" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 14"><path d="M0 1.5A1.5 1.5 0 0 1 1.5 0h17A1.5 1.5 0 0 1 20 1.5V3H0V1.5Z"/><path fill-rule="evenodd" clip-rule="evenodd" d="M0 5v7.5A1.5 1.5 0 0 0 1.5 14h17a1.5 1.5 0 0 0 1.5-1.5V5H0Zm7 4H2V7h5v2Z"/></svg>',
							'title' => esc_html__( 'Payment Logos', 'merchant' ),
							'desc' => esc_html__( 'Let visitors know what payment methods you accept by displaying the logos of credit card and payment processor companies.', 'merchant' ),
							'placeholder' => MERCHANT_URI . 'assets/images/modules/payment-logos.png',
							'tutorial_url' => 'https://docs.athemes.com/article/payment-logos/',
							'preview_url' => $product_url,
						),

						'trust-badges' => array(
							'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M1.493 2.879C3.757 2.562 6.757 1.616 9.128.233a1.733 1.733 0 0 1 1.728-.004c2.369 1.354 5.502 2.29 7.65 2.628.818.128 1.491.81 1.491 1.638v.501c.031 6.043-.48 11.332-9.472 14.903a1.45 1.45 0 0 1-1.062 0C.478 16.328-.029 11.04.001 4.996L0 4.499c-.002-.83.672-1.505 1.493-1.62Zm9.214 6.414a1 1 0 1 0-1.414 1.414 1 1 0 0 0 1.414-1.414Zm-4 0a1 1 0 1 0-1.414 1.414 1 1 0 0 0 1.414-1.414Zm8 0a1 1 0 1 0-1.414 1.414 1 1 0 0 0 1.414-1.414Z" clip-rule="evenodd"/></svg>',
							'title' => esc_html__( 'Trust Badges', 'merchant' ),
							'desc' => esc_html__( 'Reassure customers with badges that showcase the benefits of shopping with your store, e.g. ‘easy returns’ or ‘30 day money back guarantee’.', 'merchant' ),
							'placeholder' => MERCHANT_URI . 'assets/images/modules/trust-badges.png',
							'tutorial_url' => 'https://docs.athemes.com/article/trust-badges/',
							'preview_url' => $product_url,
						),

					),
				),

				'boost-revenue' => array(
					'title' => esc_html__( 'Boost Revenue', 'merchant' ),
					'modules' => array(

						'pre-orders' => array(
							'icon' => '<svg width="18" height="17" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 17"><path d="m8.69 3.772-.243.123a1 1 0 1 1-.895-1.79l2-1a.998.998 0 0 1 1.434 1.06l-1 6a1 1 0 0 1-1.973-.33l.677-4.063ZM3.617 2.924a.997.997 0 0 1-.324-.217l-1-1A1 1 0 0 1 3.707.293l1 1a.999.999 0 0 1-1.09 1.631ZM14.383 2.924a.997.997 0 0 1-.94-.092 1 1 0 0 1-.15-1.54l1-1a1 1 0 1 1 1.414 1.415l-1 1a.996.996 0 0 1-.324.217ZM14.293 6.707A1 1 0 0 1 15 5h2a1 1 0 1 1 0 2h-2a1 1 0 0 1-.707-.293ZM3 7H1a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2ZM0 15.5V10h2v2h3.5c.775 0 1.388.662 1.926 1.244l.11.12c.366.391.886.636 1.464.636s1.098-.245 1.463-.637l.11-.119C11.114 12.663 11.726 12 12.5 12H16v-2h2v5.5a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 0 15.5Z"/></svg>',
							'title' => esc_html__( 'Pre-Orders', 'merchant' ),
							'desc' => esc_html__( 'Allow visitors to pre-order products that are either out of stock or not yet released.', 'merchant' ),
							'placeholder' => MERCHANT_URI . 'assets/images/modules/pre-orders.png',
							'tutorial_url' => 'https://docs.athemes.com/article/pre-orders/',
							'preview_url' => $shop_url,
						),

					),
				),

				'reduce-abandonment' => array(
					'title' => esc_html__( 'Reduce Abandonment', 'merchant' ),
					'modules' => array(

						'cart-count-favicon' => array(
							'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="20" viewBox="0 0 16 20"><path fill-rule="evenodd" d="M15 0c.27 0 .52.11.71.29.18.19.29.44.29.71v10c0 .27-.11.52-.29.71-.19.18-.44.29-.71.29H1c-.27 0-.52-.11-.71-.29C.11 11.52 0 11.27 0 11V1C0 .73.11.48.29.29.48.11.73 0 1 0h14Zm-3.41 5.168L14 7V2H2v5l1.48-.892c.32-.16.7-.14 1 .06L7 8l3.44-2.802c.33-.25.8-.27 1.15-.03Z" clip-rule="evenodd"/><path d="M.29 14.29C.48 14.11.73 14 1 14c.27 0 .52.11.71.29.18.19.29.44.29.71v4c0 .27-.11.52-.29.71-.19.18-.44.29-.71.29-.27 0-.52-.11-.71-.29C.11 19.52 0 19.27 0 19v-4c0-.27.11-.52.29-.71ZM7 16c.27 0 .52-.11.71-.29.18-.19.29-.44.29-.71 0-.27-.11-.52-.29-.71-.19-.18-.44-.29-.71-.29-1.65 0-3 1.35-3 3s1.35 3 3 3c.27 0 .52-.11.71-.29.18-.19.29-.44.29-.71 0-.27-.11-.52-.29-.71-.19-.18-.44-.29-.71-.29a.982.982 0 0 1-.68-.31.976.976 0 0 1-.28-.69c0-.26.1-.51.28-.69.18-.19.42-.3.68-.31Z"/><path fill-rule="evenodd" d="M13 14c-1.65 0-3 1.35-3 3s1.35 3 3 3 3-1.35 3-3-1.35-3-3-3Zm.68 3.69c-.18.19-.42.3-.68.31a.982.982 0 0 1-.68-.31.976.976 0 0 1-.28-.69c0-.26.1-.51.28-.69.18-.19.42-.3.68-.31.26.01.5.12.68.31.18.18.28.43.28.69 0 .26-.1.51-.28.69Z" clip-rule="evenodd"/></svg>',
							'title' => esc_html__( 'Cart Count Favicon', 'merchant' ),
							'desc' => esc_html__( 'Make your store’s browser tab stand out by showing the number of items in the cart on the favicon.', 'merchant' ),
							'placeholder' => MERCHANT_URI . 'assets/images/modules/cart-count-favicon.png',
							'tutorial_url' => 'https://docs.athemes.com/article/cart-count-favicon/',
							'preview_url' => $shop_url,
						),

						'inactive-tab-message' => array(
							'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path d="M0 1.5A1.5 1.5 0 0 1 1.5 0h17A1.5 1.5 0 0 1 20 1.5v6A1.5 1.5 0 0 1 18.5 9h-5.889a1.5 1.5 0 0 1-1.5-1.5V5.111a1.111 1.111 0 1 0-2.222 0V7.5a1.5 1.5 0 0 1-1.5 1.5H1.5A1.5 1.5 0 0 1 0 7.5v-6Z"/><path fill-rule="evenodd" d="M7 5a3 3 0 0 1 6 0v4.384a.5.5 0 0 0 .356.479l2.695.808a2.5 2.5 0 0 1 1.756 2.748l-.633 4.435A2.5 2.5 0 0 1 14.699 20H6.96a2.5 2.5 0 0 1-2.27-1.452l-2.06-4.464a2.417 2.417 0 0 1-.106-1.777c.21-.607.719-1.16 1.516-1.273 1.035-.148 2.016.191 2.961.82V5Zm3-1a1 1 0 0 0-1 1v7.793c0 1.39-1.609 1.921-2.527 1.16-.947-.784-1.59-.987-2.069-.948a.486.486 0 0 0 .042.241l2.06 4.463A.5.5 0 0 0 6.96 18h7.74a.5.5 0 0 0 .494-.43l.633-4.434a.5.5 0 0 0-.35-.55l-2.695-.808A2.5 2.5 0 0 1 11 9.384V5a1 1 0 0 0-1-1Z" clip-rule="evenodd"/></svg>',
							'title' => esc_html__( 'Inactive Tab Message', 'merchant' ),
							'desc' => esc_html__( 'Don’t let customers forget about their order – change the title of the browser tab when visitors navigate away from your store.', 'merchant' ),
							'placeholder' => MERCHANT_URI . 'assets/images/modules/inactive-tab-messsage.png',
							'tutorial_url' => 'https://docs.athemes.com/article/inactive-tab-message/',
							'preview_url' => $shop_url,
						),

					),
				),

				'improve-experience' => array(
					'title' => esc_html__( 'Improve Experience', 'merchant' ),
					'modules' => array(

						'auto-external-links' => array(
							'icon' => '<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18"><path d="M8 4a1 1 0 1 0 0 2h2.586L7.293 9.293a1 1 0 0 0 1.414 1.414L12 7.414V10a1 1 0 1 0 2 0V5c0-.025 0-.05-.003-.075A1 1 0 0 0 13 4H8ZM0 13.5A1.5 1.5 0 0 1 1.5 12h3A1.5 1.5 0 0 1 6 13.5v3A1.5 1.5 0 0 1 4.5 18h-3A1.5 1.5 0 0 1 0 16.5v-3Z"/><path d="M1.5 0A1.5 1.5 0 0 0 0 1.5V10h2V2h14v14H8v2h8.5a1.5 1.5 0 0 0 1.5-1.5v-15A1.5 1.5 0 0 0 16.5 0h-15Z"/></svg>',
							'title' => esc_html__( 'Auto External Links', 'merchant' ),
							'desc' => esc_html__( 'Keep customers on your store by automatically opening third-party links in a new browser tab.', 'merchant' ),
							'placeholder' => MERCHANT_URI . 'assets/images/modules/auto-external-links.png',
							'tutorial_url' => 'https://docs.athemes.com/article/auto-external-links/',
							'preview_url' => $product_url,
						),

						'real-time-search' => array(
							'icon' => '<svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9 5a1 1 0 1 0-2 0v3a1 1 0 0 0 .293.707l2 2a1 1 0 0 0 1.414-1.414L9 7.586V5Z"/><path fill-rule="evenodd" clip-rule="evenodd" d="m14.312 12.897 5.395 5.396a1 1 0 1 1-1.414 1.414l-5.396-5.395A7.954 7.954 0 0 1 8 16c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8a7.946 7.946 0 0 1-1.688 4.897ZM8 2C4.691 2 2 4.691 2 8s2.691 6 6 6 6-2.691 6-6-2.691-6-6-6Z"/></svg>',
							'title' => esc_html__( 'Real-Time Search', 'merchant' ),
							'desc' => esc_html__( 'Help visitors instantly find the products they’re looking for by using predictive search.', 'merchant' ),
							'placeholder' => MERCHANT_URI . 'assets/images/modules/real-time-search.png',
							'tutorial_url' => 'https://docs.athemes.com/article/real-time-search/',
							'preview_url' => $shop_url,
						),

						'code-snippets' => array(
							'icon' => '<svg width="20" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 16"><path d="M2.707 7.707A.996.996 0 0 0 3 7V3a1 1 0 0 1 1-1 1 1 0 0 0 0-2C2.346 0 1 1.346 1 3v3.586l-.707.707a.999.999 0 0 0 0 1.414L1 9.414V13c0 1.654 1.346 3 3 3a1 1 0 0 0 0-2 1 1 0 0 1-1-1V9a.996.996 0 0 0-.293-.707L2.414 8l.293-.293ZM19.924 7.617a1 1 0 0 0-.217-.324L19 6.586V3c0-1.654-1.346-3-3-3a1 1 0 1 0 0 2 1 1 0 0 1 1 1v4a.996.996 0 0 0 .293.707l.293.293-.293.293A.996.996 0 0 0 17 9v4a1 1 0 0 1-1 1 1 1 0 0 0 0 2c1.654 0 3-1.346 3-3V9.414l.707-.707a1 1 0 0 0 .217-1.09ZM12.697 3.284a1.002 1.002 0 0 0-1.63.346l-3.996 8a.999.999 0 0 0 .56 1.299 1.006 1.006 0 0 0 1.302-.557l3.996-8a1.001 1.001 0 0 0-.232-1.088Z"/></svg>',
							'title' => esc_html__( 'Code Snippets', 'merchant' ),
							'desc' => esc_html__( 'Add custom code to your global header, body, or footer without editing your theme’s functions.php file.', 'merchant' ),
							'placeholder' => MERCHANT_URI . 'assets/images/modules/code-snippets.png',
							'tutorial_url' => 'https://docs.athemes.com/article/code-snippets/',
							'preview_url' => $shop_url,
						),

						'scroll-to-top-button' => array(
							'icon' => '<svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" clip-rule="evenodd" d="M10 0c5.514 0 10 4.486 10 10s-4.486 10-10 10S0 15.514 0 10 4.486 0 10 0Zm1 8.414 1.293 1.293a1 1 0 1 0 1.414-1.414l-3-3a.998.998 0 0 0-1.414 0l-3 3a1 1 0 0 0 1.414 1.414L9 8.414V14a1 1 0 1 0 2 0V8.414Z"/></svg>',
							'title' => esc_html__( 'Scroll to Top Button', 'merchant' ),
							'desc' => esc_html__( 'Help your customers get back easily to the top of the page with a single click.', 'merchant' ),
							'placeholder' => MERCHANT_URI . 'assets/images/modules/scroll-to-top-button.png',
							'tutorial_url' => 'https://docs.athemes.com/article/scroll-to-top-button/',
							'preview_url' => $shop_url,
						),

					),
				),


				'product-your-store' => array(
					'title' => esc_html__( 'Protect Store', 'merchant' ),
					'modules' => array(

						'agree-to-terms-checkbox' => array(
							'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><path d="M.623 1.253.014 4.836C-.09 5.446.39 6 1.021 6h.91c.58 0 1.11-.321 1.37-.83L3.897 4l.597 1.17c.26.509.79.83 1.37.83h1.169c.58 0 1.11-.321 1.369-.83L9 4l.597 1.17c.26.509.79.83 1.37.83h1.169c.58 0 1.11-.321 1.369-.83L14.102 4l.598 1.17c.259.509.789.83 1.369.83h.91c.63 0 1.11-.555 1.007-1.164l-.61-3.583A1.522 1.522 0 0 0 15.867 0H2.134C1.385 0 .746.53.623 1.253ZM12.707 8.293a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 1 1 1.414-1.414L8 11.586l3.293-3.293a1 1 0 0 1 1.414 0Z"/><path d="M3 8H1v8.5A1.5 1.5 0 0 0 2.5 18h13a1.5 1.5 0 0 0 1.5-1.5V8h-2v8H3V8Z"/></svg>',
							'title' => esc_html__( 'Agree to Terms Checkbox', 'merchant' ),
							'desc' => esc_html__( 'Get customers to agree to your Terms & Conditions as part of the checkout process.', 'merchant' ),
							'placeholder' => MERCHANT_URI . 'assets/images/modules/agree-to-terms-checkbox.png',
							'tutorial_url' => 'https://docs.athemes.com/article/agree-to-terms-checkbox/',
							'preview_url' => $checkout_url,
						),

						'cookie-banner' => array(
							'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="17" height="20" viewBox="0 0 17 20"><path d="M6 4h4v2H6V4ZM4 10v2h4v-2H4ZM4 14h6v2H4v-2Z"/><path fill-rule="evenodd" d="M0 18.5v-17C0 .7.7 0 1.5 0h13.1c.8 0 1.5.7 1.5 1.5v17c0 .8-.7 1.5-1.5 1.5H1.5C.7 20 0 19.3 0 18.5Zm2-.5h12V6h-2V4h2V2H2v2h2v2H2v12Z" clip-rule="evenodd"/></svg>',
							'title' => esc_html__( 'Cookie Banner', 'merchant' ),
							'desc' => esc_html__( 'Inform visitors about your cookie policy to comply with GDPR and other regulations.', 'merchant' ),
							'placeholder' => MERCHANT_URI . 'assets/images/modules/cookie-banner.png',
							'tutorial_url' => 'https://docs.athemes.com/article/cookie-banner/',
							'preview_url' => $shop_url,
						),

					),
				),

			);

			/**
			 * Hook: merchant_modules
			 * 
			 * @since 1.0
			 */
			$modules = apply_filters( 'merchant_modules', $modules );

			return $modules;

		}

		/**
		 * Get module info.
		 */
		public static function get_module_info( $module ) {

			$modules = self::get_modules();

			$maybe_found = array_column( array_column( $modules, 'modules' ), $module );

			if ( ! empty( $maybe_found ) && ! empty( $maybe_found[0] ) ) {
				return $maybe_found[0];
			}

			return false;

		}

	}

	Merchant_Admin_Modules::instance();

}
