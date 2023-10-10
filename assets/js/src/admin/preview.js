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
                        let value = $('[name="merchant[' + manipulators.css[key].setting + ']"]').val() + manipulators.css[key].unit;

                        const input = $('[name="merchant[' + manipulators.css[key].setting + ']"]');
                        const inputType = input.attr('type');

                        if (inputType === 'radio') {
                            value = $('[name="merchant[' + manipulators.css[key].setting + ']"]' + ':checked').val() + manipulators.css[key].unit;
                        }

                        elements.forEach((element) => {
                            element.style.setProperty(manipulators.css[key].variable, value);
                        });
                    }
                }
            }
            if (hasManipulators(manipulators.text)) {
                for (const key in manipulators.text) {
                    if (manipulators.text.hasOwnProperty(key)) {
                        let inputText = $('[name="merchant[' + manipulators.text[key].setting + ']"]').val();

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
                        let input = $('[name="merchant[' + manipulators.attributes[key].setting + ']"]');
                        let inputType = input.attr('type');

                        if (inputType === 'radio') {
                            input = $('[name="merchant[' + manipulators.attributes[key].setting + ']"]' + ':checked')
                        }

                        let inputValue = input.val();


                        if (manipulators.attributes[key].hasOwnProperty('replacements')) {
                            inputValue = setReplacements(inputValue, manipulators.attributes[key]);
                        }

                        $(manipulators.attributes[key].selector).attr(manipulators.attributes[key].attribute, inputValue)
                    }
                }
            }
            if (hasManipulators(manipulators.classes)) {
                for (const key in manipulators.classes) {
                    if (manipulators.classes.hasOwnProperty(key)) {
                        if (manipulators.classes[key].hasOwnProperty('remove')) {
                            for (const classToRemove of manipulators.classes[key].remove) {
                                $(manipulators.classes[key].selector).removeClass(classToRemove)
                            }
                        }

                        let input = $('[name="merchant[' + manipulators.classes[key].setting + ']"]');
                        let inputType = input.attr('type');

                        if (inputType === 'radio') {
                            input = $('[name="merchant[' + manipulators.classes[key].setting + ']"]' + ':checked')
                        }

                        if (inputType === 'checkbox') {
                            if (input.is(':checked')) {
                                $(manipulators.classes[key].selector).addClass(manipulators.classes[key].add)
                            } else {
                                $(manipulators.classes[key].selector).removeClass(manipulators.classes[key].add)
                            }
                        } else {
                            let inputValue = input.val();

                            if (manipulators.classes[key].hasOwnProperty('add')) {
                                $(manipulators.classes[key].selector).toggleClass(manipulators.classes[key].add)
                            } else {
                                $(manipulators.classes[key].selector).addClass(inputValue)
                            }
                        }
                    }
                }
            }
            if (hasManipulators(manipulators.icons)) {
                for (const key in manipulators.icons) {
                    if (manipulators.icons.hasOwnProperty(key)) {
                        let radioElement = $('[name="merchant[' + manipulators.icons[key].setting + ']"]' + ':checked');
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
            if (hasManipulators(manipulators.svg_icons)) {
                for (const key in manipulators.svg_icons) {
                    if (manipulators.svg_icons.hasOwnProperty(key)) {
                        let radioElement = $('[name="merchant[' + manipulators.svg_icons[key].setting + ']"]' + ':checked');
                        const iconsLib = manipulators.svg_icons[key].icons_lib;
                        let icon = iconsLib[radioElement.val()];
                        let iconSelector = $(manipulators.svg_icons[key].selector);

                        if (radioElement.val() === 'none') {
                            iconSelector.hide();
                        } else {
                            iconSelector.show();
                            iconSelector.html(icon);
                        }
                    }
                }
            }
            if (hasManipulators(manipulators.repeater_content)) {
                for (const key in manipulators.repeater_content) {
                    if (manipulators.repeater_content.hasOwnProperty(key)) {
                        const repeaterElement = $('[name="merchant[' + manipulators.repeater_content[key].setting + ']"]');
                        const repeaterValue = repeaterElement.val() ? JSON.parse(repeaterElement.val()) : [];
                        const repeaterItemSelector = $(manipulators.repeater_content[key].selector);

                        if (repeaterValue.length) {

                            // Update content.
                            for (const [index, repeaterItem] of repeaterValue.entries()) {
                                if (repeaterItemSelector.length) {
                                    repeaterItemSelector.eq(index).html(repeaterItem);
                                }
                            }

                            // Update content when removing.
                            if (repeaterItemSelector.length > repeaterValue.length) {
                                for (let i = repeaterValue.length; i < repeaterItemSelector.length; i++) {
                                    repeaterItemSelector.eq(i).parent().remove();
                                }
                            }

                            // Update content when adding.
                            if (repeaterItemSelector.length < repeaterValue.length) {
                                for (let i = repeaterItemSelector.length; i < repeaterValue.length; i++) {
                                    repeaterItemSelector.eq(0).parent().clone().appendTo(repeaterItemSelector.eq(0).parent().parent());
                                }
                            }
                        }
                    }
                }
            }

            if (hasManipulators(manipulators.flexible_content)) {
                for (const key in manipulators.flexible_content) {
                    const flexibleContentSelector = $(manipulators.flexible_content[key].selector);
                    const flexibleContentItems = flexibleContentSelector.children();
                    const flexibleContentTemplate = flexibleContentItems.eq(0);
                    const flexibleContentSettings = $('.merchant-flexible-content-control[data-id=' + manipulators.flexible_content[key].setting + ']')
                        .find('.merchant-flexible-content')
                        .children()

                    /**
                     * We're going to remove all items in the selector except for the template
                     * and regenerate the items based on the settings.
                     */

                    flexibleContentItems.each(function () {
                        if (!$(this).hasClass('flexible-content-template')) {
                            $(this).remove();
                        }
                    })

                    flexibleContentSettings.each(function () {
                        const item = flexibleContentTemplate.clone();
                        const layout = $(this).data('type');

                        for (const variable in manipulators.flexible_content[key].variables[layout]) {
                            const setting = manipulators.flexible_content[key].variables[layout][variable];
                            const field = $(this).find('.merchant-module-page-setting-field[data-id=' + setting + ']');
                            const fieldType = field.data('type');
                            const fieldInput = field.find('input').val()

                            // Handle variable replacement for text fields
                            if (fieldType === 'text') {
                                item.html(item.html().replace(variable, fieldInput))
                            }

                            // Handle variable replacement for upload fields
                            if (fieldType === 'upload') {
                                const image = field.find('.merchant-upload-image img').prop('outerHTML')

                                if (typeof image !== 'undefined') {
                                    item.html(item.html().replace(variable, image))
                                }
                            }

                            // Handle variable replacement for choices fields
                            if (fieldType === 'choices') {
                                const selectedChoice = field.find('input:checked');

                                // If choices uses SVG then we want to replace  the variable with the selected SVG
                                if (field.find('.merchant-svg').length) {
                                    const SVG = selectedChoice
                                        .parent()
                                        .find('svg')
                                        .prop('outerHTML')

                                    if (typeof SVG !== 'undefined') {
                                        item.html(item.html().replace(variable, SVG))
                                    }
                                }
                            }

                            // Append item to selector
                            item.html(item.html().replace(variable, '')) // First remove variable if it's not replaced yet
                                .removeClass('flexible-content-template') // Remove template class
                                .appendTo(flexibleContentSelector) // Append
                        }
                    })

                }
            }
        }
        const triggerChangeOnInput = (input) => {
            const inputType = input.attr('type');

            // Text inputs
            if (inputType === 'text' || input.prop('tagName') === 'TEXTAREA') {
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
            if (inputType === 'checkbox' || input.is('select')) {
                input.on('change', () => updateElements());
            }

            // Repeater
            if (input.hasClass('merchant-sortable-repeater-input')) {
                input.on('change', () => updateElements());
            }

            // Upload input
            if (input.hasClass('merchant-upload-input')) {
                input.on('change', () => updateElements());
            }
        }

        if (typeof manipulators !== 'undefined') {
            if (hasManipulators(manipulators.css)) {
                for (const key in manipulators.css) {
                    triggerChangeOnInput($('[name="merchant[' + manipulators.css[key].setting + ']"]'))
                }
            }
            if (hasManipulators(manipulators.text)) {
                for (const key in manipulators.text) {
                    triggerChangeOnInput($('[name="merchant[' + manipulators.text[key].setting + ']"]'))
                }
            }
            if (hasManipulators(manipulators.attributes)) {
                for (const key in manipulators.attributes) {
                    triggerChangeOnInput($('[name="merchant[' + manipulators.attributes[key].setting + ']"]'))
                }
            }
            if (hasManipulators(manipulators.classes)) {
                for (const key in manipulators.classes) {
                    triggerChangeOnInput($('[name="merchant[' + manipulators.classes[key].setting + ']"]'))
                }
            }
            if (hasManipulators(manipulators.icons)) {
                for (const key in manipulators.icons) {
                    triggerChangeOnInput($('[name="merchant[' + manipulators.icons[key].setting + ']"]'))
                }
            }
            if (hasManipulators(manipulators.svg_icons)) {
                for (const key in manipulators.svg_icons) {
                    triggerChangeOnInput($('[name="merchant[' + manipulators.svg_icons[key].setting + ']"]'))
                }
            }
            if (hasManipulators(manipulators.repeater_content)) {
                for (const key in manipulators.repeater_content) {
                    triggerChangeOnInput($('[name="merchant[' + manipulators.repeater_content[key].setting + ']"]'))
                }
            }
            if (hasManipulators(manipulators.flexible_content)) {
                for (const key in manipulators.flexible_content) {
                    const field = $('.merchant-flexible-content-control[data-id=' + manipulators.flexible_content[key].setting + ']');

                    triggerChangeOnInput(field.find('input'))

                    field.find('.customize-control-flexible-content-delete').on('click', () => updateElements())
                    field.on('merchant.sorted', () => updateElements())
                }
            }
            if (hasManipulators(manipulators.update)) {
                for (const key in manipulators.update) {
                    triggerChangeOnInput($('[name="merchant[' + manipulators.update[key].setting + ']"]'))
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


