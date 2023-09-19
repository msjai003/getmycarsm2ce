<?php

namespace BzoTech\Megamenu\Helper;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Module\Manager;
use Magento\Framework\Serialize\Serializer\Json;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout;
    protected $_escaper;

    public function __construct(
        private StoreManagerInterface $storeManagerInterface,
        private Manager $moduleManager,
        private Json $serialize,
        \Magento\Framework\View\LayoutInterface $layout,
        \Magento\Framework\Escaper $_escaper,
        \Magento\Framework\App\Helper\Context $context
    )
    {
        //$this->moduleManager = $moduleManager;
        //$this->_storeManager = $storeManagerInterface;
        $this->serialize     = $serialize;
        $this->layout        = $layout;
        $this->_escaper      = $_escaper;
        parent::__construct($context);
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */

    public
    function getStoreId()
    {
        return $this->storeManagerInterface->getStore()->getId();
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */

    public
    function getUrl()
    {
        return $this->storeManagerInterface->getStore()->getBaseUrl();
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */

    public
    function getMediaUrl()
    {
        return $this->storeManagerInterface->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * @param $name
     * @param null $storeCode
     * @return mixed
     */

    public
    function getMenuConfig($name, $storeCode = null)
    {
        return $this->scopeConfig->getValue(
            'megamenu/' . $name,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    /**
     * @return mixed
     */

    public
    function getSecondaryMenuConfig()
    {
        return $this->getMenuConfig('secondary_menu/menu_items');
    }

    /**
     * @return int|void
     */

    public
    function getCountSecondaryMenu()
    {
        $dataTable = $this->getSecondaryMenuConfig();

        if ($dataTable == '' || $dataTable == null)
            return;

        $unserializedata = $this->serialize->unserialize($dataTable);
        $countArray      = array();

        foreach ($unserializedata as $key => $row) {
            $countArray[] = $row['item_title'];
        }

        $countItem = count($countArray);

        return $countItem;
    }

    /**
     * @param $itemConfig
     * @return array|void
     */
    public
    function getConfigSecondaryMenuItem($itemConfig)
    {
        $dataTable = $this->getSecondaryMenuConfig();

        if ($dataTable == '' || $dataTable == null)
            return;

        $unserializedata = $this->serialize->unserialize($dataTable);
        $configArray     = array();

        foreach ($unserializedata as $key => $row) {
            $configArray[] = $row[$itemConfig];
        }

        return $configArray;
    }

    /**
     * @return string
     */

    public function secondaryMenuHtml()
    {
        $itemCenterConfig = $this->getMenuConfig('primary_secondary_menu/secondary_cfg/item_center_secondary');
        if ($itemCenterConfig) {
            $menuCenterClass = 'menu-center';
        } else {
            $menuCenterClass = '';
        }

        $html = '';

        $html .= '<div class="secondary-megamenu ' . $this->getMenuConfig('primary_secondary_menu/secondary_cfg/text_weight_secondary') . '">';
        $html .= '<nav class="megamenu-nav ' . $menuCenterClass . '">';
        $html .= '<ul class="megamenu-items nav-items clearfix">';

        for ($i = 0; $i < $this->getCountSecondaryMenu(); $i++) {
            $itemUrl                = 'javascript:void(0)';
            $itemUrlConfig          = $this->getConfigSecondaryMenuItem('item_url')[$i];
            $itemLabelHtml          = '';
            $itemDropdownWidthStyle = '';
            $parentClass            = '';
            $itemIconHtml           = '';
            $itemAlign              = '';
            $homeClass              = '';
            $itemLabelConfig        = $this->getConfigSecondaryMenuItem('item_label')[$i];
            $itemDropdown           = $this->getConfigSecondaryMenuItem('item_dropdown')[$i];
            $itemDropdownWidth      = $this->getConfigSecondaryMenuItem('item_dropdown_width')[$i];
            $itemIconConfig         = $this->getConfigSecondaryMenuItem('item_icon')[$i];
            $itemClassConfig        = $this->getConfigSecondaryMenuItem('item_class')[$i];

            if ($itemIconConfig) {
                $itemIconPath = $this->getMediaUrl() . $itemIconConfig;
                $itemIconHtml = '<span class="item-icon"><img src="' . $itemIconPath . '" /></span>';
            }

            if ($itemDropdownWidth) {
                $itemDropdownWidthStyle = 'style="width: ' . $itemDropdownWidth . '"';
            }

            if ($itemDropdown) {
                $parentClass = 'parent';
            }

            if (!$itemCenterConfig) {
                $itemAlign = $this->getConfigSecondaryMenuItem('item_align')[$i];
            }

            $liClass = $itemAlign . ' ' . $this->getConfigSecondaryMenuItem('item_dropdown_align')[$i] . ' ' . $parentClass . ' ' . $itemClassConfig;

            switch ($itemLabelConfig) {
                case "new":
                    $itemLabelHtml = '<span class="item-label ' . $itemLabelConfig . '-menu-item">' . __("New") . '</span>';
                    break;
                case "hot":
                    $itemLabelHtml = '<span class="item-label ' . $itemLabelConfig . '-menu-item">' . __("Hot") . '</span>';
                    break;
                case "sale":
                    $itemLabelHtml = '<span class="item-label ' . $itemLabelConfig . '-menu-item">' . __("Sale") . '</span>';
                    break;
                case "featured":
                    $itemLabelHtml = '<span class="item-label ' . $itemLabelConfig . '-menu-item">' . __("Featured") . '</span>';
                    break;
            }

            if (strpos($itemUrlConfig, 'http://') !== false || strpos($itemUrlConfig, 'https://') !== false) {
                $itemUrl = $itemUrlConfig;
            } else if (!empty($itemUrlConfig) && $itemUrlConfig != '/') {
                $itemUrl = $this->getUrl() . $itemUrlConfig;
            } else if ($itemUrlConfig == "/") {
                $itemUrl   = $this->getUrl();
                $homeClass = 'item-home';
            } else {
                $itemUrl = '#';
            }

            $html .= '<li class="level0 item-menu ' . $liClass . ' ' . $homeClass . '">';
            $html .= '<a class="level-top" href="' . $this->_escaper->escapeUrl($itemUrl) . '">';
            $html .= $itemIconHtml;
            $html .= '<span class="cat-name">' . $this->_escaper->escapeHtml($this->getConfigSecondaryMenuItem('item_title')[$i]) . ' </span > ';
            $html .= $itemLabelHtml;
            $html .= '</a > ';

            if ($itemDropdown) {
                $html .= '<div class="dropdowm-megamenu dropdown-secondary-menu"' . $itemDropdownWidthStyle . '>';
                $html .= $this->layout->createBlock('Magento\Cms\Block\Block')->setBlockId($itemDropdown)->toHtml();
                $html .= '</div>';
            }

            $html .= '</li > ';
        }

        $html .= '</ul > ';
        $html .= '</nav>';
        $html .= '</div > ';
        return $html;
    }
}
