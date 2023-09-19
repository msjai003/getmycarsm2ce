<?php

namespace BzoTech\Megashop\Helper;

class Sold extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_scopeConfig;
    protected $reportCollectionFactory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Reports\Model\ResourceModel\Product\Sold\CollectionFactory $reportCollectionFactory
    )
    {
        $this->reportCollectionFactory = $reportCollectionFactory;
        parent::__construct($context);
    }

    /**
     * @param null $producID
     * @return int
     * @throws \Zend_Db_Select_Exception
     */

    public function getSoldQtyByProductId($producID = null)
    {
        $soldProducts       = $this->reportCollectionFactory->create();
        $soldProdudctFilter = $soldProducts->addOrderedQty()->addAttributeToFilter('product_id', $producID);
        if (!$soldProdudctFilter->count()) {
            return 0;
        }

        $product = $soldProdudctFilter->getFirstItem();
        return (int)$product->getData('ordered_qty');
    }
}