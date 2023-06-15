'use strict';

var merchant = merchant || {};

merchant.modules = merchant.modules || {};

(function($) {

  merchant.modules.ajaxRealTimeSearch = {

    init: function() {

      var self = this;

      var cachedAddToCartText;

      $(document).on('show_variation', '.variations_form', function ( event, variation ) {

        var $button = $('.single_add_to_cart_button:not(.merchant_buy_now_button)');

        if ( ! cachedAddToCartText ) {
          cachedAddToCartText = $button.html();
        }

        if( variation.is_pre_order == true ) {
          $button.html(window.merchant.setting.pre_orders_add_button_title);
        } else {
          $button.html(cachedAddToCartText);
        }

      });

      $(document).on('click.wc-variation-form', '.reset_variations', function () {
        $('.single_add_to_cart_button:not(.merchant_buy_now_button)').html(cachedAddToCartText);
      });

    },

  };

  merchant.modules.ajaxRealTimeSearch.init();

}(jQuery));
