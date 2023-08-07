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

class Delete extends \Webkul\Pwa\Controller\Adminhtml\NotificationMessage
{
    /**
     * Push notification message edit action
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $requestData = $this->getRequest()->getParams();
        if ($requestData) {
            try {
                $id = $requestData['id'];
                $notificationMessage = $this->_notificationMessageRepository->getPushNotificationMessage($id);
                $notificationMessage->delete();
                
                $this->messageManager->addSuccess(__('You have deleted the notification message.'));
                $returnToEdit = (bool) $this->getRequest()->getParam('back', false);
            } catch (\Exception $exception) {
                $this->messageManager->addException(
                    $exception,
                    __('Something went wrong while saving the notification message. %1', $exception->getMessage())
                );
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('pwa/notificationmessage/index');

        return $resultRedirect;
    }
}
