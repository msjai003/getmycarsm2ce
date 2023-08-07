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
use Webkul\Pwa\Api\Data\PushNotificationInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * PushNotification Model.
 *
 * @method \Webkul\Pwa\Model\ResourceModel\PushNotification _getResource()
 * @method \Webkul\Pwa\Model\ResourceModel\PushNotification getResource()
 */
class PushNotification extends AbstractModel implements PushNotificationInterface, IdentityInterface
{
    /**
     * No route page id.
     */
    public const NOROUTE_ENTITY_ID = 'no-route';

    /**
     * Pwa PushNotification cache tag.
     */
    public const CACHE_TAG = 'push_notification';

    /**
     * @var string
     */
    protected $_cacheTag = 'push_notification';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'push_notification';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(\Webkul\Pwa\Model\ResourceModel\PushNotification::class);
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
            return $this->noRoutePushNotification();
        }

        return parent::load($id, $field);
    }

    /**
     * Load No-Route PushNotification.
     *
     * @return \Webkul\Pwa\Model\PushNotification
     */
    public function noRoutePushNotification()
    {
        return $this->load(self::NOROUTE_ENTITY_ID, $this->getIdFieldName());
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
     * @return \Webkul\Pwa\Api\Data\PushNotificationInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * Get Registration Id.
     *
     * @return string|null
     */
    public function getRegistrationId()
    {
        return parent::getData(self::REGISTRATION_ID);
    }

    /**
     * Set Registration Id.
     *
     * @param string $registrationId
     *
     * @return \Webkul\Pwa\Api\Data\PushNotificationInterface
     */
    public function setRegistrationId($registrationId)
    {
        return $this->setData(self::REGISTRATION_ID, $registrationId);
    }

    /**
     * Get End Point.
     *
     * @return string|null
     */
    public function getEndPoint()
    {
        return parent::getData(self::END_POINT);
    }

    /**
     * Set End Point.
     *
     * @param string $endPoint
     *
     * @return \Webkul\Pwa\Api\Data\PushNotificationInterface
     */
    public function setEndPoint($endPoint)
    {
        return $this->setData(self::END_POINT, $endPoint);
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
