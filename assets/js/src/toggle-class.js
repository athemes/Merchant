/**
 * Merchant Toggle Class.
 * 
 */

'use strict';

let merchant = merchant || {};

merchant.toggleClass = {
	init: function( event, el, triggerEvent ) {
		event.preventDefault();
		event.stopPropagation();

		var selector    = document.querySelector( el.getAttribute( 'data-merchant-selector' ) ),
			removeClass = el.getAttribute( 'data-merchant-toggle-class-remove' ),
			classname   = el.getAttribute( 'data-merchant-toggle-class' ),
			classes     = selector.classList;

		if( typeof removeClass === 'string' ) {
			classes.remove( removeClass );
		}

		classes.toggle( classname );

		if( triggerEvent ) {
			var ev = document.createEvent('HTMLEvents');

			ev.initEvent( triggerEvent, true, false);
			window.dispatchEvent(ev);
		}
	}
}
