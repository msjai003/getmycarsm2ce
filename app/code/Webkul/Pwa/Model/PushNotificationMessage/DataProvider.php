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

namespace Webkul\Pwa\Model\PushNotificationMessage;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Session\SessionManagerInterface;
use Webkul\Pwa\Model\ResourceModel\PushNotificationMessage\Collection;
use Webkul\Pwa\Model\ResourceModel\PushNotificationMessage\CollectionFactory as PushNotificationMessage;

/**
 * Class DataProvider to get data
 *
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var SessionManagerInterface
     */
    protected $session;

    /**
     * Constructor.
     *
     * @param string                  $name
     * @param string                  $primaryFieldName
     * @param string                  $requestFieldName
     * @param PushNotificationMessage $pushNotificationMessage
     * @param array                   $meta
     * @param array                   $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        PushNotificationMessage $pushNotificationMessage,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $pushNotificationMessage->create();
        $this->collection->addFieldToSelect('*');
    }

    /**
     * Get session object.
     *
     * @return SessionManagerInterface
     */
    protected function getSession()
    {
        if ($this->session === null) {
            $this->session = ObjectManager::getInstance()->get(
                \Magento\Framework\Session\SessionManagerInterface::class
            );
        }

        return $this->session;
    }

    /**
     * Get data.
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /**
 * @var Push Notification Message $pushNotification
*/
        foreach ($items as $pushNotification) {
            $result['notificationmessage'] = $pushNotification->getData();
            $this->loadedData[$pushNotification->getId()] = $result;
        }

        $data = $this->getSession()->getPushNotificationMessageFormData();
        if (!empty($data)) {
            $id = isset($data['pwa_notificationmessage']['entity_id'])
            ? $data['pwa_notificationmessage']['entity_id'] : null;
            $this->loadedData[$id] = $data;
            $this->getSession()->unsPushNotificationMessageFormData();
        }

        return $this->loadedData;
    }
}
