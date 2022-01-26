define([
    'jquery',
    'ko',
    'uiComponent',
    'DVCampus_PersonalDiscount_formSubmitRestrictions',
    'DVCampus_PersonalDiscount_form'
], function ($, ko, Component, formSubmitRestrictions) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'DVCampus_PersonalDiscount/form-open-button'
        },

        /**
         * Initialize data links (listens/imports/exports/links)
         * @returns {*}
         */
        initLinks: function () {
            this._super();

            // Check whether it is possible to open the modal - either form is modal or there are any other restrictions
            this.canShowOpenModalButton = ko.computed(function () {
                return this.isModal && !formSubmitRestrictions.formSubmitDeniedMessage();
            }.bind(this));

            return this;
        },

        /**
         * Generate event to open the form
         */
        openRequestForm: function () {
            $(document).trigger('dv_campus_personal_discount_form_open');
        }
    });
});
