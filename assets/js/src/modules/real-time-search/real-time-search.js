/**
 * Real Time Search.
 *
 */

'use strict';

var merchant = merchant || {};

merchant.modules = merchant.modules || {};

(function ($) {

	merchant.modules.ajaxRealTimeSearch = {

		init: function () {

			var self = this;
			var fields = document.querySelectorAll('.woocommerce-product-search .wc-search-field, .widget_product_search .search-field, .wp-block-search .wp-block-search__input, .wc-block-product-search-field, .woocommerce-product-search .search-field, .w-search-form input, .the7-search-form__input, input[type="search"]');

			if (fields.length) {

				var _loop = function (i) {

					fields[i].setAttribute('autocomplete', 'off');

					fields[i].addEventListener('keyup', self.debounce(function () {
						self.searchFormHandler(fields[i]);
					}, 300));

					fields[i].addEventListener('focus', self.debounce(function () {
						self.searchFormHandler(fields[i]);
					}, 300));

				};

				for (var i = 0; i < fields.length; i++) {
					_loop(i);
				}

				document.addEventListener('click', function (e) {
					if (e.target.closest('.merchant-ajax-search-wrapper') === null) {
						self.destroy();
					}
				});
			}

		},

		searchFormHandler: function ( el ) {
			if ( el.value.length < 3 ) {
				return false;
			}

			const self = this;
			const term = el.value;
			const clist = el.classList;
			const type = clist.contains( 'wc-block-product-search-field' ) || clist.contains( 'wc-search-field' ) ? 'product' : 'post';

			const {
				ajax_search_results_amount_per_search: posts_per_page = 15,
				ajax_search_results_order_by: orderby = 'title',
				ajax_search_results_order: order = 'asc',
				ajax_search_results_display_categories: display_categories = 0,
				ajax_search_results_enable_search_by_sku: enable_search_by_sku = 0
			} = window.merchant.setting.real_time_search || {};

			$.ajax( {
				url: window.merchant.setting.ajax_url,
				method: 'POST',
				data: {
					action: 'ajax_search_callback',
					nonce: window.merchant.setting.nonce,
					type: type,
					search_term: term,
					posts_per_page,
					orderby,
					order,
					display_categories,
					enable_search_by_sku
				},
				success: function (response) {
					let wrapper = el.parentNode.querySelector( '.merchant-ajax-search-wrapper' );

					if ( ! wrapper ) {
						wrapper = document.createElement( 'div' );
						wrapper.className = 'merchant-ajax-search-wrapper';
						el.parentNode.append( wrapper );
						el.parentNode.classList.add( 'merchant-ajax-search' );
					}

					wrapper.innerHTML = response.output;

					const productsWrapper = document.querySelector( '.merchant-ajax-search-products' );

					if ( productsWrapper && self.scrollbarVisible( productsWrapper ) ) {
						productsWrapper.classList.add( 'has-scrollbar' );
					}

					if ( self.elementIsOutOfScreenHorizontal( wrapper ) ) {
						wrapper.classList.add( 'merchant-reverse' );
					}
				}
			} );
		},

		destroy: function () {
			if ( document.body.classList.contains( 'wp-admin' ) ) {
				return;
			}

			const wrappers = document.querySelectorAll( '.merchant-ajax-search-wrapper' );

			wrappers.forEach( wrapper => wrapper.remove() );
		},

		debounce: function (callback, wait) {
			var timeoutId = null;
			return function () {
				for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
					args[_key] = arguments[_key];
				}

				window.clearTimeout(timeoutId);
				timeoutId = window.setTimeout(function () {
					callback.apply(null, args);
				}, wait);
			};
		},

		scrollbarVisible: function (el) {
			return el.scrollHeight > el.clientHeight;
		},

		elementIsOutOfScreenHorizontal: function (el) {
			var rect = el.getBoundingClientRect();
			return rect.x + rect.width > window.innerWidth;
		}
	};

	$(document).ready(function () {
		merchant.modules.ajaxRealTimeSearch.init();
	});

}(jQuery));