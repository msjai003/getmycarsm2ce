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

use Webkul\Pwa\Api\Data\PushNotificationMessageInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Edit extends \Webkul\Pwa\Controller\Adminhtml\NotificationMessage
{
    /**
     * Push notification message edit action
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $pushNotificationMessageId = $this->initCurrentPushNotificationMessage();
        $isExistingPushNotificationMessage = (bool)$pushNotificationMessageId;
        if ($isExistingPushNotificationMessage) {
            try {
                $pushNotificationMessageDirPath = $this->_mediaDirectory->getAbsolutePath(
                    'pwa'
                );
                $pushNotificationMessageImageDirPath = $this->_mediaDirectory->getAbsolutePath(
                    'pwa/icon'
                );
                if (!$this->_filesystemFile->fileExists($pushNotificationMessageDirPath)) {
                    $this->_filesystemFile->mkdir($pushNotificationMessageDirPath, 0777, true);
                }
                if (!$this->_filesystemFile->fileExists($pushNotificationMessageImageDirPath)) {
                    $this->_filesystemFile->mkdir($pushNotificationMessageImageDirPath, 0777, true);
                }
                $iconBaseTmpPath = 'pwa/icon/';
                $iconTarget = $this->storeManager->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ).$iconBaseTmpPath;

                $pushNotificationMessageData = [];
                $pushNotificationMessageData['pwa_notificationmessage'] = [];
                $pushNotificationMessage = null;
                $pushNotificationMessage = $this->_notificationMessageRepository
                    ->getPushNotificationMessage(
                        $pushNotificationMessageId
                    );
                $result = $pushNotificationMessage->getData();
                if (!empty($result)) {
                    $pushNotificationMessageData['pwa_notificationmessage'] = $result;

                    /* *
                     * for icon image
                     */
                    $pushNotificationMessageData['pwa_notificationmessage']['icon'] = [];
                    $pushNotificationMessageData['pwa_notificationmessage']['icon'][0] = [];
                    $pushNotificationMessageData['pwa_notificationmessage']['icon'][0]['id'] =
                    $result['icon'];
                    $pushNotificationMessageData['pwa_notificationmessage']['icon'][0]['name'] =
                    $result['icon'];
                    $pushNotificationMessageData['pwa_notificationmessage']['icon'][0]['url'] =
                    $iconTarget.$result['icon'];
                    $iconFilePath = $this->_mediaDirectory->getAbsolutePath(
                        $iconBaseTmpPath
                    ).$result['icon'];

                    if ($this->_filesystemFile->fileExists($iconFilePath)) {
                        $pushNotificationMessageData['pwa_notificationmessage']['icon'][0]['size'] =
                        $this->_mediaDirectory->stat($iconFilePath)['size'];
                    } else {
                        $pushNotificationMessageData['pwa_notificationmessage']['icon'][0]['size'] = 0;
                    }
                    $pushId = $pushNotificationMessageData['pwa_notificationmessage'];
                    $pushId[PushNotificationMessageInterface::ENTITY_ID]=
                    $pushNotificationMessageId;

                    $this->_getSession()->setPushNotificationMessageFormData($pushNotificationMessageData);
                    $this->_coreRegistry->register(
                        'pushNotificationMessageData',
                        $pushNotificationMessageData
                    );
                } else {
                    $this->messageManager->addError(
                        __('Requested notification message doesn\'t exist')
                    );
                    $resultRedirect = $this->resultRedirectFactory->create();
                    $resultRedirect->setPath('pwa/notificationmessage/index');
                    return $resultRedirect;
                }
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addException(
                    $e,
                    __('Something went wrong while editing the notification message.')
                );
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('pwa/notificationmessage/index');
                return $resultRedirect;
            }
        }

        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Webkul_Pwa::notificationmessage');
        $this->prepareDefaultNotificationMessageTitle($resultPage);
        $resultPage->setActiveMenu('Webkul_Pwa::notificationmessage');
        if ($isExistingPushNotificationMessage) {
            $resultPage->getConfig()->getTitle()->prepend(
                __('Edit Item with id %1', $pushNotificationMessageId)
            );
        } else {
            $resultPage->getConfig()->getTitle()->prepend(__('New Push Notification Message'));
        }
        return $resultPage;
    }

    /**
     * Push notification message initialization
     *
     * @return string push notification message id
     */
    protected function initCurrentPushNotificationMessage()
    {
        $pushNotificationMessageId = (int)$this->getRequest()->getParam('id');

        if ($pushNotificationMessageId) {
            $this->_coreRegistry->register(
                'pwa_notificationmessage',
                $pushNotificationMessageId
            );
        }

        return $pushNotificationMessageId;
    }

    /**
     * Prepare notificationmessage default title
     *
     * @param  \Magento\Backend\Model\View\Result\Page $resultPage
     * @return void
     */
    protected function prepareDefaultNotificationMessageTitle(
        \Magento\Backend\Model\View\Result\Page $resultPage
    ) {
        $resultPage->getConfig()->getTitle()->prepend(__('Push Notification Message'));
    }
}
