;(function ($, window, document, undefined) {
    'use strict';

    $(document).ready(function () {
        /**
         * @param merchantPreviewManipulators.css
         * @param merchantPreviewManipulators.text
         */
        const manipulators = merchantPreviewManipulators;

        const hasManipulators = (object) => typeof object !== 'undefined' && Object.keys(object).length > 0;

        const updateElements = () => {
            if (hasManipulators(manipulators.css)) {
                for (const key in manipulators.css) {
                    if (manipulators.css.hasOwnProperty(key)) {
                        const elements = document.querySelectorAll(manipulators.css[key].selector);
                        const value = $('[name="merchant[' + manipulators.css[key].setting + ']"]').val() + manipulators.css[key].unit;

                        elements.forEach((element) => {
                            element.style.setProperty(manipulators.css[key].variable, value);
                        });
                    }
                }
            }
            if (hasManipulators(manipulators.text)) {
                for (const key in manipulators.text) {
                    if (manipulators.text.hasOwnProperty(key)) {
                        let inputText = $('input[name="merchant[' + manipulators.text[key].setting + ']"]').val();

                        if (manipulators.text[key].hasOwnProperty('replacements')) {
                            inputText = setReplacements(inputText, manipulators.text[key]);
                        }

                        $(manipulators.text[key].selector).html(inputText)
                    }
                }
            }
            if (hasManipulators(manipulators.attributes)) {
                for (const key in manipulators.attributes) {
                    if (manipulators.attributes.hasOwnProperty(key)) {
                        let inputText = $('input[name="merchant[' + manipulators.attributes[key].setting + ']"]').val();

                        if (manipulators.attributes[key].hasOwnProperty('replacements')) {
                            inputText = setReplacements(inputText, manipulators.attributes[key]);
                        }

                        $(manipulators.attributes[key].selector).attr(manipulators.attributes[key].attribute, inputText)
                    }
                }
            }
            if (hasManipulators(manipulators.icons)) {
                for (const key in manipulators.icons) {
                    if (manipulators.icons.hasOwnProperty(key)) {
                        let radioElement = $('[name="merchant[' + manipulators.icons[key].setting + ']"]'+ ':checked');
                        let iconSrc = radioElement.parent().find('figure img').attr('src');
                        let iconSelector = $(manipulators.icons[key].selector)

                        if (radioElement.val() === 'none') {
                            iconSelector.hide();
                        } else {
                            iconSelector.show();
                            iconSelector.attr('src', iconSrc)
                        }
                    }
                }
            }
        }
        const triggerElementsChange = (input) => {
            const inputType = input.attr('type');

            // Text inputs
            if (inputType === 'text') {
                input.on('keyup', () => updateElements());
            }

            // Number + range inputs
            if (inputType === 'number') {
                input.on('keyup', () => updateElements());

                if (input.parent().find('input[type=range]').length) {
                    input.parent().find('input[type=range]').on('input change', () => updateElements());
                }
            }
            // Radio inputs
            if (inputType === 'radio') {
                input.on('input', () => updateElements());
            }

            // Select
            if (input.is('select')) {
                input.on('change', () => updateElements());
            }
        }


        if (typeof manipulators !== 'undefined') {
            if (hasManipulators(manipulators.css)) {
                for (const key in manipulators.css) {
                    triggerElementsChange($('[name="merchant[' + manipulators.css[key].setting + ']"]'))
                }
            }
            if (hasManipulators(manipulators.text)) {
                for (const key in manipulators.text) {
                    triggerElementsChange($('[name="merchant[' + manipulators.text[key].setting + ']"]'))
                }
            }
            if (hasManipulators(manipulators.attributes)) {
                for (const key in manipulators.attributes) {
                    triggerElementsChange($('[name="merchant[' + manipulators.attributes[key].setting + ']"]'))
                }
            }
            if (hasManipulators(manipulators.icons)) {
                for (const key in manipulators.icons) {
                    triggerElementsChange($('[name="merchant[' + manipulators.icons[key].setting + ']"]'))
                }
            }
            if (hasManipulators(manipulators.update)) {
                for (const key in manipulators.update) {
                    triggerElementsChange($('[name="merchant[' + manipulators.update[key].setting + ']"]'))
                }
            }


            // On color picker interaction
            for (const selector of [
                '.pcr-color-palette',
                '.pcr-swatches button',
                '.pcr-color-chooser',
                '.pcr-color-opacity'
            ]) {
                $(document).on('click', selector, () => updateElements());
            }
        }

        // Replacement helpers
        const setReplacements = (inputText, manipulator) => {
            let searches = manipulator['replacements'][0]; // Searches
            let replacements = manipulator['replacements'][1]; // Replacements

            // Do search replacements
            for (const [index, search] of searches.entries()) {
                let replacement = replacements[index];

                if (typeof replacement === 'string') {
                    inputText = inputText.replace(search, replacement)
                }

                if (typeof replacement === 'object') {
                    if (replacement.hasOwnProperty('conditions')) {
                        let conditionalElement = $('[name="merchant[' + replacement.setting + ']"]');

                        for (const conditionValue in replacement.conditions) {
                            if (replacement.conditions.hasOwnProperty(conditionValue)) {
                                if (conditionalElement.val() === conditionValue) {
                                    if (typeof replacement.conditions[conditionValue] === 'string') {
                                        inputText = inputText.replace(search, replacement.conditions[conditionValue])
                                    }
                                    if (typeof replacement.conditions[conditionValue] === 'object') {
                                        inputText = replaceTextBySettingsValue(inputText, search, replacement.conditions[conditionValue])
                                    }
                                }
                            }
                        }
                    } else {
                        inputText = replaceTextBySettingsValue(inputText, search, replacement)
                    }
                }
            }

            return inputText;
        }

        const replaceTextBySettingsValue = (inputText, search, replacement) => {
            let replacementValue = $('input[name="merchant[' + replacement.setting + ']"]').val();


            if (replacement.hasOwnProperty('format')) {
                replacementValue = replacement.format.replace('{string}', replacementValue);
            }

            return inputText.replace(search, replacementValue)
        }

    });


})(jQuery, window, document);


