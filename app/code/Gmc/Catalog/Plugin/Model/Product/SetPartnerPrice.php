<?php

declare(strict_types=1);
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

namespace Gmc\Catalog\Plugin\Model\Product;

use Magento\Catalog\Model\Product;
use Gmc\PartnerContribution\Model\Logger\Logger;
use Magento\Framework\App\ResourceConnection;

/**
 * Class SetPartnerPrice
 * Gmc\Catalog\Plugin\Model\Product
 */
class SetPartnerPrice
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
     * @param CoreQuote $subject CoreQuote
     * @param Item $item Result
     * @return object
     */
    public function afterGetPrice(
        Product $subject,
        $result
    ) {
        try {
            $partnerPrice = $this->getPartnerPrice($subject->getId());
            if (!$partnerPrice) {
                return $result;
            }
            return $partnerPrice;
        } catch (\Throwable $exception) {
            $this->logger->critical(
                $exception->getMessage(),
                [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine()
                ]
            );
        }
        return $result;
    } //end afterGetPrice()

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
