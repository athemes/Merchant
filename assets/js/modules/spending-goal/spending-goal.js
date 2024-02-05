"use strict";

;
(function ($) {
  $(document).ready(function () {
    var $spendingWidget = $('.js-merchant-spending-goal-widget');
    if (typeof merchant === 'undefined' || !$spendingWidget.length) {
      return;
    }
    var _ref = merchant.setting || {},
      enable_auto_slide_in = _ref.enable_auto_slide_in;
    $spendingWidget.on('click', function () {
      toggleWidget();
    });
    if (enable_auto_slide_in) {
      if ($('body.single-product').length && $('.woocommerce-notices-wrapper').is(':visible') && !$('.woocommerce-notices-wrapper').is(':empty')) {
        toggleWidget(false);
      }
      $(document.body).on('added_to_cart', function (event, fragments, cart_hash, $button, $context) {
        toggleWidget(false);
      });
    }
    function toggleWidget() {
      var toggle = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
      toggle ? $spendingWidget.toggleClass('active') : $spendingWidget.addClass('active');
    }
  });
})(jQuery);