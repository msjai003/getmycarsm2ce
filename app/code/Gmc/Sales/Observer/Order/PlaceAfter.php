<?php

/**
 * Gmc_Sales
 *
 * PHP version 8.x
 *
 * @category  PHP
 * @package   Gmc\Sales
 * @author    Gmc
 * @copyright 2023 Copyright Gmc
 */

namespace Gmc\Sales\Observer\Order;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Gmc\PartnerContribution\Model\Logger\Logger;
use Magento\Framework\App\ResourceConnection;

/**
 * Class PlaceAfter
 *
 * Gmc\Sales\Observer\Order
 */
class PlaceAfter implements ObserverInterface
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * SetPartnerPrice constructor.
     *
     * @param Logger $logger Logger
     * @param ResourceConnection $resource ResourceConnection
     */
    public function __construct(
        Logger $logger,
        ResourceConnection $resource
    ) {
        $this->logger = $logger;
        $this->resource = $resource;
    } //end __construct()

    /**
     * @param Observer $observer
     * @return mixed|void
     */
    public function execute(Observer $observer)
    {
        try {
            /**
             * @var $order \Magento\Sales\Api\Data\OrderInterface
             */
            $order = $observer->getEvent()->getOrder();
            $orderItems = $order->getAllItems();
            $itemsData   = [];
            foreach ($orderItems as $item) {
                $productId = $item->getProductId();
                $itemPrice = $item->getPrice();
                $productPrice = $item->getProduct()->getPrice();
                $estimatedSellingPrice = $item->getProduct()->getEstimatedSellingPrice();
                $estimatedSellingPrice = ($estimatedSellingPrice > $productPrice) ? $estimatedSellingPrice : $productPrice;
                $qtyOrdered = $item->getQtyOrdered();
                $itemsData[$productId] = [
                    'item_price' => $itemPrice * $qtyOrdered,
                    'product_price' => $productPrice,
                    'customer_id' => $order->getCustomerId(),
                    'order_id' => $item->getOrderId(),
                    'qty_ordered' => $qtyOrdered,
                    'estimated_selling_price' => $estimatedSellingPrice,
                ];
            }
            if (empty($itemsData)) {
                return;
            }
            $this->logger->info('Items Data', $itemsData);
            $this->addPartnerPriceContribution($itemsData);
        } catch (\Exception $exception) {
            $message = "Error occurred while update product partner price";
            $context = [
                'error' => $exception->getMessage(),
                'class' => __CLASS__
            ];
            $this->logger->critical($message, $context);
        }
        return $order;
    } //end execute()

    /**
     * Function to add partner price contribution
     */
    private function addPartnerPriceContribution($itemsData)
    {
        $connection = $this->resource->getConnection();
        $table = $connection->getTableName('gmc_partner_contribution');
        $insert = [];
        array_walk($itemsData, function ($itemData, $productId) use (&$insert) {
            $insert[$productId] = [
                'order_id' => $itemData['order_id'],
                'product_id' => $productId,
                'customer_id' => $itemData['customer_id'],
                'amount_contributed' => $itemData['item_price'],
                'ticket_size_qty' => $itemData['qty_ordered'],
                'amount_gained' => 0,
                'profit_gained' => 0,
            ];
        });
        $this->logger->info('Partner Price Contribution', $insert);
        $connection->insertMultiple($table, $insert);
    }
}//end class
