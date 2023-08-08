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
use Webkul\Pwa\Api\Data\TrackingDataInterface;
use Webkul\Pwa\Model\ResourceModel\PushNotification\CollectionFactory;

/**
 * PwaTrackingManagement Manage pwa tracking
 */
class PwaTrackingManagement implements \Webkul\Pwa\Api\PwaTrackingManagementInterface
{

    /**
     * @var \Webkul\Pwa\Model\ResourceModel\TrackingManagement
     */
    protected $_resourceModel;

    /**
     * @var \Webkul\Pwa\Api\Data\TrackingDataInterfaceFactory
     */
    protected $trackingDataFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_timezone;

    /**
     * @param \Webkul\Pwa\Model\ResourceModel\TrackingManagement $resourceModel
     * @param \Webkul\Pwa\Api\Data\TrackingDataInterfaceFactory $trackingDataFactory
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $timezone
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Webkul\Pwa\Model\ResourceModel\TrackingManagement $resourceModel,
        \Webkul\Pwa\Api\Data\TrackingDataInterfaceFactory $trackingDataFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $timezone,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_resourceModel = $resourceModel;
        $this->trackingDataFactory = $trackingDataFactory;
        $this->_timezone = $timezone;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function save(TrackingDataInterface $trakingData)
    {
        $date = $this->_timezone->gmtDate('Y-m-d');
        $this->_resourceModel->saveDownload($date);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function saveReject(\Webkul\Pwa\Api\Data\TrackingDataInterface $trakingData)
    {
        $date = $this->_timezone->gmtDate('Y-m-d');
        $this->_resourceModel->saveReject($date);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getInstalledTotal()
    {
        return $this->_resourceModel->getInstalledTotal();
    }

   /**
    * @inheritdoc
    */
    public function getRejectedTotal()
    {
        return $this->_instances[$pushNotificationId];
    }
}
