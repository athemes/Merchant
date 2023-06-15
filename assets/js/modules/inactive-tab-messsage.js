'use strict';

var merchant = merchant || {};

merchant.modules = merchant.modules || {};

(function($) {

	merchant.modules.scrollTop = {

	  init: function() {

			var initial = document.title;

			document.addEventListener('visibilitychange', function() {
				if ( document.hidden ) {
					document.title = window.merchant.setting.inactive_tab_messsage.replace("&#039;", "'");
				} else {
					document.title = initial;
				}
			});

  	},

	};

	$(document).ready(function() {
		merchant.modules.scrollTop.init();
	});

}(jQuery));
