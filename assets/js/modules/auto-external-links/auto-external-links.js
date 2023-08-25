/**
 * Auto External Links.
 * 
 */

'use strict';

var merchant = merchant || {};
merchant.modules = merchant.modules || {};
(function ($) {
  merchant.modules.autoExternalLinks = {
    init: function init() {
      var self = this;
      var $links = $(document).find('a');
      if (!$links.length) {
        return;
      }
      $links.each(function () {
        var $link = $(this);
        var hostname = $link.prop('hostname');
        if (hostname && hostname !== window.location.hostname) {
          $link.attr('target', '_blank');
          $link.attr('rel', 'noreferrer noopener');
        }
      });
    }
  };
  $(document).ready(function () {
    merchant.modules.autoExternalLinks.init();
  });
})(jQuery);