require(
    [
    'jquery',
    'Magento_Ui/js/modal/confirm',
    'mage/translate'
    ],
    function ($, confirm) {
        'use strict';

        function getNotificationmessageForm(url)
        {
            return $(
                '<form>',
                {
                    'action': url,
                    'method': 'POST'
                }
            ).append(
                $(
                    '<input>',
                    {
                        'name': 'form_key',
                        'value': window.FORM_KEY,
                        'type': 'hidden'
                        }
                )
            );
        }
    
        $('#notificationmessage-edit-delete-button').click(
            function () {
                var confirmationMsg = $.mage.__('Are you sure you want to do this?');
                var deleteUrl = $('#notificationmessage-edit-delete-button').data('url');
        
                confirm(
                    {
                        'content': confirmationMsg,
                        'actions': {
                            confirm: function () {
                                getNotificationmessageForm(deleteUrl).appendTo('body').submit();
                            }
                        }
                    }
                );

                return false;
            }
        );
    }
);
