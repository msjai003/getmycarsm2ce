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

namespace Webkul\Pwa\Controller\Manifest;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Manifest Index
 */
class Index extends Action
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * @var Magento\Framework\Module\Dir\Reader
     */
    protected $_baseDirectory;

    /**
     * @var Webkul\Pwa\Helper\Data
     */
    protected $_helper;

   /**
    * @param Context $context
    * @param \Webkul\Pwa\Helper\Data $helper
    * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
    * @param ResultFactory $resultFactory
    * @param \Magento\Framework\Filesystem\Driver\File $fileDriver
    * @param \Magento\Framework\Module\Dir\Reader $moduleReader
    */
    public function __construct(
        Context $context,
        \Webkul\Pwa\Helper\Data $helper,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        ResultFactory $resultFactory,
        \Magento\Framework\Filesystem\Driver\File $fileDriver,
        \Magento\Framework\Module\Dir\Reader $moduleReader
    ) {
        $this->_baseDirectory = $moduleReader;
        $this->_date = $date;
        $this->_helper = $helper;
        $this->fileDriver = $fileDriver;
        $this->resultFactory = $resultFactory;
        parent::__construct($context);
    }

    /**
     * For rendering manifest file.
     *
     * @return Result/Json
     */
    public function execute()
    {
        $img = explode(".", $this->_helper->getApplicationIcon());
        $imgType = "image/".end($img);
        $name = $this->_helper->getApplicationName();
        $shortName = $this->_helper->getApplicationShortName();
        $startUrl = $this->_helper->getSecureUrl();
        $display = $this->_helper->getDisplay();
        $senderId = $this->_helper->getSenderId();
        $orientation = $this->_helper->getOrientation();
        $backgroundColor = $this->_helper->getSplashBgColor();
        $themeColor = $this->_helper->getThemeColor();
        $icon512 = $this->_helper->resize(512, 512, 'icon');
        $icon144 = $this->_helper->resize(144, 144, 'icon');
        $icon192 = $this->_helper->resize(192, 192, 'icon');

        $manifestTemplate = $this->fileDriver->fileGetContents($this->_baseDirectory->getModuleDir(
            \Magento\Framework\Module\Dir::MODULE_ETC_DIR,
            'Webkul_Pwa'
        )."/templates/manifest.json.dist");
        $manifestTemplate = str_replace("%name%", $name, $manifestTemplate);
        $manifestTemplate = str_replace("%short_name%", $shortName, $manifestTemplate);
        $manifestTemplate = str_replace("%start_url%", $startUrl."?utm_source=homescreen", $manifestTemplate);
        $manifestTemplate = str_replace("%display%", $display, $manifestTemplate);
        $manifestTemplate = str_replace("%gcm_sender_id%", $senderId, $manifestTemplate);
        $manifestTemplate = str_replace("%orientation%", $orientation, $manifestTemplate);
        $manifestTemplate = str_replace("%background_color%", $backgroundColor, $manifestTemplate);
        $manifestTemplate = str_replace("%theme_color%", $themeColor, $manifestTemplate);
        $manifestTemplate = str_replace("%icon512%", $icon512, $manifestTemplate);
        $manifestTemplate = str_replace("%icon144%", $icon144, $manifestTemplate);
        $manifestTemplate = str_replace("%icon192%", $icon192, $manifestTemplate);
        $manifestContent = str_replace("%type%", $imgType, $manifestTemplate);

        $response = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Access-Control-Allow-Origin', '*');

        $response->setContents(
            $manifestContent
        );
        return $response;
    }
}
