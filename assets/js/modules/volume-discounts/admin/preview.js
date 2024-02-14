"use strict";

(function ($) {
  'use strict';

  $(document).on('click', '.merchant-flexible-content-control.volume-discounts-style .layout', function () {
    var $this = $(this),
      $parent = $this.closest('.merchant-flexible-content-control.volume-discounts-style');
    $parent.find('.layout').removeClass('active');
    $this.addClass('active');
    initPreview();
  });
  $(document).on('change.merchant keyup', function () {
    initPreview();
  });
  function initPreview() {
    var layout = $('.merchant-flexible-content-control.volume-discounts-style').find('.layout.active'),
      titleText = layout.find('.merchant-field-table_title input').val(),
      titleTextColor = layout.find('.merchant-field-title_text_color input').val(),
      titleTextFontSize = layout.find('.merchant-field-title_font_size input').val(),
      titleTextFontWeight = layout.find('.merchant-field-title_font_weight select').val(),
      bgColor = layout.find('.merchant-field-table_item_bg_color input').val(),
      borderColor = layout.find('.merchant-field-table_item_border_color input').val(),
      textColor = layout.find('.merchant-field-table_item_text_color input').val(),
      labelBgColor = layout.find('.merchant-field-table_label_bg_color input').val(),
      labelTextColor = layout.find('.merchant-field-table_label_text_color input').val();
    $('.merchant-volume-discounts-title').css({
      'color': titleTextColor,
      'font-size': titleTextFontSize + 'px',
      'font-weight': titleTextFontWeight
    }).html(titleText);
    $('.merchant-volume-discounts-item').css({
      'border-color': borderColor,
      'background-color': bgColor,
      'color': textColor
    });
    $('.merchant-volume-discounts-item-label > span').css({
      'background-color': labelBgColor,
      'color': labelTextColor
    });
  }
  $('.merchant-flexible-content-control.volume-discounts-style .layout:first-child').addClass('active').trigger('click');
})(jQuery);