define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'DVCampus_PersonalDiscount_submitFormAction',
    'DVCampus_PersonalDiscount_formSubmitRestrictions',
    'Magento_Ui/js/modal/modal',
    'mage/translate',
    'mage/cookies'
], function ($, ko, Component, customerData, submitFormAction, formSubmitRestrictions) {
    'use strict';

    return Component.extend({
        defaults: {
            action: '',
            isModal: true,
            productId: 0,
            template: 'DVCampus_PersonalDiscount/form'
        },

        customerName: '',
        customerEmail: '',
        customerMessage: '',
        isLoggedIn: !!customerData.get('personal-discount')().isLoggedIn,
        productIds: [],

        /**
         * Constructor
         */
        initialize: function () {
            this._super();

            this.updateFormState(customerData.get('personal-discount')());
            customerData.get('personal-discount').subscribe(this.updateFormState.bind(this));
        },

        /**
         * Initialize observables and subscribe to their change if needed
         * @returns {*}
         */
        initObservable: function () {
            this._super();
            // Watch name, email and message: customer may change them, or they come from the server
            // Watch isLoggedIn and productIds because they come from the server
            this.observe(['customerName', 'customerEmail', 'customerMessage', 'isLoggedIn', 'productIds']);

            // "updateFormState()" will be called later, so no need to set initial value for "requestAlreadySent()"
            this.productIds.subscribe((newValue) => {
                formSubmitRestrictions.requestAlreadySent(newValue.includes(this.productId));
            });

            this.formSubmitDeniedMessage = ko.computed(() => {
                if (formSubmitRestrictions.requestAlreadySent()) {
                    return $.mage.__('Discount request for this product has already been sent');
                }

                if (formSubmitRestrictions.customerMustLogIn()) {
                    return $.mage.__('Please, log in to send a request');
                }

                return '';
            });

            return this;
        },

        /**
         * Pre-fill form fields with data, hide fields if needed.
         * @param {Object} personalInfo
         */
        updateFormState: function (personalInfo) {
            if (personalInfo.hasOwnProperty('name')) {
                this.customerName(personalInfo.name);
            }

            if (personalInfo.hasOwnProperty('email')) {
                this.customerEmail(personalInfo.email);
            }

            if (personalInfo.hasOwnProperty('productIds')) {
                this.productIds(personalInfo.productIds);
            }

            this.isLoggedIn(!!personalInfo.isLoggedIn);
        },

        /**
         * Save current for element and initialize modal window
         * @param {Node} element
         */
        initModal: function (element) {
            this.$form = $(element);

            if (this.isModal) {
                this.$modal = this.$form.modal({
                    buttons: []
                });

                $(document).on('dv_campus_personal_discount_form_open', this.openModal.bind(this));
            }
        },

        /**
         * Open modal dialog
         */
        openModal: function () {
            this.$modal.modal('openModal');
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
            return this.$form.validation().valid();
        },

        /**
         * Submit request via AJAX. Add form key to the post data.
         */
        ajaxSubmit: function () {
            let payload = {
                name: this.customerName(),
                email: this.customerEmail(),
                message: this.customerMessage(),
                'product_id': this.productId,
                'form_key': $.mage.cookies.get('form_key'),
                isAjax: 1
            };

            submitFormAction(this.action, payload)
                .always(() => {
                    if (this.isModal) {
                        this.$modal.modal('closeModal');
                    }
                });
        }
    });
});
