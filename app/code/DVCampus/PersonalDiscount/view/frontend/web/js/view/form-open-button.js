define([
    'jquery',
    'jquery/ui'
], function ($) {
    'use strict';

    $.widget('DVCampus.personalDiscount_formOpenButton', {
        /**
         * Constructor
         * @private
         */
        _create: function () {
            $(this.element).click(this.openRequestForm.bind(this));
        },

        /**
         * Generate event to open the form
         */
        openRequestForm: function () {
            $(document).trigger('dv_campus_personal_discount_form_open');
        }
    });

    return $.DVCampus.personalDiscount_formOpenButton;
});
