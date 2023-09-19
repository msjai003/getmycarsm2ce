<?php

namespace BzoTech\Buy\Model\Config\Source;

class ListMarket implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'bzotech_buy/themeforest.png', 'label' => __('Themeforest')],
            ['value' => 'bzotech_buy/codecanyon.png', 'label' => __('Codecanyon')],
        ];
    }
}