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
            cartSaveLabel = layout.find('.merchant-group-field-cart_page .merchant-field-save_label input').val(),
            checkoutTitle = layout.find('.merchant-group-field-checkout_page .merchant-field-title input').val(),
            checkoutDiscountText = layout.find('.merchant-group-field-checkout_page .merchant-field-discount_text input').val(),
            checkoutButtonText = layout.find('.merchant-group-field-checkout_page .merchant-field-button_text input').val(),
            thankYouTitle = layout.find('.merchant-group-field-thank_you_page .merchant-field-title input').val(),
            thankYouDiscountText = layout.find('.merchant-group-field-thank_you_page .merchant-field-discount_text input').val(),
            thankYouButtonText = layout.find('.merchant-group-field-thank_you_page .merchant-field-button_text input').val(),
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
        $('.merchant-cart-preview .my-cart .cart-table .cart-item .product .product-info .upsell-offer').text(cartBundleTitle);
        $('.merchant-cart-preview .my-cart .cart-table .cart-item .product .product-info .upsell-product .upsell-info p').text(cartSaveLabel.replace('{amount}', '10%'));
        $('.merchant-cart-preview .my-cart .cart-table .cart-item .product .product-info .upsell-product .upsell-info .add-to-cart').text(cartBundleButtonText);
        $('.merchant-checkout-preview .offer-title').text(checkoutTitle.replace('{offer_quantity}', '3'));
        $('.merchant-checkout-preview .product-details .product-info p').text(checkoutDiscountText.replace('{discount}', '10%'));
        $('.merchant-checkout-preview .product-details .product-info .add-to-order').text(checkoutButtonText);
        $('.merchant-thank-you-preview .offer-title').text(thankYouTitle.replace('{offer_quantity}', '3'));
        $('.merchant-thank-you-preview .product-details .product-info p').text(thankYouDiscountText.replace('{discount}', '10%'));
        $('.merchant-thank-you-preview .product-details .product-info .add-to-order').text(thankYouButtonText);
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

    function show_thank_you_page_preview(){
        let element = $('.merchant-thank-you-preview');
        element.addClass('show');
    }

    function hide_thank_you_page_preview(){
        let element = $('.merchant-thank-you-preview');
        element.removeClass('show');
    }

    function show_checkout_page_preview(){
        let element = $('.merchant-checkout-preview');
        element.addClass('show');
    }

    function hide_checkout_page_preview(){
        let element = $('.merchant-checkout-preview');
        element.removeClass('show');
    }

    $('.merchant-module-page-setting-box').on('click', function (e) {
        let clickedElement = $(e.target);

        if (clickedElement.closest('.merchant-group-field-cart_page').length > 0 || clickedElement.hasClass('merchant-group-field-cart_page')) {
            show_cart_page_preview();
            hide_single_product_preview();
            hide_thank_you_page_preview();
            hide_checkout_page_preview();
        } else if (clickedElement.closest('.merchant-group-field-thank_you_page').length > 0 || clickedElement.hasClass('merchant-group-field-thank_you_page')) {
            show_thank_you_page_preview();
            hide_single_product_preview();
            hide_cart_page_preview();
            hide_checkout_page_preview()
        }else if (clickedElement.closest('.merchant-group-field-checkout_page').length > 0 || clickedElement.hasClass('merchant-group-field-checkout_page')) {
            show_checkout_page_preview();
            hide_single_product_preview();
            hide_cart_page_preview();
            hide_thank_you_page_preview();
        } else {
            show_single_product_preview();
            hide_cart_page_preview();
            hide_thank_you_page_preview();
            hide_checkout_page_preview();
        }
    });

    show_single_product_preview();
})(jQuery);