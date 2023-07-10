'use strict';

var merchant = merchant || {};

merchant.modules = merchant.modules || {};

(function($) {

	merchant.modules.quickView = {

		init: function() {

			var self    = this;
			var $modals = $('.merchant-quick-view-modal');

			if ( ! $modals.length ) {
				return;
			}

			$modals.each( function() {

				var $modal       = $(this);
				var $openButton  = $('.merchant-quick-view-open');
				var $closeButton = $modal.find('.merchant-quick-view-close-button');
				var $inner       = $modal.find('.merchant-quick-view-inner');
				var $content     = $modal.find('.merchant-quick-view-content');
				var $overlay     = $modal.find('.merchant-quick-view-overlay');
				var isOpen       = false;

				$openButton.on('click', function( e ) {

					e.preventDefault();

					$content.empty();

					$modal.addClass('merchant-show');
					$modal.addClass('merchant-loading');

					isOpen = true;

					$.post( window.merchant.setting.ajax_url, {
						action: 'merchant_quick_view_content',
						nonce: window.merchant.setting.nonce,
						product_id: $(this).data('product-id'),
					}, function( response ) {

						if ( response.success && isOpen ) {

							$content.html(response.data);

							$inner.addClass('merchant-show');
							$modal.removeClass('merchant-loading');

				var $gallery = $content.find('.woocommerce-product-gallery');

				wc_single_product_params.zoom_enabled = window.merchant.setting.quick_view_zoom;

							if ( typeof $.fn.wc_product_gallery === 'function' && $gallery.length ) {
				  $gallery.trigger('wc-product-gallery-before-init', [$gallery.get(0), wc_single_product_params]);
				  $gallery.wc_product_gallery(wc_single_product_params);
				  $gallery.trigger('wc-product-gallery-after-init', [$gallery.get(0), wc_single_product_params]);
							}

							var $variations = $content.find('.variations_form');
							
							if ( typeof $.fn.wc_variation_form === 'function' && $variations.length ) {
								$variations.each(function () {
									$(this).wc_variation_form();
								});
							}

							if ( window.botiga && window.botiga.productSwatch ) {
								window.botiga.productSwatch.init();
							}

							if ( window.botiga && window.botiga.qtyButton ) {
					window.botiga.qtyButton.init('quick-view');
							}

						} else {

							$content.html(response.data);

							$inner.addClass('merchant-show');
							$modal.removeClass('merchant-loading');
			
						}

					}).fail( function( xhr, textStatus ) {

						$content.html(textStatus);
			
						$inner.addClass('merchant-show');
						$modal.removeClass('merchant-loading');

					});
					
				});
				
				$overlay.on('click', function( e ) {
					
					e.preventDefault();

					$closeButton.trigger('click');

				});
				
				$closeButton.on('click', function( e ) {
					
					e.preventDefault();
					
					isOpen = false;
					$modal.removeClass('merchant-show');
					$inner.removeClass('merchant-show');

				});

			});

		},

	};

	$(document).ready(function() {
		merchant.modules.quickView.init();
	});

}(jQuery));
