(function ($) {
    'use strict';

    $(document).on('click', '.merchant-flexible-content-control.buy-x-get-y-style .layout', function () {
        var $this = $(this);
        var $parent = $this.closest('.merchant-flexible-content-control.buy-x-get-y-style');
        $parent.find('.layout').removeClass('active');
        $this.addClass('active');
        initPreview();
    });

    $(document).on('change.merchant keyup', function () {
        initPreview();
    });

    function initPreview() {
        let layout = $('.merchant-flexible-content-control.buy-x-get-y-style').find('.layout.active'),
            titleTextColor = layout.find('.merchant-field-title_text_color input').val(),
            labelBgColor = layout.find('.merchant-field-label_bg_color input').val(),
            labelTextColor = layout.find('.merchant-field-label_text_color input').val(),
            arrowBgColor = layout.find('.merchant-field-arrow_bg_color input').val(),
            arrowTextColor = layout.find('.merchant-field-arrow_text_color input').val(),
            offerBorderColor = layout.find('.merchant-field-offer_border_color input').val(),
            offerBorderRadius = layout.find('.merchant-field-offer_border_radius input.merchant-range-number-input').val(),
            cartOfferTitle = layout.find('.merchant-group-field-cart_page .merchant-field-title input').val(),
            cartDiscountText = layout.find('.merchant-group-field-cart_page .merchant-field-discount_text input').val(),
            cartBundleButtonText = layout.find('.merchant-group-field-cart_page .merchant-field-button_text input').val();

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

        $('.merchant-cart-preview .cart-item-offer__container .offer-title').text(
            cartOfferTitle.replace('{min_quantity}', '3').replace('{offer_quantity}', '1')
        );

        $('.merchant-cart-preview .cart-item-offer__container .discount-savings .label').html(
            cartDiscountText.replace('{discount}', `<strong>20%</strong>`)
        );
        $('.merchant-cart-preview .cart-item-offer__container .add-to-cart .add-to-cart-button').text(cartBundleButtonText);
    }

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

    // thank you page preview
    function show_thank_you_page_preview(){
        let element = $('.merchant-thank-you-preview');
        element.addClass('show');
    }

    function hide_thank_you_page_preview(){
        let element = $('.merchant-thank-you-preview');
        element.removeClass('show');
    }

    $('.merchant-flexible-content-control .layout:first-child').addClass('active').trigger('click');

	$('.merchant-module-page-setting-box').on('click', function (e) {
		let clickedElement = $(e.target);

		if (clickedElement.closest('.merchant-group-field-cart_page').length > 0 || clickedElement.hasClass('merchant-group-field-cart_page')) {
			show_cart_page_preview();
			hide_single_product_preview()
            hide_thank_you_page_preview();
		} else if (clickedElement.closest('.merchant-group-field-thank_you_page').length > 0 || clickedElement.hasClass('merchant-group-field-thank_you_page')) {
			show_thank_you_page_preview();
			hide_single_product_preview();
            hide_cart_page_preview();
		} else {
			show_single_product_preview();
			hide_cart_page_preview();
            hide_thank_you_page_preview();
		}
	});

    show_single_product_preview();
})(jQuery);