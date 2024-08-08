(function ($) {
    'use strict';

    const shapesDefaultStyles = {
        text: {
            'text-shape-1' : {
                width: 100,
                height: 32,
                borderRadius: 5,
                marginX: 10,
                marginY: 10,
            },
            'text-shape-2' : {
                width: 100,
                height: 32,
                borderRadius: 5,
                marginX: 10,
                marginY: 10,
            },
            'text-shape-3' : {
                width: 125,
                height: 32,
                borderRadius: 5,
                marginX: 10,
                marginY: 10,
            },
            'text-shape-4' : {
                width: 125,
                height: 32,
                borderRadius: 5,
                marginX: 10,
                marginY: 10,
            },
            'text-shape-5' : {
                width: 140,
                height: 32,
                borderRadius: 0,
                marginX: 25,
                marginY: 10,
                disableInShortCode: true,
            },
            'text-shape-6' : {
                width: 140,
                height: 32,
                borderRadius: 0,
                marginX: 25,
                marginY: 10,
                disableInShortCode: true,
            },
            'text-shape-7' : {
                width: 90,
                height: 90,
                borderRadius: 5,
                marginX: 10,
                marginY: 10,
            },
            'text-shape-8' : {
                width: 100,
                height: 100,
                borderRadius: 50,
                marginX: 10,
                marginY: 10,
            },
        },
        image: {
            'image-shape-1' : {
                width: 45,
                height: 45,
            },
            'image-shape-2' : {
                width: 45,
                height: 25,
            },
            'image-shape-3' : {
                width: 45,
                height: 15,
            },
            'image-shape-4' : {
                width: 45,
                height: 25,
            },
            'image-shape-5' : {
                width: 45,
                height: 15,
            },
            'image-shape-6' : {
                width: 38,
                height: 45,
            },
            'image-shape-7' : {
                width: 45,
                height: 30,
            },
            'image-shape-8' : {
                width: 45,
                height: 15,
            },
            'image-shape-9' : {
                width: 45,
                height: 27,
            },
            'image-shape-10' : {
                width: 38,
                height: 45,
            },
            'image-shape-11' : {
                width: 45,
                height: 12,
            },
            'image-shape-12' : {
                width: 45,
                height: 15,
            },
            'image-shape-13' : {
                width: 45,
                height: 32,
            },
            'image-shape-14' : {
                width: 42,
                height: 45,
            },
            'image-shape-15' : {
                width: 45,
                height: 10,
            },
            'image-shape-16' : {
                width: 45,
                height: 32,
            },
            'image-shape-17' : {
                width: 45,
                height: 14,
            },
            'image-shape-18' : {
                width: 38,
                height: 45,
            },
            'image-shape-19' : {
                width: 45,
                height: 30,
            },
            'image-shape-20' : {
                width: 45,
                height: 28,
            },
            'image-shape-21' : {
                width: 38,
                height: 45,
            },
            'image-shape-22' : {
                width: 45,
                height: 45,
            },
            'image-shape-23' : {
                width: 45,
                height: 35,
            },
            'image-shape-24' : {
                width: 45,
                height: 45,
            },
        },
    }

    $( document).on('click', '.merchant-flexible-content-control.product-labels-style .layout', function () {
        let $this = $(this),
            $parent = $this.closest('.merchant-flexible-content-control.product-labels-style');
        $parent.find('.layout').removeClass('active');
        $this.addClass('active');
        initPreview();
    } );

    $( document).on('merchant-flexible-content-added', function ( e, $layout ) {
        $('.merchant-flexible-content-control.product-labels-style').find('.layout').removeClass('active');
        $layout.addClass('active');
        initPreview();
    } );

    $(document).on('merchant-product-labels-reload-product-preview', function (e) {
        initPreview();
    } );

    $( document ).on( 'change.merchant keyup', function () {
        initPreview();
    } );

    function initPreview() {
        let layout = $('.merchant-flexible-content-control.product-labels-style').find('.layout.active'),
            labelContent = layout.find('.merchant-field-label input').val(),
            labelType = layout.find('.merchant-field-label_type input:checked').val(),
            textShapeEl = layout.find('.merchant-choices-label_text_shape input:checked'),
            textShape = textShapeEl.val(),
            imageShapeEl = layout.find('.merchant-choices-label_image_shape input:checked'),
            imageShape = imageShapeEl.val(),
            customImageEl = layout.find('.merchant-field-label_image_shape_custom input[type="hidden"]'),
            customImage = customImageEl.val(),
            marginX = layout.find('.merchant-field-margin_x input').val(),
            marginXEl = layout.find('.merchant-field-margin_x'),
            marginY = layout.find('.merchant-field-margin_y input').val(),
            labelWidth = layout.find('.merchant-field-label_width input').val(),
            labelHeight = layout.find('.merchant-field-label_height input').val(),
            backgroundColor = layout.find('.merchant-field-background_color input').val(),
            textColor = layout.find('.merchant-field-text_color input').val(),
            borderRadius = layout.find('.merchant-field-shape_radius input').val(),
            fontSize = layout.find('.merchant-field-font_size input').val(),
            fontStyle = layout.find('.merchant-field-font_style select').val(),
            position = layout.find( '.merchant-field-label_position select' ).val();

        const labelPreview = $( '.merchant-product-labels-preview' ).find( '.merchant-product-labels' );

        let classes = `merchant-product-labels__regular position-${position} merchant-product-labels__${labelType}`;
        classes += labelType === 'text' ? ` merchant-product-labels__${textShape}` : '';

        const labelClassPattern = /\bmerchant-product-labels__\S+/g;

        let css = {
            'top' : marginY + 'px',
            'left': position === 'top-left' ? marginX + 'px' : '',
            'right': position === 'top-right' ? marginX + 'px' : '',
        }

        if ( labelType === 'text' ) {
            css['width']            = labelWidth + 'px';
            css['height']           = labelHeight + 'px';
            css['background-color'] = backgroundColor;
            css['color']            = textColor;
            css['font-size']        = fontSize + 'px';
            css['border-radius']    = borderRadius + 'px';

            const fontStyles = {
                'normal': { 'font-style': '', 'font-weight': '' },
                'italic': { 'font-style': 'italic', 'font-weight': '' },
                'bold': { 'font-style': '', 'font-weight': 'bold' },
                'bold_italic': { 'font-style': 'italic', 'font-weight': 'bold' }
            };

            css = {
                ...css,
                ...fontStyles[fontStyle]
            };

            const currency = labelPreview.attr( 'data-currency' );

            // Update shortcode to content
            labelContent = labelContent
                .replace( /{sale}/g, '50%' )
                .replace( /{sale_amount}/g, `${currency}50` )
                .replace( /{inventory}/g, 'In stock' )
                .replace( /{inventory_quantity}/g, 19 );

            // Update label content & styles
            labelPreview
                .css( css )
                .removeClass( ( index, className ) => ( className.match( labelClassPattern) || [] ).join( ' ' ) )
                .removeClass( 'position-top-right position-top-left' )
                .addClass( classes )
                .find( 'span' )
                .css( {
                    width: '',
                    height: '',
                } )
                .text( labelContent.trim() )
        } else {
            css['width']            = ''
            css['height']           = '';
            css['background-color'] = '';
            css['color']            = '';
            css['font-size']        = '';
            css['border-radius']    = '';

            const img = customImage
                ? customImageEl.closest( '.merchant-field-label_image_shape_custom' ).find( '.merchant-upload-image img' ).clone()
                : imageShapeEl.closest( 'label' ).find( 'img' ).clone();

            // Update label content & styles
            labelPreview
                .css( css )
                .removeClass( ( index, className ) => ( className.match( labelClassPattern) || [] ).join( ' ' ) )
                .removeClass( 'position-top-right position-top-left' )
                .addClass( classes )
                .find( 'span' )
                .css( {
                    width: labelWidth + 'px',
                    height: labelHeight + 'px',
                } )
                .html( img )

            // Toggle custom image upload element
            layout.find( '.merchant-upload-button-drag-drop' ).toggle( ! customImage );
        }

        // Change Margin X label
        marginXEl
            .closest( '.layout-field' )
            .find( '.merchant-module-page-setting-field-title' )
            .text( position === 'top-left' ? 'Margin left' : 'Margin right' );
    }

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

    // Shapes
    $( document ).on( 'change', '.merchant-field-label_type input', function () {
        const labelType = $( this ).val();
        const $layout = $( this ).closest( '.layout' );
        const shape =  $layout.find( labelType === 'text' ? '.merchant-choices-label_text_shape input:checked' : '.merchant-choices-label_image_shape input:checked' ).val();

        updateStyles( labelType, shape, $( this ) );
    } );

    $( document ).on( 'change', '.merchant-choices-label_text_shape input', function () {
        const $layout = $( this ).closest( '.layout' )
        const shape = $layout.find( '.merchant-choices-label_text_shape input:checked' ).val();

        updateStyles( 'text', shape, $( this ) );
    } );

    $( document ).on( 'change', '.merchant-choices-label_image_shape input', function () {
        const $layout = $( this ).closest( '.layout' )
        const shape = $layout.find( '.merchant-choices-label_image_shape input:checked' ).val();

        updateStyles( 'image', shape, $( this ) );
    } );

    const dataPrev = 'data-previous';
    $( document ).on( 'change', '.merchant-field-use_shortcode input', function () {
        const shortcode = $( this );

        $( '.merchant-flexible-content-control.product-labels-style .layout .merchant-choices-label_text_shape' ).each( function () {
            const $input = $( this ).find( 'input:checked' );
            const selectedShape = $input.val();
            const isDisabled = shapesDefaultStyles.text[ selectedShape ]?.disableInShortCode;

            if ( shortcode.is( ':checked' ) ) {
                if ( isDisabled ) {
                    $input.attr( dataPrev, selectedShape );
                    $input.prop( 'checked', false );
                    $( this ).find('input[value="text-shape-1"]').prop('checked', true);

                    updateStyles( 'text', 'text-shape-1' , $( this ) );
                }
            } else {
                const $previouslySelectedInput = $( this ).find( 'input['+ dataPrev +']' );
                const previousValue = $previouslySelectedInput.val();

                if ( previousValue ) {
                    $input.prop( 'checked', false );

                    $( this ).find('input[value="'+ previousValue +'"]').prop( 'checked', true );

                    $previouslySelectedInput.removeAttr( dataPrev );

                    updateStyles( 'text', previousValue , $( this ) );
                }
            }
        } );
    } );

    const updateStyles = ( shapeType, selectedShape, $input ) => {
        const $layout = $input.closest( '.layout' );

        const properties = {
            'merchant-field-label_width': 'width',
            'merchant-field-label_height': 'height',
            'merchant-field-shape_radius': 'borderRadius',
            'merchant-field-margin_x': 'marginX',
            'merchant-field-margin_y': 'marginY',
        };

        const labelType = $layout.find( '.merchant-field-label_type input:checked' ).val();

        // If custom image uploaded, don't change style when trying to select predefined images
        if ( labelType === 'image' ) {
           const customImage = $layout.find( '.merchant-field-label_image_shape_custom input[type="hidden"]' ).val();
           if ( customImage && $input.closest( '.merchant-choices-label_image_shape' ).length ) {
               return;
           }
        }

        for ( const [ inputWrapper, propertyName ] of Object.entries( properties ) ) {
            const value = shapesDefaultStyles[ shapeType ][ selectedShape ]?.[ propertyName ];
            if ( value || value === 0 ) {
                $layout.find( `.${inputWrapper} input` )
                    .val( value )
                    .attr( 'value', value )
                    .trigger( 'input' )
                    .trigger( 'change' );
            }
        }
    }
})(jQuery);
