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
            $orderId = $order->getId();
            $orderItems = $order->getAllItems();
            $itemsData   = [];
            foreach ($orderItems as $item) {
                $productId = $item->getProductId();
                $itemPrice = $item->getPrice();
                $productPrice = $item->getProduct()->getPrice();
                $estimatedSellingPrice = $item->getProduct()->getEstimatedSellingPrice();
                $estimatedSellingPrice = ($estimatedSellingPrice > $productPrice) ? $estimatedSellingPrice : $productPrice;
                $productOptions = $item->getProductOptions();
                $percentageContributed = $productOptions['info_buyRequest']['contribution_percentage'] ?? 0;
                $itemsData[$productId] = [
                    'item_price' => $itemPrice,
                    'product_price' => $productPrice,
                    'customer_id' => $order->getCustomerId(),
                    'order_id' => $item->getOrderId(),
                    'percentage_contributed' => $percentageContributed,
                    'estimated_selling_price' => $estimatedSellingPrice,
                ];
            }
            if (empty($itemsData)) {
                return;
            }
            $this->logger->info('Items Data', $itemsData);
            $this->updatePartnerPrice($itemsData);
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
     * Function to update partner price by product IDs
     */
    private function updatePartnerPrice($itemsData)
    {
        $connection = $this->resource->getConnection();
        $select = $connection->select();
        $table = $connection->getTableName('gmc_product_partner_price');
        $select
            ->from($table, ['product_id', 'partner_price'])
            ->where('product_id IN (?)', array_keys($itemsData));
        $result = $connection->fetchAssoc($select);
        $insertData = [];
        $processedIds = [];
        array_walk($result, function ($row, $productId) use ($itemsData, &$insertData, &$processedIds) {
            $partnerPrice = $row['partner_price'];
            $itemData = $itemsData[$productId];
            $itemPrice = $itemData['item_price'];
            $newPartnerPrice = ($partnerPrice - $itemPrice);
            $productPrice = $itemData['product_price'];
            $this->preparePartnerPriceData($productId, $productPrice, $newPartnerPrice, $insertData);
            $processedIds[] = $productId;
        });
        $this->logger->info('Exist Partner Price Data', $insertData);
        $newProductIds = array_diff(array_keys($itemsData), $processedIds);
        if (!empty($newProductIds)) {
            array_map(function ($productId) use (&$insertData, $itemsData) {
                $item = $itemsData[$productId];
                $itemPrice = $item['item_price'];
                $productPrice = $item['product_price'];
                $newPartnerPrice = ($productPrice - $itemPrice);
                $this->preparePartnerPriceData($productId, $productPrice, $newPartnerPrice, $insertData);
            }, $newProductIds);
        }
        $this->logger->info('New Partner Price Data', $insertData);
        $connection->insertOnDuplicate($table, $insertData);
    } //end getPartnerPrice()

    /**
     * Function to prepare insert data
     */
    private function preparePartnerPriceData($productId, $productPrice, $partnerPrice, &$insertData)
    {
        $insertData[$productId] = [
            'product_id' => $productId,
            'min_contribution_price' => 1,
            'max_contribution_price' => $partnerPrice,
            'price' => $productPrice,
            'partner_price' => $partnerPrice,
        ];
    } //end preparePartnerPriceData()

    /**
     * Function to add partner price contribution
     */
    private function addPartnerPriceContribution($itemsData)
    {
        $connection = $this->resource->getConnection();
        $table = $connection->getTableName('gmc_partner_contribution');
        $insert = [];
        array_walk($itemsData, function ($itemData, $productId) use (&$insert) {
            $estimatedSellingPrice = $itemData['estimated_selling_price'];
            $percentageContributed = $itemData['percentage_contributed'];
            $amountGained = ($estimatedSellingPrice * ($percentageContributed/100));
            $insert[$productId] = [
                'order_id' => $itemData['order_id'],
                'product_id' => $productId,
                'customer_id' => $itemData['customer_id'],
                'amount_contributed' => $itemData['item_price'],
                'percentage_contributed' => $percentageContributed,
                'amount_gained' => $amountGained,
                'profit_gained' => ($amountGained - $itemData['item_price']),
            ];
        });
        $this->logger->info('Partner Price Contribution', $insert);
        $connection->insertMultiple($table, $insert);
    }
}//end class
