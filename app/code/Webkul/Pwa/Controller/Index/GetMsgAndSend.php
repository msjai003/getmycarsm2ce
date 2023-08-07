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
use Webkul\Pwa\Api\Data\PushNotificationMessageInterfaceFactory;
use Webkul\Pwa\Api\PushNotificationMessageRepositoryInterface;

/**
 * To Get and  Send Message (Notification)
 */
class GetMsgAndSend extends Action
{
    /**
     * @var PushNotificationMessageInterfaceFactory
     */
    protected $pushNotificationMessageDataFactory;

    /**
     * @var PushNotificationMessageRepositoryInterface
     */
    protected $_pushNotificationMessageRepository;

    /**
     * @var Magento\Framework\Json\Helper\Data
     */

    protected $data;

    /**
     * @param Context $context
     * @param PushNotificationMessageInterfaceFactory $pushNotificationMessageDataFactory
     * @param PushNotificationMessageRepositoryInterface $pushNotificationMessageRepository
     * @param \Magento\Framework\Json\Helper\Data $data
     */
    public function __construct(
        Context $context,
        PushNotificationMessageInterfaceFactory $pushNotificationMessageDataFactory,
        PushNotificationMessageRepositoryInterface $pushNotificationMessageRepository,
        \Magento\Framework\Json\Helper\Data $data
    ) {
        $this->pushNotificationMessageDataFactory = $pushNotificationMessageDataFactory;
        $this->_pushNotificationMessageRepository = $pushNotificationMessageRepository;
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
        $collection = $this->_pushNotificationMessageRepository->getActivePushNotificationMessage();
        $notificationData = [];
        foreach ($collection as $key => $value) {
            $notificationData['title'] = $value['title'];
            $notificationData['body'] = $value['body'];
            $notificationData['target_url'] = $value['url'];
            $notificationData['icon'] = 'media/pwa/icon/'.$value['icon'];
        }
        $this->getResponse()->representJson(
            $this->data->jsonEncode($notificationData)
        );
    }
}
