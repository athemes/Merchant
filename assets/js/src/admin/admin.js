;(function ($, window, document, undefined) {
    'use strict';

    var merchant = merchant || {};

    $(document).ready(function () {
        // AjaxSave
        var $ajaxForm = $('.merchant-module-page-ajax-form');
        var $ajaxHeader = $('.merchant-module-page-ajax-header');
        var $ajaxSaveBtn = $('.merchant-module-save-button');

        $('.merchant-module-page-content').on('change keypress change.merchant', function () {
            if (!$(this).is('.merchant-module-question-answer-textarea, .merchant-license-code-input')) {
                if (!merchant.show_save) {
                    $ajaxHeader.addClass('merchant-show');
                    $ajaxHeader.removeClass('merchant-saving');
                    merchant.show_save = true;
                }
            }
        });

        $ajaxForm.ajaxForm({
            beforeSubmit: function () {
                $ajaxHeader.addClass('merchant-saving');
            },
            success: function () {

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
        const $disableModuleSubmitBtn = $('.merchant-module-question-answer-button');
        const $disableModuleTextField = $('.merchant-module-question-answer-textarea');
        $disableModuleTextField.on('input', function () {
            $disableModuleSubmitBtn.prop('disabled', $(this).val().trim() === '');
        });

        $disableModuleSubmitBtn.on('click', function (e) {
            e.preventDefault();

            const message = $disableModuleTextField.val();
            if (!message.trim()) {
                alert('Please provide the required information.');
                return;
            }

            const $button = $(this);

            $('.merchant-module-question-answer-dropdown').removeClass('merchant-show');
            $('.merchant-module-question-thank-you-dropdown').addClass('merchant-show');

            window.wp.ajax.post('merchant_module_feedback', {
                subject: $disableModuleTextField.attr('data-subject'),
                message: message,
                module: $button.closest('.merchant-module-action').find('.merchant-module-page-button-action-activate').data('module'),
                nonce: window.merchant.nonce,
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
                nonce: window.merchant.nonce,
            }).done(function () {

                $('body').removeClass('merchant-module-disabled').addClass('merchant-module-enabled');
                $('.merchant-module-action').addClass('merchant-enabled');

            });

        });

        $('.merchant-module-page-button-action-deactivate').on('click', function (e) {

            e.preventDefault();

            window.wp.ajax.post('merchant_module_deactivate', {
                module: $(this).data('module'),
                nonce: window.merchant.nonce,
            }).done(function () {

                $('body').removeClass('merchant-module-enabled').addClass('merchant-module-disabled');
                $('.merchant-module-action').removeClass('merchant-enabled');
                $('.merchant-module-question-list-dropdown').addClass('merchant-show');

            });

        });

        $('.merchant-module-question-list-dropdown li').on('click', function (e) {

            $disableModuleSubmitBtn.prop('disabled', $disableModuleTextField.val().trim() === '');

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
                var value = ((e.type === 'merchant') ? $numberInput.val() : $range.val()) || 0;
                var min = $range.attr('min') || 0;
                var max = $range.attr('max') || 1;
                var percentage = (((value - min) / (max - min)) * 100);
                if ($('body').hasClass('rtl')) {
                    $range.css({
                        'background': 'linear-gradient(to left, #3858E9 0%, #3858E9 ' + percentage + '%, #ddd ' + percentage + '%, #ddd 100%)'
                    });
                } else {
                    $range.css({
                        'background': 'linear-gradient(to right, #3858E9 0%, #3858E9 ' + percentage + '%, #ddd ' + percentage + '%, #ddd 100%)'
                    });
                }
                $rangeInput.val(value);
                $numberInput.val(value);

            }).trigger('merchant-init.range');

            $numberInput.on('change input blur', function () {

                if ($rangeInput.hasClass('merchant-range-input')) {
                    $rangeInput.val($(this).val()).trigger('merchant.range');
                }

            });

        });

        // Add support for toggle field inside flexible content.
        const flexibleToggleField = {
            init: function (field) {
                this.events();
            },

            events: function () {
                $(document).on('click', '.merchant-flexible-content .merchant-toggle-switch .toggle-switch-label span', function () {
                    let checkBox = $(this).closest('.merchant-toggle-switch').find('.toggle-switch-checkbox');
                    checkBox.prop('checked', !checkBox.prop('checked'));
                }).trigger('merchant.change');
            }
        }

        const dateTimePickerField = {
            init: function () {
                this.initiate_datepicker();
                this.events();
            },

            initiate_datepicker: function () {
                let elements = $('.merchant-module-page-setting-field .merchant-datetime-field');
                if (elements.length === 0) {
                    return;
                }
                elements.each(function () {
                    let input = $(this).find('input'),
                        options = {
                            locale: JSON.parse(merchant_datepicker_locale),
                            selectedDates: [input.val() ? new Date(input.val()) : new Date()],
                            onSelect: ({date, formattedDate, datepicker}) => {
                                input.trigger('change.merchant')
                            }
                        },
                        fieldOptions = $(this).data('options');

                    if (fieldOptions) {
                        if (fieldOptions.minDate !== undefined && fieldOptions.minDate === 'today') {
                            fieldOptions.minDate = new Date();
                        }
                        options = Object.assign(options, fieldOptions);
                    }
                    new AirDatepicker(input.getPath(), options);
                    input.attr('readonly', true);
                });
            },

            events: function () {
                const self = this;
                $(document).on('merchant-flexible-content-added', function () {
                    self.initiate_datepicker();
                });
            }
        }

        dateTimePickerField.init();

        // Sortable.
        const SortableField = {
            init: function (field) {
                this.events();
            },

            events: function () {
                const self = this;

                $('.merchant-sortable').each(function () {

                    const
                        field = $(this),
                        input = field.find('.merchant-sortable-input');

                    // Init sortable.
                    $(field.find('ul.merchant-sortable-list').first()).sortable({

                        // Update value when we stop sorting.
                        update: function () {
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

            sortableGetNewVal: function (field) {
                const items = $(field.find('li'));
                let newVal = [];

                _.each(items, function (item) {
                    if (!$(item).hasClass('invisible')) {
                        newVal.push($(item).data('value'));
                    }
                });

                return JSON.stringify(newVal);
            }
        }

        // Intialize Sortable.
        SortableField.init();
        flexibleToggleField.init();

        // Sortable Repeater.
        const SortableRepeaterField = {
            init: function (field) {
                const self = this;

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
                            update: function (event, ui) {
                                self.getAllInputs($(this).parent());
                            }
                        });
                    }
                });

                // Events.
                this.events();
            },

            events: function () {
                const self = this;

                // Remove item starting from its parent element
                $('.merchant-sortable-repeater.sortable').on('click', '.customize-control-sortable-repeater-delete', function (event) {
                    event.preventDefault();
                    $(this).parent().slideUp('fast', function () {
                        var parentContainer = $(this).parent().parent();
                        $(this).remove();
                        self.getAllInputs(parentContainer);
                    })
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
                })

                $('.merchant-sortable-repeater.sortable').on('focusout', '.repeater-input', function () {
                    self.getAllInputs($(this).parent());
                });
            },

            /**
             * Append a new row to our list of elements.
             *
             */
            appendRow: function ($element, defaultValue = '') {
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
            getAllInputs: function ($element) {
                var inputValues = $element.find('.repeater-input').map(function () {
                    return $(this).val();
                }).toArray();

                // Keep one empty item if all deleted.
                if ( ! inputValues.length ) {
                    inputValues.push( '' );
                }

                // Add all the values from our repeater fields to the hidden field (which is the one that actually gets saved)
                $element.find('.merchant-sortable-repeater-input').val(JSON.stringify(inputValues));
                // Important! Make sure to trigger change event so Customizer knows it has to save the field
                $element.find('.merchant-sortable-repeater-input').trigger('change');
            }
        }

        // Initialize Sortable Repeater.
        SortableRepeaterField.init();

        // Flexible Content.
        const FlexibleContentField = {
            init: function (field) {
                const self = this;

                // Update the values for all our input fields and initialise the sortable repeater.
                $('.merchant-flexible-content-control').each(function () {
                    let hasAccordion = $(this).hasClass('has-accordion'),
                        $content = $(this).find('.merchant-flexible-content');

                    if (hasAccordion) {
                        $content.accordion({
                            collapsible: true,
                            header: "> div > .layout-header",
                            heightStyle: "content"
                        }).sortable({
                            axis: 'y',
                            cursor: 'move',
                            helper: 'original',
                            handle: '.customize-control-flexible-content-move',
                            stop: function (event, ui) {
                                $content.trigger('merchant.sorted')
                                self.refreshNumbers($content);
                                $content.accordion("refresh");
                            }
                        });
                    } else {
                        $content.sortable({
                            axis: 'y',
                            cursor: 'move',
                            helper: 'original',
                            handle: '.customize-control-flexible-content-move',
                            stop: function (event, ui) {
                                $content.trigger('merchant.sorted')
                                self.refreshNumbers($content);
                                $content.accordion("refresh");
                            }
                        });
                    }

                });
                this.updateLayoutTitle();
                // Events.
                this.events();
            },

            updateLayoutTitle: function () {
                // Update the title for all layout header.
                $('.merchant-flexible-content .layout').each(function () {
                    let title = $(this).find('.layout-title[data-title-field]');
                    if (title.length) {
                        let input = $(this).find('.layout-body .merchant-field-' + title.data('title-field') + ' input');
                        input.on('change keyup', function () {
                            title.text($(this).val());
                        });
                        title.text(input.val());
                    }
                });
            },

            events: function () {
                const self = this;

                $('.customize-control-flexible-content-add-button').click(function (event) {
                    event.preventDefault();
                    event.stopImmediatePropagation();

                    if ($(this).parent().find('.customize-control-flexible-content-add-list a').length === 1) {
                        // If there is only one layout, trigger click on it.
                        $(this).parent().find('.customize-control-flexible-content-add-list a').trigger('click');
                        return;
                    }

                    $(this).parent().find('.customize-control-flexible-content-add-list').toggleClass('active');
                });

                $('.customize-control-flexible-content-add').click(function (event) {
                    event.preventDefault();
                    event.stopImmediatePropagation();

                    var $field = $('.merchant-flexible-content-control[data-id=' + $(this).data('id') + ']')
                    var $layouts = $field.find('.layouts');
                    var $selected = $(this).data('layout');
                    var $layout = $layouts.find('.layout[data-type=' + $selected + ']').clone(true);
                    var $content = $field.find('.merchant-flexible-content');
                    var $items = $content.find('.layout')

                    $layout.find('input, select').each(function () {
                        if ($(this).data('name')) {
                            $(this).attr('name', $(this).data('name').replace('0', ($items.length)))
                        }
                    })
                    $layout.find('.layout-count').text($items.length + 1)

                    $content.append($layout);
                    $content.removeClass('empty')

                    $(this).parent().removeClass('active')

                    if ($layout.find('.merchant-module-page-setting-field-upload').length) {
                        initUploadField($layout.find('.merchant-module-page-setting-field-upload'))
                    }

                    if ($layout.find('.merchant-module-page-setting-field-select_ajax').length) {
                        initSelectAjax($layout.find('.merchant-module-page-setting-field-select_ajax'))
                    }

                    let parentDiv = $(this).closest('.merchant-flexible-content-control'),
                        hasAccordion = parentDiv.hasClass('has-accordion')
                    if (hasAccordion) {
                        parentDiv.find('.merchant-flexible-content').accordion("refresh");
                        // Expand the accordion last added
                        parentDiv.find('.merchant-flexible-content').accordion("option", "active", -1);
                    }

                    $(document).trigger('merchant-flexible-content-added', [$layout]);

                    self.updateLayoutTitle()
                });

                $('.customize-control-flexible-content-delete').click(function (event) {
                    event.preventDefault();


                    var $item = $(this).closest('.layout');
                    var $content = $item.parent();

                    $item.remove();

                    if ($content.find('.layout').length === 0) {
                        $content.addClass('empty')
                    }

                    self.refreshNumbers($content);
                    $(document).trigger('merchant-flexible-content-deleted', [$item]);

                    let parentDiv = $(this).closest('.merchant-flexible-content-control'),
                        hasAccordion = parentDiv.hasClass('has-accordion')
                    if (hasAccordion) {
                        parentDiv.find('.merchant-flexible-content').accordion("refresh");
                    }
                    $(document).trigger('change.merchant');
                });
            },

            refreshNumbers: function ($content) {
                $content.find('.layout').each(function (index) {

                    var $count = $(this).find('.layout-count').text();
                    var $inputIndex = parseInt($count) - 1;

                    $(this).find('.layout-count').text(index + 1);

                    $(this).find('input, select').each(function () {
                        if ($(this).attr('name')) {
                            $(this).attr('name', $(this).attr('name').replace('[' + $inputIndex + ']', '[*refreshed*' + (index) + ']'))
                        }
                    })
                })
                $content.find('.layout').each(function (index) {
                    $(this).find('input, select').each(function () {
                        // We've added *refreshed* to the attribute name in the prior loop as refreshing the numbers in the attribute can cause
                        // checked boxes to be unchecked due to similar attribute names during the change while sorting, within this loop we remove them
                        let nameAttr = $(this).attr('name');
                        if (nameAttr) { // Check if name attribute exists
                            $(this).attr('name', nameAttr.replace('*refreshed*', ''));
                        }
                    });
                });
                $content.parent().find('input').trigger('change.merchant')
            },
        }

        // Initialize Flexible Content.
        FlexibleContentField.init();

        // Products selector.
        // Handle keyup event for the search input
        $(document).on('keyup', '.merchant-module-page-setting-field-products_selector .merchant-search-field', function () {
            let parent = $(this).closest('.merchant-products-search-container');
            if ($(this).val() !== '') {
                parent.find('.merchant-searching').addClass('active');
                let data = {
                    action: 'merchant_admin_products_search',
                    nonce: merchant_admin_options.ajaxnonce,
                    keyword: $(this).val(),
                    product_types: $(this).data('allowed-types'),
                    ids: parent.find('.merchant-selected-products').val(),
                };

                $.post(merchant_admin_options.ajaxurl, data, function (response) {
                    let results = parent.find('.merchant-selections-products-preview');
                    results.show();
                    results.html(response);
                    parent.find('.merchant-searching').removeClass('active');
                });
            } else {
                parent.find('.merchant-selections-products-preview').html('').hide();
            }
        });

        // Products selector.
        // Handle click/touch event for the search results
        $(document).on('click touch', '.merchant-module-page-setting-field-products_selector .merchant-selections-products-preview li', function () {
            let parent = $(this).closest('.merchant-products-search-container'),
                valueField = parent.find('.merchant-selected-products'),
                oldValue = valueField.val(),
                multiple = parent.data('multiple') === 'multiple';
            if (parent.find('.merchant-selected-products-preview ul li').length > 0 && !multiple) {
                // replace the first item
                parent.find('.merchant-selected-products-preview ul li').remove();
                valueField.val('');
            }
            $(this).children('.remove').attr('aria-label', 'Remove').html('×');
            parent.find('.merchant-selected-products-preview ul').append($(this));
            parent.find('.merchant-selections-products-preview').html('').hide();
            parent.find('.merchant-search-field').val('');
            if (oldValue === '') {
                valueField.val($(this).data('id'));
            } else {
                let newValue = oldValue.split(',');
                newValue.push($(this).data('id'));
                valueField.val(newValue.join(','));
            }
        });

        // Products selector.
        // Handle click/touch event for the remove button.
        $(document).on('click touch', '.merchant-selected-products-preview .remove', function () {
            // Store a reference to the remove button
            let removeButton = $(this);
            // Ask for confirmation before removing the product
            if (confirm(merchant_admin_options.product_delete_confirmation_message)) {
                let parent = removeButton.closest('.merchant-products-search-container'),
                    valueField = parent.find('.merchant-selected-products'),
                    id = removeButton.parent().data('id');
                removeButton.parent().remove();
                let currentValue = valueField.val().split(',');
                if (currentValue.length > 0) {
                    for (var key in currentValue) {
                        if (parseInt(currentValue[key]) === parseInt(id)) {
                            currentValue.splice(key, 1);
                        }
                    }
                }
                valueField.val(currentValue.join(','));
                valueField.trigger('change.merchant');
            }
        });


        $(document).on('merchant-admin-check-fields merchant-flexible-content-added', function () {
            $('.merchant-module-page-setting-field').each(function () {
                var $field = $(this);
                if ($field.data('condition') && $field.data('condition').length) {
                    var condition = $field.data('condition');
                    var $target = $(this).closest('.layout-body').find('input[name*="' + condition[0] + '"],select[name*="' + condition[0] + '"]');
                    if (!$target.length) {
                        $target = $('input[name="merchant[' + condition[0] + ']"],select[name="merchant[' + condition[0] + ']"]');
                    }
                    if ($target.length) {
                        var passed = false;
                        switch (condition[1]) {
                            case '==':
                                if ($target.attr('type') === 'radio' || $target.attr('type') === 'checkbox') {
                                    if ($target.is(':checked') && $target.val() == condition[2]) {
                                        passed = true;
                                    }
                                }
                                if ($target.is('select') && $target.val() == condition[2]) {
                                    passed = true;
                                }
                                break;
                            case 'any':
                                if ($target.attr('type') === 'radio' || $target.attr('type') === 'checkbox') {
                                    if ($target.is(':checked') && condition[2].split('|').includes($target.val())) {
                                        passed = true;
                                    }
                                }
                                if ($target.is('select') && condition[2].split('|').includes($target.val())) {
                                    passed = true;
                                }
                                break;
                        }
                        if (passed) {
                            $field.removeClass('merchant-hide').addClass('merchant-show');
                        } else {
                            $field.removeClass('merchant-show').addClass('merchant-hide');
                        }
                    }
                }
            });
        }).trigger('merchant.change');
        $(document).on('change', '.merchant-module-page-setting-field', function () {
            $(document).trigger('merchant-admin-check-fields');
        }).trigger('merchant.change');

        $(document).trigger('merchant-admin-check-fields');
        $(document).on('merchant-admin-check-color-fields merchant-flexible-content-added', function () {
            $('.merchant-color').each(function () {
                let $color = $(this);
                let $picker = $color.find('.merchant-color-picker');
                let $input = $color.find('.merchant-color-input');
                let inited = false;
                let pickr;

                $picker.off('click').on('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var $bodyHTML = $('body,html');

                    $bodyHTML.addClass('merchant-height-auto');

                    if (!inited) {

                        pickr = new Pickr({
                            el: $picker.get(0),
                            container: 'body',
                            theme: 'merchant',
                            appClass: 'merchant-pcr-app',
                            default: $input.val() || $picker.data('default-color') || '#212121',
                            swatches: ['#000000', '#F44336', '#E91E63', '#673AB7', '#03A9F4', '#8BC34A', '#FFEB3B', '#FFC107', '#FFFFFF'],
                            sliders: 'h',
                            useAsButton: true,
                            components: {
                                hue: true,
                                preview: true,
                                opacity: true,
                                interaction: {
                                    input: true,
                                    clear: true,
                                },
                            },
                            i18n: {
                                'btn:clear': 'Default',
                            },
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

                            $picker.css({'background-color': colorCode});

                            if ($input.val() !== colorCode) {
                                $input.val(colorCode).trigger('change.merchant');
                            }

                        });

                        pickr.on('clear', function () {

                            var defaultColor = $picker.data('default-color');

                            if (defaultColor) {
                                pickr.setColor(defaultColor);
                            } else {
                                $picker.css({'background-color': 'white'});
                                $input.val('');
                            }

                        });

                        pickr.on('hide', function () {
                            $bodyHTML.removeClass('merchant-height-auto');
                        });

                        $picker.data('pickr', pickr);

                        setTimeout(function () {
                            pickr.show();
                        }, 200);

                        inited = true;
                    } else {
                        pickr.setColor($input.val());
                    }
                });

                $input.on('change keyup', function () {

                    var colorCode = $(this).val();

                    $picker.css({'background-color': colorCode});

                });

            });
        });
        $(document).trigger('merchant-admin-check-color-fields');

        // Create Page Control.
        const CreatePageControl = {
            init: function () {
                this.events();
            },

            events: function () {
                const self = this;

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
                        success: function (response) {
                            self.ajaxResponseHandler(response, $this, $success_message, $create_message)
                        }
                    });
                });
            },

            ajaxResponseHandler: function (response, $this, $success_message, $create_message) {
                if ('success' === response.status) {
                    const
                        href = $success_message.find('a').attr('href'),
                        newhref = href.replace('?post=&', '?post=' + response.page_id + '&');

                    $success_message.find('a').attr('href', newhref);
                    $success_message.css('display', 'block');

                    $create_message.remove();
                    $this.remove();
                }
            }
        }

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
                update: function (event, ui) {

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
                var mode = (ids) ? 'edit' : 'add';

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
                        var thumb = (item.sizes && item.sizes.thumbnail && item.sizes.thumbnail.url) ? item.sizes.thumbnail.url : item.url;

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


        const initUploadField = (element) => {
            var $this = element;
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
                        },
                    });

                }

                wpMediaFrame.open();

                wpMediaFrame.on('select', function () {

                    $wrapper.empty();

                    var item = wpMediaFrame.state().get('selection').first().attributes;
                    var thumb = (item.sizes && item.sizes.thumbnail && item.sizes.thumbnail.url) ? item.sizes.thumbnail.url : item.url;

                    $wrapper.append('<div class="merchant-upload-image"><i class="merchant-upload-remove dashicons dashicons-no-alt"></i><img src="' + thumb + '" /></div>');

                    $input.val(item.id).trigger('change');

                    $this.find('.merchant-upload-remove').on('click', function (e) {

                        e.preventDefault();

                        $(this).parent().remove();

                        $input.val('').trigger('change');

                    });


                });

            });
        }

        $('.merchant-module-page-setting-field-upload:not(.template)').each(function () {
            initUploadField($(this))
        });

        const initSelectAjax = ($selectAjax) => {
            var $select = $selectAjax.find('select');
            var $source = $select.data('source');
            var $config = window.merchant_admin_options;
            var $object = {
                width: '100%',
            }

            if ($source === 'post' || $source === 'product') {
                $object.minimumInputLength = 1;
                $object.ajax = {
                    url: $config.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                    data: function (params) {
                        return {
                            action: 'merchant_admin_options_select_ajax',
                            nonce: $config.ajaxnonce,
                            term: params.term,
                            source: $source,
                        };
                    },
                    processResults: function (response, params) {
                        if (response.success) {
                            return {
                                results: response.data,
                            };
                        }
                        return {};
                    },
                };
            }

            $select.select2($object);

            $selectAjax.find('.select2-selection--multiple').append('<span class="merchant-select2-clear"></span>');
        }

        $('.merchant-module-page-setting-field-select_ajax:not(.template)').each(function () {
            initSelectAjax($(this))
        });


        $('.merchant-module-page-settings-responsive').each(function () {
            var $this = $(this);
            var $button = $this.find('.merchant-module-page-settings-devices button');
            var $container = $this.find('.merchant-module-page-settings-device-container');

            $button.on('click', function (e) {
                e.preventDefault();

                var $device = $(this).data('device');

                $button.removeClass('active');
                $container.removeClass('active');

                $(this).addClass('active');

                $container.each(function () {
                    if ($(this).data('device') === $device) {
                        $(this).addClass('active');
                    }
                });

            })
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
                        latest_notification_date: latestNotificationDate,
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
        const tabs = $('.merchant-tabs-nav');
        if (tabs.length) {

            tabs.each(function () {
                const tabWrapperId = $(this).data('tab-wrapper-id');

                $(this).find('.merchant-tabs-nav-link').on('click', function (e) {
                    e.preventDefault();

                    const
                        tabsNavLink = $(this).closest('.merchant-tabs-nav').find('.merchant-tabs-nav-link'),
                        to = $(this).data('tab-to');

                    // Tab Nav Item
                    tabsNavLink.each(function () {
                        $(this).closest('.merchant-tabs-nav-item').removeClass('active');
                    });

                    $(this).closest('.merchant-tabs-nav-item').addClass('active');

                    // Tab Content
                    const tabContentWrapper = $('.merchant-tab-content-wrapper[data-tab-wrapper-id="' + tabWrapperId + '"]');
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

// Extend jQuery to add getPath func to get accurate dynamic selector to an element.
jQuery.fn.extend({
    getPath: function() {
        var pathes = [];

        this.each(function(index, element) {
            var path, $node = jQuery(element);

            while ($node.length) {
                var realNode = $node.get(0), name = realNode.localName;
                if (!name) { break; }

                name = name.toLowerCase();
                var parent = $node.parent();
                var sameTagSiblings = parent.children(name);

                if (sameTagSiblings.length > 1)
                {
                    var allSiblings = parent.children();
                    var index = allSiblings.index(realNode) + 1;
                    if (index > 0) {
                        name += ':nth-child(' + index + ')';
                    }
                }

                path = name + (path ? ' > ' + path : '');
                $node = parent;
            }

            pathes.push(path);
        });

        return pathes.join(',');
    }
});