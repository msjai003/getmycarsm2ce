<?php

namespace BzoTech\Megashop\Model\Config\Source;

class ListHeaderStyles implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'header-1', 'label' => __('Header Style 1')],
            ['value' => 'header-2', 'label' => __('Header Style 2')],
            ['value' => 'header-3', 'label' => __('Header Style 3')],
            ['value' => 'header-4', 'label' => __('Header Style 4')],
        ];
    }
}