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
            offerBorderRadius = layout.find('.merchant-field-bundle_border_radius input.merchant-range-number-input').val(),
            cartBundleTitle = layout.find('.merchant-group-field-cart_page .merchant-field-title input').val(),
            cartBundlePriceLabel = layout.find('.merchant-group-field-cart_page .merchant-field-price_label input').val(),
            cartBundleSaveLabel = layout.find('.merchant-group-field-cart_page .merchant-field-save_label input').val(),
            cartBundleButtonText = layout.find('.merchant-group-field-cart_page .merchant-field-button_text input').val();

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
        $('.cart-item-offer__container .offer-title').text(cartBundleTitle)
        $('.cart-item-offer__container .discount-label .label').text(cartBundlePriceLabel)
        $('.cart-item-offer__container .discount-savings .label').text(cartBundleSaveLabel.replace('{amount}', ''))
        $('.cart-item-offer__container .add-to-cart .add-to-cart-button').text(cartBundleButtonText)
    }

    $('.merchant-flexible-content-control .layout:first-child').addClass('active').trigger('click');

    function show_single_product_preview(){
        let element = $('.merchant-single-product-preview');
        element.addClass('show');
    }

    function hide_single_product_preview(){
        let element = $('.merchant-single-product-preview');
        element.removeClass('show');
    }

    function show_cart_page_preview(){
        let element = $('.merchant-cart-preview');
        element.addClass('show');
    }

    function hide_cart_page_preview(){
        let element = $('.merchant-cart-preview');
        element.removeClass('show');
    }

    $('.merchant-module-page-setting-box').on('click', function (e) {
        let clickedElement = $(e.target);

        if (clickedElement.closest('.merchant-group-field-cart_page').length > 0 || clickedElement.hasClass('merchant-group-field-cart_page')) {
            show_cart_page_preview();
            hide_single_product_preview()
            console.log('cart page preview')
        } else {
            show_single_product_preview();
            hide_cart_page_preview();
            console.log('single product preview')
        }
    });

    show_single_product_preview();
})(jQuery);