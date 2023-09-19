<?php

namespace BzoTech\Megashop\Model\Config\Source;

class ListFooterStyles implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'footer-1', 'label' => __('Footer Style 1')],
            ['value' => 'footer-2', 'label' => __('Footer Style 2')],
            ['value' => 'footer-3', 'label' => __('Footer Style 3')],
            ['value' => 'footer-4', 'label' => __('Footer Style 4')],
        ];
    }
}