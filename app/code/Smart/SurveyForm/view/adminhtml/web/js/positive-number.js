define([
    'jquery',
    'moment'
], function ($, moment) {
    'use strict';

    return function (validator) {
        validator.addRule(
            'positive-number',
            function (value,element) {
                if (value >= 0) {
                    return true;
                } else {
                    return false;
                }
            },
            $.mage.__("Please enter number equal or more than 0")
        );
        return validator;
    };
});