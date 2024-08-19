const merchant = merchant || {};

;( function( $, window, document ) {
	$( document ).ready( function( $ ) {
		new ClearCart( $ );
	} );
} )( jQuery, window, document );

class ClearCart {
	constructor() {
		this.timer;

		this.clearCartCookie = 'merchant_clear_cart';

		this.init();
	}

	init() {
		this.events();
	}

	events() {
		this.clearCartPageLoadAlert();
		this.clearCartButtonAlert();
		this.clearCartAutoAlert();
	}

	clearCartAjax( $button = null ) {
		const $ = jQuery;

		$button?.prop( 'disabled', true );

		$.ajax( {
			url: merchant?.setting?.ajax_url,
			type: 'POST',
			data: {
				action: 'clear_cart',
				nonce: merchant?.setting?.nonce
			},
			success: function( response ) {
				if ( response.success ) {
					const redirectUrl = response.data?.url;

					$( document.body ).trigger( 'wc_cart_emptied' );

					if ( redirectUrl ) {
						window.location.href = redirectUrl;
					} else {
						// If no redirect URL, refresh the Cart table & Mini/Side Cart
						$( document.body ).trigger( 'wc_update_cart' ).trigger( 'wc_fragment_refresh' );
					}
				}
			},
			error: function ( error ) {
				console.log( error )
			},
			complete: function() {
				$button?.prop( 'disabled', false );
			}
		} );
	}

	clearCartPageLoadAlert() {
		const $ = jQuery;
		const that = this;

		$( window ).on( 'load', function () {
			if ( ! merchant?.setting?.clear_cart_auto_clear ) {
				that.deleteClearCartCookie();
				return;
			}

			const expirationTime = that.getCookie( that.clearCartCookie );
			if ( expirationTime && that.getCurrentTime() > expirationTime ) {
				that.showClearCartAlert(); // Maybe after
			}
		} );
	}

	clearCartButtonAlert() {
		const $ = jQuery;
		const that = this;

		// Clear Cart On Button Click
		$( document ).on( 'click', '.merchant-clear-cart-button', function( e ) {
			e.preventDefault();
			that.showClearCartAlert( $( this ) );
		} );
	}

	clearCartAutoAlert() {
		const $ = jQuery;
		const that = this;

		const {
			clear_cart_is_cart_page,
			clear_cart_auto_clear,
			clear_cart_total_items,
			clear_cart_threshold,
			clear_cart_added_to_cart
		} = merchant?.setting || {};

		// Cart is being emptied
		$( document.body ).on( 'wc_cart_emptied', function( event ) {
			that.deleteClearCartCookie()
		} );

		// All Removed in Side/Mini Cart
		$( document.body ).on( 'removed_from_cart', function( event, fragments, hash, button ) {
			if ( ! hash ) {
				that.deleteClearCartCookie();
			}
		} );

		// Product Single Page
		if ( clear_cart_added_to_cart ) {
			const expireTime = that.setClearCartCookie();
			that.timer = setTimeout( () => that.showClearCartAlert(), expireTime );
		}

		// Adding & Updating Qty - AJAX.
		$( document.body ).on( 'wc_fragment_refresh added_to_cart updated_wc_div removed_from_cart', function( event, data, hash, button ) {

			// Cart Page
			if ( event?.type === 'updated_wc_div' && clear_cart_is_cart_page && ! $( '.woocommerce-cart-form' ).length ) {
				that.deleteClearCartCookie();
				return;
			}

			if ( event?.type === 'removed_from_cart' && ! hash ) {
				return;
			}

			let refresh = clear_cart_total_items >= clear_cart_threshold;

			if ( data && data['.merchant_clear_cart_cart_count'] !== undefined ) {
				refresh = data['.merchant_clear_cart_cart_count'] >= clear_cart_threshold;
				if ( ! refresh ) {
					that.deleteClearCartCookie();
					return;
				}
			}

			if ( clear_cart_auto_clear && refresh ) {
				const expireTime = that.setClearCartCookie();
				that.timer = setTimeout( () => that.showClearCartAlert(), expireTime );
			}
		} );
	}

	showClearCartAlert( $button = null ) {
		const that = this;

		const expirationTime = that.getCookie( that.clearCartCookie );

		if ( ! expirationTime && ! $button.length ) {
			that.deleteClearCartCookie();
			return;
		}

		if ( window.confirm( merchant?.setting?.clear_cart_popup_message ) ) {
			that.deleteClearCartCookie();
			that.clearCartAjax( $button );
		} else {
			if ( ! $button ) {
				const expireTime = that.setClearCartCookie();

				that.timer = setTimeout( () => that.showClearCartAlert(), expireTime );
			}
		}
	}

	setClearCartCookie() {
		clearTimeout( this.timer );

		const { clear_cart_expiration_time, wc_session_expiration_time } = merchant?.setting || {};

		const cartExpireDuration = clear_cart_expiration_time * 1000;

		const cartExpireTime = this.getCurrentTime() + cartExpireDuration; // millisecond
		const cookieExpireTime = this.getCurrentTime() + ( wc_session_expiration_time * 1000 ); // millisecond

		this.setCookie( this.clearCartCookie, cartExpireTime, cookieExpireTime );

		return cartExpireDuration;
	}

	deleteClearCartCookie() {
		clearTimeout( this.timer );
		this.deleteCookie( this.clearCartCookie );
	}

	getCurrentTime( time = true ) {
		const date = new Date();
		return time ? date.getTime() : date;
	}

	setCookie( name, value, expiration ) {
		const date = new Date( expiration );

		const expires = `expires=${ date.toUTCString() }`;
		document.cookie = `${ name }=${ value };${ expires };path=/`;
	}

	getCookie( name ) {
		const cookies = document.cookie.split( ';' );

		for ( let cookie of cookies ) {
			const [ cookieName, cookieValue ] = cookie.trim().split( '=' );
			if ( cookieName === name ) {
				return decodeURIComponent( cookieValue );
			}
		}

		return null;
	}

	deleteCookie( name ) {
		document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
	}
}
