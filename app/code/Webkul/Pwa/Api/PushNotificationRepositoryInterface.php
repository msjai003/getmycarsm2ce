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

namespace Webkul\Pwa\Api;

/**
 * @api
 */
interface PushNotificationRepositoryInterface
{
    /**
     * Save Push Notification.
     *
     * @param  \Webkul\Pwa\Api\Data\PushNotificationInterface $pushNotification
     * @return \Webkul\Pwa\Api\Data\PushNotificationInterface
     *
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Webkul\Pwa\Api\Data\PushNotificationInterface $pushNotification);

    /**
     * Get info about Push Notification by registrationId and endPoint.
     *
     * @param string $registrationId
     * @param string $endPoint
     *
     * @return \Webkul\Pwa\Api\Data\PushNotificationInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPushNotification($registrationId, $endPoint);

    /**
     * Get all Push Notification.
     *
     * @return \Webkul\Pwa\Api\Data\PushNotificationInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getAllPushNotification();
}
