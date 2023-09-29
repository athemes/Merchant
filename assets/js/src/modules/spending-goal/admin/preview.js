;(function ($, window, document, undefined) {
    'use strict';

    const spendingGoalInputSelector = 'input[name="merchant[spending_goal]"]';
    const discountInputSelector = 'input[name="merchant[discount_amount]"]';
    const discountTypeSelector = 'select[name="merchant[discount_type]"]';
    const beforeInputSelector = '.merchant-module-page-setting-field-before-input';

    const setDiscountType = () => {
        const type = $(discountTypeSelector).val();

        if (type === 'percent') {
            $(discountInputSelector).parent().find(beforeInputSelector).text('%')
        } else {
            $(discountInputSelector).parent().find(beforeInputSelector).text(merchantSpendingGoal.currencySymbol)
        }
    }

    $(document).ready(function () {
        $(spendingGoalInputSelector).before('<span class="merchant-module-page-setting-field-before-input">' + merchantSpendingGoal.currencySymbol + '</span>')
        $(discountInputSelector).before('<span class="merchant-module-page-setting-field-before-input"></span>')

        setDiscountType();

        $(discountTypeSelector).on('change', () => setDiscountType());
    });


})(jQuery, window, document);