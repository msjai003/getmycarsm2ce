<?php

namespace BzoTech\Megamenu\Model\Config\Source;

class Style implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => '', 'label' => __('Inherit')],
            ['value' => 'style-uppercase', 'label' => __('Uppercase')],
            ['value' => 'style-capitalize', 'label' => __('Capitalize')],
            ['value' => 'style-lowercase', 'label' => __('Lowercase')],
        ];
    }
}