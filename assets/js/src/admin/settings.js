;( function ( $, window, document, undefined ) {

	jQuery( document ).ready( function ( $ ) {
		if ( typeof wp.codeEditor !== 'undefined' ) {
			const settings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};

			// To use a theme need to get required CSS from https://github.com/codemirror/codemirror5/tree/master/theme
			// settings.codemirror.theme = '3024-day';

			// Initialize codeEditor for CSS fields
			$( '.merchant-field-custom_css textarea' ).each( function () {
				const editor = wp.codeEditor.initialize( this, settings ).codemirror;

				// Trigger event on any change
				editor.on( 'change', function () {
					$( editor.getTextArea() ).trigger( 'change' );
				} );
			} );

			// Initialize codeEditor for JS fields
			$( '.merchant-field-custom_js textarea' ).each( function () {
				const jsSettings = _.clone( settings );
				jsSettings.codemirror.mode = 'javascript';
				const editor = wp.codeEditor.initialize( this, jsSettings ).codemirror;

				// Trigger event on any change
				editor.on( 'change', function () {
					$( editor.getTextArea() ).trigger( 'change' );
				} );
			} );
		}
	} );

} )( jQuery, window, document );
