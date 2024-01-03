/**
 * Merchant Scroll To.
 * 
 */

'use strict';

var merchant = merchant || {};
merchant.scrollTo = {
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
    var elements = document.querySelectorAll('[data-merchant-scroll-to]');
    if ('null' === typeof elements) {
      return;
    }
    elements.forEach(function (el) {
      el.addEventListener('click', function (event) {
        event.preventDefault();
        merchant.scrollTo.scrollTo(el);
      });
    });
  },
  scrollTo: function scrollTo(el) {
    var scrollToSelector = el.getAttribute('data-merchant-scroll-to');
    var scrollToOffset = el.getAttribute('data-merchant-scroll-to-offset') || 0;
    if (!scrollToSelector) {
      return;
    }
    var elClientRect = document.querySelector(scrollToSelector).getBoundingClientRect();
    var scrollPosition = parseInt(elClientRect.top + window.pageYOffset - scrollToOffset);
    window.dispatchEvent(new Event('mrc-scrollto.before.scrolling'), {
      eventData: {
        context: el
      }
    });
    window.scrollTo({
      top: scrollPosition,
      behavior: 'smooth'
    });
    setTimeout(function () {
      window.dispatchEvent(new Event('mrc-scrollto.after.scrolling', {
        eventData: {
          context: el
        }
      }));
    }, 500);
  }
};

// Initialize.
merchant.scrollTo.domReady(function () {
  merchant.scrollTo.init();
});