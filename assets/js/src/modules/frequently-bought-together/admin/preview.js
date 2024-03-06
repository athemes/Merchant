(function ($) {
    'use strict';

    $(document).on('click', '.merchant-flexible-content-control.frequently-bought-together-style .layout', function () {
        var $this = $(this);
        var $parent = $this.closest('.merchant-flexible-content-control.frequently-bought-together-style');
        $parent.find('.layout').removeClass('active');
        $this.addClass('active');
        initPreview();
    });

    $(document).on('change.merchant keyup', function () {
        initPreview();
    });

    function initPreview() {
        let layout = $('.merchant-flexible-content-control.frequently-bought-together-style').find('.layout.active'),
            titleTextColor = layout.find('.merchant-field-title input').val(),
            totalText = layout.find('.merchant-field-price_label input').val(),
            addToCartText = layout.find('.merchant-field-button_text input').val(),
            plusSignBgColor = layout.find('.merchant-field-plus_bg_color input').val(),
            plusSignTextColor = layout.find('.merchant-field-plus_text_color input').val(),
            offerBorderColor = layout.find('.merchant-field-bundle_border_color input').val(),
            offerBorderRadius = layout.find('.merchant-field-bundle_border_radius input.merchant-range-number-input').val();

        $('.merchant-frequently-bought-together-bundle-product-plus').css({
            'background-color': plusSignBgColor,
            'color': plusSignTextColor
        });
        $('.merchant-frequently-bought-together-bundle-product:not(:first-child)').css({
            'border-color': offerBorderColor,
            'border-radius': offerBorderRadius + 'px'
        });

        $('.merchant-frequently-bought-together-title').html(titleTextColor);
        $('.merchant-frequently-bought-together-bundle-total').html(totalText);
        $('.merchant-frequently-bought-together-bundles .merchant-add-bundle-to-cart').html(addToCartText);
    }

    $('.merchant-flexible-content-control .layout:first-child').addClass('active').trigger('click');


})(jQuery);