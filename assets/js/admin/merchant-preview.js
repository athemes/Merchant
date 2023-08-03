"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _iterableToArrayLimit(arr, i) { var _i = null == arr ? null : "undefined" != typeof Symbol && arr[Symbol.iterator] || arr["@@iterator"]; if (null != _i) { var _s, _e, _x, _r, _arr = [], _n = !0, _d = !1; try { if (_x = (_i = _i.call(arr)).next, 0 === i) { if (Object(_i) !== _i) return; _n = !1; } else for (; !(_n = (_s = _x.call(_i)).done) && (_arr.push(_s.value), _arr.length !== i); _n = !0); } catch (err) { _d = !0, _e = err; } finally { try { if (!_n && null != _i.return && (_r = _i.return(), Object(_r) !== _r)) return; } finally { if (_d) throw _e; } } return _arr; } }
function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e2) { throw _e2; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e3) { didErr = true; err = _e3; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }
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
            var inputText = $('input[name="merchant[' + manipulators.text[_key].setting + ']"]').val();
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
            var _inputText = $('input[name="merchant[' + manipulators.attributes[_key2].setting + ']"]').val();
            if (manipulators.attributes[_key2].hasOwnProperty('replacements')) {
              _inputText = setReplacements(_inputText, manipulators.attributes[_key2]);
            }
            $(manipulators.attributes[_key2].selector).attr(manipulators.attributes[_key2].attribute, _inputText);
          }
        }
      }
      if (hasManipulators(manipulators.icons)) {
        for (var _key3 in manipulators.icons) {
          if (manipulators.icons.hasOwnProperty(_key3)) {
            var radioElement = $('[name="merchant[' + manipulators.icons[_key3].setting + ']"]' + ':checked');
            var iconSrc = radioElement.parent().find('figure img').attr('src');
            var iconSelector = $(manipulators.icons[_key3].selector);
            if (radioElement.val() === 'none') {
              iconSelector.hide();
            } else {
              iconSelector.show();
              iconSelector.attr('src', iconSrc);
            }
          }
        }
      }
    };
    var triggerElementsChange = function triggerElementsChange(input) {
      var inputType = input.attr('type');

      // Text inputs
      if (inputType === 'text') {
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
      if (input.is('select')) {
        input.on('change', function () {
          return updateElements();
        });
      }
    };
    if (typeof manipulators !== 'undefined') {
      if (hasManipulators(manipulators.css)) {
        for (var key in manipulators.css) {
          triggerElementsChange($('[name="merchant[' + manipulators.css[key].setting + ']"]'));
        }
      }
      if (hasManipulators(manipulators.text)) {
        for (var _key4 in manipulators.text) {
          triggerElementsChange($('[name="merchant[' + manipulators.text[_key4].setting + ']"]'));
        }
      }
      if (hasManipulators(manipulators.attributes)) {
        for (var _key5 in manipulators.attributes) {
          triggerElementsChange($('[name="merchant[' + manipulators.attributes[_key5].setting + ']"]'));
        }
      }
      if (hasManipulators(manipulators.icons)) {
        for (var _key6 in manipulators.icons) {
          triggerElementsChange($('[name="merchant[' + manipulators.icons[_key6].setting + ']"]'));
        }
      }
      if (hasManipulators(manipulators.update)) {
        for (var _key7 in manipulators.update) {
          triggerElementsChange($('[name="merchant[' + manipulators.update[_key7].setting + ']"]'));
        }
      }

      // On color picker interaction
      for (var _i = 0, _arr = ['.pcr-color-palette', '.pcr-swatches button', '.pcr-color-chooser', '.pcr-color-opacity']; _i < _arr.length; _i++) {
        var selector = _arr[_i];
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
      var _iterator = _createForOfIteratorHelper(searches.entries()),
        _step;
      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var _step$value = _slicedToArray(_step.value, 2),
            index = _step$value[0],
            search = _step$value[1];
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
        _iterator.e(err);
      } finally {
        _iterator.f();
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