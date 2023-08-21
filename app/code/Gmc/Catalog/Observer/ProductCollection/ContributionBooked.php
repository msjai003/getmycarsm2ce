<?php

/**
 * Gmc_Catalog
 *
 * PHP version 8.x
 *
 * @category  PHP
 * @package   Gmc\Catalog
 * @author    Gmc
 * @copyright 2023 Copyright Gmc
 */

namespace Gmc\Catalog\Observer\ProductCollection;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Gmc\PartnerContribution\Model\Logger\Logger;
use Magento\Framework\App\ResourceConnection;

/**
 * Class ContributionBooked
 *
 * Gmc\Catalog\Observer\ProductCollection
 */
class ContributionBooked implements ObserverInterface
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    private $connection;

    /**
     * AddPartnerPrice Constructor.
     *
     * @param Logger $logger Logger
     * @param ResourceConnection $resource
     */
    public function __construct(
        Logger $logger,
        ResourceConnection $resource
    ) {
        $this->logger = $logger;
        $this->connection = $resource->getConnection();
    } //end __construct()

    /**
     * @param Observer $observer
     * @return mixed|void
     */
    public function execute(Observer $observer)
    {
        try {
            /**
             * @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection
             */
            $collection = $observer->getEvent()->getCollection();
            $productIds = $collection->getColumnValues('entity_id');
            $contributionBookedData = $this->getContributionBooked($productIds);
            if (empty($contributionBookedData)) {
                return;
            }
            $collection->walk(function ($product) use ($contributionBookedData) {
                $productId = $product->getId();
                if (!isset($contributionBookedData[$productId])) {
                    $product->setData('contribution_booked', 0);
                    return;
                }
                $price = $product->getPrice();
                $contributionBooked = $contributionBookedData[$productId]['contribution_booked'];
                if ($contributionBooked >= $price) {
                    $product->setData('contribution_booked', 100);
                    return;
                }
                $contributionBooked = floor(($contributionBooked / $price) * 100);
                $product->setData('contribution_booked', $contributionBooked);
            });
        } catch (\Exception $exception) {
            $message = "Error occurred while add contribution booked";
            $context = [
                'error' => $exception->getMessage(),
                'class' => __CLASS__
            ];
            $this->logger->critical($message, $context);
        }
    } //end execute()

    /**
     * Function to retrieve total contribution booked by product IDs
     *
     * @param array $productIds
     * @return array
     */
    public function getContributionBooked($productIds)
    {
        $connection = $this->connection;
        $table = $connection->getTableName('gmc_partner_contribution');
        $select = $connection->select();
        $select
            ->from($table, ['product_id', 'SUM(amount_contributed) AS contribution_booked'])
            ->where('product_id IN (?)', $productIds)
            ->group('product_id');
        return $connection->fetchAssoc($select);
    } //end getContributionBooked()    
}//end class
