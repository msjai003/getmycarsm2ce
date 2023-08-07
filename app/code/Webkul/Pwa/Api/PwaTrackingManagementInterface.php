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

namespace Webkul\Pwa\Api;

/**
 * @api
 */
interface PwaTrackingManagementInterface
{
    /**
     * Save Pwa downloads.
     *
     * @param  \Webkul\Pwa\Api\Data\TrackingDataInterface $trakingData
     * @return boolean
     *
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Webkul\Pwa\Api\Data\TrackingDataInterface $trakingData);

    /**
     * Save Rejected download.
     *
     * @param  \Webkul\Pwa\Api\Data\TrackingDataInterface $trakingData
     * @return boolean
     *
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function saveReject(\Webkul\Pwa\Api\Data\TrackingDataInterface $trakingData);

    /**
     * Get info about installed.
     *
     * @return \Webkul\Pwa\Api\Data\TrackingResponseInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getInstalledTotal();

    /**
     * Get info about installed.
     *
     * @return \Webkul\Pwa\Api\Data\TrackingResponseInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getRejectedTotal();
}
