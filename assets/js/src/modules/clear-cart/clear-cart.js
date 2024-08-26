const merchant = merchant || {};

const { clear_cart: clearCartObj = {} } = merchant?.setting || {};

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

	/**
	 * Clear the cart AJAX
	 *
	 * @param $button
	 */
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

	/**
	 * Check if time passed as soon as the page loaded and show the alert if so.
	 */
	clearCartPageLoadAlert() {
		const that = this;

		jQuery( window ).on( 'load', function () {
			if ( ! clearCartObj?.auto_clear ) {
				that.deleteClearCartCookie();
				return;
			}

			const expirationTime = that.getCookie( that.clearCartCookie );
			if ( expirationTime && that.getCurrentTime() > expirationTime ) {
				setTimeout( () => that.showClearCartAlert(), 1000 );
			}
		} );
	}

	/**
	 * Show Alert on Clear Cart Button Click
	 */
	clearCartButtonAlert() {
		const $ = jQuery;
		const that = this;

		// Clear Cart On Button Click
		$( document ).on( 'click', '.merchant-clear-cart-button', function( e ) {
			e.preventDefault();
			that.showClearCartAlert( $( this ) );

			// For some reason cookie doesn't get clear automatically when clicking clear button on home page(or others)
			// On that case delete it after a delay
			setTimeout( () => that.deleteClearCartCookie(), 1000 );
		} );
	}

	/**
	 * Auto Clear Cart
	 */
	clearCartAutoAlert() {
		const $ = jQuery;
		const that = this;

		const {
			is_product_single,
			is_cart_page,
			auto_clear,
			total_items,
			threshold,
			added_to_cart_no_ajax,
		} = clearCartObj;

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
		if ( auto_clear && added_to_cart_no_ajax ) {
			const expireTime = that.setClearCartCookie();
			that.timer = setTimeout( () => that.showClearCartAlert(), expireTime );
		}

		// Adding & Updating Qty - AJAX.
		$( document.body ).on( 'wc_fragment_refresh added_to_cart updated_wc_div removed_from_cart', function( event, data, hash, button ) {
			// Cart Page
			if ( event?.type === 'updated_wc_div' && is_cart_page && ! $( '.woocommerce-cart-form' ).length ) {
				that.deleteClearCartCookie();
				return;
			}

			// Product Single
			if ( is_product_single && event?.type === 'wc_fragment_refresh' ) {
				that.deleteClearCartCookie();
				return;
			}

			// If All items removed.
			if ( event?.type === 'removed_from_cart' && ! hash ) {
				return;
			}

			let refreshCookie = total_items >= threshold;

			if ( data && data['.merchant_clear_cart_cart_count'] !== undefined ) {
				refreshCookie = data['.merchant_clear_cart_cart_count'] >= threshold;
				if ( ! refreshCookie ) {
					that.deleteClearCartCookie();
					return;
				}
			}

			if ( auto_clear && refreshCookie ) {
				const expireTime = that.setClearCartCookie();
				that.timer = setTimeout( () => that.showClearCartAlert(), expireTime );
			}
		} );
	}

	/**
	 * Show Alert.
	 *
	 * @param $button
	 */
	showClearCartAlert( $button = null ) {
		const that = this;

		const expirationTime = that.getCookie( that.clearCartCookie );

		if ( ! expirationTime && ! $button.length ) {
			that.deleteClearCartCookie();
			return;
		}

		const message = ( $button && $button.length ) ? clearCartObj?.popup_message : clearCartObj?.popup_message_inactive;

		if ( window.confirm( message ) ) {
			that.deleteClearCartCookie();
			that.clearCartAjax( $button );
		} else {
			if ( ! $button ) {
				const expireTime = that.setClearCartCookie();

				that.timer = setTimeout( () => that.showClearCartAlert(), expireTime );
			}
		}
	}

	/**
	 * Set Clear Cart Cookie.
	 *
	 * @returns {number}
	 */
	setClearCartCookie() {
		clearTimeout( this.timer );

		const { expiration_time, wc_session_expiration_time } = clearCartObj || {};

		const cartExpireDuration = expiration_time * 1000;

		const cartExpireTime = this.getCurrentTime() + cartExpireDuration; // millisecond
		const cookieExpireTime = this.getCurrentTime() + ( wc_session_expiration_time * 1000 ); // millisecond

		this.setCookie( this.clearCartCookie, cartExpireTime, cookieExpireTime );

		return cartExpireDuration;
	}

	/**
	 * Delete Clear Cart Cookie.
	 */
	deleteClearCartCookie() {
		clearTimeout( this.timer );
		this.deleteCookie( this.clearCartCookie );
	}

	/**
	 * Get Current time.
	 *
	 * @param time
	 * @returns {number|Date}
	 */
	getCurrentTime( time = true ) {
		const date = new Date();
		return time ? date.getTime() : date;
	}

	/**
	 * Set Cookie Helper.
	 *
	 * @param name
	 * @param value
	 * @param expiration
	 */
	setCookie( name, value, expiration ) {
		const date = new Date( expiration );

		const expires = `expires=${ date.toUTCString() }`;
		document.cookie = `${ name }=${ value };${ expires };path=/`;
	}

	/**
	 * Get Cookie Helper.
	 *
	 * @param name
	 * @returns {null|string}
	 */
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

	/**
	 * Delete Cookie Helper.
	 *
	 * @param name
	 */
	deleteCookie( name ) {
		document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
	}
}
