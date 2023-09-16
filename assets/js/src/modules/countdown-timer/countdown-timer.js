;(function ($, window, document, undefined) {
    'use strict';

    let countdownTimer, countdownTimerDate, countdownMinExpiration, countdownMaxExpiration;

    const countdownTimerId = 'merchant-countdown-timer';
    const countdownTimerCookie = 'merchant_countdown_timer_date';
    const countdownTimerCoolOffCookie = 'merchant_countdown_timer_cool_off_date';

    const setCookie = (name, value, expirationDays) => {
        const date = new Date();
        date.setTime(date.getTime() + (expirationDays * 24 * 60 * 60 * 1000)); // Calculate expiration date

        const expires = "expires=" + date.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/";
    }

    const getCookie = (name) => {
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
    }

    const deleteCookie = (name) => {
        document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }

    const updateCountdown = () => {
        const now = new Date().getTime();
        const minExpirationTime = countdownMinExpiration.getTime();
        const maxExpirationTime = countdownMaxExpiration.getTime();

        if (now < minExpirationTime) {
            // The current time is before the minimum expiration deadline
            displayCountdown(maxExpirationTime - now);
        } else if (now > maxExpirationTime) {
            // The current time is after the maximum expiration deadline
            destroyCountDown()
        } else {
            // The current time is between the minimum and maximum expiration deadlines
            destroyCountDown()
        }
    }

    const displayCountdown = (timeLeft) => {
        const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, '0');
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0');
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000).toString().padStart(2, '0');

        document.getElementById(countdownTimerId).innerHTML = `${days} days ${hours}:${minutes}:${seconds}`;

        if (timeLeft > 0) {
            setTimeout(updateCountdown, 1000);
        }
    }

    const destroyCountDown = () => {
        displayCountdown(0);
        deleteCookie(countdownTimerCookie)
        setCoolOffDate()
        countdownTimer.hide();
    }

    const setCoolOffDate = () => {
        if (!getCookie(countdownTimerCoolOffCookie)) {
            const coolOffDate = new Date();
            coolOffDate.setMinutes(coolOffDate.getMinutes() + parseInt(countdownTimer.attr('data-cop')))
            setCookie(countdownTimerCoolOffCookie, coolOffDate, 1)
        }
    }


    $(document).ready(function () {
        countdownTimer = $('.merchant-countdown-timer');

        if (countdownTimer.length) {
            const maxExpirationHours = parseInt(countdownTimer.attr('data-max'));
            const minExpirationHours = maxExpirationHours - parseInt(countdownTimer.attr('data-min'));

            let startCountdown = true;

            if (getCookie(countdownTimerCoolOffCookie)) {
                const coolOffPeriodDate = new Date(getCookie(countdownTimerCoolOffCookie));
                const now = new Date().getTime();

                if (now > coolOffPeriodDate) {
                    deleteCookie(countdownTimerCoolOffCookie)
                } else {
                    startCountdown = false;
                }
            }

            if (startCountdown) {
                countdownTimer.show();

                if (!getCookie(countdownTimerCookie)) {
                    countdownTimerDate = new Date();
                    setCookie(countdownTimerCookie, countdownTimerDate, 9)
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


