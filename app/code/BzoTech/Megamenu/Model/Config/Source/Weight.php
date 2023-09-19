<?php

namespace BzoTech\Megamenu\Model\Config\Source;

class Weight implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'style-bold', 'label' => __('Bold')],
            ['value' => 'style-normal', 'label' => __('Normal')],
        ];
    }
}