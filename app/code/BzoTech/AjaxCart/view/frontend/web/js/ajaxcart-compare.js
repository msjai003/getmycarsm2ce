/**
 * @category BZOTech
 * @package BzoTech_AjaxCart
 * @version 1.0.0
 * @copyright Copyright (c) 2022 BZOTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * @author BZOTech Company  
 *   
 */
define([
    "jquery",
    'jquery-ui-modules/widget',
    "mage/decorate",
    'mage/translate',
    "ajaxCart"
], function ($) {
    "use strict";

    $.widget('mage.compareItems', {
        _create: function () {
            this.element.decorate('list', true);
            this._confirm(this.options.removeSelector, this.options.removeConfirmMessage);
            this._confirm(this.options.clearAllSelector, this.options.clearAllConfirmMessage);
        },

        /**
         * Set up a click event on the given selector to display a confirmation request message
         * and ask for that confirmation.
         * @param selector Selector for the confirmation on click event
         * @param message Message to display asking for confirmation to perform action
         * @private
         */
        _confirm: function (selector, message) {
            if (typeof ajaxCart !== 'undefined' && !ajaxCart.options.isAjaxCart) {
                $(selector).on('click', function () {
                    return confirm(message);
                });
            }
        }
    });

    return $.mage.compareItems;
});
