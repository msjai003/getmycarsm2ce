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

use Webkul\Pwa\Api\Data\TrackingDataInterface;

/**
 * Pwa TrackingData.
 *
 * @api
 */
class TrackingData extends \Magento\Framework\Api\AbstractExtensibleObject implements TrackingDataInterface
{

    /**
     * Set record created on
     *
     * @param string $date
     * @return \Webkul\Pwa\Api\Data\TrackingDataInterface
     */
    public function setCreatedOn($date)
    {
        return $this->setData(self::CREATED_ON, $date);
    }

    /**
     * Get date function
     *
     * @return string
     */
    public function getCreatedOn()
    {
        return $this->_get(self::CREATED_ON);
    }

    /**
     * Set record total
     *
     * @param string $total
     * @return \Webkul\Pwa\Api\Data\TrackingDataInterface
     */
    public function setTotal($total)
    {
        return $this->setData(self::TOTAL, $total);
    }

    /**
     * Get total record function
     *
     * @return string
     */
    public function getTotal()
    {
        return $this->_get(self::TOTAL);
    }
}
