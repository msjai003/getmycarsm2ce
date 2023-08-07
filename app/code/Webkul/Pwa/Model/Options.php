<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_Pwa
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\Pwa\Model;

class Options implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Get PWA Position Config options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => '',
                'label' => __('--Select--')
            ],
            [
                'value' => 'top',
                'label' => __('Top')
            ],
            [
                'value' => 'bottom',
                'label' => __('Bottom')
            ],
            [
                'value' => 'bottom-right',
                'label' => __('Bottom Right')
            ],
            [
                'value' => 'top-right',
                'label' => __('Top Right')
            ],
        ];
    }
}
