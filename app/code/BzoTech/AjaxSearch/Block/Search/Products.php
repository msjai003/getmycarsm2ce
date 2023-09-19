<?php
/**
 * @category BZOTech
 * @package BzoTech_AjaxSearch
 * @version 1.0.0
 * @copyright Copyright (c) 2022 BZOTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * @author BZOTech Company  
 *   
 */

namespace BzoTech\AjaxSearch\Block\Search;

use Magento\Widget\Block\BlockInterface;

class Products extends \Magento\Catalog\Block\Product\AbstractProduct implements BlockInterface
{
    protected $_collection;
    protected $_resource;
    protected $_storeManager;
    protected $_storeId;
    protected $_catalogProductVisibility;
    protected $_objectManager;


    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $collection,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Catalog\Block\Product\Context $context,
        array $data = []
    )
    {
        $this->_objectManager            = $objectManager;
        $this->_collection               = $collection;
        $this->_resource                 = $resource;
        $this->_storeManager             = $context->getStoreManager();
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_storeId                  = (int)$this->_storeManager->getStore()->getId();
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */

    public function getProducts()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $helper        = $objectManager->get('\BzoTech\AjaxSearch\Helper\Data');
        $limit         = $helper->getAjaxSearchConfig('general/product_limit');
        $productId     = $helper->getAjaxSearchConfig('general/trending_product_ids');

        !is_array($productId) && $productId = preg_split('/[\s|,|;]/', $productId, -1, PREG_SPLIT_NO_EMPTY);
        $stringOrder = "DESC";
        $collection  = $this->_objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\Collection');
        $collection->addAttributeToSelect('*');
        $collection->addFieldToFilter('entity_id', array(
                'in' => $productId)
        );
        $collection->getSelect()->order('entity_id  ' . $stringOrder);
        $collection->getSelect()->distinct(true)->group('e.entity_id')->limit($limit);
        return $collection;
    }
}
