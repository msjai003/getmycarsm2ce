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

/**
 * Product helper view model.
 */
class Helper extends DataObject implements ArgumentInterface
{
    // Constants
    const XML_PATH_PRICE_CONTRIBUTION_STEP = 'partner_settings/general/contribution_price_step';

    /**
     * @var DataHelper
     */
    private $dataHelper;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param DataHelper $dataHelper
     * @param Registry $registry
     */
    public function __construct(
        DataHelper $dataHelper,
        Registry $registry,
    ) {
        parent::__construct();
        $this->dataHelper = $dataHelper;
        $this->registry = $registry;
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
}
