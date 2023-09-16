"use strict";

;
(function ($, window, document, undefined) {
  'use strict';

  var countdownTimer, countdownTimerDate, countdownMinExpiration, countdownMaxExpiration;
  var countdownTimerId = 'merchant-countdown-timer';
  var countdownTimerCookie = 'merchant_countdown_timer_date';
  var countdownTimerCoolOffCookie = 'merchant_countdown_timer_cool_off_date';
  var setCookie = function setCookie(name, value, expirationDays) {
    var date = new Date();
    date.setTime(date.getTime() + expirationDays * 24 * 60 * 60 * 1000); // Calculate expiration date

    var expires = "expires=" + date.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
  };
  var getCookie = function getCookie(name) {
    var cookieArr = document.cookie.split(';');
    for (var i = 0; i < cookieArr.length; i++) {
      var cookiePair = cookieArr[i].split('=');
      var cookieName = cookiePair[0].trim();
      var cookieValue = cookiePair[1].trim();
      if (cookieName === name) {
        return decodeURIComponent(cookieValue);
      }
    }
    return null;
  };
  var deleteCookie = function deleteCookie(name) {
    document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
  };
  var updateCountdown = function updateCountdown() {
    var now = new Date().getTime();
    var minExpirationTime = countdownMinExpiration.getTime();
    var maxExpirationTime = countdownMaxExpiration.getTime();
    if (now < minExpirationTime) {
      // The current time is before the minimum expiration deadline
      displayCountdown(maxExpirationTime - now);
    } else if (now > maxExpirationTime) {
      // The current time is after the maximum expiration deadline
      destroyCountDown();
    } else {
      // The current time is between the minimum and maximum expiration deadlines
      destroyCountDown();
    }
  };
  var displayCountdown = function displayCountdown(timeLeft) {
    var days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
    var hours = Math.floor(timeLeft % (1000 * 60 * 60 * 24) / (1000 * 60 * 60)).toString().padStart(2, '0');
    var minutes = Math.floor(timeLeft % (1000 * 60 * 60) / (1000 * 60)).toString().padStart(2, '0');
    var seconds = Math.floor(timeLeft % (1000 * 60) / 1000).toString().padStart(2, '0');
    document.getElementById(countdownTimerId).innerHTML = "".concat(days, " days ").concat(hours, ":").concat(minutes, ":").concat(seconds);
    if (timeLeft > 0) {
      setTimeout(updateCountdown, 1000);
    }
  };
  var destroyCountDown = function destroyCountDown() {
    displayCountdown(0);
    deleteCookie(countdownTimerCookie);
    setCoolOffDate();
    countdownTimer.hide();
  };
  var setCoolOffDate = function setCoolOffDate() {
    if (!getCookie(countdownTimerCoolOffCookie)) {
      var coolOffDate = new Date();
      coolOffDate.setMinutes(coolOffDate.getMinutes() + parseInt(countdownTimer.attr('data-cop')));
      setCookie(countdownTimerCoolOffCookie, coolOffDate, 1);
    }
  };
  $(document).ready(function () {
    countdownTimer = $('.merchant-countdown-timer');
    if (countdownTimer.length) {
      var maxExpirationHours = parseInt(countdownTimer.attr('data-max'));
      var minExpirationHours = maxExpirationHours - parseInt(countdownTimer.attr('data-min'));
      var startCountdown = true;
      if (getCookie(countdownTimerCoolOffCookie)) {
        var coolOffPeriodDate = new Date(getCookie(countdownTimerCoolOffCookie));
        var now = new Date().getTime();
        if (now > coolOffPeriodDate) {
          deleteCookie(countdownTimerCoolOffCookie);
        } else {
          startCountdown = false;
        }
      }
      if (startCountdown) {
        countdownTimer.show();
        if (!getCookie(countdownTimerCookie)) {
          countdownTimerDate = new Date();
          setCookie(countdownTimerCookie, countdownTimerDate, 9);
        } else {
          countdownTimerDate = new Date(getCookie(countdownTimerCookie));
        }
        countdownMaxExpiration = new Date(countdownTimerDate.getTime());
        countdownMaxExpiration.setHours(countdownMaxExpiration.getHours() + maxExpirationHours);
        countdownMinExpiration = new Date(countdownTimerDate.getTime());
        countdownMinExpiration.setHours(countdownMinExpiration.getHours() + minExpirationHours);
        updateCountdown();
      }
    }
  });
})(jQuery, window, document);