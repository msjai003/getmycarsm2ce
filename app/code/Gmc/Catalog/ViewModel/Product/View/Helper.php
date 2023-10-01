<?php

/**
 * Gmc_Catalog
 *
 * PHP version 8.x
 *
 * @category  PHP
 * @package   Gmc\Catalog
 * @author    Gmc
 * @copyright 2023 Copyright Gmc
 */

declare(strict_types=1);

namespace Gmc\Catalog\ViewModel\Product\View;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Registry;
use Gmc\Catalog\Helper\Data as DataHelper;
use Gmc\Catalog\Model\AttributeOptions;
use Magento\Customer\Model\Session as CustomerSession;

/**
 * Product helper view model.
 */
class Helper extends DataObject implements ArgumentInterface
{
    const CARD_ATTRIBUTES = [
        'fuel_type', 'owner', 'transmission', 'seater'
    ];

    /**
     * @param DataHelper $dataHelper
     * @param Registry $registry
     * @param AttributeOptions $attributeOptions
     * @param CustomerSession $customerSession
     */
    public function __construct(
        private DataHelper $dataHelper,
        private Registry $registry,
        private AttributeOptions $attributeOptions,
        private CustomerSession $customerSession
    ) {
        parent::__construct();
    }

    /**
     * Function to retrieve tickets left
     */
    public function getTicketsLeft()
    {
        $product = $this->registry->registry('product');
        return $this->dataHelper->getTicketsLeft($product);
    } //end getTicketsLeft()

    /**
     * Function to retrieve currency symbol
     */
    public function getCurrencySymbol()
    {
        return $this->dataHelper->getCurrencySymbol();
    } //end getCurrencySymbol()

    /**
     * Function to retrieve formatted price
     */
    public function getFormattedPrice($price)
    {
        return $this->dataHelper->getFormattedPrice($price);
    } //end getFormattedPrice()     

    /**
     * Function to retrieve product card attributes
     */    
    public function getProductCardAttributes($product)
    {
        $optionIds =  array_map(function($attributeCode) use($product) {
            return $product->getData($attributeCode);
        }, self::CARD_ATTRIBUTES);
        return $this->attributeOptions->getOptionsArray($optionIds, self::CARD_ATTRIBUTES);
    }//end getProductCardAttributes()

    /**
     * Function to check is customer logged in
     */
    public function isCustomerLoggedIn()
    {
        return $this->customerSession->isLoggedIn();
    } //end isCustomerLoggedIn()     
}
