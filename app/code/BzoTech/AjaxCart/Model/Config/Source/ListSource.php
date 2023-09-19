<?php
/**
 * @category BZOTech
 * @package BzoTech_AjaxCart
 * @version 1.0.0
 * @copyright Copyright (c) 2022 BZOTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * @author BZOTech Company 
 */

namespace BzoTech\AjaxCart\Model\Config\Source;

class ListSource implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'both', 'label' => __('Quick View & Ajax Cart')],
            ['value' => 'quickview', 'label' => __('Quick View')],
            ['value' => 'ajaxcart', 'label' => __('Ajax Cart')]
        ];
    }
}