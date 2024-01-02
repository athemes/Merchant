/**
 * Merchant Scroll To.
 * 
 */

'use strict';

let merchant = merchant || {};

merchant.scrollTo = {
	domReady: function( fn ) {
		if ( typeof fn !== 'function' ) {
			return;
		}
	
		if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
			return fn();
		}
	
		document.addEventListener( 'DOMContentLoaded', fn, false );
	},
	init: function() {
		const elements = document.querySelectorAll( '[data-merchant-scroll-to]' );

		if( 'null' === typeof elements ) {
			return;
		}

		elements.forEach( function( el ) {
			el.addEventListener( 'click', function( event ) {
				event.preventDefault();

				merchant.scrollTo.scrollTo( el );
			} );
		} );
	},
	scrollTo: function( el ) {
		const scrollToSelector	= el.getAttribute( 'data-merchant-scroll-to' );
		const scrollToOffset    = el.getAttribute( 'data-merchant-scroll-to-offset' ) || 0;

		if( ! scrollToSelector ) {
			return;
		}

		const elClientRect   = document.querySelector( scrollToSelector ).getBoundingClientRect();
		const scrollPosition = parseInt( ( elClientRect.top + window.pageYOffset ) - scrollToOffset );

		window.scrollTo( {
			top: scrollPosition,
			behavior: 'smooth'
		} );
	}
}

// Initialize.
merchant.scrollTo.domReady( function(){
	merchant.scrollTo.init();
} );