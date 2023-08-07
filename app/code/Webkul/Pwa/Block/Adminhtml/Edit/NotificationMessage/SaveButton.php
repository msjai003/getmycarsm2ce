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

namespace Webkul\Pwa\Block\Adminhtml\Edit\NotificationMessage;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Webkul\Pwa\Block\Adminhtml\Edit\GenericButton;

/**
 * Class SaveButton add button for save notification message
 */
class SaveButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * Get Button configuration for rendering.
     *
     * @return array
     */
    public function getButtonData()
    {
        $data = [
            'label' => __('Save Notification Message'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];

        return $data;
    }
}
