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
interface PushNotificationMessageRepositoryInterface
{
    /**
     * Save Push Notification.
     *
     * @param  \Webkul\Pwa\Api\Data\PushNotificationMessageInterface $pushNotification
     * @return \Webkul\Pwa\Api\Data\PushNotificationMessageInterface
     *
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Webkul\Pwa\Api\Data\PushNotificationMessageInterface $pushNotification);

    /**
     * Get info about Push Notification by id.
     *
     * @param string $id
     *
     * @return \Webkul\Pwa\Api\Data\PushNotificationMessageInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPushNotificationMessage($id);

    /**
     * Get all active Push Notification.
     *
     * @return \Webkul\Pwa\Api\Data\PushNotificationMessageInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getActivePushNotificationMessage();
}
