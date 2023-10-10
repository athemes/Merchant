/**
 * Cookie Banner.
 * 
 */

'use strict';

var merchant = merchant || {};
merchant.modules = merchant.modules || {};
(function ($) {
  merchant.modules.cookieBanner = {
    init: function init() {
      var self = this;
      var $banner = $('.merchant-cookie-banner');
      if (!$banner.length) {
        return;
      }
      if (!self.getCookie('merchant_cookie_banner')) {
        $('.merchant-cookie-banner-button, .merchant-cookie-close-button').on('click', function (e) {
          e.preventDefault();
          self.setCookie('merchant_cookie_banner', true, window.merchant.setting.cookie_banner_duration);
          $banner.removeClass('merchant-show');
        });
        $banner.addClass('merchant-show');
      }
    },
    setCookie: function setCookie(cookieName, cookieValue, expDays) {
      var dateObj = new Date();
      dateObj.setTime(dateObj.getTime() + expDays * 24 * 60 * 60 * 1000);
      document.cookie = cookieName + "=" + cookieValue + ";expires=" + dateObj.toUTCString() + "";
    },
    getCookie: function getCookie(cookieName) {
      var name = cookieName + '=';
      var cookies = decodeURIComponent(document.cookie).split(';');
      for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        while (cookie.charAt(0) === ' ') {
          cookie = cookie.substring(1);
        }
        if (cookie.indexOf(name) === 0) {
          return cookie.substring(name.length, cookie.length);
        }
      }
      return '';
    }
  };
  $(document).ready(function () {
    merchant.modules.cookieBanner.init();
  });
})(jQuery);