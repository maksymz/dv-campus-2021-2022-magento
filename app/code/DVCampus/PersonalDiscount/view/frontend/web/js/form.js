define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'Magento_Ui/js/modal/modal',
    'mage/translate'
], function ($, alert) {
    'use strict';

    $.widget('DVCampus.personalDiscount_form', {
        options: {
            action: '',
            isModal: 1
        },

        /**
         * @private
         */
        _create: function () {
            $(this.element).on('submit.dv_campus_personal_discount_form', this.sendRequest.bind(this));

            if (this.options.isModal) {
                $(this.element).modal({
                    buttons: []
                });

                $(document).on('dv_campus_personal_discount_form_open', this.openModal.bind(this));
            }
        },

        /**
         * Open modal dialog
         */
        openModal: function () {
            $(this.element).modal('openModal');
        },

        /**
         * Validate form and send request
         */
        sendRequest: function () {
            if (!this.validateForm()) {
                return;
            }

            this.ajaxSubmit();
        },

        /**
         * Validate request form
         */
        validateForm: function () {
            return $(this.element).validation().valid();
        },

        /**
         * Submit request via AJAX. Add form key to the post data.
         */
        ajaxSubmit: function () {
            let formData = new FormData($(this.element).get(0));

            formData.append('form_key', $.mage.cookies.get('form_key'));
            formData.append('isAjax', 1);

            $.ajax({
                url: this.options.action,
                data: formData,
                processData: false,
                contentType: false,
                type: 'post',
                dataType: 'json',
                context: this,

                /** @inheritdoc */
                beforeSend: function () {
                    $('body').trigger('processStart');
                },

                /**
                 * Success means that response from the server was received, but not that the request was saved!
                 *
                 * @inheritdoc
                 */
                success: function (response) {
                    $(this.element).modal('closeModal');
                    alert({
                        title: $.mage.__('Posting your request...'),
                        content: response.message
                    });
                },

                /** @inheritdoc */
                error: function () {
                    alert({
                        title: $.mage.__('Error'),
                        content: $.mage.__('Your request can\'t be sent. Please, contact us if you see this message.')
                    });
                },

                /** @inheritdoc */
                complete: function () {
                    $('body').trigger('processStop');
                }
            });
        }
    });

    return $.DVCampus.personalDiscount_form;
});
