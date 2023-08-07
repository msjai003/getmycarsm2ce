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

namespace Webkul\Pwa\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\ForwardFactory;

use Webkul\Pwa\Api\Data\PushNotificationMessageInterfaceFactory;
use Webkul\Pwa\Api\PushNotificationMessageRepositoryInterface;

use Webkul\Pwa\Api\PushNotificationRepositoryInterface;
use Webkul\Pwa\Helper\Data as HelperData;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\DataObject;
use Magento\Framework\HTTP\Client\Curl;

/**
 * Webkul Pwa Admin NotificationMessage Controller
 */
abstract class NotificationMessage extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var \Magento\Backend\Model\View\Result\Page
     */
    protected $_resultPage;

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var PushNotificationMessageInterfaceFactory
     */
    protected $notificationMessageDataFactory;

    /**
     * @var PushNotificationMessageRepositoryInterface
     */
    protected $_notificationMessageRepository;

    /**
     * @var PushNotificationRepositoryInterface
     */
    protected $_notificationRepository;

    /**
     * @var HelperData
     */
    protected $_helperData;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $_mediaDirectory;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $_fileUploaderFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    protected $_filesystemFile;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    protected $curl;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param ForwardFactory $resultForwardFactory
     * @param PushNotificationMessageInterfaceFactory $notificationMessageDataFactory
     * @param PushNotificationMessageRepositoryInterface $notificationMessageRepository
     * @param PushNotificationRepositoryInterface $notificationRepository
     * @param HelperData $helperData
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param Filesystem $filesystem
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\Filesystem\Io\File $filesystemFile
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param Curl $curl
     * @param DataObject $dataObject
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        PushNotificationMessageInterfaceFactory $notificationMessageDataFactory,
        PushNotificationMessageRepositoryInterface $notificationMessageRepository,
        PushNotificationRepositoryInterface $notificationRepository,
        HelperData $helperData,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Filesystem\Io\File $filesystemFile,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        Curl $curl,
        DataObject $dataObject
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->notificationMessageDataFactory = $notificationMessageDataFactory;
        $this->_notificationMessageRepository = $notificationMessageRepository;
        $this->_notificationRepository = $notificationRepository;
        $this->_helperData = $helperData;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->storeManager = $storeManager;
        $this->_coreRegistry = $coreRegistry;
        $this->_filesystemFile = $filesystemFile;
        $this->_date = $date;
        $this->dataObject =$dataObject;
        $this->curl = $curl;
    }

    /**
     * Check for is allowed.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_Pwa::notificationmessage');
    }
}
