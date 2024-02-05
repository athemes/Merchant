"use strict";

;
(function ($, window, document, undefined) {
  'use strict';

  var spendingGoalInputSelector = 'input[name="merchant[spending_goal]"]';
  var discountInputSelector = 'input[name="merchant[discount_amount]"]';
  var discountTypeSelector = 'select[name="merchant[discount_type]"]';
  var beforeInputSelector = '.merchant-module-page-setting-field-before-input';
  var setDiscountType = function setDiscountType() {
    var type = $(discountTypeSelector).val();
    if (type === 'percent') {
      $(discountInputSelector).parent().find(beforeInputSelector).text('%');
    } else {
      $(discountInputSelector).parent().find(beforeInputSelector).text(merchantSpendingGoal.currencySymbol);
    }
  };
  $(document).ready(function () {
    $('.js-merchant-spending-goal-widget').on('click', function () {
      $(this).toggleClass('active');
    });
    $(spendingGoalInputSelector).before('<span class="merchant-module-page-setting-field-before-input">' + merchantSpendingGoal.currencySymbol + '</span>');
    $(discountInputSelector).before('<span class="merchant-module-page-setting-field-before-input"></span>');
    setDiscountType();
    $(discountTypeSelector).on('change', function () {
      return setDiscountType();
    });
  });
})(jQuery, window, document);