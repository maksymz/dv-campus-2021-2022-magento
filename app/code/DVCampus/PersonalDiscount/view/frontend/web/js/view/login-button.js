define([
    'uiComponent',
    'DVCampus_PersonalDiscount_formSubmitRestrictions',
    'Magento_Customer/js/model/authentication-popup',
    'DVCampus_PersonalDiscount_form'
], function (Component, formSubmitRestrictions, authenticationPopup) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'DVCampus_PersonalDiscount/login-button'
        },

        customerMustLogIn: formSubmitRestrictions.customerMustLogIn,

        showModal: function () {
            authenticationPopup.showModal();
        }
    });
});
