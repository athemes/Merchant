"use strict";

(function ($) {
  'use strict';

  $(document).on('merchant-product-labels-reload-product-preview', function (e) {
    var activeLayout = $('.merchant-flexible-content-control.product-labels-style .layout.active'),
      label = activeLayout.find('.merchant-module-page-setting-field-text input').val(),
      backgroundColor = activeLayout.find('.merchant-module-page-setting-field-color[data-id=background_color] input').val(),
      textColor = activeLayout.find('.merchant-module-page-setting-field-color[data-id=text_color] input').val(),
      fontSize = $('.merchant-module-page-setting-field-range[data-id=font-size] input').val(),
      borderRadius = $('.merchant-module-page-setting-field-range[data-id=label_shape] input').val(),
      padding = $('.merchant-module-page-setting-field-range[data-id=padding] input').val(),
      letterCase = $('.merchant-module-page-setting-field-select[data-id=label_text_transform] select').val(),
      position = $('.merchant-module-page-setting-field-select[data-id=label_position] select').val(),
      labelPreview = $('.merchant-product-labels-preview .merchant-label');
    labelPreview.css({
      'background-color': backgroundColor,
      'color': textColor,
      'font-size': fontSize + 'px',
      'border-radius': borderRadius + 'px',
      'padding': padding + 'px',
      'display': label && label.trim().length ? 'inline' : 'none',
      'text-transform': letterCase
    }).text(label.trim()).removeClass('top-right top-left').addClass(position);
  });
  $(document).on('change input change.merchant', '.merchant-module-page-setting-box', function (e) {
    $(document).trigger('merchant-product-labels-reload-product-preview');
  });
  $(document).on('click', '.merchant-flexible-content-control.product-labels-style .layout', function (e) {
    $('.merchant-flexible-content-control.product-labels-style .layout').removeClass('active');
    $(this).addClass('active');
    $(document).trigger('merchant-product-labels-reload-product-preview');
  });
  $(document).on('merchant-flexible-content-deleted', function (e, deletedItem) {
    if (deletedItem.hasClass('active')) {
      // if active item was deleted, set previous item to active
      $('.merchant-flexible-content-control.product-labels-style .merchant-flexible-content .layout:first-child').addClass('active');
      $(document).trigger('merchant-product-labels-reload-product-preview');
    }
  });
  $('.merchant-flexible-content-control.product-labels-style .merchant-flexible-content .layout:first-child').addClass('active');
  $(document).trigger('merchant-product-labels-reload-product-preview');
})(jQuery);