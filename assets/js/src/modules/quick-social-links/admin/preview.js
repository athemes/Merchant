;(function ($, window, document, undefined) {
  'use strict';

  $(document).ready(function() {
    $('[data-id="links"] input[type="text"]').each(function() {
      $(this).on('change keyup', function() {
        const input  = $(this).val();
        const layout = $(this).closest('.layout');

        const socialMediaIcons = [
          'facebook',
          'instagram',
          'twitter',
          'tiktok',
          'pinterest',
          'whatsapp',
          'telegram',
          'flickr',
          'linkedin',
          'youtube',
          'discord',
          'xing',
          'dribble',
          'vk',
          'weibo',
          'vimeo',
          'mix',
          'behance',
          'spotify',
          'soundcloud',
          'twitch',
          'etsy',
          'bandcamp',
          'reddit',
          'github',
          'bsky',
        ];

        for (let i = 0; i < socialMediaIcons.length; i++) {
          const socialMediaIcon = socialMediaIcons[i];

          if (input.includes(socialMediaIcon)) {
            layout.find('input[type=radio][value="' + socialMediaIcon + '"]').prop('checked', true);
          }
        }
      });
    });
  });

})(jQuery, window, document);