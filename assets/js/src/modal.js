/**
 * Merchant Modal.
 * 
 */

'use strict';

let merchant = merchant || {};

merchant.modal = {
	init: function() {
		const
			self = this, 
			els  = document.querySelectorAll( '[data-merchant-modal-trigger]' );

		if( ! els.length ) {
			return;
		}
		
		els.forEach( function( el ) {
			el.addEventListener( 'click', self.openModal );
		} );

		document.querySelectorAll( '[data-merchant-modal-close]' ).forEach( function( el ) {
			el.addEventListener( 'click', self.buttonCloseModal );
		} );
	},

	toggleBodyClass: function() {
		document.body.classList.toggle( 'merchant-modal-opened' );	
	},

	openModal: function( event ) {
		event.preventDefault();
		event.stopPropagation();

		const
			popupId = this.getAttribute( 'data-merchant-modal' ),
			popup   = document.querySelector( '.merchant-modal[data-merchant-modal="'+ popupId +'"]' );

		merchant.modal.toggleBodyClass();
		popup.classList.add( 'show' );

		popup.addEventListener( 'click', merchant.modal.closeModal );
	},

	closeModal: function( event ) {
		if( event.target.closest( '.merchant-modal-body' ) === null ) {
			merchant.modal.toggleBodyClass();
			this.classList.remove( 'show' );
		}
	},

	buttonCloseModal: function( event ) {
		event.preventDefault();
		event.stopPropagation();

		const popup = this.closest( '.merchant-modal' );

		merchant.modal.toggleBodyClass();
		popup.classList.remove( 'show' );
	}

}

document.addEventListener( 'DOMContentLoaded', function() {
	merchant.modal.init();
} );