"use strict";

;
(function ($, window, document, undefined) {
  'use strict';

  var deleteCookie = function deleteCookie(name) {
    document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
  };
  $(document).ready(function () {
    deleteCookie('merchant_countdown_timer_cool_off_date');
    deleteCookie('merchant_countdown_timer_date');
  });
})(jQuery, window, document);