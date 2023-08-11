/**
 * Merchant Cart Count Favicon.
 * 
 */

'use strict';

var merchant = merchant || {};
merchant.modules = merchant.modules || {};
(function ($) {
  merchant.modules.cartCountFavicon = {
    init: function init() {
      var count = parseInt(window.merchant.setting.cart_count_favicon_count) || 0;
      var favicon = new Favico({
        animation: window.merchant.setting.cart_count_favicon_animation,
        type: window.merchant.setting.cart_count_favicon_shape,
        position: window.merchant.setting.cart_count_favicon_position,
        bgColor: window.merchant.setting.cart_count_favicon_background_color,
        textColor: window.merchant.setting.cart_count_favicon_text_color
      });
      $(document.body).on('added_to_cart removed_from_cart', function (event, data) {
        if (data && data['.merchant_cart_count'] !== undefined) {
          count = data['.merchant_cart_count'];
          favicon.badge(count);
        }
      });
      favicon.badge(count);
      var delay = parseInt(window.merchant.setting.cart_count_favicon_delay) * 1000;
      if (delay) {
        setInterval(function () {
          favicon.badge(0);
        }, delay);
        setInterval(function () {
          favicon.badge(count);
        }, delay * 2);
      }
    }
  };
  merchant.modules.cartCountFavicon.init();
})(jQuery);