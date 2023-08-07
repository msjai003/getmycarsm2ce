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

namespace Webkul\Pwa\Api\Data;

/**
 * Pwa PushNotification Interface.
 *
 * @api
 */
interface PushNotificationInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    public const ENTITY_ID = 'entity_id';

    public const REGISTRATION_ID = 'registration_id';

    public const END_POINT = 'end_point';

    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = 'updated_at';

    /**#@-*/

    /**
     * Get ID.
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID.
     *
     * @param int $id
     *
     * @return \Webkul\Pwa\Api\Data\PushNotificationInterface
     */
    public function setId($id);

    /**
     * Get Registration Id.
     *
     * @return string|null
     */
    public function getRegistrationId();

    /**
     * Set Registration Id.
     *
     * @param string $registrationId
     *
     * @return \Webkul\Pwa\Api\Data\PushNotificationInterface
     */
    public function setRegistrationId($registrationId);

    /**
     * Get End Point.
     *
     * @return string|null
     */
    public function getEndPoint();

    /**
     * Set End Point.
     *
     * @param string $endPoint
     *
     * @return \Webkul\Pwa\Api\Data\PushNotificationInterface
     */
    public function setEndPoint($endPoint);

    /**
     * Get created date.
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created date.
     *
     * @param string $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated date.
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated date.
     *
     * @param string $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt($updatedAt);
}
