;( function ( $, window, document, undefined ) {

    const { spending_texts } = merchant || {};

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

    // Todo implement already added campaign


} )( jQuery, window, document );
