"use strict";

;
(function ($, window, document, undefined) {
  'use strict';

  var merchant = merchant || {};
  $(document).ready(function () {
    $('.merchant-module-page-setting-field').each(function () {
      var $field = $(this);
      if ($field.data('condition') && $field.data('condition').length) {
        var condition = $field.data('condition');
        var $target = $('input[name="merchant[' + condition[0] + ']"],select[name="merchant[' + condition[0] + ']"]');
        if ($target.length) {
          $target.on('change merchant.change', function (e) {
            var $element = $(this);
            var passed = false;
            switch (condition[1]) {
              case '==':
                if ($element.attr('type') === 'radio' || $element.attr('type') === 'checkbox') {
                  if ($element.is(':checked') && $element.val() == condition[2]) {
                    passed = true;
                  }
                }
                if ($element.is('select') && $element.val() == condition[2]) {
                  passed = true;
                }
                break;
              case 'any':
                if ($element.attr('type') === 'radio' || $element.attr('type') === 'checkbox') {
                  if ($element.is(':checked') && condition[2].split('|').includes($element.val())) {
                    passed = true;
                  }
                }
                if ($element.is('select') && condition[2].split('|').includes($element.val())) {
                  passed = true;
                }
                break;
            }
            if (passed) {
              $field.removeClass('merchant-hide').addClass('merchant-show');
            } else {
              $field.removeClass('merchant-show').addClass('merchant-hide');
            }
          }).trigger('merchant.change');
        }
      }
    });

    // AjaxSave
    var $ajaxForm = $('.merchant-module-page-ajax-form');
    var $ajaxHeader = $('.merchant-module-page-ajax-header');
    var $ajaxSaveBtn = $('.merchant-module-save-button');
    $('.merchant-module-page-content').on('change keypress change.merchant', ':input:not(.merchant-module-question-answer-textarea):not(.merchant-license-code-input)', function () {
      if (!merchant.show_save) {
        $ajaxHeader.addClass('merchant-show');
        $ajaxHeader.removeClass('merchant-saving');
        merchant.show_save = true;
      }
    });
    $ajaxForm.ajaxForm({
      beforeSubmit: function beforeSubmit() {
        $ajaxHeader.addClass('merchant-saving');
      },
      success: function success() {
        $ajaxHeader.removeClass('merchant-show');
        merchant.show_save = false;

        // Module Alert after Ajax Save
        if (!$('.merchant-module-action').hasClass('merchant-enabled')) {
          var $moduleAlert = $('.merchant-module-alert');
          $moduleAlert.addClass('merchant-show');
          $(document).off('click.merchant-alert-close');
          $(document).on('click.merchant-alert-close', function (e) {
            if (!$(e.target).closest('.merchant-module-alert-wrapper').length) {
              $moduleAlert.removeClass('merchant-show');
              $(document).off('click.merchant-alert-close');
            }
          });
        }
      }
    });
    $('.merchant-module-question-answer-button').on('click', function (e) {
      e.preventDefault();
      var $button = $(this);
      var $textarea = $('.merchant-module-question-answer-textarea');
      $('.merchant-module-question-answer-dropdown').removeClass('merchant-show');
      $('.merchant-module-question-thank-you-dropdown').addClass('merchant-show');
      window.wp.ajax.post('merchant_module_feedback', {
        subject: $textarea.data('subject'),
        message: $textarea.val(),
        module: $button.closest('.merchant-module-action').find('.merchant-module-page-button-action-activate').data('module'),
        nonce: window.merchant.nonce
      });
    });
    $('.merchant-module-page-button-action-activate').on('click', function (e) {
      e.preventDefault();
      $('.merchant-module-question-list-dropdown').removeClass('merchant-show');
      $('.merchant-module-question-answer-dropdown').removeClass('merchant-show');
      $('.merchant-module-question-answer-form').removeClass('merchant-show');
      $('.merchant-module-question-answer-title').removeClass('merchant-show');
      $('.merchant-module-question-thank-you-dropdown').removeClass('merchant-show');
      $('.merchant-module-question-answer-textarea').val('');
      window.wp.ajax.post('merchant_module_activate', {
        module: $(this).data('module'),
        nonce: window.merchant.nonce
      }).done(function () {
        $('body').removeClass('merchant-module-disabled').addClass('merchant-module-enabled');
        $('.merchant-module-action').addClass('merchant-enabled');
      });
    });
    $('.merchant-module-page-button-action-deactivate').on('click', function (e) {
      e.preventDefault();
      window.wp.ajax.post('merchant_module_deactivate', {
        module: $(this).data('module'),
        nonce: window.merchant.nonce
      }).done(function () {
        $('body').removeClass('merchant-module-enabled').addClass('merchant-module-disabled');
        $('.merchant-module-action').removeClass('merchant-enabled');
        $('.merchant-module-question-list-dropdown').addClass('merchant-show');
      });
    });
    $('.merchant-module-question-list-dropdown li').on('click', function (e) {
      var $question = $(this);
      var target = $question.data('answer-target');
      var $answer = $('[data-answer-title="' + target + '"]');
      if ($answer.length) {
        $answer.addClass('merchant-show').siblings().removeClass('merchant-show');
        $('.merchant-module-question-answer-dropdown').addClass('merchant-show');
        $('.merchant-module-question-answer-textarea').attr('data-subject', $question.text().trim());
      } else {
        $('.merchant-module-question-thank-you-dropdown').addClass('merchant-show');
        $('.merchant-module-question-answer-dropdown').removeClass('merchant-show');
      }
      $('.merchant-module-question-answer-textarea').val('');
      $('.merchant-module-question-list-dropdown').removeClass('merchant-show');
    });
    $('.merchant-module-dropdown-close').on('click', function (e) {
      e.preventDefault();
      $(this).closest('.merchant-module-dropdown').removeClass('merchant-show');
    });
    $('.merchant-module-page-button-deactivate').on('click', function (e) {
      e.preventDefault();
      var $button = $(this);
      var $dropdown = $('.merchant-module-deactivate-dropdown');
      $dropdown.toggleClass('merchant-show');
      $(document).off('click.merchant-close');
      $(document).on('click.merchant-close', function (e) {
        if (!$(e.target).closest('.merchant-module-deactivate').length) {
          $dropdown.removeClass('merchant-show');
          $(document).off('click.merchant-close');
        }
      });
    });
    $('.merchant-range').each(function () {
      var $range = $(this);
      var $rangeInput = $range.find('.merchant-range-input');
      var $numberInput = $range.find('.merchant-range-number-input');
      $rangeInput.on('change input merchant.range merchant-init.range', function (e) {
        var $range = $(this);
        var value = (e.type === 'merchant' ? $numberInput.val() : $range.val()) || 0;
        var min = $range.attr('min') || 0;
        var max = $range.attr('max') || 1;
        var percentage = (value - min) / (max - min) * 100;
        $range.css({
          'background': 'linear-gradient(to right, #3858E9 0%, #3858E9 ' + percentage + '%, #ddd ' + percentage + '%, #ddd 100%)'
        });
        $rangeInput.val(value);
        $numberInput.val(value);
      }).trigger('merchant-init.range');
      $numberInput.on('change input blur', function () {
        if ($rangeInput.hasClass('merchant-range-input')) {
          $rangeInput.val($(this).val()).trigger('merchant.range');
        }
      });
    });
    $('.merchant-color').each(function () {
      var $color = $(this);
      var $picker = $color.find('.merchant-color-picker');
      var $input = $color.find('.merchant-color-input');
      var inited;
      var pickr;
      $picker.on('click', function () {
        var $bodyHTML = $('body,html');
        $bodyHTML.addClass('merchant-height-auto');
        if (!inited) {
          pickr = new Pickr({
            el: $picker.get(0),
            container: 'body',
            theme: 'merchant',
            appClass: 'merchant-pcr-app',
            default: $input.val() || '#212121',
            swatches: ['#000000', '#F44336', '#E91E63', '#673AB7', '#03A9F4', '#8BC34A', '#FFEB3B', '#FFC107', '#FFFFFF'],
            sliders: 'h',
            useAsButton: true,
            components: {
              hue: true,
              preview: true,
              opacity: true,
              interaction: {
                input: true,
                clear: true
              }
            },
            i18n: {
              'btn:clear': 'Default'
            }
          });
          pickr.on('change', function (color) {
            var colorCode;
            if (color.a === 1) {
              pickr.setColorRepresentation('HEX');
              colorCode = color.toHEXA().toString(0);
            } else {
              pickr.setColorRepresentation('RGBA');
              colorCode = color.toRGBA().toString(0);
            }
            $picker.css({
              'background-color': colorCode
            });
            if ($input.val() !== colorCode) {
              $input.val(colorCode).trigger('change.merchant');
            }
          });
          pickr.on('clear', function () {
            var defaultColor = $picker.data('default-color');
            if (defaultColor) {
              pickr.setColor(defaultColor);
            } else {
              $picker.css({
                'background-color': 'white'
              });
              $input.val('');
            }
          });
          pickr.on('hide', function () {
            $bodyHTML.removeClass('merchant-height-auto');
          });
          $picker.data('pickr', pickr);
          setTimeout(function () {
            pickr.show();
          });
          inited = true;
        } else {
          pickr.setColor($input.val());
        }
      });
      $input.on('change keyup', function () {
        var colorCode = $(this).val();
        $picker.css({
          'background-color': colorCode
        });
      });
    });

    // Sortable.
    var SortableField = {
      init: function init(field) {
        this.events();
      },
      events: function events() {
        var self = this;
        $('.merchant-sortable').each(function () {
          var field = $(this),
            input = field.find('.merchant-sortable-input');

          // Init sortable.
          $(field.find('ul.merchant-sortable-list').first()).sortable({
            // Update value when we stop sorting.
            update: function update() {
              input.val(self.sortableGetNewVal(field)).trigger('change.merchant');
            }
          }).disableSelection().find('li').each(function () {
            // Enable/disable options when we click on the eye of Thundera.
            $(this).find('i.visibility').click(function () {
              $(this).toggleClass('dashicons-visibility-faint').parents('li:eq(0)').toggleClass('invisible');
            });
          }).click(function () {
            // Update value when click in the eye.
            if ($(event.target).hasClass('dashicons-visibility')) {
              input.val(self.sortableGetNewVal(field)).trigger('change.merchant');
            }
          });
        });
      },
      sortableGetNewVal: function sortableGetNewVal(field) {
        var items = $(field.find('li'));
        var newVal = [];
        _.each(items, function (item) {
          if (!$(item).hasClass('invisible')) {
            newVal.push($(item).data('value'));
          }
        });
        return JSON.stringify(newVal);
      }
    };

    // Intialize Sortable.
    SortableField.init();

    // Sortable Repeater.
    var SortableRepeaterField = {
      init: function init(field) {
        var self = this;

        // Update the values for all our input fields and initialise the sortable repeater.
        $('.merchant-sortable-repeater-control').each(function () {
          // If there is an existing customizer value, populate our rows
          var defaultValuesArray = JSON.parse($(this).find('.merchant-sortable-repeater-input').val());
          var numRepeaterItems = defaultValuesArray.length;
          if (numRepeaterItems > 0) {
            // Add the first item to our existing input field
            $(this).find('.repeater-input').val(defaultValuesArray[0]);

            // Create a new row for each new value
            if (numRepeaterItems > 1) {
              var i;
              for (i = 1; i < numRepeaterItems; ++i) {
                self.appendRow($(this), defaultValuesArray[i]);
              }
            }
          }

          // Make our Repeater fields sortable.
          if (!$(this).hasClass('disable-sorting')) {
            $(this).find('.merchant-sortable-repeater.sortable').sortable({
              update: function update(event, ui) {
                self.getAllInputs($(this).parent());
              }
            });
          }
        });

        // Events.
        this.events();
      },
      events: function events() {
        var self = this;

        // Remove item starting from it's parent element
        $('.merchant-sortable-repeater.sortable').on('click', '.customize-control-sortable-repeater-delete', function (event) {
          event.preventDefault();
          var numItems = $(this).parent().parent().find('.repeater').length;
          if (numItems > 1) {
            $(this).parent().slideUp('fast', function () {
              var parentContainer = $(this).parent().parent();
              $(this).remove();
              self.getAllInputs(parentContainer);
            });
          } else {
            // $(this).parent().find('.repeater-input').val('');
            self.getAllInputs($(this).parent().parent().parent());
          }
        });

        // Add new item
        $('.customize-control-sortable-repeater-add').click(function (event) {
          event.preventDefault();
          self.appendRow($(this).parent());
          self.getAllInputs($(this).parent());
        });

        // Refresh our hidden field if any fields change
        $('.merchant-sortable-repeater.sortable').change(function () {
          self.getAllInputs($(this).parent());
        });
        $('.merchant-sortable-repeater.sortable').on('focusout', '.repeater-input', function () {
          self.getAllInputs($(this).parent());
        });
      },
      /**
       * Append a new row to our list of elements.
       * 
       */
      appendRow: function appendRow($element) {
        var defaultValue = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
        var newRow = '<div class="repeater" style="display:none"><input type="text" value="' + defaultValue + '" class="repeater-input" /><span class="dashicons dashicons-menu"></span><a class="customize-control-sortable-repeater-delete" href="#"><span class="dashicons dashicons-no-alt"></span></a></div>';
        $element.find('.sortable').append(newRow);
        $element.find('.sortable').find('.repeater:last').slideDown('slow', function () {
          $(this).find('input').focus();
        });
      },
      /**
       * Get the values from the repeater input fields and add to our hidden field.
       * 
       */
      getAllInputs: function getAllInputs($element) {
        var inputValues = $element.find('.repeater-input').map(function () {
          return $(this).val();
        }).toArray();

        // Add all the values from our repeater fields to the hidden field (which is the one that actually gets saved)
        $element.find('.merchant-sortable-repeater-input').val(JSON.stringify(inputValues));
        // Important! Make sure to trigger change event so Customizer knows it has to save the field
        $element.find('.merchant-sortable-repeater-input').trigger('change');
      }
    };

    // Initialize Sortable Repeater.
    SortableRepeaterField.init();

    // Create Page Control.
    var CreatePageControl = {
      init: function init() {
        this.events();
      },
      events: function events() {
        var self = this;
        $(document).on('click', '.merchant-create-page-control-button', function (e) {
          e.preventDefault();
          var $this = $(this),
            $create_message = $this.parent().find('.merchant-create-page-control-create-message'),
            $success_message = $this.parent().find('.merchant-create-page-control-success-message'),
            initial_text = $this.text(),
            creating_text = $this.data('creating-text'),
            created_text = $this.data('created-text'),
            page_title = $this.data('page-title'),
            page_meta_key = $this.data('page-meta-key'),
            page_meta_value = $this.data('page-meta-value'),
            option_name = $this.data('option-name'),
            nonce = $this.data('nonce');
          if (!page_title) {
            return false;
          }
          $(this).text(creating_text);
          $(this).attr('disabled', true);
          $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
              action: 'merchant_create_page_control',
              page_title: page_title,
              page_meta_key: page_meta_key,
              page_meta_value: page_meta_value,
              option_name: option_name,
              nonce: nonce
            },
            success: function success(response) {
              self.ajaxResponseHandler(response, $this, $success_message, $create_message);
            }
          });
        });
      },
      ajaxResponseHandler: function ajaxResponseHandler(response, $this, $success_message, $create_message) {
        if ('success' === response.status) {
          var href = $success_message.find('a').attr('href'),
            newhref = href.replace('?post=&', '?post=' + response.page_id + '&');
          $success_message.find('a').attr('href', newhref);
          $success_message.css('display', 'block');
          $create_message.remove();
          $this.remove();
        }
      }
    };

    // Initialize Create Page Control.
    CreatePageControl.init();
    $('.merchant-module-page-setting-field-gallery').each(function () {
      var $this = $(this);
      var $button = $this.find('.merchant-gallery-button');
      var $input = $this.find('.merchant-gallery-input');
      var $images = $this.find('.merchant-gallery-images');
      var $remove = $this.find('.merchant-gallery-remove');
      var wpMediaFrame;
      var sortable = $images.sortable({
        helper: 'original',
        update: function update(event, ui) {
          var selectedIds = [];
          $images.find('.merchant-gallery-image').each(function () {
            selectedIds.push($(this).data('item-id'));
          });
          $input.val(selectedIds.join(',')).trigger('change');
        }
      });
      $remove.on('click', function (e) {
        e.preventDefault();
        $(this).parent().remove();
        var selectedIds = [];
        $images.find('.merchant-gallery-image').each(function () {
          selectedIds.push($(this).data('item-id'));
        });
        $input.val(selectedIds.join(',')).trigger('change');
      });
      $button.on('click', function (e) {
        var $btn = $(this);
        var ids = $input.val();
        var mode = ids ? 'edit' : 'add';
        e.preventDefault();
        if (typeof window.wp === 'undefined' || !window.wp.media || !window.wp.media.gallery) {
          return;
        }
        if (mode === 'add') {
          wpMediaFrame = window.wp.media({
            library: {
              type: 'image'
            },
            frame: 'post',
            state: 'gallery',
            multiple: true
          });
          wpMediaFrame.open();
        } else {
          wpMediaFrame = window.wp.media.gallery.edit('[gallery ids="' + ids + '"]');
        }
        wpMediaFrame.on('update', function (selection) {
          $images.empty();
          var selectedIds = selection.models.map(function (attachment) {
            var item = attachment.toJSON();
            var thumb = item.sizes && item.sizes.thumbnail && item.sizes.thumbnail.url ? item.sizes.thumbnail.url : item.url;
            $images.append('<div class="merchant-gallery-image" data-item-id="' + item.id + '"><i class="merchant-gallery-remove dashicons dashicons-no-alt"></i><img src="' + thumb + '" /></div>');
            return item.id;
          });
          $input.val(selectedIds.join(',')).trigger('change');
          $this.find('.merchant-gallery-remove').on('click', function (e) {
            e.preventDefault();
            $(this).parent().remove();
            var selectedIds = [];
            $images.find('.merchant-gallery-image').each(function () {
              selectedIds.push($(this).data('item-id'));
            });
            $input.val(selectedIds.join(',')).trigger('change');
          });
        });
      });
    });
    $('.merchant-module-page-setting-field-upload').each(function () {
      var $this = $(this);
      var $button = $this.find('.merchant-upload-button');
      var $input = $this.find('.merchant-upload-input');
      var $wrapper = $this.find('.merchant-upload-wrapper');
      var $remove = $this.find('.merchant-upload-remove');
      var wpMediaFrame;
      $remove.on('click', function (e) {
        e.preventDefault();
        $(this).parent().remove();
        $input.val('').trigger('change');
      });
      $button.on('click', function (e) {
        e.preventDefault();
        if (typeof window.wp === 'undefined' || !window.wp.media) {
          return;
        }
        if (!wpMediaFrame) {
          wpMediaFrame = window.wp.media({
            library: {
              type: 'image'
            }
          });
        }
        wpMediaFrame.open();
        wpMediaFrame.on('select', function () {
          $wrapper.empty();
          var item = wpMediaFrame.state().get('selection').first().attributes;
          var thumb = item.sizes && item.sizes.thumbnail && item.sizes.thumbnail.url ? item.sizes.thumbnail.url : item.url;
          $wrapper.append('<div class="merchant-upload-image"><i class="merchant-upload-remove dashicons dashicons-no-alt"></i><img src="' + thumb + '" /></div>');
          $input.val(item.id).trigger('change');
          $this.find('.merchant-upload-remove').on('click', function (e) {
            e.preventDefault();
            $(this).parent().remove();
            $input.val('').trigger('change');
          });
        });
      });
    });
    $('.merchant-animated-buttons').each(function () {
      var $button = $(this).find('label');
      var $demo = $('.merchant-animation-demo');
      var animation;
      var animationHover;
      $button.on('click', function () {
        $demo.removeClass('merchant-animation-' + animation);
        $demo.removeClass('merchant-animation-' + animationHover);
        animation = $(this).find('input').attr('value');
        setTimeout(function () {
          $demo.addClass('merchant-animation-' + animation);
        }, 100);
        setTimeout(function () {
          $demo.removeClass('merchant-animation-' + animation);
        }, 1000);
      });
      $button.mouseover(function () {
        $demo.removeClass('merchant-animation-' + animation);
        animationHover = $(this).find('input').attr('value');
        $demo.addClass('merchant-animation-' + animationHover);
      }).mouseout(function () {
        $demo.removeClass('merchant-animation-' + animationHover);
      });
    });

    // Notifications Sidebar
    var $notificationsSidebar = $('.merchant-notifications-sidebar');
    if ($notificationsSidebar.length) {
      var $notifications = $('.merchant-notifications');
      $notifications.on('click', function (e) {
        e.preventDefault();
        var $notification = $(this);
        var latestNotificationDate = $notificationsSidebar.find('.merchant-notification:first-child .merchant-notification-date').data('raw-date');
        $notificationsSidebar.toggleClass('opened');
        if (!$notification.hasClass('read')) {
          $.post(window.merchant.ajax_url, {
            action: 'merchant_notifications_read',
            nonce: window.merchant.nonce,
            latest_notification_date: latestNotificationDate
          }, function (response) {
            if (response.success) {
              setTimeout(function () {
                $notification.addClass('read');
              }, 2000);
            }
          });
        }
      });
      $(window).on('scroll', function () {
        if (window.pageYOffset > 60) {
          $notificationsSidebar.addClass('closing');
          setTimeout(function () {
            $notificationsSidebar.removeClass('opened');
            $notificationsSidebar.removeClass('closing');
          }, 300);
        }
      });

      // Close Sidebar
      $('.merchant-notifications-sidebar-close').on('click', function (e) {
        e.preventDefault();
        $notificationsSidebar.addClass('closing');
        setTimeout(function () {
          $notificationsSidebar.removeClass('opened');
          $notificationsSidebar.removeClass('closing');
        }, 300);
      });
    }

    // Tabs Navigation.
    var tabs = $('.merchant-tabs-nav');
    if (tabs.length) {
      tabs.each(function () {
        var tabWrapperId = $(this).data('tab-wrapper-id');
        $(this).find('.merchant-tabs-nav-link').on('click', function (e) {
          e.preventDefault();
          var tabsNavLink = $(this).closest('.merchant-tabs-nav').find('.merchant-tabs-nav-link'),
            to = $(this).data('tab-to');

          // Tab Nav Item
          tabsNavLink.each(function () {
            $(this).closest('.merchant-tabs-nav-item').removeClass('active');
          });
          $(this).closest('.merchant-tabs-nav-item').addClass('active');

          // Tab Content
          var tabContentWrapper = $('.merchant-tab-content-wrapper[data-tab-wrapper-id="' + tabWrapperId + '"]');
          tabContentWrapper.find('> .merchant-tab-content').removeClass('active');
          tabContentWrapper.find('> .merchant-tab-content[data-tab-content-id="' + to + '"]').addClass('active');
        });
      });
    }

    // Module Alert
    var $moduleAlert = $('.merchant-module-alert');
    if ($moduleAlert.length) {
      $moduleAlert.find('.merchant-module-alert-close').on('click', function (e) {
        e.preventDefault();
        $moduleAlert.removeClass('merchant-show');
        $(document).off('click.merchant-alert-close');
      });
    }
  });
})(jQuery, window, document);