/**
 * @category BZOTech
 * @package BzoTech_AjaxCart
 * @version 1.0.0
 * @copyright Copyright (c) 2022 BZOTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * @author BZOTech Company  
 *   
 */
define(['jquery', 'Magento_Ui/js/modal/modal'],
    function ($) {
        "use strict";
        $.widget('cartQuickPro.customModal', $.mage.modal, {
            options: {
                clsModalPopup: '.bzo-ajaxcart-modal-popup',
                clsInerWrap: '.bzo-ajaxcart-modal-popup .modal-inner-wrap',
                clsLoadMark: '#bzo-ajaxcart-container .loading-mask',
                idContents: '#bzo-ajaxcart-contents',
                idReport: '#bzo-ajaxcart-report',
                responsive: true,
            },
            _init: function () {
                this._super();
            },
            openModal: function () {
                var _self = this, _options = _self.options;
                var _el = $('.bzo-ajaxcart-modal-popup');
                $(_options.clsInerWrap).removeClass("bzo-ajaxcart-options report-messages");
                $(_options.clsInerWrap).addClass("bzo-ajaxcart-loading");
                $(_options.clsLoadMark).show();
                $(_options.idContents).html('').hide();
                return this._super();
            },
            closeModal: function () {
                var _self = this, _options = _self.options;
                $(_options.idContents).html('').hide();
                $(_options.idReport).hide();
                if (typeof window.ajaxCart !== "undefined" && typeof window.ajaxCart.options.timeCountDown !== "undefined") {
                    window.clearTimeout(window.ajaxCart.options.timeCountDown);
                    window.ajaxCart.options.timeCountDown = 0;
                }
                return this._super();
            },
        });
        return $.cartQuickPro.customModal;
    });
		