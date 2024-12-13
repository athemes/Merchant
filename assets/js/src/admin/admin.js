;(function ($, window, document, undefined) {
    'use strict';

    var merchant = merchant || {};

    const params = new URLSearchParams( window.location.search );
    const currentModule = params.get( 'module' );

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

            GroubField.initFlag();
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

                $( document ).trigger( 'save.merchant', [ currentModule ] );
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

            if ( $(this).hasClass( 'merchant-module-deactivated-by-bp' ) ) {
                return false;
            }

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

        $( document ).on( 'click', '.merchant-module-page-setting-field-hidden-desc-trigger', function() {
            const $trigger = $( this );

            $trigger.toggleClass( 'expanded' );

            const showText = $trigger.attr( 'data-show-text' );
            const hiddenText = $trigger.attr( 'data-hidden-text' );

            $( this ).find( 'span:first' ).text( $trigger.text() === showText ? hiddenText : showText );

            $( this ).closest( '.merchant-module-page-setting-field' ).find( '.merchant-module-page-setting-field-hidden-desc' ).stop(true, true).slideToggle( 'fast' );
        } );

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
                elements.each(function ( index ) {
                    let input = $(this).find('input'),
                        options = {
                            locale: JSON.parse(merchant_datepicker_locale),
                            selectedDates: [input.val() ? new Date(input.val()) : ''],
                            onSelect: ({date, formattedDate, datepicker}) => {
                                if (typeof (formattedDate) === "undefined") {
                                    // allow removing date
                                    // input.val('');
                                    datepicker.$el.value = '';
                                }
                                input.trigger('change.merchant');
                                input.trigger('change.merchant-datepicker', [ formattedDate, input, options, index ]);
                            }
                        },
                        fieldOptions = $(this).data('options');
                    // add buttons to fieldOptions
                    fieldOptions.buttons = ['clear'];

                    if (fieldOptions) {
                        if (fieldOptions.minDate !== undefined && fieldOptions.minDate === 'today') {
                            fieldOptions.minDate = new Date();
                            if(fieldOptions.timeZone !== undefined && fieldOptions.timeZone !== ''){
                                fieldOptions.minDate = new Date(fieldOptions.minDate.toLocaleString('en-US', {timeZone: fieldOptions.timeZone}));
                            }
                        }
                        options = Object.assign(options, fieldOptions);
                    }

                    const datepickerObj = new AirDatepicker(input.getPath(), options);
                    input.attr('readonly', true);

                    $( document ).trigger( 'initiated.merchant-datepicker', [ datepickerObj, input, options, index ] );
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

        // Initialize Sortable.
        SortableField.init();
        flexibleToggleField.init();

        // When adding/duplicating new item, refresh sorting
        $( document ).on('merchant-flexible-content-added', function (e, $layout) {
            const $sortableWrapper = $layout.find( '.merchant-sortable-repeater-control' );
            const $sortableElement = $sortableWrapper.find('.merchant-sortable-repeater.sortable');

            SortableRepeaterField.makeFieldsSortable( $sortableElement );
        });

        // Sortable Repeater.
        const SortableRepeaterField = {
            init: function (field) {
                const self = this;

                // Update the values for all our input fields and initialise the sortable repeater.
                $('.merchant-sortable-repeater-control').each(function () {

                    // If there is an existing customizer value, populate our rows
                    const defaultValuesArray = JSON.parse($(this).find('.merchant-sortable-repeater-input').val());
                    const numRepeaterItems = defaultValuesArray.length;

                    if (numRepeaterItems > 0) {
                        // Add the first item to our existing input field
                        $(this).find('.repeater-input').val(defaultValuesArray[0]);

                        // Create a new row for each new value
                        if (numRepeaterItems > 1) {
                            // var i;
                            for (let i = 1; i < numRepeaterItems; ++i) {
                                self.appendRow($(this), defaultValuesArray[i]);
                            }
                        }
                    }

                    // Todo: remove
                    // Make our Repeater fields sortable. Doesn't work with flexible content
                    // if (!$(this).hasClass('disable-sorting')) {
                    //     $(this).find('.merchant-sortable-repeater.sortable').sortable({
                    //         update: function (event, ui) {
                    //             self.getAllInputs($(this).parent());
                    //         }
                    //     });
                    // }
                });

                // Events.
                this.events();
            },

            events: function () {
                const self = this;

                // Remove item starting from its parent element
                $( document ).on('click', '.merchant-sortable-repeater.sortable .customize-control-sortable-repeater-delete', function (event) {
                    event.preventDefault();
                    $(this).parent().slideUp('fast', function () {
                        var parentContainer = $(this).parent().parent();
                        $(this).remove();
                        self.getAllInputs(parentContainer);
                    })

                    $( document ).trigger( 'merchant-sortable-repeater-item-deleted' );
                });

                // Add new item
                $( document ).on('click', '.customize-control-sortable-repeater-add', function(event) {
                    event.preventDefault();
                    self.appendRow($(this).parent());
                    self.getAllInputs($(this).parent());
                });

                // Refresh our hidden field if any fields change
                $(document).on( 'change', '.merchant-sortable-repeater.sortable', function() {
                    self.getAllInputs($(this).parent());
                })

                $( document ).on('focusout', '.merchant-sortable-repeater.sortable .repeater-input', function() {
                    self.getAllInputs($(this).parent());
                });
            },

            /**
             * Append a new row to our list of elements.
             *
             */
            appendRow: function ($element, defaultValue = '') {
                const newRow = '<div class="repeater" style="display:none"><input type="text" value="' + defaultValue + '" class="repeater-input" /><span class="dashicons dashicons-menu"></span><a class="customize-control-sortable-repeater-delete" href="#"><span class="dashicons dashicons-no-alt"></span></a></div>';

                $element.find('.sortable').append(newRow);

                const $newItem = $element.find('.sortable').find('.repeater:last');

                $newItem.slideDown('slow', function () {
                    $(this).find('input').focus();
                });

                // Make Repeater fields sortable; Putting here works better with flexible content
                this.makeFieldsSortable( $element.find('.sortable') );

                $( document ).trigger( 'merchant-sortable-repeater-item-added', [ $newItem, $element.find('.sortable') ] );
            },

            makeFieldsSortable: function ( $sortableElement ) {
                if ( ! $sortableElement.hasClass( 'disable-sorting' ) ) {
                    $sortableElement.sortable( {
                        update: function ( event, ui ) {
                            SortableRepeaterField.getAllInputs( $sortableElement.parent() );
                        }
                    } );
                }
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

                $element.find('.merchant-sortable-repeater-input').trigger('sortable.repeater.change');
            }
        }

        // Initialize Sortable Repeater.
        SortableRepeaterField.init();

        const GroubField = {
            init: function () {
                const self = this;
                self.initAccordion();
                self.initFlag();
            },
            initAccordion: function (){
                const self = this;
                $('.merchant-group-field.has-accordion').each(function () {
                    let element = $(this);
                    element.accordion({
                        collapsible: true,
                        header: "> .title-area",
                        heightStyle: "content",
                        active: element.hasClass('open') ? 0 : false,
                    })
                });
            },
            initFlag: function (){
                $('.merchant-group-field.has-flag').each(function () {
                    let element = $(this);
                    let field_id = element.data('id');
                    let status_field = element.find(`.merchant-field-${field_id}_status select`);
                    let selected_value = status_field.val();
                    let selected_label = status_field.find('option:selected').text();
                    let status_element = element.find('.field-status');
                    status_element.removeClass('hidden active inactive').text(selected_label).addClass(selected_value);
                });
            }
        }

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
                            //header: "> div > .layout-header",
                            header: function( elem ) {
                                return elem.find( '.layout__inner > .layout-header' );
                            },
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
                this.updateDiscountPercentMaxVal();
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

            updateDiscountPercentMaxVal: function() {
                $( '.merchant-flexible-content .layout' ).each( function () {
                    const $layout = $( this );
                    const $discountType = $layout.find( '.merchant-module-page-setting-field[data-id="discount_type"]' );

                    let $discountVal = $layout.find( '.merchant-module-page-setting-field[data-id="discount_value"]' );
                    $discountVal = $discountVal.length ? $discountVal : $layout.find( '.merchant-module-page-setting-field[data-id="discount"]' );
                    $discountVal = $discountVal.length ? $discountVal : $layout.find( '.merchant-module-page-setting-field[data-id="discount_amount"]' );

                    const checkedValue = $discountType.find( 'input:checked' ).val();

                    // Set/Remove max value based on the discount type.
                    ( checkedValue === 'percentage_discount' || checkedValue === 'percentage' )
                        ? $discountVal.find( 'input' ).attr( 'max', 100 )
                        : $discountVal.find( 'input' ).removeAttr( 'max' );
                } );

                $( '.merchant-module-page-setting-fields' )
            },

            events: function () {
                const self = this;

                $( document ).on( 'change', '.merchant-module-page-setting-field[data-id="discount_type"] input', function() {
                    self.updateDiscountPercentMaxVal();
                } );

                // Update "selected" attribute on select. It's Required for duplicating layouts.
                $( document ).on( 'change', 'select', function() {
                    const selectedValue = $( this ).val();
                    const isMultiple    = $( this ).prop( 'multiple' );

                    $( this ).find( 'option' ).each( function() {
                        const currentValue = $( this ).val();
                        const isSelected = isMultiple ? selectedValue.includes( currentValue ) : currentValue === selectedValue;
                        $( this ).attr( 'selected', isSelected );
                    } );
                } );

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

                // Add new item
                $( document ).on( 'click', '.customize-control-flexible-content-add', function (event) {
                    event.preventDefault();
                    event.stopImmediatePropagation();

                    var $field = $('.merchant-flexible-content-control[data-id=' + $(this).data('id') + ']')
                    var $layouts = $field.find('.layouts');
                    var $selected = $(this).data('layout');
                    var $layout = $layouts.find('.layout[data-type=' + $selected + ']').clone(true);
                    var $content = $field.find('.merchant-flexible-content');
                    var $items = $content.find('.layout')

                    $layout.find('input, select, textarea').each(function () {
                        if ($(this).data('name')) {
                            $(this).attr('name', $(this).data('name').replace('0', ($items.length)))
                        }

                        if ($(this).is(':checkbox, :radio') && $(this).attr('checked')) {
                            $(this).prop('checked', true);
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

                    GroubField.init();

                    $(document).trigger('merchant-flexible-content-added', [$layout]);

                    self.updateLayoutTitle();
                    self.updateDiscountPercentMaxVal();
                });

                // Duplicate item
                $( document ).on( 'click', '.customize-control-flexible-content-duplicate', function( event ) {
                    event.preventDefault();
                    event.stopImmediatePropagation();

                    const $duplicateBtn = $( this );

                    const $flexibleContentWrapper = $duplicateBtn.closest( '.merchant-flexible-content-control[data-id=' + $duplicateBtn.data( 'id' ) + ']' );
                    const $flexibleContent        = $flexibleContentWrapper?.find( '.merchant-flexible-content' );

                    if ( ! $flexibleContentWrapper.length || ! $flexibleContent.length ) {
                        return;
                    }

                    const $sourceLayout = $duplicateBtn.closest( '.layout' );
                    if ( ! $sourceLayout.length ) {
                        return;
                    }

                    $sourceLayout.find( '.layout-actions__inner' ).hide();

                    // Clone the layout without data & events.
                    const $clonedLayout = $sourceLayout.clone();
                    const $items = $flexibleContent.find('.layout');

                    const index = $sourceLayout.find('.layout-count').text();

                    $clonedLayout.find( 'input, select, textarea' ).each( function () {
                        const $input = $( this );
                        const inputName = $input.attr( 'name' );

                        if ( inputName ) {
                            const prefix    = inputName.split( '[' )[0];
                            const indexPart = inputName.match( /\[(.*?)\]/g );

                            if ( indexPart && indexPart.length > 1 ) {
                                indexPart[1]           = '['+ index +']';
                                const newName = `${ prefix }${ indexPart.join( '' ) }`;
                                $input.attr( 'name', newName );
                            }
                        }
                    } );

                    // Find select2, Remove and Re-init again. select.select2('destroy') doesn't work.
                    $clonedLayout.find( 'select' ).each( function () {
                        if ( $( this ).hasClass( 'select2-hidden-accessible' ) ) {
                            $( this )
                                .removeClass( 'select2-hidden-accessible' )
                                .removeAttr('data-live-search')
                                .removeAttr('data-select2-id')
                                .removeAttr('aria-hidden')
                                .removeAttr('tabindex');

                            // Remove the existing dropdown
                            $( this ).nextAll( '.select2-container' ).remove();

                            // Re-init
                            $( this ).select2();
                        }
                    } );

                    // Removing copied style to make the accordion work properly.
                    $clonedLayout.find( '.layout-body' ).removeAttr( 'style' );

                    // Append the cloned layout right after the source one with fadeIn effect.
                    $clonedLayout.hide();
                    $clonedLayout.insertAfter( $sourceLayout );
                    $clonedLayout.fadeIn();

                    if ( $clonedLayout.find( '.merchant-module-page-setting-field-upload').length ) {
                        initUploadField( $clonedLayout.find( '.merchant-module-page-setting-field-upload' ) );
                    }

                    if ( $clonedLayout.find( '.merchant-module-page-setting-field-select_ajax' ).length ) {
                        initSelectAjax( $clonedLayout.find( '.merchant-module-page-setting-field-select_ajax' ) );
                    }

                    self.refreshNumbers( $flexibleContent );

                    $( document ).trigger( 'merchant-flexible-content-added', [ $clonedLayout ] );

                    if ( $flexibleContentWrapper.hasClass( 'has-accordion' ) ) {
                        $flexibleContent.accordion( 'refresh' );
                    }

                    self.updateLayoutTitle();
                    GroubField.init();
                } );

                // Delete item
                $( document ).on( 'click', '.customize-control-flexible-content-delete', function (event) {
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

                // Toggle Actions(delete/duplicate)
                $( document ).on( 'click', '.layout-actions__toggle', function( e ) {
                    e.preventDefault();

                    // Hide other opened elements
                    hideOtherActions( $( this ).closest( '.layout' ) )

                    // Toggle the current element
                    $( this )
                        .closest( '.layout-actions' )
                        .find( '.layout-actions__inner' )
                        .stop()
                        .slideToggle( 300 );
                } );

                // Hide Actions when collapse/open
                $( document ).on( 'click', '.layout-header', function() {
                    hideOtherActions( $( this ).closest( '.layout' ) );
                } )

                $( document ).on( 'merchant-flexible-content-added', function( e, $layout ) {
                    hideOtherActions( $layout );
                } );

                function hideOtherActions( $layout ) {
                    if ( $layout && $layout.length ) {
                        $layout.siblings().find( '.layout-actions__inner' ).slideUp( 300 );
                    }
                }
            },

            refreshNumbers: function ($content) {
                $content.find('.layout').each(function (index) {

                    var $count = $(this).find('.layout-count').text();
                    var $inputIndex = parseInt($count) - 1;

                    $(this).find('.layout-count').text(index + 1);

                    $(this).find('input, select, textarea').each(function () {
                        if ($(this).attr('name')) {
                            $(this).attr('name', $(this).attr('name').replace('[' + $inputIndex + ']', '[*refreshed*' + (index) + ']'))
                        }
                    })
                })
                $content.find('.layout').each(function (index) {
                    $(this).find('input, select, textarea').each(function () {
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
        GroubField.init();

        // Products selector.
        // Handle keyup event for the search input
        let debounceTimer;
        $(document).on('keyup', '.merchant-module-page-setting-field-products_selector .merchant-search-field', function () {
            clearTimeout( debounceTimer );

            let categories = [];

            const $excluded = $( this ).closest( '[data-id="excluded_products"]' );
            if ( $excluded.length ) {
                const $layout = $(this).closest( '.layout' );
                const rules = $layout.find( '.merchant-field-rules_to_apply select' ).val()
                    || $layout.find( '.merchant-field-rules_to_display select' ).val()
                    || $layout.find( '.merchant-field-display_rules select' ).val();

                if ( rules === 'categories' || rules === 'by_category' ) {
                    categories = $layout.find('.merchant-field-category_slugs select').val() || $layout.find('.merchant-field-product_cats select').val();
                }
            }

            let parent = $(this).closest('.merchant-products-search-container');

            if ($(this).val() !== '') {
                parent.find('.merchant-searching').addClass('active');
                let data = {
                    action: 'merchant_admin_products_search',
                    nonce: merchant_admin_options.ajaxnonce,
                    keyword: $(this).val(),
                    product_types: $(this).data('allowed-types'),
                    ids: parent.find('.merchant-selected-products').val(),
                    categories,
                };

                debounceTimer = setTimeout( () => {
                    $.post(merchant_admin_options.ajaxurl, data, function (response) {
                        let results = parent.find('.merchant-selections-products-preview');
                        results.show();
                        results.html(response);
                        parent.find('.merchant-searching').removeClass('active');
                    });
                }, 250 );
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
                valueField.val('').change();
            }
            $(this).children('.remove').attr('aria-label', 'Remove').html('×');
            parent.find('.merchant-selected-products-preview ul').append($(this));
            parent.find('.merchant-selections-products-preview').html('').hide();
            parent.find('.merchant-search-field').val('').change();
            if (oldValue === '') {
                valueField.val($(this).data('id'));
            } else {
                if (!multiple) {
                    valueField.val($(this).data('id')).change();
                } else {
                    let newValue = oldValue.split(',');
                    newValue.push($(this).data('id'));
                    valueField.val(newValue.join(',')).change();
                }
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
                valueField.val(currentValue.join(',')).change();
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
                                    let checked = $target.parent().find('input:checked');
                                    if (checked.length && checked.val() === condition[2]) {
                                        passed = true;
                                    }
                                }
                                if ($target.is('select') && $target.val() == condition[2]) {
                                    passed = true;
                                }
                                break;
                            case 'any':
                                if ($target.attr('type') === 'radio' || $target.attr('type') === 'checkbox') {
                                    let checked = $target.parent().find('input:checked');
                                    if (checked.length && condition[2].split('|').includes(checked.val())) {
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

        $(document).on('merchant-admin-check-fields merchant-flexible-content-added change keyup', function () {
            $(document).find('.merchant-module-page-setting-field').each(function () {
                let $field = $(this);
                if ($field.data('conditions')) {
                    let conditions = $field.data('conditions'),
                        passed = evaluateConditions(conditions, $field);
                    if (passed) {
                        $field.removeClass('merchant-hide').addClass('merchant-show');
                    } else {
                        $field.removeClass('merchant-show').addClass('merchant-hide');
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
                            $(document).trigger('merchant-color-picker-updated', [colorCode, $input]);
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
                    $this.find( '.merchant-upload-button-drag-drop' ).hide();

                    $this.find('.merchant-upload-remove').on('click', function (e) {

                        e.preventDefault();

                        $(this).parent().remove();

                        $input.val('').trigger('change');
                        $this.find( '.merchant-upload-button-drag-drop' ).show();
                    });
                });
            });
        }

        $('.merchant-module-page-setting-field-upload:not(.template)').each(function () {
            initUploadField($(this))
        });

        // Drag & Drag
        const events = [ 'dragenter', 'dragover', 'dragleave', 'drop' ];
        jQuery.each( events, function( index, eventName ) {
            $( document).on( eventName, '.merchant-upload-button-drag-drop', function( e ) {
                e.preventDefault();
                e.stopPropagation();
            } );
        } );

        $( document).on( 'dragenter', '.merchant-upload-button-drag-drop', function( e ) {
            $( this ).closest('.merchant-module-page-setting-field-upload').find( '.merchant-upload-button' ).click();
        } );

        const initSelectAjax = ($selectAjax) => {
            var $select = $selectAjax.find('select');
            var $source = $select.data('source');
            var $config = window.merchant_admin_options;
            var $object = {
                width: '100%',
                templateSelection: function( category ) {
                    return category.text.replace(/&nbsp;-*\s*/g, '').trim();
                },
            }

            if ($source === 'post' || $source === 'product' || $source === 'user') {
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

    /**
     * Check if a string is numeric.
     *
     * @param str the string to check
     *
     * @returns {boolean} true if numeric, false otherwise
     */
    function isNumeric(str) {
        if (typeof str != "string") return false // we only process strings!
        return !isNaN(str) && // use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
            !isNaN(parseFloat(str)) // ...and ensure strings of whitespace fail
    }

    /**
     * Evaluate conditional fields.
     *
     * @param conditions the array of conditions
     * @param field the field that is being evaluated
     *
     * @returns {boolean}
     */
    function evaluateConditions(conditions, field = false) {
        let passed = false;

        if ('relation' in conditions) {
            //loop through terms
            let relation = conditions.relation.toUpperCase();
            // lowercase relation
            if (relation === 'OR') {
                for (let i = 0; i < conditions.terms.length; i++) {
                    let term = conditions.terms[i];
                    passed = evaluateConditions(term, field);
                    if (passed) {
                        return true;
                    }
                }
            } else if (relation === 'AND') {
                let n = 0;
                for (let i = 0; i < conditions.terms.length; i++) {
                    // check if inner terms are passed
                    let term = conditions.terms[i];
                    if (evaluateConditions(term, field)) {
                        n++;
                    }
                }
                if (n === conditions.terms.length) {
                    passed = true;
                }
            }
        } else {
            let condition = '';
            if ('terms' in conditions) {
                condition = conditions.terms[0];
            } else {
                condition = conditions;
            }
            let $target = $('input[name="merchant[' + condition.field + ']"],select[name="merchant[' + condition.field + ']"]');
            if (!$target.length) {
                // check if inside flexible content
                let flexibleContentParent = field.closest('.layout-body');
                if (flexibleContentParent.length > 0) {
                    $target = flexibleContentParent.find('.merchant-field-' + condition.field).find('input, select');
                }
            }

	        if (!$target.length) {
				// Maybe the field is a multiple field
		        $target = $('input[name="merchant[' + condition.field + '][]"],select[name="merchant[' + condition.field + '][]"]')
	        }

            if (!$target.length) {
                // Maybe the field is inside fields group
                $target = $('.merchant-group-fields-container').find('.merchant-field-' + condition.field + ' input[name*="' + condition.field + '"],.merchant-field-' + condition.field + ' select[name*="' + condition.field + '"]');
            }

            let value = $target.val();
            if ($target.attr('type') === 'checkbox') {
                value = $target.is(':checked');
            }

            if ($target.attr('type') === 'radio') {
                value = $target.filter(':checked').val();
            }

	        // check if the field is multiple checkbox
	        if ($target.attr('type') === 'checkbox' && $target.length > 1) {
		        value = [];
		        $target.each(function () {
			        if ($(this).is(':checked')) {
				        value.push($(this).val());
			        }
		        });
	        }

            // cast value as string if numeric
            if (isNumeric(value)) {
                value = Number(value);
            }

            // check if is array condition.value
            if (Array.isArray(condition.value)) {
                condition.value = condition.value.map(function (item) {
                    if (isNumeric(item)) {
                        return Number(item);
                    }
                    return item;
                });
            }
            if (condition.operator === '===' && value === condition.value) {
                passed = true;
            } else if (condition.operator === '!==' && value !== condition.value) {
                passed = true;
            } else if (condition.operator === '>' && value > condition.value) {
                passed = true;
            } else if (condition.operator === '<' && value < condition.value) {
                passed = true;
            } else if (condition.operator === '>=' && value >= condition.value) {
                passed = true;
            } else if (condition.operator === '<=' && value <= condition.value) {
                passed = true;
            } else if (condition.operator === 'in' && condition.value.includes(value)) {
                passed = true;
            } else if (condition.operator === '!in' && !condition.value.includes(value)) {
                passed = true;
            } else if (condition.operator === 'contains' && Array.isArray(value) && value.includes(condition.value)) {
	            passed = true;
            } else if (condition.operator === '!contains' && Array.isArray(value) && !value.includes(condition.value)) {
	            passed = true;
            }
        }

        return passed;
    }

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
