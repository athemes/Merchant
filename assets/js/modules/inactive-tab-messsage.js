'use strict';

var merchant = merchant || {};

merchant.modules = merchant.modules || {};

(function($) {

	merchant.modules.inactiveTabMessage = {

	  init: function() {

	  	var count = window.merchant.setting.inactive_tab_cart_count;

			$( document.body ).on('added_to_cart removed_from_cart', function( event, data ) {
				if ( data && data['.merchant_cart_count'] !== undefined ) {
					count = data['.merchant_cart_count'];
				}
			});
			
			var initial = document.title;
			var message;

			document.addEventListener('visibilitychange', function() {
				if ( document.hidden ) {
					var message = ( count ) ? window.merchant.setting.inactive_tab_abandoned_message : window.merchant.setting.inactive_tab_messsage;
					document.title = message.replace("&#039;", "'");
				} else {
					document.title = initial;
				}
			});

  	},

	};

	merchant.modules.inactiveTabMessage.init();

}(jQuery));
