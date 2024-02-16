"use strict";

(function ($) {
  'use strict';

  $(document).on('click', '.merchant-flexible-content-control.buy-x-get-y-style .layout', function () {
    var $this = $(this);
    var $parent = $this.closest('.merchant-flexible-content-control.buy-x-get-y-style');
    $parent.find('.layout').removeClass('active');
    $this.addClass('active');
    initPreview();
  });
  $(document).on('change.merchant', function () {
    initPreview();
  });
  function initPreview() {
    var layout = $('.merchant-flexible-content-control.buy-x-get-y-style').find('.layout.active'),
      titleTextColor = layout.find('.merchant-field-title_text_color input').val(),
      labelBgColor = layout.find('.merchant-field-label_bg_color input').val(),
      labelTextColor = layout.find('.merchant-field-label_text_color input').val(),
      arrowBgColor = layout.find('.merchant-field-arrow_bg_color input').val(),
      arrowTextColor = layout.find('.merchant-field-arrow_text_color input').val(),
      offerBorderColor = layout.find('.merchant-field-offer_border_color input').val(),
      offerBorderRadius = layout.find('.merchant-field-offer_border_radius input.merchant-range-number-input').val();
    $('.merchant-bogo-title').css('color', titleTextColor);
    $('.merchant-bogo-product-label').css({
      'background-color': labelBgColor,
      'color': labelTextColor
    });
    $('.merchant-bogo-arrow').css({
      'background-color': arrowBgColor,
      'color': arrowTextColor
    });
    $('.merchant-bogo-product-y').css({
      'border-color': offerBorderColor,
      'border-radius': offerBorderRadius + 'px'
    });
  }
  $('.merchant-flexible-content-control .layout:first-child').addClass('active').trigger('click');
})(jQuery);