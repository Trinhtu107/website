define([
    'jquery',
    'Magento_Ui/js/form/element/select'
], function ($, Select) {
    'use strict';

    return Select.extend({
        defaults: {
            customName: '${ $.parentName }.${ $.index }_input'
        },
        /**
         * Change currently selected option
         *
         * @param {String} id
         */
        selectOption: function (id) {

            var typeText = $('div[data-index="type_text"]');
            var typeSelect = $('div[data-index="type_selected"]');
            var typeMulti = $('div[data-index="type_multiselect"]');
            if (($("#"+id).val() == 1) || ($("#"+id).val() == undefined)) {
                typeText.show();
                typeSelect.parent().hide();
                typeMulti.parent().hide();
            } else {
                if ($("#"+id).val() == 2) {
                    typeText.hide();
                    typeSelect.parent().show();
                    typeMulti.parent().hide();
                } else {
                    if ($("#" + id).val() == 3) {
                        typeText.hide();
                        typeSelect.parent().hide();
                        typeMulti.parent().show();
                    }
                }
            }
        },
    });
});