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

namespace Webkul\Pwa\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Webkul\Pwa\Api\Data\PushNotificationInterfaceFactory;
use Webkul\Pwa\Api\PushNotificationRepositoryInterface;

/**
 * PWa Index Page
 */
class Index extends Action
{
    /**
     * @var PushNotificationInterfaceFactory
     */
    protected $pushNotificationDataFactory;

    /**
     * @var PushNotificationRepositoryInterface
     */
    protected $_pushNotificationRepository;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * @var Magento\Framework\Json\Helper\Data
     */
    protected $data;

    /**
     * @param Context $context
     * @param \Magento\Framework\Json\Helper\Data $data
     * @param PushNotificationInterfaceFactory $pushNotificationDataFactory
     * @param PushNotificationRepositoryInterface $pushNotificationRepository
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Json\Helper\Data $data,
        PushNotificationInterfaceFactory $pushNotificationDataFactory,
        PushNotificationRepositoryInterface $pushNotificationRepository,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ) {
        $this->pushNotificationDataFactory = $pushNotificationDataFactory;
        $this->_pushNotificationRepository = $pushNotificationRepository;
        $this->_date = $date;
        $this->data = $data;
        parent::__construct($context);
    }

    /**
     * PWA Home Page.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
       
        $data = $this->getRequest()->getParams();
        if (isset($data['regId']) && isset($data['endpoint'])) {
            $registrationId = $data['regId'];
            $endPoint = $data['endpoint'];
            $getPushNotification = $this->_pushNotificationRepository->getPushNotification(
                $registrationId,
                $endPoint
            );
            if (!$getPushNotification->getId()) {
                $pushNotificationData = [];
                $pushNotificationData['registration_id'] = $registrationId;
                $pushNotificationData['end_point'] = $endPoint;
                $pushNotificationData['created_at'] = $this->_date->gmtDate();
                $pushNotificationData['updated_at'] = $this->_date->gmtDate();
                $pushNotification = $this->pushNotificationDataFactory->create();
                $pushNotification->setData($pushNotificationData);
                $this->_pushNotificationRepository->save($pushNotification);
            }
        }
        $this->getResponse()->representJson(
            $this->data->jsonEncode([true])
        );
    }
}
