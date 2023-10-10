"use strict";

;
(function ($, window, document, undefined) {
  'use strict';

  $(document).ready(function () {
    $('[data-id="links"] input[type="text"]').each(function () {
      $(this).on('change keyup', function () {
        var input = $(this).val();
        var layout = $(this).closest('.layout');
        var socialMediaIcons = ['facebook', 'instagram', 'twitter', 'tiktok', 'pinterest', 'whatsapp', 'telegram', 'flickr', 'linkedin', 'youtube', 'discord', 'xing', 'dribble', 'vk', 'weibo', 'vimeo', 'mix', 'behance', 'spotify', 'soundcloud', 'twitch', 'etsy', 'bandcamp', 'reddit', 'github'];
        for (var i = 0; i < socialMediaIcons.length; i++) {
          var socialMediaIcon = socialMediaIcons[i];
          if (input.includes(socialMediaIcon)) {
            layout.find('input[type=radio][value="' + socialMediaIcon + '"]').prop('checked', true);
          }
        }
      });
    });
  });
})(jQuery, window, document);