"use strict";

;
(function ($) {
  'use strict';

  $.fn.merchantMetabox = function () {
    function initSelectAjax($selectAjax) {
      $selectAjax.each(function () {
        var $select = $(this).find('select');
        var source = $select.data('source');
        var config = window.merchant_metabox;
        $select.select2({
          width: '100%',
          minimumInputLength: 1,
          ajax: {
            url: config.ajaxurl,
            dataType: 'json',
            delay: 250,
            cache: true,
            data: function data(params) {
              return {
                action: 'merchant_select_ajax',
                nonce: config.ajaxnonce,
                term: params.term,
                source: source
              };
            },
            processResults: function processResults(response, params) {
              if (response.success) {
                return {
                  results: response.data
                };
              }
              return {};
            }
          }
        });
        $selectAjax.find('.select2-selection--multiple').append('<span class="merchant-select2-clear"></span>');
      });
    }
    return this.each(function () {
      var $this = $(this);
      var $tabs = $this.find('.merchant-metabox-tab');
      var $contents = $this.find('.merchant-metabox-content');
      $tabs.each(function () {
        var $tab = $(this);
        $tab.on('click', function (e) {
          e.preventDefault();
          var $content = $contents.eq($tab.index());
          $tab.addClass('active').siblings().removeClass('active');
          $content.addClass('active').siblings().removeClass('active');
          $(document).trigger('merchant-metabox-content-show', $content);
        });
      });
      var $flexibleContent = $contents.find('.merchant-metabox-field-flexible-content');
      if ($flexibleContent.length) {
        $flexibleContent.each(function () {
          var $selectAjaxSelector = '.merchant-metabox-field-flexible-content-select-ajax';
          var $selectAjax = $($selectAjaxSelector);
          if ($selectAjax.length) {
            initSelectAjax($selectAjax);
          }
          var $list = $(this).find('.merchant-metabox-field-flexible-content-list');
          $list.sortable({
            axis: 'y',
            cursor: 'move',
            helper: 'original',
            handle: '.merchant-metabox-field-flexible-content-move',
            stop: function stop(event, ui) {
              $list.find('> li').each(function (index) {
                var $countSelector = '.merchant-metabox-field-flexible-content-item-count';
                var $count = $(this).find($countSelector).text();
                var $inputIndex = parseInt($count) - 1;
                $(this).find($countSelector).text(index);
                $(this).find('input, select').each(function () {
                  if ($(this).attr('name')) {
                    $(this).attr('name', $(this).attr('name').replace('[' + $inputIndex + ']', '[' + (index - 1) + ']'));
                  }
                });
              });
            }
          });
          $flexibleContent.find('.merchant-metabox-field-flexible-content-add-button').on('click', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            $(this).parent().find('.merchant-metabox-field-flexible-content-add-list').toggleClass('active');
          });
          $flexibleContent.find('.merchant-metabox-field-flexible-content-add').on('click', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var $layouts = $('.merchant-metabox-field-flexible-content-list[data-id=' + $(this).data('id') + ']');
            var $layout = $(this).data('layout');
            var $items = $layouts.find('> li');
            var $item = $layouts.find('> li').first().clone(true);
            $item.find(' > div').each(function () {
              if ($(this).data('layout') !== $layout) {
                $(this).remove();
              } else {
                $(this).children().appendTo($(this).parent());
                $(this).remove();
              }
            });
            $item.find('input, select').each(function () {
              if ($(this).data('name')) {
                $(this).attr('name', $(this).data('name').replace('0', $items.length - 1));
              }
            });
            $item.find('.merchant-metabox-field-flexible-content-item-count').text($items.length);
            $item.find('.merchant-metabox-field-flexible-content-select-ajax-clone').each(function () {
              $(this).removeClass('merchant-metabox-field-flexible-content-select-ajax-clone');
              $(this).addClass('merchant-metabox-field-flexible-content-select-ajax');
            });
            $item.removeClass('hidden');
            $layouts.append($item);
            $selectAjax = $($selectAjaxSelector);
            if ($selectAjax.length) {
              initSelectAjax($selectAjax);
            }
            $(this).parent().removeClass('active');
            $layouts.removeClass('empty');
          });
          $flexibleContent.find('.merchant-metabox-field-flexible-content-remove').on('click', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var $item = $(this).closest('li');
            var $layouts = $item.parent();
            $item.remove();
            if ($layouts.find('>li').length === 1) {
              $layouts.addClass('empty');
            }
          });
        });
      }
      var $repeater = $contents.find('.merchant-metabox-field-repeater, .merchant-metabox-field-flexible-content-repeater');
      if ($repeater.length) {
        $repeater.each(function () {
          var $list = $(this).find('ul');
          $list.sortable({
            axis: 'y',
            cursor: 'move',
            helper: 'original',
            handle: '.merchant-metabox-field-repeater-move'
          });
          $repeater.find('.merchant-metabox-field-repeater-add').on('click', function (e) {
            e.preventDefault();
            var $items = $list.find('li');
            var $item = $list.find('li').first().clone(true);
            var $input = $item.find('input');
            if ($item.find('.merchant-metabox-field-repeater-list-item-fields').length) {
              $item.find('input').each(function () {
                $(this).attr('name', $(this).data('name').replace('0', $items.length - 1));
              });
            } else {
              $input.attr('name', $input.data('name'));
            }
            $item.removeClass('hidden');
            $list.append($item);
          });
          $repeater.find('.merchant-metabox-field-repeater-remove').on('click', function (e) {
            e.preventDefault();
            $(this).closest('li').remove();
          });
        });
      }
      var $uploads = $contents.find('.merchant-metabox-field-uploads');
      if ($uploads.length) {
        $uploads.each(function () {
          var $list = $(this).find('ul');
          $list.sortable({
            axis: 'y',
            cursor: 'move',
            helper: 'original',
            handle: '.merchant-metabox-field-uploads-move'
          });
          $uploads.find('.merchant-metabox-field-uploads-add').on('click', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var $list = $(this).parent().find('ul');
            var $items = $list.find('li');
            var $item = $items.first().clone(true);
            $item.find('input').each(function () {
              $(this).attr('name', $(this).data('name').replace('0', $items.length));
            });
            $item.removeClass('hidden');
            $list.append($item);
          });
          var wpMediaFrame;
          var wpMediaInput;
          $uploads.find('.merchant-metabox-field-uploads-upload').on('click', function (e) {
            e.preventDefault();
            wpMediaInput = $(this).closest('li').find(' > input');
            if (!wpMediaInput.attr('name').length) {
              return;
            }
            if (wpMediaFrame && wpMediaFrame.options.library.type === $list.data('library')) {
              wpMediaFrame.open();
              return;
            }
            wpMediaFrame = window.wp.media({
              library: {
                type: $list.data('library') || 'image'
              }
            }).open();
            wpMediaFrame.on('select', function () {
              var attachment = wpMediaFrame.state().get('selection').first().toJSON();
              wpMediaInput.val(attachment.url);
            });
          });
          $uploads.find('.merchant-metabox-field-uploads-thumbnail-upload').on('click', function (e) {
            e.preventDefault();
            wpMediaInput = $(this).parent().find('input');
            if (!wpMediaInput.attr('name').length) {
              return;
            }
            if (wpMediaFrame && wpMediaFrame.options.library.type === 'image') {
              wpMediaFrame.open();
              return;
            }
            wpMediaFrame = window.wp.media({
              library: {
                type: 'image'
              }
            }).open();
            wpMediaFrame.on('select', function () {
              var attachment = wpMediaFrame.state().get('selection').first().toJSON();
              var thumbnail = wpMediaInput.parent();
              wpMediaInput.val(attachment.id);
              thumbnail.find('span').hide();
              thumbnail.find('img').remove();
              thumbnail.find('.merchant-metabox-field-uploads-thumbnail-remove').show();
              thumbnail.find('.merchant-metabox-field-uploads-thumbnail-upload').append($('<img>').attr({
                'src': attachment.url
              }));
            });
          });
          $uploads.find('.merchant-metabox-field-uploads-thumbnail-remove').on('click', function (e) {
            e.preventDefault();
            var thumbnail = $(this).parent();
            thumbnail.find('span').show();
            thumbnail.find('img').remove();
            thumbnail.find('input').val('');
            thumbnail.find('.merchant-metabox-field-uploads-thumbnail-remove').hide();
          });
          $uploads.find('.merchant-metabox-field-uploads-remove').on('click', function (e) {
            e.preventDefault();
            $(this).closest('li').remove();
          });
        });
      }
      var $sizeChart = $contents.find('.merchant-metabox-field-size-chart');
      if ($sizeChart.length) {
        $sizeChart.on('multidimensional', function (event, $table) {
          var $wrap = $table || $sizeChart;
          $wrap.find('input').each(function () {
            var $input = $(this);
            var liIndex = Math.max(0, $input.closest('li').index() - 1);
            var trIndex = Math.max(0, $input.closest('tr').index() - 1);
            var tdIndex = Math.max(0, $input.closest('td').index());
            this.name = this.name.replace(/(\[\d+\])\[sizes\](\[\d+\])(\[\d+\])/, '[' + liIndex + '][sizes][' + trIndex + '][' + tdIndex + ']');
            this.name = this.name.replace(/(\[\d+\])\[name\]/, '[' + liIndex + '][name]');
          });
        });
        $sizeChart.each(function () {
          var $list = $(this).find('ul');
          $sizeChart.on('click', '.merchant-add', function (e) {
            e.preventDefault();
            var $item = $list.find('li').first().clone(true);
            var $input = $item.find('input');
            $input.each(function () {
              $(this).attr('name', $(this).data('name'));
              $(this).removeAttr('data-name');
            });
            $item.removeClass('hidden');
            $list.append($item);
            $sizeChart.trigger('multidimensional', [$item]);
          });
          $sizeChart.on('click', '.merchant-add-col', function (e) {
            e.preventDefault();
            var $td = $(this).closest('td');
            var $table = $(this).closest('table');
            var $columns = $(this).closest('tbody').find('tr td:nth-child(' + ($td.index() + 1) + ')');
            $columns.each(function () {
              var $column = $(this);
              var $clone = $column.clone(true);
              $clone.find('input').val('');
              $column.after($clone);
            });
            $sizeChart.trigger('multidimensional', [$table]);
          });
          $sizeChart.on('click', '.merchant-del-col', function (e) {
            e.preventDefault();
            var $td = $(this).closest('td');
            var $table = $(this).closest('table');
            var $count = $(this).closest('tr').find('td').length;
            var $target = $(this).closest('tbody').find('tr td:nth-child(' + ($td.index() + 1) + ')');
            if ($count > 2) {
              $target.remove();
            } else {
              $target.find('input').val('');
            }
            $sizeChart.trigger('multidimensional', [$table]);
          });
          $sizeChart.on('click', '.merchant-add-row', function (e) {
            e.preventDefault();
            var $tr = $(this).closest('tr');
            var $table = $(this).closest('table');
            var $clone = $tr.clone(true);
            $clone.find('input').val('');
            $tr.after($clone);
            $sizeChart.trigger('multidimensional', [$table]);
          });
          $sizeChart.on('click', '.merchant-del-row', function (e) {
            e.preventDefault();
            var $tr = $(this).closest('tr');
            var $table = $(this).closest('table');
            var $count = $(this).closest('tbody').find('tr').length;
            if ($count > 2) {
              $tr.remove();
            } else {
              $tr.find('input').val('');
            }
            $sizeChart.trigger('multidimensional', [$table]);
          });
          $sizeChart.on('click', '.merchant-remove', function (e) {
            e.preventDefault();
            $(this).closest('li').remove();
            $sizeChart.trigger('multidimensional');
          });
          $sizeChart.on('click', '.merchant-duplicate', function (e) {
            e.preventDefault();
            var $li = $(this).closest('li');
            var $clone = $li.clone(true);
            $li.after($clone);
            $sizeChart.trigger('multidimensional');
          });
        });
      }
      var $mediaField = $('.merchant-metabox-field-media');
      if ($mediaField.length) {
        $mediaField.each(function () {
          var $field = $(this);
          var $input = $field.find('.merchant-metabox-field-media-input');
          var $image = $field.find('.merchant-metabox-field-media-preview img');
          var $upload = $field.find('.merchant-metabox-field-media-upload');
          var $remove = $field.find('.merchant-metabox-field-media-remove');
          var placeholder = $image.data('placeholder');
          var wpMediaFrame;
          $upload.on('click', function (e) {
            e.preventDefault();
            if (wpMediaFrame) {
              wpMediaFrame.open();
              return;
            }
            wpMediaFrame = window.wp.media({
              library: {
                type: 'image'
              }
            });
            wpMediaFrame.on('select', function () {
              var attachment = wpMediaFrame.state().get('selection').first().toJSON();
              var thumbnail;
              if (attachment && attachment.sizes && attachment.sizes.thumbnail) {
                thumbnail = attachment.sizes.thumbnail.url;
              } else {
                thumbnail = attachment.url;
              }
              $input.val(attachment.id);
              $image.attr('src', thumbnail);
              $remove.removeClass('hidden');
            });
            wpMediaFrame.open();
          });
          $remove.on('click', function (e) {
            e.preventDefault();
            $input.val('');
            $image.attr('src', placeholder);
            $remove.addClass('hidden');
          });
        });
      }
      var $selectAjax = $('.merchant-metabox-field-select-ajax');
      if ($selectAjax.length) {
        initSelectAjax($selectAjax);
      }
      var $attributes = $('.merchant-metabox-field-wc-attributes');
      if ($attributes.length) {
        $attributes.each(function () {
          var $sortable = $(this).find('ul');
          $sortable.sortable({
            axis: 'y',
            cursor: 'move',
            helper: 'original'
          });
        });
      }
      $(document).on('merchant-metabox-content-show', function (event, content) {
        var $content = $(content);
        if (!$content.data('code-editor-initalized')) {
          var $codeEditors = $('.merchant-metabox-field-code-editor', $content);
          if ($codeEditors.length) {
            $codeEditors.each(function () {
              var $textarea = $(this).find('textarea');
              var editorSettings = wp.codeEditor.defaultSettings || {};
              editorSettings.codemirror = _.extend({}, editorSettings.codemirror, {
                gutters: []
              });
              var editor = wp.codeEditor.initialize($textarea, editorSettings);
              editor.codemirror.on('keyup', function (instance) {
                instance.save();
              });
            });
          }
          $content.data('code-editor-initalized', true);
        }
      });
      var $depends = $contents.find('[data-depend-on]');
      if ($depends.length) {
        $depends.each(function () {
          var $depend = $(this);
          var $target = $contents.find('[name="' + $depend.data('depend-on') + '"]');
          if (!$target.data('depend-on')) {
            $target.on('change', function () {
              var $dependOn = $contents.find('[data-depend-on="' + $depend.data('depend-on') + '"]');
              if ($(this).is(':checked')) {
                $dependOn.removeClass('merchant-metabox-field-hidden');
              } else {
                $dependOn.addClass('merchant-metabox-field-hidden');
              }
            });
            $target.data('depend-on', true);
          }
        });
      }
    });
  };
  $(document).ready(function ($) {
    $('.merchant-metabox').merchantMetabox();
  });
})(jQuery);