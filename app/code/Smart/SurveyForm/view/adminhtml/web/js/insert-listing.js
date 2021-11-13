/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'Magento_Ui/js/form/components/insert-listing',
    'underscore'
], function (Insert, _) {
    'use strict';

    return Insert.extend({

        /**
         * On action call
         *
         * @param {Object} data - customer address and actions
         */
        onAction: function (data) {
            this[data.action + 'Action'].call(this, data.data);
        },

        /**
         * On mass action call
         *
         * @param {Object} data - customer address
         */
        onMassAction: function (data) {
            this[data.action + 'Massaction'].call(this, data.data);
        },

        /**
         * Delete customer address
         *
         * @param {Object} data - customer address
         */
        deleteAction: function (data) {
            this._delete([parseFloat(data[data['id_field_name']])]);
        }
    });
});
