"use strict";

;
(function ($) {
  $(document).ready(function () {
    var spendingWidgetSelector = '.js-merchant-spending-goal-widget';
    if (typeof merchant === 'undefined' || !$(spendingWidgetSelector).length) {
      return;
    }
    var _ref = merchant.setting || {},
      enable_auto_slide_in = _ref.enable_auto_slide_in,
      spending_goal_nonce = _ref.spending_goal_nonce,
      ajax_url = _ref.ajax_url;

    // Show/Hide widget when clicking on it.
    $(document).on('click', spendingWidgetSelector, function () {
      showWidget(true);
    });

    // Auto open after a product is added to the Cart on Product Single Page.
    if (enable_auto_slide_in) {
      if ($('body.single-product').length && $('.woocommerce-notices-wrapper').is(':visible') && !$('.woocommerce-notices-wrapper').is(':empty')) {
        showWidget();
      }
    }

    // Update the widget and Auto slide when a product is Added/Removed to cart via AJAX.
    // Works on pages like Shop & Cart, updated_cart_totals updated_wc_div events are required for the Cart page.
    $(document.body).on('added_to_cart removed_from_cart updated_cart_totals updated_wc_div', function (event, data) {
      $.ajax({
        type: 'POST',
        url: ajax_url,
        data: {
          action: 'update_spending_goal_widget',
          nonce: spending_goal_nonce
        },
        success: function success(response) {
          if (!response || !response.data) {
            return;
          }

          // Check if widget is currently open
          var isVisible = $(spendingWidgetSelector).hasClass('active');

          // Replace the widget markup
          $(spendingWidgetSelector).replaceWith(response.data.markup);

          // Open widget immediately if open, else with slight delay for slide-in effect
          if (enable_auto_slide_in) {
            isVisible ? showWidget() : setTimeout(showWidget, 100);
          }
        },
        error: function error(_error) {
          console.log(_error);
        }
      });
    });

    // Helper
    function showWidget() {
      var toggle = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
      toggle ? $(spendingWidgetSelector).toggleClass('active') : $(spendingWidgetSelector).addClass('active');
    }
  });
})(jQuery);