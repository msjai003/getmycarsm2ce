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
namespace Webkul\Pwa\Ui\DataProvider\PushNotificationMessage;

use Webkul\Pwa\Model\ResourceModel\PushNotificationMessage\Grid\Collection as SearchCollection;
use Magento\Framework\Data\Collection;
use Magento\Ui\DataProvider\AddFilterToCollectionInterface;

class AddFulltextFilterToCollection implements AddFilterToCollectionInterface
{
    /**
     * @var SearchCollection
     */
    private $searchCollection;

    /**
     * @param SearchCollection $searchCollection
     */
    public function __construct(SearchCollection $searchCollection)
    {
        $this->searchCollection = $searchCollection;
    }

    /**
     * @inheritdoc
     */
    public function addFilter(Collection $collection, $field, $condition = null)
    {
        if (isset($condition['fulltext']) && (string)$condition['fulltext'] !== '') {
            $this->searchCollection->addBackendSearchFilter($condition['fulltext']);
            $productIds = $this->searchCollection->load()->getAllIds();
            if (empty($productIds)) {
                $productIds = -1;
            }
            $collection->addIdFilter($productIds);
        }
    }
}
