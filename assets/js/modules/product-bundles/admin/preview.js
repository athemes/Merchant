"use strict";

(function ($) {
  'use strict';

  $(document).on('change', '#bundled_thumb', function (e) {
    var field = $(this);
    if (field.is(':checked')) {
      $('.mrc-preview-bundle-product-image').show();
    } else {
      $('.mrc-preview-bundle-product-image').hide();
    }
  });
  $(document).on('change', '#bundled_qty', function (e) {
    var field = $(this);
    if (field.is(':checked')) {
      $('.mrc-preview-bundle-product-description').show();
    } else {
      $('.mrc-preview-bundle-product-description').hide();
    }
  });
  $(document).on('change', '#bundled_description', function (e) {
    var field = $(this);
    if (field.is(':checked')) {
      $('.mrc-preview-bundle-product-price').show();
    } else {
      $('.mrc-preview-bundle-product-price').hide();
    }
  });
  $(document).on('change', 'select[name="merchant[placement]"]', function (e) {
    var field = $(this),
      bundle = $('.mrc-preview-bundle-wrapper'),
      cart = $('.mrc-preview-addtocart-placeholder');
    if (field.val() === 'woocommerce_after_add_to_cart_form') {
      bundle.insertAfter(cart);
    } else {
      bundle.insertBefore(cart);
    }
  });
  $('select[name="merchant[placement]"], #bundled_description, #bundled_qty, #bundled_thumb').trigger('change');
})(jQuery);