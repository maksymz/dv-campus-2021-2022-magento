define([
    'jquery',
    'uiComponent',
    'DVCampus_PersonalDiscount_formSubmitRestrictions'
], function ($, Component, formSubmitRestrictions) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'DVCampus_PersonalDiscount/form-open-button'
        },

        formSubmitIsRestricted: formSubmitRestrictions.formSubmitDeniedMessage,

        /**
         * Generate event to open the form
         */
        openRequestForm: function () {
            $(document).trigger('dv_campus_personal_discount_form_open');
        }
    });
});
