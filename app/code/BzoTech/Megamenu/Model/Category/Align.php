<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace BzoTech\Megamenu\Model\Category;

/**
 * Catalog category landing page attribute source
 *
 * @author Magento Core Team <core@magentocommerce.com>
 */
class Align extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['value' => 'align-item', 'label' => __('Align Item (Type Vertical and Horizontal)')],
                ['value' => 'align-container-top', 'label' => __('Align Container Top (Type Vertical)')],
                ['value' => 'align-container-left', 'label' => __('Align Container Left (Type Horizontal)')],
                ['value' => 'align-container-right', 'label' => __('Align Container Right (Type Horizontal)')],
            ];
        }
        return $this->_options;
    }
}
