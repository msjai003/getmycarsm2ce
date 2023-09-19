<?php
/**
 * @category BZOTech
 * @package BzoTech_PopupContent
 * @version 1.0.0
 * @copyright Copyright (c) 2022 BZOTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * @author BZOTech Company  
 *   
 */

namespace BzoTech\PopupContent\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_CONFIG_POPUP = 'popupcontent/';
    protected $storeManager;
    protected $objectManager;
    protected $_logo;

    /**
     * Data constructor.
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        \Magento\Theme\Block\Html\Header\Logo $logo
    )
    {
        $this->storeManager  = $storeManager;
        $this->objectManager = $objectManager;
        $this->_logo         = $logo;
        parent::__construct($context);
    }

    /**
     * @param $field
     * @param null $storeId
     * @return mixed
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @return \Magento\Store\Api\Data\StoreInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */

    public function getCurrentStore()
    {
        return $this->storeManager->getStore();
    }

    /**
     * @param $code
     * @param null $storeId
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */

    public function getPopupConfig($code, $storeId = null)
    {
        if (!$storeId) {
            $storeId = $this->getCurrentStore()->getId();
        }

        return $this->getConfigValue(self::XML_PATH_CONFIG_POPUP . $code, $storeId);
    }

    /**
     * @return mixed
     */

    public function isHomePage()
    {
        return $this->_logo->isHomePage();
    }

    /**
     * @return bool|string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */

    public function getTemplate()
    {
        $template      = 'BzoTech_PopupContent::popup-content.phtml';
        $isHomePage    = $this->isHomePage();
        $shownHomeOnly = $this->getPopupConfig('pc_general/shown_home_only');
        if ($shownHomeOnly) {
            if ($isHomePage) {
                return $template;
            } else {
                return false;
            }

        } else {
            return $template;
        }
    }
}
