'use strict';

const merchant = merchant || {};

merchant.modules = merchant.modules || {};

( function($) {
	merchant.modules.loginPopups = {
		init: function() {

			// Remove required attribute from inputs as it prevents saving options in module's dashboard.
			if ( merchant?.is_admin ) {
				$( '.merchant-module-page-preview input' ).removeAttr( 'required' );
			}

			const $body   = $( 'body' );
			const $toggle = $( '.merchant-login-popup-toggle' );

			if ( ! $toggle.length ) {
				return;
			}

			const $popupBody = $( '.merchant-login-popup-body' );

			// Show login popup
			$toggle.on( 'click', function( e ) {
				e.preventDefault();

				$body.toggleClass( 'merchant-login-popup-show' );

				if ( ! $popupBody.hasClass( 'merchant-show' ) ) {
					setTimeout(() => $popupBody.addClass( 'merchant-show' ), 200 );
				} else {
					$popupBody.removeClass( 'merchant-show' );
				}
			} );

			const $footerToggle = $( '.merchant-login-popup-footer a' );
			const $content = $( '.merchant-login-popup-content' );

			// Toggle Login/Register form
			if ( $footerToggle.length ) {
				let flag = true;

				$footerToggle.on('click', function( e ) {
					e.preventDefault();

					$( this )
						.parent()
						.toggleClass( 'merchant-show' )
						.siblings()
						.toggleClass( 'merchant-show' );

					// Toggle visibility of columns based on the current state of `flag`
					if ( flag ) {
						$content.find( '.col-1' ).hide();
						$content.find( '.col-2' ).show();
					} else {
						$content.find( '.col-1' ).show();
						$content.find( '.col-2' ).hide();
					}

					// Flip the visibility flag for the next click
					flag = ! flag;
				} );
			}

			// AJAX login/register
			$( document ).on( 'submit', '.merchant-login-popup .woocommerce-form', function( e ) {
				e.preventDefault();

				const { nonce, ajax_url } = merchant?.setting || {};

				if ( ! ajax_url || ! nonce ) {
					return;
				}

				const $form = $( this );
				const isLogin = $form.hasClass( 'woocommerce-form-login' );

				const data = {
					action: 'merchant_ajax_login_register',
					form:  isLogin ? 'login' : 'register',
					username: $form.find( 'input[name="username"]' ).val(),
					password: $form.find('input[name="password"]' ).val(),
					email: $form.find( 'input[name="email"]' ).val(),
					remember: $form.find( 'input[name="rememberme"]' ).is( ':checked' ),
					nonce,
				};

				$.ajax( {
					type: 'POST',
					url: ajax_url,
					data,
					beforeSend: function( e ) {
						$form.find( 'button[type="submit"]' ).prop( 'disabled', true );

						$form.block( {
							message: null,
							overlayCSS: {
								background: '#fff',
								opacity: 0.6,
							},
						} );
					},
					success: function( response ) {
						const $noticeWrapper = $content.find( '.woocommerce-notices-wrapper' );
						if ( $noticeWrapper.length ) {
							$noticeWrapper.fadeOut( 200, function() {
								$( this )
									.empty()
									.append( response.data?.notice )
									.fadeIn( 200, function() {
										$popupBody.animate( {
											scrollTop: 0,
										}, 200 );
									} );
							} );
						}

						if ( response?.success ) {
							// Remove Register from after successful login/register.
							$popupBody.find( '.u-column2' ).remove();

							if ( isLogin ) {
								// window.location.href = response.redirect_url;
								setTimeout( () => window.location.reload(), 300 );
							}
						}
					},
					error: function( error ) {
						console.log( error );
					},
					complete: function() {
						$form
							.unblock()
							.find( 'button[type="submit"]' )
							.prop( 'disabled', false );
					}
				} );
			} );
		},
	};

	$( document).ready( function() {
		merchant.modules.loginPopups.init();
	} );
}( jQuery ) );
