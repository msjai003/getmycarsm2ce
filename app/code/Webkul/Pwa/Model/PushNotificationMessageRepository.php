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
use Webkul\Pwa\Api\Data\PushNotificationMessageInterface;
use Webkul\Pwa\Model\ResourceModel\PushNotificationMessage\CollectionFactory;

/**
 * PushNotificationMessageRepository Repository
 */
class PushNotificationMessageRepository implements \Webkul\Pwa\Api\PushNotificationMessageRepositoryInterface
{
    /**
     * @var PushNotificationMessageFactory
     */
    protected $_pushNotificationMessageFactory;

    /**
     * @var PushNotificationMessage[]
     */
    protected $_instances = [];

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Webkul\Pwa\Model\ResourceModel\PushNotificationMessage
     */
    protected $_resourceModel;

    /**
     * @param PushNotificationMessageFactory $pushNotificationMessageFactory
     * @param CollectionFactory $collectionFactory
     * @param \Webkul\Pwa\Model\ResourceModel\PushNotificationMessage $resourceModel
     */
    public function __construct(
        PushNotificationMessageFactory $pushNotificationMessageFactory,
        CollectionFactory $collectionFactory,
        \Webkul\Pwa\Model\ResourceModel\PushNotificationMessage $resourceModel
    ) {
        $this->_resourceModel = $resourceModel;
        $this->_pushNotificationMessageFactory = $pushNotificationMessageFactory;
        $this->_collectionFactory = $collectionFactory;
    }

    /**
     * @inheritdoc
     */
    public function save(PushNotificationMessageInterface $pushNotificationMessage)
    {
        try {
            $this->_resourceModel->save($pushNotificationMessage);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                $e->getMessage()
            );
        }
        unset($this->_instances[$pushNotificationMessage->getId()]);

        return $this->getPushNotificationMessage($pushNotificationMessage->getId());
    }

    /**
     * @inheritdoc
     */
    public function getPushNotificationMessage($id = null)
    {
        $pushNotificationMessageData = $this->_pushNotificationMessageFactory->create();
        $pushNotificationMessageData->load($id);
        $this->_instances[$id] = $pushNotificationMessageData;

        return $this->_instances[$id];
    }

    /**
     * @inheritdoc
     */
    public function getActivePushNotificationMessage()
    {
        $collection = $this->_collectionFactory->create()
            ->addFieldToFilter('status', 1);
        $collection->load();

        return $collection;
    }
}
