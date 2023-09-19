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

class TypeListing implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'all', 'label' => __('All')],
            ['value' => 'deals', 'label' => __('Only Deals')],
            // ['value' => 'featured', 'label' => __('Only Featured')],
            ['value' => 'under', 'label' => __('Under Price')],
        ];
    }
}