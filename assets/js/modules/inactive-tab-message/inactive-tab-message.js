/**
 * Merchant Inactive Tab Message.
 * 
 */

'use strict';

var merchant = merchant || {};
merchant.modules = merchant.modules || {};
(function ($) {
  merchant.modules.inactiveTabMessage = {
    init: function init() {
      var setting = merchant.setting;
      var _ref = setting || {},
        noItemsMessage = _ref.inactive_tab_message,
        itemsInCartMessage = _ref.inactive_tab_abandoned_message,
        shouldBlink = _ref.inactive_tab_enable_blink;
      var _ref2 = setting || {},
        cartCount = _ref2.inactive_tab_cart_count;
      $(document.body).on('added_to_cart removed_from_cart updated_wc_div', function (event, data) {
        if (data && data['.merchant_cart_count'] !== undefined) {
          cartCount = data['.merchant_cart_count'];
        } else {
          // Cart page
          cartCount = $('.woocommerce-cart-form tr.cart_item').length;
        }
      });
      var defaultTitle = document.title;
      var blinkTimeout;
      document.addEventListener('visibilitychange', function () {
        var modifiedTitle = cartCount ? itemsInCartMessage : noItemsMessage;
        if (!modifiedTitle) {
          return;
        }
        var isTabActive = !document.hidden;

        // Change the title.
        document.title = isTabActive ? defaultTitle : modifiedTitle.replaceAll('&#039;', "'");

        // Blink the title when tab is inactive.
        if (shouldBlink && !isTabActive) {
          blinkTimeout = setInterval(function () {
            document.title = document.title === modifiedTitle ? defaultTitle : modifiedTitle;
          }, 500);
        } else {
          clearInterval(blinkTimeout);
        }
      });
    }
  };
  merchant.modules.inactiveTabMessage.init();
})(jQuery);