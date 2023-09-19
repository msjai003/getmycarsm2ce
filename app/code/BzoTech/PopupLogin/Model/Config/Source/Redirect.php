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

class Redirect implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'home', 'label' => __('Home Page')],
            ['value' => 'current', 'label' => __('Current Page')],
            ['value' => 'sales/order/history', 'label' => __('My Orders')],
            ['value' => 'checkout/cart', 'label' => __('Shopping Cart')],
            ['value' => 'checkout', 'label' => __('Checkout')],
            ['value' => 'custom', 'label' => __('Custom Page')],
        ];
    }
}