<?php
/*------------------------------------------------------------------------
# BzoTech ThemeCore
# Copyright (c) 2016 BzoTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: BzoTech Company
-------------------------------------------------------------------------*/

namespace BzoTech\ThemeCore\Helper;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var \Magento\Framework\Registry
     */

    public function __construct(
        private Image $imageHelper,
        private ProductRepositoryInterface $productRepository,
        private StoreManagerInterface $storeManagerInterface,
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Request\Http $request
    )
    {
        //$this->imageHelper       = $imageHelper;
        $this->productRepository = $productRepository;
        //$this->_storeManager     = $storeManagerInterface;
        $this->_request          = $request;
        parent::__construct($context);
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreId()
    {
        return $this->storeManagerInterface->getStore()->getId();
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getUrl()
    {
        return $this->storeManagerInterface->getStore()->getBaseUrl();
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMediaUrl()
    {
        return $this->storeManagerInterface->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * @param $name
     * @param null $storeCode
     * @return mixed
     */
    public function getCoreConfig($name, $storeCode = null)
    {
        return $this->scopeConfig->getValue(
            'themecore/' . $name,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    /**
     * @param $name
     * @param null $storeCode
     * @return mixed
     */
    public function getStoreConfig($name, $storeCode = null)
    {
        return $this->scopeConfig->getValue(
            $name,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    /**
     * @param $name
     * @param null $storeCode
     * @return mixed
     */
    public function getSliderConfig($name)
    {
        $option = $this->getCoreConfig($name);

        if ($option == '0') {
            return 'false';
        } else {
            return 'true';
        }
    }

    /**
     * @param $hexCode
     * @param $adjustPercent
     * @param   float $adjustPercent A number between -1 and 1. E.g. 0.3 = 30% lighter; -0.4 = 40% darker.
     * @return string
     */
    function adjustBrightness($hexCode, $adjustPercent)
    {
        $hexCode = ltrim($hexCode, '#');

        if (strlen($hexCode) == 3) {
            $hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
        }

        $hexCode = array_map('hexdec', str_split($hexCode, 2));

        foreach ($hexCode as & $color) {
            $adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
            $adjustAmount    = ceil($adjustableLimit * $adjustPercent);

            $color = str_pad(dechex($color + $adjustAmount), 2, '0', STR_PAD_LEFT);
        }

        return implode($hexCode);
    }

    /**
     * @param $_product
     * @return string
     */

    public function getLabelProduct($_product)
    {
        $newLabelText    = __('New');
        $saleLabelText   = __('On Sale');
        $showNewLabel    = $this->scopeConfig->getValue('themecore/advanced/product_group/show_newlabel', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $showSaleLabel   = $this->scopeConfig->getValue('themecore/advanced/product_group/show_salelabel', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $showSalePercent = $this->scopeConfig->getValue('themecore/advanced/product_group/show_discount', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $product_label = "";
        $labelProduct  = "";

        if ($showSaleLabel) {
            $defaultPrice = $_product->getPrice();
            $finalPrice   = $_product->getFinalPrice();
            if ($finalPrice < $defaultPrice) {
                if ($showSalePercent) {
                    $save_percent  = 100 - round(($finalPrice / $defaultPrice) * 100);
                    $product_label .= '<div class="product-label sale-label"><span><span class="text-label">' . $saleLabelText . '</span> -' . $save_percent . '%' . '</span></div>';
                } else {
                    $product_label .= '<div class="product-label sale-label"><span>' . $saleLabelText . '</span></div>';
                }
            }
            if($_product->getTypeID() == 'configurable'){
                $productTypeInstance = $_product->getTypeInstance();
                $usedProducts = $productTypeInstance->getUsedProducts($_product);
                foreach ($usedProducts as $simple) {
                    $default_price = $simple->getPrice();
                    if ($finalPrice < $default_price) {
                        if ($showSalePercent) {
                            $save_percent  = 100 - round(($finalPrice / $default_price) * 100);
                            $product_label .= '<div class="product-label sale-label"><span><span class="text-label">' . $saleLabelText . '</span> -' . $save_percent . '%' . '</span></div>';
                        } else {
                            $product_label .= '<div class="product-label sale-label"><span>' . $saleLabelText . '</span></div>';
                        }
                        break;
                    }
                }
            }
        }
        if ($showNewLabel) {
            $now      = date("Y-m-d");

            if ($_product->getData('news_from_date')) {
                $newsFrom = substr($_product->getData('news_from_date'), 0, 10);
            } else {
                $newsFrom = '';
            }

            if ($_product->getData('news_to_date')) {
                $newsTo = substr($_product->getData('news_to_date'), 0, 10);
            } else {
                $newsTo = '';
            }

            if ($newsTo != '' || $newsFrom != '') {
                if (($newsTo != '' && $newsFrom != '' && $now >= $newsFrom && $now <= $newsTo) || ($newsTo == '' && $now >= $newsFrom) || ($newsFrom == '' && $now <= $newsTo)) {
                    $product_label .= '<div class="product-label new-label"><span>' . $newLabelText . '</span></div>';
                }
            }
        }

        if ($product_label)
            $labelProduct = '<div class="product-labels">' . $product_label . '</div>';

        return $labelProduct;
    }

    /**
     * @param $productId
     * @param $imageSize
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */

    public function getItemImage($productId, $imageSize)
    {
        try {
            $_product = $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            return 'product not found';
        }
        $image_url = $this->imageHelper->init($_product, $imageSize)->getUrl();
        return $image_url;
    }

    public function getProductCategories($productId)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $category      = $objectManager->get('Magento\Framework\Registry')->registry('current_category');

        if ($this->_request->getFullActionName() == 'catalog_category_view') {
            return '<a href="' . $category->getUrl() . '">' . $category->getName() . '</a>';
        } else {
            $product      = $objectManager->create('\Magento\Catalog\Model\ProductRepository')->getById($productId);
            $categoryId   = $product->getCategoryIds()[0];
            $categoryInfo = $objectManager->create('\Magento\Catalog\Model\Category')->load($categoryId);
            return '<a href="' . $categoryInfo->getUrl() . '">' . $categoryInfo->getName() . '</a>';
        }
    }
}
