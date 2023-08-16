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

namespace Gmc\Catalog\Observer\Product\View;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface as Logger;
use Magento\Framework\App\ResourceConnection;

/**
 * Class SetPartnerPrice
 *
 * Gmc\Catalog\Observer\Product
 */
class SetPartnerPrice implements ObserverInterface
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
             * @var $collection \Magento\Catalog\Model\Product
             */
            $product = $observer->getEvent()->getProduct();
            $partnerPrice = $this->getPartnerPrice($product->getId());
            if (!$partnerPrice) {
                return $product;
            }
            $product->setPrice($partnerPrice);
            $product->setFinalPrice($partnerPrice);
        } catch (\Exception $exception) {
            $message = "Error occurred while set product partner price";
            $context = [
                'error' => $exception->getMessage(),
                'class' => __CLASS__
            ];
            $this->logger->critical($message, $context);
        }
        return $product;
    } //end execute()

    /**
     * Function to retrieve partner price by product ID
     */
    private function getPartnerPrice($productId)
    {
        $connection = $this->resource->getConnection();
        $select = $connection->select();
        $table = $connection->getTableName('gmc_product_partner_price_range');
        $select
            ->from($table, ['partner_price'])
            ->where('product_id = ?', $productId);
        return $connection->fetchOne($select);
    } //end getPartnerPrice()
}//end class
