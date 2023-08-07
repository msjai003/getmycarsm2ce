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
 * Pwa TrackingDataInterface Interface.
 *
 * @api
 */
interface TrackingDataInterface
{
    public const CREATED_ON = 'created_on';

    public const TOTAL = 'total';

    /**
     * Set record created on
     *
     * @param string $date
     * @return \Webkul\Pwa\Api\Data\TrackingDataInterface
     */
    public function setCreatedOn($date);

    /**
     * Get date function
     *
     * @return string
     */
    public function getCreatedOn();

    /**
     * Set record total
     *
     * @param string $total
     * @return \Webkul\Pwa\Api\Data\TrackingDataInterface
     */
    public function setTotal($total);

    /**
     * Get total record function
     *
     * @return string
     */
    public function getTotal();
}
