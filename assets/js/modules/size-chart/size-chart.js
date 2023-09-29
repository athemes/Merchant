'use strict';

var merchant = merchant || {};
merchant.modules = merchant.modules || {};
(function ($) {
  merchant.modules.sizeChart = {
    init: function init($wrapper) {
      var $sizeChart = $wrapper ? $wrapper.find('.merchant-product-size-chart') : $('.merchant-product-size-chart');
      if (!$sizeChart.length) {
        return;
      }
      var $body = $('body');
      var $button = $sizeChart.find('.merchant-product-size-chart-button a');
      var $modal = $sizeChart.find('.merchant-product-size-chart-modal');
      $button.on('click', function (e) {
        e.preventDefault();
        $body.addClass('merchant-product-size-chart-modal-open');
      });
      $modal.on('click', function (e) {
        e.preventDefault();
        var $target = $(e.target);
        var $content = $sizeChart.find('.merchant-product-size-chart-modal-content');
        if ($target.is($modal) || $target.closest('.merchant-product-size-chart-modal-close').length) {
          $content.scrollTop(0);
          $body.removeClass('merchant-product-size-chart-modal-open');
        }
      });
      var $tabs = $sizeChart.find('.merchant-product-size-chart-modal-tab');
      var $tables = $sizeChart.find('.merchant-product-size-chart-modal-table');
      if ($tabs.length && $tables.length) {
        $tabs.each(function () {
          var $tab = $(this);
          $tab.on('click', function () {
            $tab.addClass('active').siblings().removeClass('active');
            $tables.eq($tab.index()).addClass('active').siblings().removeClass('active');
          });
        });
      }
    }
  };
  $(document).ready(function () {
    merchant.modules.sizeChart.init();
  });
})(jQuery);