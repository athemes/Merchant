"use strict";

function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t.return && (u = t.return(), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
;
(function ($, window, document, undefined) {
  'use strict';

  $(document).ready(function () {
    /**
     * @param merchantPreviewManipulators.css
     * @param merchantPreviewManipulators.text
     */
    var manipulators = merchantPreviewManipulators;
    var hasManipulators = function hasManipulators(object) {
      return typeof object !== 'undefined' && Object.keys(object).length > 0;
    };
    var updateElements = function updateElements() {
      if (hasManipulators(manipulators.css)) {
        var _loop = function _loop(key) {
          if (manipulators.css.hasOwnProperty(key)) {
            var elements = document.querySelectorAll(manipulators.css[key].selector);
            var value = $('[name="merchant[' + manipulators.css[key].setting + ']"]').val() + manipulators.css[key].unit;
            var input = $('[name="merchant[' + manipulators.css[key].setting + ']"]');
            var inputType = input.attr('type');
            if (inputType === 'radio') {
              value = $('[name="merchant[' + manipulators.css[key].setting + ']"]' + ':checked').val() + manipulators.css[key].unit;
            }
            elements.forEach(function (element) {
              element.style.setProperty(manipulators.css[key].variable, value);
            });
          }
        };
        for (var key in manipulators.css) {
          _loop(key);
        }
      }
      if (hasManipulators(manipulators.text)) {
        for (var _key in manipulators.text) {
          if (manipulators.text.hasOwnProperty(_key)) {
            var inputText = $('[name="merchant[' + manipulators.text[_key].setting + ']"]').val();
            if (manipulators.text[_key].hasOwnProperty('replacements')) {
              inputText = setReplacements(inputText, manipulators.text[_key]);
            }
            $(manipulators.text[_key].selector).html(inputText);
          }
        }
      }
      if (hasManipulators(manipulators.attributes)) {
        for (var _key2 in manipulators.attributes) {
          if (manipulators.attributes.hasOwnProperty(_key2)) {
            var input = $('[name="merchant[' + manipulators.attributes[_key2].setting + ']"]');
            var inputType = input.attr('type');
            if (inputType === 'radio') {
              input = $('[name="merchant[' + manipulators.attributes[_key2].setting + ']"]' + ':checked');
            }
            var inputValue = input.val();
            if (manipulators.attributes[_key2].hasOwnProperty('replacements')) {
              inputValue = setReplacements(inputValue, manipulators.attributes[_key2]);
            }
            $(manipulators.attributes[_key2].selector).attr(manipulators.attributes[_key2].attribute, inputValue);
          }
        }
      }
      if (hasManipulators(manipulators.classes)) {
        for (var _key3 in manipulators.classes) {
          if (manipulators.classes.hasOwnProperty(_key3)) {
            if (manipulators.classes[_key3].hasOwnProperty('remove')) {
              var _iterator = _createForOfIteratorHelper(manipulators.classes[_key3].remove),
                _step;
              try {
                for (_iterator.s(); !(_step = _iterator.n()).done;) {
                  var classToRemove = _step.value;
                  $(manipulators.classes[_key3].selector).removeClass(classToRemove);
                }
              } catch (err) {
                _iterator.e(err);
              } finally {
                _iterator.f();
              }
            }
            var _input = $('[name="merchant[' + manipulators.classes[_key3].setting + ']"]');
            var _inputType = _input.attr('type');
            if (_inputType === 'radio') {
              _input = $('[name="merchant[' + manipulators.classes[_key3].setting + ']"]' + ':checked');
            }
            if (_inputType === 'checkbox') {
              if (_input.is(':checked')) {
                $(manipulators.classes[_key3].selector).addClass(manipulators.classes[_key3].add);
              } else {
                $(manipulators.classes[_key3].selector).removeClass(manipulators.classes[_key3].add);
              }
            } else {
              var _inputValue = _input.val();
              if (manipulators.classes[_key3].hasOwnProperty('add')) {
                $(manipulators.classes[_key3].selector).toggleClass(manipulators.classes[_key3].add);
              } else {
                $(manipulators.classes[_key3].selector).addClass(_inputValue);
              }
            }
          }
        }
      }
      if (hasManipulators(manipulators.icons)) {
        for (var _key4 in manipulators.icons) {
          if (manipulators.icons.hasOwnProperty(_key4)) {
            var radioElement = $('[name="merchant[' + manipulators.icons[_key4].setting + ']"]' + ':checked');
            var iconSrc = radioElement.parent().find('figure img').attr('src');
            var iconSelector = $(manipulators.icons[_key4].selector);
            if (radioElement.val() === 'none') {
              iconSelector.hide();
            } else {
              iconSelector.show();
              iconSelector.attr('src', iconSrc);
            }
          }
        }
      }
      if (hasManipulators(manipulators.svg_icons)) {
        for (var _key5 in manipulators.svg_icons) {
          if (manipulators.svg_icons.hasOwnProperty(_key5)) {
            var _radioElement = $('[name="merchant[' + manipulators.svg_icons[_key5].setting + ']"]' + ':checked');
            var iconsLib = manipulators.svg_icons[_key5].icons_lib;
            var icon = iconsLib[_radioElement.val()];
            var _iconSelector = $(manipulators.svg_icons[_key5].selector);
            if (_radioElement.val() === 'none') {
              _iconSelector.hide();
            } else {
              _iconSelector.show();
              _iconSelector.html(icon);
            }
          }
        }
      }
      if (hasManipulators(manipulators.repeater_content)) {
        for (var _key6 in manipulators.repeater_content) {
          if (manipulators.repeater_content.hasOwnProperty(_key6)) {
            var repeaterElement = $('[name="merchant[' + manipulators.repeater_content[_key6].setting + ']"]');
            var repeaterValue = repeaterElement.val() ? JSON.parse(repeaterElement.val()) : [];
            var repeaterItemSelector = $(manipulators.repeater_content[_key6].selector);
            if (repeaterValue.length) {
              // Update content.
              var _iterator2 = _createForOfIteratorHelper(repeaterValue.entries()),
                _step2;
              try {
                for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
                  var _step2$value = _slicedToArray(_step2.value, 2),
                    index = _step2$value[0],
                    repeaterItem = _step2$value[1];
                  if (repeaterItemSelector.length) {
                    repeaterItemSelector.eq(index).html(repeaterItem);
                  }
                }

                // Update content when removing.
              } catch (err) {
                _iterator2.e(err);
              } finally {
                _iterator2.f();
              }
              if (repeaterItemSelector.length > repeaterValue.length) {
                for (var i = repeaterValue.length; i < repeaterItemSelector.length; i++) {
                  repeaterItemSelector.eq(i).parent().remove();
                }
              }

              // Update content when adding.
              if (repeaterItemSelector.length < repeaterValue.length) {
                for (var _i = repeaterItemSelector.length; _i < repeaterValue.length; _i++) {
                  repeaterItemSelector.eq(0).parent().clone().appendTo(repeaterItemSelector.eq(0).parent().parent());
                }
              }
            }
          }
        }
      }
      if (hasManipulators(manipulators.flexible_content)) {
        var _loop2 = function _loop2(_key7) {
          var flexibleContentSelector = $(manipulators.flexible_content[_key7].selector);
          var flexibleContentItems = flexibleContentSelector.children();
          var flexibleContentTemplate = flexibleContentItems.eq(0);
          var flexibleContentSettings = $('.merchant-flexible-content-control[data-id=' + manipulators.flexible_content[_key7].setting + ']').find('.merchant-flexible-content').children();

          /**
           * We're going to remove all items in the selector except for the template
           * and regenerate the items based on the settings.
           */

          flexibleContentItems.each(function () {
            if (!$(this).hasClass('flexible-content-template')) {
              $(this).remove();
            }
          });
          flexibleContentSettings.each(function () {
            var item = flexibleContentTemplate.clone();
            var layout = $(this).data('type');
            for (var variable in manipulators.flexible_content[_key7].variables[layout]) {
              var setting = manipulators.flexible_content[_key7].variables[layout][variable];
              var field = $(this).find('.merchant-module-page-setting-field[data-id=' + setting + ']');
              var fieldType = field.data('type');
              var fieldInput = field.find('input').val();

              // Handle variable replacement for text fields
              if (fieldType === 'text') {
                item.html(item.html().replace(variable, fieldInput));
              }

              // Handle variable replacement for upload fields
              if (fieldType === 'upload') {
                var image = field.find('.merchant-upload-image img').prop('outerHTML');
                if (typeof image !== 'undefined') {
                  item.html(item.html().replace(variable, image));
                }
              }

              // Handle variable replacement for choices fields
              if (fieldType === 'choices') {
                var selectedChoice = field.find('input:checked');

                // If choices uses SVG then we want to replace  the variable with the selected SVG
                if (field.find('.merchant-svg').length) {
                  var SVG = selectedChoice.parent().find('svg').prop('outerHTML');
                  if (typeof SVG !== 'undefined') {
                    item.html(item.html().replace(variable, SVG));
                  }
                }
              }

              // Append item to selector
              item.html(item.html().replace(variable, '')) // First remove variable if it's not replaced yet
              .removeClass('flexible-content-template') // Remove template class
              .appendTo(flexibleContentSelector); // Append
            }
          });
        };
        for (var _key7 in manipulators.flexible_content) {
          _loop2(_key7);
        }
      }
    };
    var triggerChangeOnInput = function triggerChangeOnInput(input) {
      var inputType = input.attr('type');

      // Text inputs
      if (inputType === 'text' || input.prop('tagName') === 'TEXTAREA') {
        input.on('keyup', function () {
          return updateElements();
        });
      }

      // Number + range inputs
      if (inputType === 'number') {
        input.on('keyup', function () {
          return updateElements();
        });
        if (input.parent().find('input[type=range]').length) {
          input.parent().find('input[type=range]').on('input change', function () {
            return updateElements();
          });
        }
      }
      // Radio inputs
      if (inputType === 'radio') {
        input.on('input', function () {
          return updateElements();
        });
      }

      // Select
      if (inputType === 'checkbox' || input.is('select')) {
        input.on('change', function () {
          return updateElements();
        });
      }

      // Repeater
      if (input.hasClass('merchant-sortable-repeater-input')) {
        input.on('change', function () {
          return updateElements();
        });
      }

      // Upload input
      if (input.hasClass('merchant-upload-input')) {
        input.on('change', function () {
          return updateElements();
        });
      }
    };
    if (typeof manipulators !== 'undefined') {
      if (hasManipulators(manipulators.css)) {
        for (var key in manipulators.css) {
          triggerChangeOnInput($('[name="merchant[' + manipulators.css[key].setting + ']"]'));
        }
      }
      if (hasManipulators(manipulators.text)) {
        for (var _key8 in manipulators.text) {
          triggerChangeOnInput($('[name="merchant[' + manipulators.text[_key8].setting + ']"]'));
        }
      }
      if (hasManipulators(manipulators.attributes)) {
        for (var _key9 in manipulators.attributes) {
          triggerChangeOnInput($('[name="merchant[' + manipulators.attributes[_key9].setting + ']"]'));
        }
      }
      if (hasManipulators(manipulators.classes)) {
        for (var _key10 in manipulators.classes) {
          triggerChangeOnInput($('[name="merchant[' + manipulators.classes[_key10].setting + ']"]'));
        }
      }
      if (hasManipulators(manipulators.icons)) {
        for (var _key11 in manipulators.icons) {
          triggerChangeOnInput($('[name="merchant[' + manipulators.icons[_key11].setting + ']"]'));
        }
      }
      if (hasManipulators(manipulators.svg_icons)) {
        for (var _key12 in manipulators.svg_icons) {
          triggerChangeOnInput($('[name="merchant[' + manipulators.svg_icons[_key12].setting + ']"]'));
        }
      }
      if (hasManipulators(manipulators.repeater_content)) {
        for (var _key13 in manipulators.repeater_content) {
          triggerChangeOnInput($('[name="merchant[' + manipulators.repeater_content[_key13].setting + ']"]'));
        }
      }
      if (hasManipulators(manipulators.flexible_content)) {
        for (var _key14 in manipulators.flexible_content) {
          var field = $('.merchant-flexible-content-control[data-id=' + manipulators.flexible_content[_key14].setting + ']');
          triggerChangeOnInput(field.find('input'));
          field.find('.customize-control-flexible-content-delete').on('click', function () {
            return updateElements();
          });
          field.on('merchant.sorted', function () {
            return updateElements();
          });
        }
      }
      if (hasManipulators(manipulators.update)) {
        for (var _key15 in manipulators.update) {
          triggerChangeOnInput($('[name="merchant[' + manipulators.update[_key15].setting + ']"]'));
        }
      }

      // On color picker interaction
      for (var _i2 = 0, _arr = ['.pcr-color-palette', '.pcr-swatches button', '.pcr-color-chooser', '.pcr-color-opacity']; _i2 < _arr.length; _i2++) {
        var selector = _arr[_i2];
        $(document).on('click', selector, function () {
          return updateElements();
        });
      }
    }

    // Replacement helpers
    var setReplacements = function setReplacements(inputText, manipulator) {
      var searches = manipulator['replacements'][0]; // Searches
      var replacements = manipulator['replacements'][1]; // Replacements

      // Do search replacements
      var _iterator3 = _createForOfIteratorHelper(searches.entries()),
        _step3;
      try {
        for (_iterator3.s(); !(_step3 = _iterator3.n()).done;) {
          var _step3$value = _slicedToArray(_step3.value, 2),
            index = _step3$value[0],
            search = _step3$value[1];
          var replacement = replacements[index];
          if (typeof replacement === 'string') {
            inputText = inputText.replace(search, replacement);
          }
          if (_typeof(replacement) === 'object') {
            if (replacement.hasOwnProperty('conditions')) {
              var conditionalElement = $('[name="merchant[' + replacement.setting + ']"]');
              for (var conditionValue in replacement.conditions) {
                if (replacement.conditions.hasOwnProperty(conditionValue)) {
                  if (conditionalElement.val() === conditionValue) {
                    if (typeof replacement.conditions[conditionValue] === 'string') {
                      inputText = inputText.replace(search, replacement.conditions[conditionValue]);
                    }
                    if (_typeof(replacement.conditions[conditionValue]) === 'object') {
                      inputText = replaceTextBySettingsValue(inputText, search, replacement.conditions[conditionValue]);
                    }
                  }
                }
              }
            } else {
              inputText = replaceTextBySettingsValue(inputText, search, replacement);
            }
          }
        }
      } catch (err) {
        _iterator3.e(err);
      } finally {
        _iterator3.f();
      }
      return inputText;
    };
    var replaceTextBySettingsValue = function replaceTextBySettingsValue(inputText, search, replacement) {
      var replacementValue = $('input[name="merchant[' + replacement.setting + ']"]').val();
      if (replacement.hasOwnProperty('format')) {
        replacementValue = replacement.format.replace('{string}', replacementValue);
      }
      return inputText.replace(search, replacementValue);
    };
  });
})(jQuery, window, document);