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
 * Class SetTicketsLeft
 *
 * Gmc\Catalog\Observer\ProductCollection
 */
class SetTicketsLeft implements ObserverInterface
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
            $ticketsBookedData = $this->getTicketsBooked($productIds);
            $collection->walk(function ($product) use ($ticketsBookedData) {
                $productId = $product->getId();
                $productTicketSize = $product->getData('ticket_size');
                if (!isset($ticketsBookedData[$productId])) {
                    $product->setData('tickets_left', $productTicketSize);
                    return;
                }
                $ticketsBooked = $ticketsBookedData[$productId]['tickets_booked'];
                if ($ticketsBooked >= $productTicketSize) {
                    $product->setData('tickets_left', 0);
                    return;
                }
                $ticketsLeft = (int) $productTicketSize - $ticketsBooked;
                $product->setData('tickets_left', $ticketsLeft);
            });
        } catch (\Exception $exception) {
            $message = "Error occurred while add tickets left";
            $context = [
                'error' => $exception->getMessage(),
                'class' => __CLASS__
            ];
            $this->logger->critical($message, $context);
        }
    } //end execute()

    /**
     * Function to retrieve total tickets booked by product IDs
     *
     * @param array $productIds
     * @return array
     */
    public function getTicketsBooked($productIds)
    {
        $connection = $this->connection;
        $table = $connection->getTableName('gmc_partner_contribution');
        $select = $connection->select();
        $select
            ->from($table, ['product_id', 'SUM(ticket_size_qty) AS tickets_booked'])
            ->where('product_id IN (?)', $productIds)
            ->group('product_id');
        return $connection->fetchAssoc($select);
    } //end getTicketsBooked()    
}//end class
