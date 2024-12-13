(function ($) {
	'use strict';
	let merchant_pre_order_object = {
		init() {
			// Initialize events
			this.initEvents();
			this.initPreview();

			this.datePickers = [];
		},


		initEvents() {
			const self = this;

			$( document ).on( 'change.merchant keyup', this.initPreview.bind( this ) );
			$( document ).on( 'click', '.merchant-flexible-content-control.pre-orders-style .layout', this.updateActiveLayout.bind( this ) );
			$( '.merchant-flexible-content-control .layout:first-child' ).addClass( 'active' ).trigger( 'click' );

			// Remove datepicker form pre-order start & end fields if associated Shipping fields is empty on Page load.
			$( document ).on( 'initiated.merchant-datepicker', function ( e, datePicker, $input, options, index ) {
				if ( ! $input.closest( '.merchant-flexible-content' ).length ) {
					return;
				}

				const $preOrderStartField = $input.closest( '.merchant-field-pre_order_start' );
				const $preOrderEndField   = $input.closest( '.merchant-field-pre_order_end' );
				const $shippingField      = $input.closest( '.layout-body' ).find( '.merchant-field-shipping_date input' );

				// Process only if the current input is a pre-order start or end field
				if ( $preOrderStartField.length || $preOrderEndField.length ) {

					// Add the same index to all related fields
					$shippingField
						.add( $input.closest( '.layout-body' ).find( '.merchant-field-pre_order_start input' ) )
						.add( $input.closest( '.layout-body' ).find( '.merchant-field-pre_order_end input' ) )
						.attr( 'data-datepicker-index', index );

					if ( $shippingField.length ) {
						const datepickerIndex = $shippingField.data( 'datepicker-index' );

						// Check if a group with this index already exists
						const existingGroup = self.datePickers.find( ( group ) => group.id === datepickerIndex );

						if ( existingGroup ) {
							existingGroup.datePickers.push( datePicker );
						} else {
							self.datePickers.push( {
								id: datepickerIndex,
								datePickers: [ datePicker ],
							} );
						}

						// If the shipping field has no value, destroy the datePicker
						if ( ! $shippingField.val() ) {
							datePicker?.destroy();

							// To prevent console error
							datePicker.opts = {};
							datePicker.$datepicker = '';
						}
					}
				}
			} );

			// Based on Shipping field selection, initiate or destroy pre-order start & end date fields
			$( document ).on( 'change.merchant-datepicker', function ( e, formattedDate, $input, options ) {
				if ( ! $input?.closest( '.merchant-field-shipping_date' ).length ) {
					return;
				}

				$input.css( 'borderColor', '' );

				const $shippingField  = $input.closest( '.layout-body' ).find( '.merchant-field-shipping_date input' );
				const $preOrderFields = $input.closest( '.layout-body' ).find( '.merchant-field-pre_order_start input, .merchant-field-pre_order_end input' );

				if ( $shippingField.length ) {
					const datepickerIndex = $shippingField.data( 'datepicker-index' );

					if ( $shippingField.val() ) {
						const newDatePickers = [];

						// Create new date picker instances for pre-order fields
						$preOrderFields.each( function () {
							const datePickerInstance = new AirDatepicker( $( this ).getPath(), {
								...options,
								selectedDates: '', // Ensure no pre-selected dates
							} );

							newDatePickers.push( datePickerInstance );
						} );

						// Push the new group into `self.datePickers`
						self.datePickers.push( {
							id: datepickerIndex,
							datePickers: newDatePickers,
						} );
					} else {
						// Destroy and remove date pickers for this index when shipping field is cleared
						self.datePickers = self.datePickers.filter( ( datePickerGroup ) => {

							// Destroy all date picker instances in the group
							if ( datePickerGroup.id === datepickerIndex ) {
								datePickerGroup.datePickers.forEach( ( datePickerInstance ) => {
									datePickerInstance?.destroy();
									datePickerInstance?.clear(); // Clear selected dates

									// Prevent console errors by resetting internal properties
									datePickerInstance.opts = {};
									datePickerInstance.$datepicker = '';
								} );
								return false; // Remove this group from the array
							}

							return true; // Retain other groups
						} );
					}
				}
			} );

			// Show alert if Shipping date not selected
			$( document ).on( 'click', '.merchant-field-pre_order_start input, .merchant-field-pre_order_end input', function () {
				const $shippingDateField = $( this ).closest( '.layout-body' ).find( '.merchant-field-shipping_date input' );
				if ( $shippingDateField.length && ! $shippingDateField.val() ) {
					$shippingDateField.css( 'borderColor', '#f00' );
					alert( merchant?.shipping_date_missing_text || 'Please set a Shipping date first' );
				}
			} );
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
				'--mrc-po-text-color': btnTextColor,
				'--mrc-po-text-hover-color': btnTextColorHover,
				'--mrc-po-border-color': btnBorderColor,
				'--mrc-po-border-hover-color': btnBorderColorHover,
				'--mrc-po-background-color': btnBgColor,
				'--mrc-po-background-hover-color': btnBgColorHover,
			});
		},

		updateActiveLayout(e) {
			let $this = $(e.currentTarget);
			let $parent = $this.closest('.merchant-flexible-content-control.pre-orders-style');
			$parent.find('.layout').removeClass('active');
			$this.addClass('active');
			this.initPreview();
		},
	};

	merchant_pre_order_object.init();
})(jQuery);