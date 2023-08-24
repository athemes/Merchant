/**
 * Merchant Scroll To Top Button.
 * 
 */

'use strict';

var merchant = merchant || {};
merchant.modules = merchant.modules || {};
(function ($) {
  merchant.modules.scrollTop = {
    init: function init() {
      var self = this;
      var $button = $('.merchant-scroll-to-top-button');
      if (!$button.length) {
        return;
      }
      $button.on('click', function () {
        self.onScrollTop();
      });
      $(window).on('scroll.merchant-scroll-to-top', function () {
        self.onScroll($button);
      });
      self.onScroll($button);
    },
    onScroll: function onScroll($button) {
      var rootElement = document.documentElement;
      var scrollTotal = rootElement.scrollHeight - rootElement.clientHeight;
      if (rootElement.scrollTop / scrollTotal >= 0.5) {
        $button.addClass('merchant-show');
      } else {
        $button.removeClass('merchant-show');
      }
    },
    onScrollTop: function onScrollTop() {
      $('html, body').animate({
        scrollTop: 0
      }, 200, function () {
        if (window.history.pushState) {
          window.history.pushState(null, null, ' ');
        } else {
          window.location.hash = ' ';
        }
      });
    }
  };
  merchant.modules.scrollTop.init();
})(jQuery);