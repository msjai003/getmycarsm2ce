<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_Pwa
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\Pwa\Controller\Adminhtml\NotificationMessage;

/**
 * Validate Notification
 */
class Validate extends \Webkul\Pwa\Controller\Adminhtml\NotificationMessage
{
    /**
     * Push NotificationMessage validation
     *
     * @param  \Magento\Framework\DataObject $response
     * @return NotificationMessageInterface|null
     *
     * @throws \Magento\Framework\Validator\Exception|\Exception
     */
    protected function _validateNotificationMessage($response)
    {
        $notificationMessage = null;
        $errors = [];

        try {
            /**
 * @var NotificationMessageInterface $notificationMessage
*/
            $notificationMessage = $this->notificationMessageDataFactory->create();

            $data = $this->getRequest()->getParams();
            $dataResult = $data['pwa_notificationmessage'];
            $errors = [];
            if (!isset($dataResult['icon'][0]['name'])) {
                $errors[] =  __('Please upload icon.');
            }
            if (!isset($dataResult['title'])) {
                $errors[] =  __('Title field can not be blank.');
            }
            if (!isset($dataResult['body'])) {
                $errors[] =  __('Body field can not be blank.');
            }
            if (!isset($dataResult['url'])) {
                $errors[] =  __('Target url field can not be blank.');
            }
        } catch (\Magento\Framework\Validator\Exception $exception) {
            $exceptionMsg = $exception->getMessages(
                \Magento\Framework\Message\MessageInterface::TYPE_ERROR
            );
            /**
             * @var $error Error
             */
            foreach ($exceptionMsg as $error) {
                $errors[] = $error->getText();
            }
        }

        if ($errors) {
            $messages = $response->hasMessages() ? $response->getMessages() : [];
            foreach ($errors as $error) {
                $messages[] = $error;
            }
            $response->setMessages($messages);
            $response->setError(1);
        }

        return $notificationMessage;
    }

    /**
     * AJAX customer validation action
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $response = $this->dataObject;
        $response->setError(0);

        $notificationMessage = $this->_validateNotificationMessage($response);

        $resultJson = $this->resultJsonFactory->create();
        if ($response->getError()) {
            $response->setError(true);
            $response->setMessages($response->getMessages());
        }

        $resultJson->setData($response);
        return $resultJson;
    }
}
