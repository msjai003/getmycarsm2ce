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
 * Send Notification
 */
class Send extends \Webkul\Pwa\Controller\Adminhtml\NotificationMessage
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
        $allPushNotifications = $this->_notificationRepository->getAllPushNotification();
        $count = 0;
        if ($allPushNotifications->getSize()) {
            $notificationMessageId = (int)$this->getRequest()->getParam('id');
            $pushNotificationMessage = $this->_notificationMessageRepository
                ->getPushNotificationMessage(
                    $notificationMessageId
                );
            $result = $pushNotificationMessage->getData();
            $count = $this->getResult($result, $allPushNotifications);
            
        }
        if ($count > 0) {
            $this->messageManager->addSuccess(__('Notification sent.'));
        } else {
            $this->messageManager->addError(__('Notification did not send.'));
        }

        $resultRedirect = $this->resultRedirectFactory->create();

        $resultRedirect->setPath('pwa/notificationmessage/index');

        return $resultRedirect;
    }

    /**
     * Get notificationmessage for mozila browser.
     *
     * @param string $registrationId
     * @param string $notification
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function dispatchToMozilla($registrationId, $notification)
    {
        $url = 'https://updates.push.services.mozilla.com/wpush/v1/'.$registrationId;

        $headers = [
            'Content-Type: application/json',
            'TTL: 600000',
        ];
     
        // Set the url, number of POST vars, POST data
        $this->curl->setOption(CURLOPT_URL, $url);
        $this->curl->setOption(CURLOPT_POST, true);
        $this->curl->setOption(CURLOPT_HTTPHEADER, $headers);
        $this->curl->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->curl->setOption(CURLOPT_SSL_VERIFYPEER, $url);
        $this->curl->setOption(CURLOPT_POSTFIELDS, json_encode($fields));
        $this->curl->post($url, []);
        $response = $this->curl->getBody();
        return $response;
    }

    /**
     * Get notificationmessage for chrome browser.
     *
     * @param string $registrationId
     * @param string $notification
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function dispatchToChrome($registrationId, $notification)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = [
            'data' => $notification,
            'to' => $registrationId,
        ];
        $headers = [
            'Authorization: key=' . $this->_helperData->getServerKey(),
            'Content-Type: application/json',
        ];
        $this->curl->setOption(CURLOPT_URL, $url);
        $this->curl->setOption(CURLOPT_POST, true);
        $this->curl->setOption(CURLOPT_HTTPHEADER, $headers);
        $this->curl->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->curl->setOption(CURLOPT_SSL_VERIFYPEER, $url);
        $this->curl->setOption(CURLOPT_POSTFIELDS, json_encode($fields));
        $this->curl->post($url, []);
        $response = $this->curl->getBody();
        return $response;
    }

    /**
     * Return Notification Count.
     *
     * @param string $result
     * @param string $allPushNotifications
     * @return int
     */
    public function getResult($result, $allPushNotifications)
    {
        $count = 0;
        if ($result['status']) {
            $notificationData['title'] = $result['title'];
            $notificationData['body'] = $result['body'];
            $notificationData['actions'][0]['action'] = $result['url'];
            $notificationData['actions'][0]['title'] = __('Go to the Site');
            $notificationData['icon'] = $this->_helperData->getMediaUrl('pwa/icon/').$result['icon'];
            
            $notification['notification'] = $notificationData;
           
            $response = [];
            foreach ($allPushNotifications as $key => $value) {
                if (strpos($value['end_point'], 'mozilla') !== false) {
                    $response[] = $this->dispatchToMozilla($value['registration_id'], $notification);
                } else {
                    $response[] = $this->dispatchToChrome($value['registration_id'], $notification);
                }
                if ($response) {
                    foreach ($response as $res) {
                        $res_arr = json_decode($res, true);
                        if ($res_arr['success'] == 1) {
                            $count++;
                        }
                    }
                }
            }
            return $count;
        }
    }
}
