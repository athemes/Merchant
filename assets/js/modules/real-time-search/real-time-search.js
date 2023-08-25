/**
 * Real Time Search.
 * 
 */

'use strict';

var merchant = merchant || {};
merchant.modules = merchant.modules || {};
(function ($) {
  merchant.modules.ajaxRealTimeSearch = {
    init: function init() {
      var self = this;
      var fields = document.querySelectorAll('.woocommerce-product-search .wc-search-field, .widget_product_search .search-field, .wp-block-search .wp-block-search__input, .wc-block-product-search-field');
      if (fields.length) {
        var _loop = function _loop(i) {
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
    searchFormHandler: function searchFormHandler(el) {
      if (el.value.length < 3) {
        return false;
      }
      var self = this,
        term = el.value,
        clist = el.classList,
        type = clist.contains('wc-block-product-search-field') || clist.contains('wc-search-field') ? 'product' : 'post';
      $.post(window.merchant.setting.ajax_url, {
        action: 'ajax_search_callback',
        nonce: window.merchant.setting.nonce,
        type: type,
        search_term: term,
        posts_per_page: window.merchant.setting.ajax_search_results_amounth_per_search,
        orderby: window.merchant.setting.ajax_search_results_order_by,
        order: window.merchant.setting.ajax_search_results_order,
        display_categories: window.merchant.setting.ajax_search_results_display_categories,
        enable_search_by_sku: window.merchant.setting.ajax_search_results_enable_search_by_sku
      }, function (response) {
        var wrapper = el.parentNode.getElementsByClassName('merchant-ajax-search-wrapper')[0];
        if (typeof wrapper === 'undefined') {
          wrapper = document.createElement('div');
          wrapper.className = 'merchant-ajax-search-wrapper';
          el.parentNode.append(wrapper);
          el.parentNode.classList.add('merchant-ajax-search');
        }
        wrapper.innerHTML = response.output;
        var products_wrapper = document.querySelector('.merchant-ajax-search-products');
        if (products_wrapper !== null && self.scrollbarVisible(products_wrapper)) {
          products_wrapper.classList.add('has-scrollbar');
        }
        if (self.elementIsOutOfScreenHorizontal(wrapper)) {
          wrapper.classList.add('merchant-reverse');
        }
      });
    },
    destroy: function destroy() {
      if (typeof document.querySelector('body.wp-admin') !== 'undefined') {
        return false;
      }
      var wrappers = document.querySelectorAll('.merchant-ajax-search-wrapper');
      if (wrappers.length) {
        for (var i = 0; i < wrappers.length; i++) {
          wrappers[i].remove();
        }
      }
    },
    debounce: function debounce(callback, wait) {
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
    scrollbarVisible: function scrollbarVisible(el) {
      return el.scrollHeight > el.clientHeight;
    },
    elementIsOutOfScreenHorizontal: function elementIsOutOfScreenHorizontal(el) {
      var rect = el.getBoundingClientRect();
      return rect.x + rect.width > window.innerWidth;
    }
  };
  merchant.modules.ajaxRealTimeSearch.init();
})(jQuery);