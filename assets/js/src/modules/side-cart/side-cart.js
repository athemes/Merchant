/**
 * Merchant side cart
 *
 */

jQuery(document).ready(function($) {

  if (merchant.setting.hasOwnProperty('show_after_add_to_cart_single_product')) {
    if ($('body.single-product') && $('.woocommerce-notices-wrapper').is(':visible')) {
      $('body').toggleClass('merchant-floating-side-mini-cart-show');
      $(window).trigger('merchant.floating-mini-cart-resize');
    }
  }

  // Add to cart AJAX event.
  if (merchant.setting.hasOwnProperty('add_to_cart_slide_out')) {
    $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
      $('body').toggleClass('merchant-floating-side-mini-cart-show');
      $(window).trigger('merchant.floating-mini-cart-resize');
    });
  }

  // On cart URL click
  if (merchant.setting.hasOwnProperty('cart_url')) {
    $('[href="' + merchant.setting.cart_url + '"]').on('click', function(e) {
      e.preventDefault();
      $(window).trigger('merchant.floating-mini-cart-resize');
      $('body').toggleClass('merchant-floating-side-mini-cart-show');
    });

  }
});

