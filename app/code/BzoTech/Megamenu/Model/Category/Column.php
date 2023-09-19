<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace BzoTech\Megamenu\Model\Category;

/**
 * Catalog category landing page attribute source
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Column extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['value' => '1', 'label' => __('1 Column')],
                ['value' => '2', 'label' => __('2 Columns')],
                ['value' => '3', 'label' => __('3 Columns')],
                ['value' => '4', 'label' => __('4 Columns')],
                ['value' => '5', 'label' => __('5 Columns')],
                ['value' => '6', 'label' => __('6 Columns')],
                ['value' => '7', 'label' => __('7 Columns')],
                ['value' => '8', 'label' => __('8 Columns')],
            ];
        }
        return $this->_options;
    }
}
