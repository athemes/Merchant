/**
 * Merchant side cart
 *
 */

jQuery(document).ready(function($) {

  if (merchant.setting.hasOwnProperty('show_after_add_to_cart_single_product')) {
    if ($('body.single-product') && $('.woocommerce-notices-wrapper').is(':visible')) {
      $('body').toggleClass('merchant-floating-side-mini-cart-show');
      $(window).trigger('merchant.floating-mini-cart-resize');
    }
  }

  // Add to cart AJAX event.
  if (merchant.setting.hasOwnProperty('add_to_cart_slide_out')) {
    $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button, $context) {
      if ( $context !== 'side-cart' ) {
        $('body').toggleClass('merchant-floating-side-mini-cart-show')
      }
      $(window).trigger('merchant.floating-mini-cart-resize');
    });
  }

  // On cart URL click
  if (merchant.setting.hasOwnProperty('cart_url')) {
    $('[href="' + merchant.setting.cart_url + '"]').on('click', function(e) {
      e.preventDefault();
      $(window).trigger('merchant.floating-mini-cart-resize');
      $('body').toggleClass('merchant-floating-side-mini-cart-show');
    });
  }

  // Update Product quantity in Side Cart
  if ( merchant.setting.hasOwnProperty('add_to_cart_slide_out') ) {
    let debounceTimer;

    // Update quantity on plus/minus click
    $( document ).on( 'click', '.js-merchant-quantity-btn', function ( e ) {
      e.preventDefault();

      const $btn = $( this );

      const $input = $btn.closest( '.merchant-quantity-wrap' ).find( '.js-update-quantity' );
      if ( ! $input.length ) {
        return;
      }

      let quantity = +( $input.val() || 1 );
      const minimum = +( $input.attr( 'min' ) );
      const maximum = +( $input.attr('max') );
      const stepSize = Math.round( parseFloat( $input.attr( 'step' ) ) );

      if ( $btn.hasClass( 'merchant-quantity-plus' ) ) {
        quantity += stepSize;
        quantity = maximum && maximum !== -1 ? Math.min( quantity, maximum ) : quantity;
      } else if ( $btn.hasClass( 'merchant-quantity-minus' ) ) {
        quantity -= stepSize;
        quantity = minimum ? Math.max( quantity, minimum ) : quantity;
      }

      $input.val( quantity );
      merchant_update_side_cart_quantity( $input );
    } );

    // Update quantity on input value change
    $( document ).on( 'input change', '.js-update-quantity', function ( e ) {
      e.preventDefault();
      merchant_update_side_cart_quantity( $( this ) );
    } );

    // Update quantity helper
    function merchant_update_side_cart_quantity( $input ) {
      const { ajax_url, side_cart_nonce } = merchant.setting || {};

      if ( ! $input.length || ! ajax_url || ! side_cart_nonce ) {
        return;
      }

      const cartItemKey = $input.attr( 'name' );
      const quantity = Math.round( parseFloat( $input.val() || 1 ) );

      const $cart_item = $input.closest( '.js-side-cart-item' );

      // Clear previous timer
      clearTimeout( debounceTimer );

      // Set a new timer to delay the AJAX request
      debounceTimer = setTimeout( () => {
        $.ajax( {
          type: 'POST',
          url: ajax_url,
          data: {
            action: 'update_side_cart_quantity',
            cart_item_key: cartItemKey,
            quantity: quantity,
            nonce: side_cart_nonce,
          },
          beforeSend: function () {
            if ( $cart_item.length ) {
              $cart_item.block( {
                message: null,
                overlayCSS: {
                  background: '#fff',
                  opacity: 0.6,
                },
              } );
            }
          },
          success: ( response ) => {
            if ( ! response || ! response.fragments ) {
              return;
            }

            $( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, $input, 'side-cart' ] );

            if ( $cart_item.length ) {
              $cart_item.unblock();
            }
          },
          error: ( error ) => {
            console.log( 'Error:', error );
          },
        } );
      }, 350 );
    }
  }
});
