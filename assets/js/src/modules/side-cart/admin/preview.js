(function ($) {
	'use strict';

	/**
	 * MerchantSideCartAdminPreview
	 * Add tweaks to control the side cart upsell groups label in the module settings page.
	 */
	class MerchantSideCartAdminPreview {
		constructor() {
			this.init();
		}

		/**
		 * Initialize the logic.
		 */
		init() {
			this.preview();
			this.events();
			this.flexibleContentLabel();
		}

		preview() {
			$( document ).on( 'change', '.merchant-field-slide_direction input', function() {
				if ( $( this ).is( ':checked' ) ) {
					$( '.merchant-side-cart' )
						.removeClass( 'slide-left slide-right' )
						.addClass( 'slide-' + $( this ).val() );
				}
			} );
		}

		/**
		 * Events.
		 */
		events() {
			$(document).on('change keyup merchant.change', '.merchant-module-page-setting-fields', this.flexibleContentLabel.bind(this));
		}

		/**
		 * Update the label of the upsell groups in the side cart settings page based on the selected options.
		 */
		flexibleContentLabel() {
			let self = this;
			if (this.cartUpsellsToggleState() && this.cartUpsellType() === 'custom_upsell') {
				let upsellsGroups = $('.merchant-field-custom_upsells .merchant-flexible-content .layout');
				upsellsGroups.each(function () {
					let group = $(this),
						trigger = self.customUpsellTrigger(group),
						categories = self.customUpsellCategories(group),
						products = self.customUpsellProducts(group);
					if ('categories' === trigger) {
						self.updateGroupLabel(group, 'categories', categories.length, categories);
					}
					if ('products' === trigger) {
						self.updateGroupLabel(group, 'products', products.length, products);
					}
					if ('all' === trigger) {
						self.updateGroupLabel(group, 'all', 10, []);
					}
				});
			}
		}

		/**
		 * Get the upsells count label badge.
		 * @param group {jQuery} The upsell group.
		 * @returns {string}
		 */
		upsellsLabelBadge(group) {
			let upsellsType = group.find('.merchant-field-custom_upsell_type select').val();
			if ('products' === upsellsType) {
				let products = group.find('.merchant-field-upsells_product_ids .merchant-selected-products-preview li');
				if (products.length) {
					return `<span class="merchant-upsells-badge">Upsells: ${products.length}</span>`;
				}
			}
			return '';
		}

		/**
		 * Update the group label based on the selected options.
		 * @param group {jQuery} The upsell group.
		 * @param type {string} The type of the upsell group
		 * @param count {number} The count of the selected items
		 * @param data {Array} The selected items data
		 */
		updateGroupLabel(group, type, count, data) {
			let groupLabel = group.find('.layout-title');
			if ('categories' === type) {
				if (count > 1) {
					groupLabel.html(merchant_side_cart_params.keywords.multi_categories + this.upsellsLabelBadge(group));
				} else if (count === 1) {
					groupLabel.html(merchant_side_cart_params.keywords.category_trigger + ' ' + data[0] + this.upsellsLabelBadge(group));
				} else {
					groupLabel.html(merchant_side_cart_params.keywords.no_cats_selected + this.upsellsLabelBadge(group));
				}
			}
			if ('products' === type) {
				if (count > 1) {
					groupLabel.html(merchant_side_cart_params.keywords.multi_products + this.upsellsLabelBadge(group));
				} else if (count === 1) {
					groupLabel.html(data[0].name + ' (#' + data[0].id + ')' + this.upsellsLabelBadge(group));
				} else {
					groupLabel.html(merchant_side_cart_params.keywords.no_products_selected + this.upsellsLabelBadge(group));
				}
			}
			if ('all' === type) {
				groupLabel.html(merchant_side_cart_params.keywords.all_products + this.upsellsLabelBadge(group));
			}
		}

		/**
		 * Get the selected trigger for the upsell group.
		 * @param group {jQuery} The upsell group.
		 * @returns {string} The selected trigger value.
		 */
		customUpsellTrigger(group) {
			return group.find('.merchant-field-upsell_based_on select').val();
		}

		/**
		 * Get the selected products for the upsell group.
		 * @param group {jQuery} The upsell group.
		 * @returns {Array} The selected products data.
		 */
		customUpsellProducts(group) {
			let foundProducts = group.find('.merchant-field-product_ids .merchant-selected-products-preview li');
			let products = [];
			foundProducts.each(function () {
				products.push({
					id: $(this).data('id'),
					name: $(this).data('name'),
					element: $(this)
				});
			});

			return products;
		}

		/**
		 * Get the selected categories for the upsell group.
		 * @param group {jQuery} The upsell group.
		 * @returns {Array} The selected categories.
		 */
		customUpsellCategories(group) {
			let upsellDropdown = group.find('.merchant-field-category_slugs select');
			return upsellDropdown.find('option:selected').map(function () {
				return $(this).text().trim();
			}).get();
		}

		/**
		 * Check the state of the upsells main toggle.
		 * @returns {boolean} True if the toggle is checked otherwise false.
		 */
		cartUpsellsToggleState() {
			let toggle = $('.merchant-field-use_upsells input');
			return !!toggle.is(':checked');
		}

		/**
		 * Get the selected upsell type.
		 * @returns {string} The selected upsell type.
		 */
		cartUpsellType() { // custom_upsell
			let type = $('.merchant-field-upsells_type select');
			return type.val();
		}
	}

	// Instantiate the class when the DOM is ready
	$(document).ready(function () {
		new MerchantSideCartAdminPreview();
	});
})(jQuery);