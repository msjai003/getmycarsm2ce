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

namespace Webkul\Pwa\ViewModel;

use Webkul\Pwa\Api\PwaTrackingManagementInterface;
use Magento\Framework\App\RequestInterface;
class TackingData implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var array
     */
    protected $analyticsRecord = [];

    /**
     * @param PwaTrackingManagementInterface $trackingManagement
     * @param RequestInterface $request
     */
    public function __construct(
        private readonly PwaTrackingManagementInterface $trackingManagement,
        private readonly RequestInterface $request
    ) {
    }

    /**
     * Get pwa analytics record
     *
     * @return array
     */
    public function getAnalyticsRecord()
    {
        if (empty($this->analyticsRecord)) {
            $this->analyticsRecord = $this->trackingManagement->getInstalledTotal();
        }

        return $this->analyticsRecord;
    }

    /**
     * Get total download records
     *
     * @return array
     */
    public function getTotalDownloads(): array
    {
        $records = $this->getAnalyticsRecord();
        $downloadTotal = [];
        $downloads = [];
        foreach ($records as $record) {
            $downloads['date'] = $record['created_on'];
            $downloads['total'] = $record['total_download'];
            $downloadTotal[] = $downloads;
        }

        return $downloadTotal;
    }

    /**
     * Get total rejected records
     *
     * @return array
     */
    public function getTotalRejected(): array
    {
        $records = $this->getAnalyticsRecord();
        $rejectTotal = [];
        $rejects = [];
        foreach ($records as $record) {
            $rejects['date'] = $record['created_on'];
            $rejects['total'] = $record['total_reject'];
            $rejectTotal[] = $rejects;
        }

        return $rejectTotal;
    }
}
