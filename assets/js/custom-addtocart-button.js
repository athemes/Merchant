/**
 * Merchant custom add to cart button.
 * 
 */

'use strict';

var merchant = merchant || {};
merchant.customAddToCartButton = {
  domReady: function domReady(fn) {
    if (typeof fn !== 'function') {
      return;
    }
    if (document.readyState === 'interactive' || document.readyState === 'complete') {
      return fn();
    }
    document.addEventListener('DOMContentLoaded', fn, false);
  },
  init: function init() {
    var button = document.querySelectorAll('.merchant-custom-addtocart');
    if (!button.length) {
      return false;
    }
    for (var i = 0; i < button.length; i++) {
      button[i].addEventListener('click', function (e) {
        e.preventDefault();
        var button = this,
          productId = this.getAttribute('data-product-id'),
          initial_text = this.innerHTML,
          loading_text = this.getAttribute('data-loading-text'),
          added_text = this.getAttribute('data-added-text'),
          nonce = this.getAttribute('data-nonce');
        var ajax = new XMLHttpRequest();
        ajax.open('POST', merchant.setting.ajax_url, true);
        ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        button.innerHTML = loading_text;
        ajax.onload = function () {
          if (this.status >= 200 && this.status < 400) {
            button.innerHTML = added_text;
            setTimeout(function () {
              button.innerHTML = initial_text;
            }, 1500);
            jQuery(document.body).trigger('wc_fragment_refresh');
            jQuery(document.body).trigger('added_to_cart');
            document.body.dispatchEvent(new Event('merchant.custom_added_to_cart'));
          }
        };
        ajax.send('action=merchant_custom_addtocart&product_id=' + productId + '&nonce=' + nonce);
      });
    }
  }
};

// Initialize.
merchant.customAddToCartButton.domReady(function () {
  merchant.customAddToCartButton.init();
});