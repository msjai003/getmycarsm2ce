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
 * Pwa PushNotificationMessage Interface.
 *
 * @api
 */
interface PushNotificationMessageInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    public const ENTITY_ID = 'entity_id';

    public const TITLE = 'title';

    public const BODY = 'body';

    public const URL = 'url';

    public const ICON = 'icon';

    public const STATUS = 'status';

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
     * @return \Webkul\Pwa\Api\Data\PushNotificationMessageInterface
     */
    public function setId($id);

    /**
     * Get Title.
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Set Title.
     *
     * @param string $title
     *
     * @return \Webkul\Pwa\Api\Data\PushNotificationMessageInterface
     */
    public function setTitle($title);

    /**
     * Get Body Data.
     *
     * @return string|null
     */
    public function getBody();

    /**
     * Set Body Data.
     *
     * @param string $body
     *
     * @return \Webkul\Pwa\Api\Data\PushNotificationMessageInterface
     */
    public function setBody($body);

    /**
     * Get Url.
     *
     * @return string|null
     */
    public function getUrl();

    /**
     * Set Url.
     *
     * @param string $url
     *
     * @return \Webkul\Pwa\Api\Data\PushNotificationMessageInterface
     */
    public function setUrl($url);

    /**
     * Get Icon.
     *
     * @return string|null
     */
    public function getIcon();

    /**
     * Set Icon.
     *
     * @param string $icon
     *
     * @return \Webkul\Pwa\Api\Data\PushNotificationMessageInterface
     */
    public function setIcon($icon);

    /**
     * Get Status.
     *
     * @return string|null
     */
    public function getStatus();

    /**
     * Set Status.
     *
     * @param string $status
     *
     * @return \Webkul\Pwa\Api\Data\PushNotificationMessageInterface
     */
    public function setStatus($status);

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
