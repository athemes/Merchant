(function ($) {
	'use strict';
	let merchant_pre_order_object = {
		init() {
			// Initialize events
			this.initEvents();
			this.initPreview();
		},

		initEvents() {
			$(document).on('change.merchant keyup', this.initPreview.bind(this));
			$(document).on('click', '.merchant-flexible-content-control.pre-orders-style .layout', this.updateActiveLayout.bind(this));
			$('.merchant-flexible-content-control .layout:first-child').addClass('active').trigger('click');
		},

		initPreview() {
			let layout = $('.merchant-flexible-content-control.pre-orders-style').find('.layout.active'),
				btnText = layout.find('.merchant-field-button_text input').val(),
				btnTextColor = layout.find('.merchant-field-text-color input').val(),
				btnTextColorHover = layout.find('.merchant-field-text-hover-color input').val(),
				btnBorderColor = layout.find('.merchant-field-border-color input').val(),
				btnBorderColorHover = layout.find('.merchant-field-border-hover-color input').val(),
				btnBgColor = layout.find('.merchant-field-background-color input').val(),
				btnBgColorHover = layout.find('.merchant-field-background-hover-color input').val(),
				btn = $('.merchant-pre-ordered-product .add_to_cart_button');
			btn.text(btnText);
			// Set the CSS variables
			$('.merchant-pre-ordered-product').css({
				'--mrc-po-text-color': btnTextColor, // replace with your desired value
				'--mrc-po-text-hover-color': btnTextColorHover, // replace with your desired value
				'--mrc-po-border-color': btnBorderColor, // replace with your desired value
				'--mrc-po-border-hover-color': btnBorderColorHover, // replace with your desired value
				'--mrc-po-background-color': btnBgColor, // replace with your desired value
				'--mrc-po-background-hover-color': btnBgColorHover // replace with your desired value
			});
		},

		updateActiveLayout(e) {
			let $this = $(e.currentTarget);
			let $parent = $this.closest('.merchant-flexible-content-control.pre-orders-style');
			$parent.find('.layout').removeClass('active');
			$this.addClass('active');
			this.initPreview();
		}
	};

	merchant_pre_order_object.init();
})(jQuery);