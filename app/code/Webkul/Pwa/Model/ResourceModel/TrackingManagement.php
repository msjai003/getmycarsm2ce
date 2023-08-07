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

namespace Webkul\Pwa\Model\ResourceModel;

class TrackingManagement extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Store model
     *
     * @var null|\Magento\Store\Model\Store
     */
    protected $_store = null;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('webkul_pwa_analytics', 'entity_id');
    }

    /**
     * Get record by date
     *
     * @param string $date
     * @return null|array
     */
    public function getRecordByDate($date = null)
    {
        $bind = [':created_on' => (string)$date];
        $select = $this->getConnection()
            ->select()
            ->from(
                $this->getMainTable(),
                ['*']
            )->where('created_on=?', $date);
        $result = $this->getConnection()->fetchRow($select, $bind);

        return $result;
    }

    /**
     * Get total pwa insalled record.
     *
     * @return null|array
     */
    public function getInstalledTotal()
    {
        $select = $this->getConnection()
            ->select()
            ->from(
                $this->getMainTable(),
                ['*']
            );
        $result = $this->getConnection()->fetchAll($select);

        return $result;
    }

    /**
     * Save if pwa downloads
     *
     * @param string $date
     * @return boolean
     */
    public function saveDownload($date)
    {
        $data = $this->getRecordByDate($date);
        if (!empty($data)) {
            $data['total_download'] += 1;
        } else {
            $data = [
                'created_on' => $date,
                'total_download' => 1,
                'total_reject' => 0,
            ];
        }
        $this->getConnection()->insertOnDuplicate(
            $this->getMainTable(),
            $data,
            ['created_on', 'total_download', 'total_reject']
        );

        return true;
    }

    /**
     * Save if pwa download rejection
     *
     * @param string $date
     * @return boolean
     */
    public function saveReject($date)
    {
        $data = $this->getRecordByDate($date);
        if (!empty($data)) {
            $data['total_reject'] += 1;
        } else {
            $data = [
                'created_on' => $date,
                'total_download' => 0,
                'total_reject' => 1,
            ];
        }
        $this->getConnection()->insertOnDuplicate(
            $this->getMainTable(),
            $data,
            ['created_on', 'total_download', 'total_reject']
        );

        return true;
    }
}
