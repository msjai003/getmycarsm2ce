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

use Magento\Framework\Model\AbstractModel;
use Webkul\Pwa\Api\Data\PushNotificationMessageInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * PushNotificationMessage Model.
 *
 * @method \Webkul\Pwa\Model\ResourceModel\PushNotificationMessage _getResource()
 * @method \Webkul\Pwa\Model\ResourceModel\PushNotificationMessage getResource()
 */
class PushNotificationMessage extends AbstractModel implements PushNotificationMessageInterface, IdentityInterface
{
    /**
     * No route page id.
     */
    public const NOROUTE_ENTITY_ID = 'no-route';

    /**#@+
     * Statuses
     */
    public const STATUS_ENABLED = 1;
    public const STATUS_DISABLED = 0;

    /**
     * Pwa PushNotificationMessage cache tag.
     */
    public const CACHE_TAG = 'push_notification_message';

    /**
     * @var string
     */
    protected $_cacheTag = 'push_notification_message';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'push_notification_message';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(\Webkul\Pwa\Model\ResourceModel\PushNotificationMessage::class);
    }

    /**
     * Load object data.
     *
     * @param int|null $id
     * @param string   $field
     *
     * @return $this
     */
    public function load($id, $field = null)
    {
        if ($id === null) {
            return $this->noRoutePushNotificationMessage();
        }

        return parent::load($id, $field);
    }

    /**
     * Load No-Route PushNotificationMessage.
     *
     * @return \Webkul\Pwa\Model\PushNotificationMessage
     */
    public function noRoutePushNotificationMessage()
    {
        return $this->load(self::NOROUTE_ENTITY_ID, $this->getIdFieldName());
    }

    /**
     * Prepare statuses. Available event statuses to customize statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    /**
     * Get identities.
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG.'_'.$this->getId()];
    }

    /**
     * Get ID.
     *
     * @return int
     */
    public function getId()
    {
        return parent::getData(self::ENTITY_ID);
    }

    /**
     * Set ID.
     *
     * @param int $id
     *
     * @return \Webkul\Pwa\Api\Data\PushNotificationMessageInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * Get Title.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return parent::getData(self::TITLE);
    }

    /**
     * Set Title.
     *
     * @param string $title
     *
     * @return \Webkul\Pwa\Api\Data\PushNotificationMessageInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Get Body Data.
     *
     * @return string|null
     */
    public function getBody()
    {
        return parent::getData(self::BODY);
    }

    /**
     * Set Body Data.
     *
     * @param string $body
     *
     * @return \Webkul\Pwa\Api\Data\PushNotificationMessageInterface
     */
    public function setBody($body)
    {
        return $this->setData(self::BODY, $body);
    }

    /**
     * Get Url.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return parent::getData(self::URL);
    }

    /**
     * Set Url.
     *
     * @param string $url
     *
     * @return \Webkul\Pwa\Api\Data\PushNotificationMessageInterface
     */
    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * Get Icon.
     *
     * @return string|null
     */
    public function getIcon()
    {
        return parent::getData(self::ICON);
    }

    /**
     * Set Icon.
     *
     * @param string $icon
     *
     * @return \Webkul\Pwa\Api\Data\PushNotificationMessageInterface
     */
    public function setIcon($icon)
    {
        return $this->setData(self::ICON, $icon);
    }

    /**
     * Get Status.
     *
     * @return string|null
     */
    public function getStatus()
    {
        return parent::getData(self::STATUS);
    }

    /**
     * Set Status.
     *
     * @param string $status
     *
     * @return \Webkul\Pwa\Api\Data\PushNotificationMessageInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get creation date.
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->_getData(self::CREATED_AT);
    }

    /**
     * Set created date.
     *
     * @param string $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get update date.
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->_getData(self::UPDATED_AT);
    }

    /**
     * Set updated date.
     *
     * @param string $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
