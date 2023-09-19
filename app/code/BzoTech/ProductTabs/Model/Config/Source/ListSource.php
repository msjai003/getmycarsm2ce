<?php
/**
 * @category BZOTech
 * @package BzoTech_ProductTabs
 * @version 1.0.0
 * @copyright Copyright (c) 2022 BZOTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * @author BZOTech Company  
 *   
 */

namespace BzoTech\ProductTabs\Model\Config\Source;

class ListSource implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'categories', 'label' => __('Categories')],
            ['value' => 'fieldproducts', 'label' => __('Field Products')]
        ];
    }
}