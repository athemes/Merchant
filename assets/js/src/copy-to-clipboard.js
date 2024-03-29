/**
 * Merchant Copy To Clipboard.
 * 
 */

'use strict';

let merchant = merchant || {};

merchant.copyToClipboard = {
	init: function() {
		const 
			self = this,
			els = document.querySelectorAll( '[data-merchant-copy-to-clipboard]' );

		if( ! els.length ) {
			return;
		}

		els.forEach( function( el ) {
			el.addEventListener( 'click', self.copyToClipboard );
		});
	},

	copyToClipboard: function( event ) {
		event.preventDefault();

		const 
			textToCopy   = this.querySelector( '.merchant-copy-to-clipboard-value' ),
			tooltipLabel = this.getAttribute( 'data-merchant-tooltip' );

		navigator.clipboard.writeText( textToCopy.value );

		this.setAttribute( 'data-merchant-tooltip', merchantCopyToClipboard.i18n.copied );
		setTimeout( function() {
			this.setAttribute( 'data-merchant-tooltip', tooltipLabel );
		}.bind( this ), 1000 );
	}
}

document.addEventListener( 'DOMContentLoaded', function() {
	merchant.copyToClipboard.init();
} );