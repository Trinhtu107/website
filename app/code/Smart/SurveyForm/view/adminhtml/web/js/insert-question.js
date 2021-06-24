/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'Magento_Ui/js/form/components/insert-form'
], function (Insert) {
    'use strict';

    return Insert.extend({
        defaults: {
            listens: {
                responseData: 'onResponse'
            },
            modules: {
                questionListing: '${ $.questionListingProvider }',
                questionModal: '${ $.questionModalProvider }'
            }
        },
        /**
         * Close modal, reload customer address listing and save customer address
         *
         * @param {Object} responseData
         */
        onResponse: function (responseData) {
            var data;

            if (!responseData.error) {
                this.questionModal().closeModal();
                this.questionListing().reload({
                    refresh: true
                });
                // data = this.externalSource().get('data');
                // this.saveAddress(responseData, data);
            }
        },

        /**
         * Event method that closes "Edit customer address" modal and refreshes grid after customer address
         * was removed through "Delete" button on the "Edit customer address" modal
         *
         * @param {String} id - customer address ID to delete
         */
        onQuestionDelete: function (id) {
            this.questionModal().closeModal();
            this.questionListing().reload({
                refresh: true
            });
        }
    });
});
