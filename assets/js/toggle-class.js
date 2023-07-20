/**
 * Merchant Toggle Class.
 * 
 */

'use strict';

var merchant = merchant || {};

/**
 * Toggle any class on any element.
 * 
 */
merchant.toggleClass = {
  init: function init(event, el, triggerEvent) {
    event.preventDefault();
    event.stopPropagation();
    var selector = document.querySelector(el.getAttribute('data-merchant-selector')),
      removeClass = el.getAttribute('data-merchant-toggle-class-remove'),
      classname = el.getAttribute('data-merchant-toggle-class'),
      classes = selector.classList;
    if (typeof removeClass === 'string') {
      classes.remove(removeClass);
    }
    classes.toggle(classname);
    if (triggerEvent) {
      var ev = document.createEvent('HTMLEvents');
      ev.initEvent(triggerEvent, true, false);
      window.dispatchEvent(ev);
    }
  }
};

/**
 * Toggle any class wheter certain amoung of pixels are scrolled.
 * 
 */
merchant.scrollToggleClass = {
  init: function init() {
    var els = document.querySelectorAll('[data-merchant-scroll-toggle-class]');
    if (!els.length) {
      return;
    }
    els.forEach(function (el) {
      window.addEventListener('scroll', merchant.scrollToggleClass.scrollEventHandler.bind(null, el));
    });
  },
  scrollEventHandler: function scrollEventHandler(el) {
    var scrollPosition = window.scrollY,
      scrollOffset = Number(el.getAttribute('data-merchant-scroll-toggle-class-offset')),
      classname = el.getAttribute('data-merchant-scroll-toggle-class'),
      bodyClasses = document.body.classList;
    if (scrollPosition >= scrollOffset && 0 !== scrollPosition) {
      bodyClasses.add(classname);
    } else {
      bodyClasses.remove(classname);
    }
  }
};
document.addEventListener('DOMContentLoaded', function () {
  merchant.scrollToggleClass.init();
});