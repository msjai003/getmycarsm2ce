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

namespace Webkul\Pwa\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Webkul\Pwa\Api\Data\PushNotificationInterface;
use Webkul\Pwa\Model\ResourceModel\PushNotification\CollectionFactory;

/**
 * PushNotificationRepository Repository
 */
class PushNotificationRepository implements \Webkul\Pwa\Api\PushNotificationRepositoryInterface
{
    /**
     * @var PushNotificationFactory
     */
    protected $_pushNotificationFactory;

    /**
     * @var PushNotification[]
     */
    protected $_instances = [];

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Webkul\Pwa\Model\ResourceModel\PushNotification
     */
    protected $_resourceModel;

    /**
     * @param PushNotificationFactory                          $pushNotificationFactory
     * @param CollectionFactory                                $collectionFactory
     * @param \Webkul\Pwa\Model\ResourceModel\PushNotification $resourceModel
     */
    public function __construct(
        PushNotificationFactory $pushNotificationFactory,
        CollectionFactory $collectionFactory,
        \Webkul\Pwa\Model\ResourceModel\PushNotification $resourceModel
    ) {
        $this->_resourceModel = $resourceModel;
        $this->_pushNotificationFactory = $pushNotificationFactory;
        $this->_collectionFactory = $collectionFactory;
    }

    /**
     * @inheritdoc
     */
    public function save(PushNotificationInterface $pushNotification)
    {
        $pushNotificationId = $pushNotification->getId();
        try {
            $this->_resourceModel->save($pushNotification);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                $e->getMessage()
            );
        }
        unset($this->_instances[$pushNotification->getId()]);

        return $this->getPushNotification($pushNotification->getId());
    }

    /**
     * @inheritdoc
     */
    public function getPushNotification($registrationId = null, $endPoint = null)
    {
        $pushNotificationData = $this->_pushNotificationFactory->create();
        $pushNotificationId = '';
        $pushNotificationCollection = $this->_collectionFactory->create()
            ->addFieldToFilter('registration_id', $registrationId)
            ->addFieldToFilter('end_point', $endPoint);
        foreach ($pushNotificationCollection as $value) {
            $pushNotificationId = $value->getId();
        }
        /* @var \Webkul\Pwa\Model\ResourceModel\PushNotification\Collection $pushNotificationData */
        $pushNotificationData->load($pushNotificationId);
        $this->_instances[$pushNotificationId] = $pushNotificationData;

        return $this->_instances[$pushNotificationId];
    }

    /**
     * @inheritdoc
     */
    public function getAllPushNotification()
    {
        $collection = $this->_collectionFactory->create();
        $collection->load();

        return $collection;
    }
}
