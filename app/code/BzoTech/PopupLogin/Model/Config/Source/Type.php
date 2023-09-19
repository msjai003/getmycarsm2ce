<?php
/**
 * @category BZOTech
 * @package BzoTech_PopupLogin
 * @version 1.0.0
 * @copyright Copyright (c) 2022 BZOTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * @author BZOTech Company  
 *   
 */

namespace BzoTech\PopupLogin\Model\Config\Source;

class Type implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'modal', 'label' => __('Modal')],
            ['value' => 'sidebar', 'label' => __('Sidebar')],
        ];
    }
}