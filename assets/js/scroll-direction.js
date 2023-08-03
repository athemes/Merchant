/**
 * Merchant Scroll Direction.
 * 
 */

'use strict';

var merchant = merchant || {};
merchant.scrollDirection = {
  domReady: function domReady(fn) {
    if (typeof fn !== 'function') {
      return;
    }
    if (document.readyState === 'interactive' || document.readyState === 'complete') {
      return fn();
    }
    document.addEventListener('DOMContentLoaded', fn, false);
  },
  init: function init() {
    var elements = document.querySelectorAll('.merchant-sticky-add-to-cart-wrapper.hide-when-scroll'),
      body = document.getElementsByTagName('body')[0];
    if ('null' === typeof elements) {
      return;
    }
    var lastScrollTop = 0;
    window.addEventListener('scroll', function () {
      var scroll = window.pageYOffset || document.documentElement.scrollTop;
      if (scroll > lastScrollTop) {
        body.classList.remove('merchant-scrolling-up');
        body.classList.add('merchant-scrolling-down');
      } else {
        body.classList.remove('merchant-scrolling-down');
        body.classList.add('merchant-scrolling-up');
      }
      lastScrollTop = scroll <= 0 ? 0 : scroll;
    }, false);
  }
};

// Initialize.
merchant.scrollDirection.domReady(function () {
  merchant.scrollDirection.init();
});