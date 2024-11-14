(function ($) {
    'use strict';

    $(document).on('click', '.merchant-flexible-content-control.volume-discounts-style .layout', function () {
        let $this = $(this),
            $parent = $this.closest('.merchant-flexible-content-control.volume-discounts-style');
        $parent.find('.layout').removeClass('active');
        $this.addClass('active');
        initPreview();
    });

    $(document).on('change.merchant keyup', function () {
        initPreview();
    });

    function initPreview() {
        let layout = $('.merchant-flexible-content-control.volume-discounts-style').find('.layout.active'),
            titleText = layout.find('.merchant-field-table_title input').val(),
            titleTextColor = layout.find('.merchant-field-title_text_color input').val(),
            titleTextFontSize = layout.find('.merchant-field-title_font_size input').val(),
            titleTextFontWeight = layout.find('.merchant-field-title_font_weight select').val(),
            bgColor = layout.find('.merchant-field-table_item_bg_color input').val(),
            bgColorHover = layout.find('.merchant-field-table_item_bg_color_hover input').val(),
            borderColor = layout.find('.merchant-field-table_item_border_color input').val(),
            borderColorHover = layout.find('.merchant-field-table_item_border_color_hover input').val(),
            textColor = layout.find('.merchant-field-table_item_text_color input').val(),
            textColorHover = layout.find('.merchant-field-table_item_text_color_hover input').val(),
            labelBgColor = layout.find('.merchant-field-table_label_bg_color input').val(),
            labelTextColor = layout.find('.merchant-field-table_label_text_color input').val(),
            discountType = layout.find('.merchant-field-discount_type input:checked').val(),
            discountAmount = +layout.find('.merchant-field-discount input').val(),
            discountAmountField = layout.find('.merchant-field-discount input'),
            saveLabelValue = layout.find('.merchant-field-save_label input').val(),
            buyLabelValue = layout.find('.merchant-field-buy_text input').val(),
            quantityValue = +layout.find('.merchant-field-quantity input').val(),
            cartOfferTitle = layout.find('.merchant-group-field-cart_page .merchant-field-title input').val(),
            checkoutTitle = layout.find('.merchant-group-field-checkout_page .merchant-field-title input').val(),
            checkoutDiscountText = layout.find('.merchant-group-field-checkout_page .merchant-field-discount_text input').val(),
            checkoutButtonText = layout.find('.merchant-group-field-checkout_page .merchant-field-button_text input').val(),
            thankYouTitle = layout.find('.merchant-group-field-thank_you_page .merchant-field-title input').val(),
            thankYouDiscountText = layout.find('.merchant-group-field-thank_you_page .merchant-field-discount_text input').val(),
            thankYouButtonText = layout.find('.merchant-group-field-thank_you_page .merchant-field-button_text input').val(),
            cartBundleButtonText = layout.find('.merchant-group-field-cart_page .merchant-field-button_text input').val();

        $('.merchant-volume-discounts-title').css({
            'color': titleTextColor,
            'font-size': titleTextFontSize + 'px',
            'font-weight': titleTextFontWeight
        }).html(titleText);

        console.log(bgColorHover)

        $('.merchant-volume-discounts-item').css({
            '--merchant-item-text-color': textColor,
            '--merchant-item-text-color-hover': textColorHover || textColor,
            '--merchant-item-bg-color': bgColor,
            '--merchant-item-bg-color-hover': bgColorHover || bgColor,
            '--merchant-item-border-color': borderColor,
            '--merchant-item-border-color-hover': borderColorHover || borderColor,
        });

        const $saveLabelPreview = $( '.merchant-volume-discounts-item-label' );
        const $buyLabelPreview = $( '.merchant-volume-discounts-buy-label' );

        $saveLabelPreview.find( 'span:first' ).css({
            '--merchant-label-text-color': labelTextColor,
            '--merchant-label-bg-color': labelBgColor
        });

        const currency = $saveLabelPreview.closest( '.mrc-preview-right-column' ).attr( 'data-currency' );
        const discountEach = discountType === 'fixed_discount' ? `${currency}${ discountAmount }` : `${discountAmount}%`;
        const discountTotal = discountType === 'fixed_discount' ? `${currency}${ discountAmount * quantityValue }` : `${discountAmount}%`;

        // Update Save label content
        saveLabelValue = saveLabelValue.replace( /{amount}|{percent}/g, discountTotal );
        $saveLabelPreview.find( 'span:first' ).text( saveLabelValue );

        // Update Tier format text content
        buyLabelValue = buyLabelValue
            .replace( /{discount}|{percent}/g, `<strong>${ discountEach }</strong>` )
            .replace( /{quantity}|{amount}/g, `<strong>${ quantityValue }</strong>` );
        $buyLabelPreview.html( buyLabelValue );

        $('.merchant-cart-preview .my-cart .cart-table .cart-item .product .product-info .upsell-offer').text(
            cartOfferTitle.replace('{quantity}', '3').replace('{discount}', '20%')
        );
        $('.merchant-cart-preview .my-cart .cart-table .cart-item .product .product-info .upsell-product .upsell-info .add-to-cart').text(cartBundleButtonText);
        $('.merchant-checkout-preview .offer-title').text(checkoutTitle.replace('{quantity}', '3').replace('{discount}', '10%'));
        $('.merchant-checkout-preview .product-details .product-info p').html(checkoutDiscountText.replace('{discount}', '10%').replace('{product_price}', merchant_volume_discounts.mock_item_price));
        $('.merchant-checkout-preview .product-details .product-info .add-to-order').text(checkoutButtonText);
        $('.merchant-thank-you-preview .offer-title').text(thankYouTitle
            .replace('{quantity}', '3')
            .replace('{discount}', '15%')
            .replace('{total_quantity}', '5')
        );
        $('.merchant-thank-you-preview .product-details .product-info p').html(thankYouDiscountText.replace('{product_price}', merchant_volume_discounts.mock_item_price).replace('{discount}', '10%'));
        $('.merchant-thank-you-preview .product-details .product-info .add-to-order').text(thankYouButtonText);

        if (discountType === 'percentage_discount') {
            discountAmountField.attr('max', 100);
            if (discountAmountField.val() > 100) {
                discountAmountField.val(100);
            }
        } else {
            // remove attribute
            discountAmountField.removeAttr('max');
        }
    }

    $('.merchant-flexible-content-control.volume-discounts-style .layout:first-child').addClass('active').trigger('click');

    $( document ).on( 'change', 'input[type="radio"]', function() {
        const value = $( this ).val();
        if ( value !== 'percentage_discount' && value !== 'fixed_discount' ) {
            return;
        }

        const $layout = $( this ).closest( '.layout' );

        $layout.find( 'input[type="text"], textarea').each( function() {
            // Define the replacement string based on the radio button value
            let replacement = value === 'percentage_discount' ? '{percent}' : '{amount}';

            const currentValue = $( this ).val();

            /**
             * Previously wrong variable `{amount}` was used for this field. Correct one is `{quantity}`.
             * So fix it as soon as Discount type is changed.
             * Keep it for backward compatibility
             */
            if ( $( this ).attr('name').includes( 'buy_text' ) ) {
                replacement = '{quantity}';
            }

            // Replace occurrences of {amount} or {percent} with the appropriate replacement
            const newValue = currentValue.replace( /{amount}|{percent}/g, replacement );
            $( this ).val( newValue );
        } );
    } );

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
