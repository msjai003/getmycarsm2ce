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

namespace Webkul\Pwa\Controller\ServiceWorker;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Pwa Index Page
 */
class Index extends Action
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * @var Webkul\Pwa\Helper\Data
     */
    protected $data;

    /**
     * @param Context $context
     * @param \Webkul\Pwa\Helper\Data $data
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param ResultFactory $resultFactory
     * @param \Magento\Framework\Filesystem\Driver\File $fileDriver
     * @param \Magento\Framework\Module\Dir\Reader $moduleReader
     */
    public function __construct(
        Context $context,
        \Webkul\Pwa\Helper\Data $data,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        ResultFactory $resultFactory,
        \Magento\Framework\Filesystem\Driver\File $fileDriver,
        \Magento\Framework\Module\Dir\Reader $moduleReader
    ) {
        $this->_baseDirectory = $moduleReader;
        $this->_date = $date;
        $this->data = $data;
        $this->fileDriver = $fileDriver;
        $this->resultFactory = $resultFactory;
        parent::__construct($context);
    }

    /**
     * PWA Home Page.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $version = $this->data->getVersion();
        $debug = $this->data->getCanDebug();
        $identifier = $this->data->getOfflineConfig('cms_offline_page') ?: 'no-route';
        $searchReadyText = __('Your search is ready!');
        $icon = '';
        $swTemplate = $this->fileDriver->fileGetContents($this->_baseDirectory->getModuleDir(
            \Magento\Framework\Module\Dir::MODULE_ETC_DIR,
            'Webkul_Pwa'
        )."/templates/service-worker.js.dist");
        $swTemplate = str_replace("%version%", $version, $swTemplate);
        $swTemplate = str_replace("%debug%", $debug, $swTemplate);
        $swTemplate = str_replace("%identifier%", $identifier, $swTemplate);
        $swTemplate = str_replace("%search_ready_text%", $searchReadyText, $swTemplate);
        $response = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        $response->setHeader('Content-type', 'application/javascript');
        $response->setHeader('Access-Control-Allow-Origin', '*');

        $response->setContents(
            $swTemplate
        );
        return $response;
    }
}
