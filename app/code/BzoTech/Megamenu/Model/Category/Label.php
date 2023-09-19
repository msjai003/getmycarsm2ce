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
class Label extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['value' => '', 'label' => __('Please select label')],
                ['value' => 'new', 'label' => __('New')],
                ['value' => 'hot', 'label' => __('Hot')],
                ['value' => 'sale', 'label' => __('Sale')],
                ['value' => 'featured', 'label' => __('Featured')],
            ];
        }
        return $this->_options;
    }
}
