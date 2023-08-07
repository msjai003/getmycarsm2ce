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
 * Class DeleteButton add button for delete notification message
 */
class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * Get Button rendering configuration.
     *
     * @return array
     */
    public function getButtonData()
    {
        $pushNotificationMessageId = $this->getNotificationMessageId();
        $data = [];
        if ($pushNotificationMessageId) {
            $data = [
                'label' => __('Delete Notification Message'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\''
                    . __('Are you sure you want to delete this notification message?')
                    . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
        }

        return $data;
    }

    /**
     * Url for deleting NotificationMessage
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['id' => $this->getNotificationMessageId()]);
    }
}
