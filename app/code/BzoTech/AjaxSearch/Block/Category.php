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

namespace BzoTech\AjaxSearch\Block;


class Category extends \Magento\Framework\View\Element\Template
{
    protected $_storeManager;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Block\Product\Context $context,
        $data = []
    )
    {
        $this->_storeManager = $storeManager;
        parent::__construct($context, $data);
    }


    function getCategories($searchKeyword)
    {
        $objectManager   = \Magento\Framework\App\ObjectManager::getInstance();
        $categoryFactory = $objectManager->create('Magento\Catalog\Model\ResourceModel\Category\CollectionFactory');

        $categories = $categoryFactory->create()
            ->addAttributeToSelect('*')
            ->setStore($this->_storeManager->getStore())
            ->addAttributeToFilter('name', array('like' => '%' . $searchKeyword . '%'));

        return $categories;
    }
}
