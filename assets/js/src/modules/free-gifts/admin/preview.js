;( function ( $, window, document, undefined ) {

    const { spending_texts, gifts_icons } = merchant || {};

    $( document ).on( 'ready', function () {
        $( '.merchant-flexible-content-control.free-gifts-style .merchant-flexible-content .layout:first').addClass( 'active' );
        initPreview();
    })

    $(document).on('click', '.merchant-flexible-content-control.free-gifts-style .layout', function () {
        let $this = $(this),
            $parent = $this.closest( '.merchant-flexible-content-control.free-gifts-style' );
        $parent.find('.layout' ).removeClass( 'active' );
        $this.addClass( 'active' );
        initPreview();
    });

    $( document).on( 'change.merchant keyup input', function () {
        initPreview();
    } );

    function initPreview() {
        let layout = $( '.merchant-flexible-content-control.free-gifts-style' ).find( '.layout.active' ),
            rule = layout.find( '.merchant-field-rules_to_apply select' ).val(),
            spendingText = layout.find( '.merchant-field-spending_text_0 input' ).val(),
            spendingGoal = layout.find( '.merchant-field-amount input' ).val();

        let productName = 'Any';

        if ( rule === 'product' ) {
            productName = layout.find( '.merchant-field-product_to_purchase .product-item' ).attr( 'data-name' );
            productName = productName || 'Specific product';
        } else if ( rule === 'categories' ) {
            const productNames = [];
            layout.find( '.merchant-field-category_slugs .select2-selection__choice' ).each( function() {
                productNames.push( $(this).attr( 'title' ) )
            } );
            productName = productNames.length ? productNames.join( ', ' ) : 'Categories';
        }

        spendingText = spendingText?.replace( /{amount}|{goalAmount}/g, spendingGoal );
        spendingText = spendingText?.replace( /{productName}|{categories}/g, productName );
        $( '.merchant-free-gifts-widget-offer-label' ).html( spendingText );

        $( document ).on( 'change', '.merchant-field-rules_to_apply select', function() {
            const value = $( this ).val();
            if ( ! value ) {
                return;
            }

            const $layout = $( this ).closest( '.layout' );

            const currentTextObj = spending_texts[ value ] || {};

            for ( let key in currentTextObj ) {
                if ( currentTextObj.hasOwnProperty( key ) ) {
                    const $input = $layout.find( `input[name*="${key}"]` );

                    if ( $input.length ) {
                        $input.val( currentTextObj[ key ] );
                    }
                }
            }
        } );

        $( document ).on( 'change', '.merchant-choices-icon input', function() {
            const value = $( this ).val();
            if ( ! value ) {
                return;
            }

            const url = $( this ).closest( 'label' ).find( 'img' ).attr( 'src' );
            if ( url ) {
                $( '.merchant-module-page-preview-box' )
                    .find( '.merchant-free-gifts-widget-icon' )
                    .html( gifts_icons[value] )
            }
        } );

        $( document ).on( 'change', '.merchant-field-position select', function() {
            const value = $( this ).val();
            if ( ! value ) {
                return;
            }

            $( '.merchant-field-position select option' ).each( function() {
                $( '.merchant-free-gifts-widget' ).removeClass( 'merchant-free-gifts-widget--' + $( this ).val() );
            } )

            $( '.merchant-free-gifts-widget' ).addClass( 'merchant-free-gifts-widget--' + value );

            // Set distance value based on select field selection
            let distanceValue = ( value === 'bottom_left' || value === 'bottom_right' ) ? 100 : 150;
            $( '.merchant-field-distance input' ).val( distanceValue );
            $( '.merchant-free-gifts-widget' ).css( '--merchant-free-gifts-distance', distanceValue + 'px' );
        } );
    }

    // Todo implement already added campaign

} )( jQuery, window, document );
