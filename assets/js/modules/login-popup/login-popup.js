'use strict';

var merchant = merchant || {};
merchant.modules = merchant.modules || {};
(function ($) {
  merchant.modules.loginPopups = {
    init: function init() {
      var self = this;
      var $body = $('body');
      var $toggle = $('.merchant-login-popup-toggle');
      if (!$toggle.length) {
        return;
      }
      var $popupBody = $('.merchant-login-popup-body');
      $toggle.on('click', function (e) {
        e.preventDefault();
        $body.toggleClass('merchant-login-popup-show');
        if ($popupBody.hasClass('merchant-show')) {
          $popupBody.removeClass('merchant-show');
        } else {
          setTimeout(function () {
            $popupBody.addClass('merchant-show');
          }, 200);
        }
      });
      var $footerToggle = $('.merchant-login-popup-footer a');
      if ($footerToggle.length) {
        var flag = true;
        $footerToggle.on('click', function (e) {
          e.preventDefault();
          var $content = $('.merchant-login-popup-content');
          $(this).parent().toggleClass('merchant-show').siblings().toggleClass('merchant-show');
          if (flag === true) {
            $content.find('.col-1').hide();
            $content.find('.col-2').show();
            flag = false;
          } else {
            $content.find('.col-1').show();
            $content.find('.col-2').hide();
            flag = true;
          }
        });
      }
    }
  };
  $(document).ready(function () {
    merchant.modules.loginPopups.init();
  });
})(jQuery);