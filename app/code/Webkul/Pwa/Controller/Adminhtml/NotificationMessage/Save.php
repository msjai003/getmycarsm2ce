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
 *  Save Notification
 */
class Save extends \Webkul\Pwa\Controller\Adminhtml\NotificationMessage
{
    /**
     * Save notification message action.
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     *
     * @throws \Magento\Framework\Validator\Exception|\Exception
     */
    public function execute()
    {
        $returnToEdit = false;
        $originalRequestData = $this->getRequest()->getPostValue();
        $notificationMessageId = isset($originalRequestData['pwa_notificationmessage']['entity_id'])
            ? $originalRequestData['pwa_notificationmessage']['entity_id']
            : null;
        if ($originalRequestData) {
            try {
                $notificationMessageData = $originalRequestData['pwa_notificationmessage'];
                $notificationMessageData['icon'] = $this->getNotificationMessageImageName($notificationMessageData);
                $request = $this->getRequest();
                $isExistingNotificationMessage = (bool) $notificationMessageId;
                $notificationMessage = $this->notificationMessageDataFactory->create();
                if ($isExistingNotificationMessage) {
                    $notificationMessageData['entity_id'] = $notificationMessageId;
                }
                $notificationMessage->setData($notificationMessageData);
                if (!$notificationMessage->getId()) {
                    $notificationMessage->setCreatedAt($this->_date->gmtDate());
                }
                $notificationMessage->setUpdatedAt($this->_date->gmtDate());

                // Save notificationmessage
                if ($isExistingNotificationMessage) {
                    $this->_notificationMessageRepository->save($notificationMessage);
                } else {
                    $notificationMessage = $this->_notificationMessageRepository->save($notificationMessage);
                    $notificationMessageId = $notificationMessage->getId();
                }

                $this->_getSession()->unsNotificationMessageFormData();
                // Done Saving notificationMessage, finish save action
                $this->messageManager->addSuccess(__('You saved the notification message.'));
                $returnToEdit = (bool) $this->getRequest()->getParam('back', false);
            } catch (\Magento\Framework\Validator\Exception $exception) {
                $messages = $exception->getMessages();
                if (empty($messages)) {
                    $messages = $exception->getMessage();
                }
                $this->_addSessionErrorMessages($messages);
                $this->_getSession()->setNotificationMessageFormData($originalRequestData);
                $returnToEdit = true;
            } catch (\Exception $exception) {
                $this->messageManager->addException(
                    $exception,
                    __('Something went wrong while saving the notification message. %1', $exception->getMessage())
                );
                $this->_getSession()->setNotificationMessageFormData($originalRequestData);
                $returnToEdit = true;
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($returnToEdit) {
            if ($notificationMessageId) {
                $resultRedirect->setPath(
                    'pwa/notificationmessage/edit',
                    ['id' => $notificationMessageId, '_current' => true]
                );
            } else {
                $resultRedirect->setPath(
                    'pwa/notificationmessage/new',
                    ['_current' => true]
                );
            }
        } else {
            $resultRedirect->setPath('pwa/notificationmessage/index');
        }

        return $resultRedirect;
    }

    /**
     * Get notificationmessage image name.
     *
     * @param array $notificationMessageData
     * @return string
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getNotificationMessageImageName($notificationMessageData)
    {
        if (isset($notificationMessageData['icon'][0]['name'])) {
            if (isset($notificationMessageData['icon'][0]['name'])) {
                return $notificationMessageData['icon'] = $notificationMessageData['icon'][0]['name'];
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Please upload icon.')
                );
            }
        } else {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Please upload icon.')
            );
        }
    }
}
